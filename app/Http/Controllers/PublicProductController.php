<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class PublicProductController extends Controller
{
    public function index(Request $request,)
    {
        $query = Product::with('seller')
            ->whereHas('seller', function ($q) {
                $q->where('status', 'approved');
            });


        // Search
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sort
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(12);

        return view('products.index', compact('products'));
    }

    public function show(Product $product)
    {
        if ($product->seller->status !== 'approved') {
            abort(404);
        }

        $relatedProducts = Product::with('seller')
            ->where('id', '!=', $product->id)
            ->whereHas('seller', function ($q) {
                $q->where('status', 'approved');
            })
            ->limit(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }

    public function purchase(Product $product)
    {
        if ($product->seller->status !== 'approved') {
            abort(404);
        }

        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Create order
        $order = Order::create([
            'user_id' => Auth::id(),
            'invoice_number' => 'INV-' . time() . '-' . Auth::id(),
            'gross_amount' => (int) $product->price,
            'status' => 'pending'
        ]);

        // Add product to order using the pivot table
        $order->products()->attach($product->id, [
            'quantity' => 1,
            'price' => $product->price
        ]);

        // Configure Midtrans
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production', false);
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $transaction_details = [
            'order_id' => $order->invoice_number,
            'gross_amount' => (int) $product->price,
        ];

        $customer_details = [
            'first_name' => Auth::user()->name,
            'email' => Auth::user()->email,
        ];

        $item_details = [
            [
                'id' => $product->id,
                'price' => (int) $product->price,
                'quantity' => 1,
                'name' => $product->name,
            ]
        ];

        $transaction = [
            'transaction_details' => $transaction_details,
            'customer_details' => $customer_details,
            'item_details' => $item_details,
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($transaction);

            // Create payment record
            Payment::create([
                'order_id' => $order->id,
                'amount' => $product->price,
                'status' => 'pending',
                'snap_token' => $snapToken
            ]);

            return response()->json([
                'snap_token' => $snapToken,
                'order_id' => $order->invoice_number  // Use invoice_number instead of id
            ]);
        } catch (\Exception $e) {
            $order->delete(); // Clean up failed order
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
