<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Auth::user()->cart()->with(['product.seller'])->get();
        $total = $cartItems->sum('total_price');

        return view('cart.index', compact('cartItems', 'total'));
    }

    public function add(Request $request, Product $product)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Please login first'], 401);
        }

        // Check if user is trying to add their own product
        if ($product->seller->user_id === Auth::id()) {
            return response()->json(['error' => 'You cannot add your own product to cart'], 400);
        }

        // Check if seller is approved
        if ($product->seller->status !== 'approved') {
            return response()->json(['error' => 'Product is not available'], 400);
        }

        $quantity = $request->input('quantity', 1);

        $cartItem = Cart::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => $quantity,
            ]);
        }

        $cartCount = Auth::user()->cart()->count();

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully',
            'cart_count' => $cartCount
        ]);
    }

    public function update(Request $request, Cart $cart)
    {
        // Check ownership
        if ($cart->user_id !== Auth::id()) {
            abort(403);
        }

        $quantity = $request->input('quantity', 1);

        if ($quantity <= 0) {
            $cart->delete();
        } else {
            $cart->update(['quantity' => $quantity]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully'
        ]);
    }

    public function remove(Cart $cart)
    {
        // Check ownership
        if ($cart->user_id !== Auth::id()) {
            abort(403);
        }

        $cart->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart successfully'
        ]);
    }

    public function count()
    {
        if (!Auth::check()) {
            return response()->json(['count' => 0]);
        }

        $count = Auth::user()->cart()->count();
        return response()->json(['count' => $count]);
    }

    public function checkout()
    {
        $cartItems = Auth::user()->cart()->with(['product.seller'])->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }

        // Group by seller to create separate orders
        $sellerGroups = $cartItems->groupBy(function ($item) {
            return $item->product->seller_id;
        });

        $orders = [];

        DB::beginTransaction();

        try {
            foreach ($sellerGroups as $sellerId => $items) {
                $totalAmount = $items->sum('total_price');

                // Create order
                $order = Order::create([
                    'user_id' => Auth::id(),
                    'gross_amount' => $totalAmount,
                    'status' => 'pending'
                ]);

                // Add products to order
                foreach ($items as $item) {
                    $order->products()->attach($item->product_id, [
                        'quantity' => $item->quantity,
                        'price' => $item->product->price
                    ]);
                }

                $orders[] = $order;
            }

            // Clear cart
            Auth::user()->cart()->delete();

            DB::commit();

            // If single order, redirect to payment
            if (count($orders) === 1) {
                return $this->processPayment($orders[0]);
            }

            // Multiple orders, show summary and let user choose payment
            return view('cart.checkout-summary', compact('orders'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('cart.index')->with('error', 'Checkout failed: ' . $e->getMessage());
        }
    }

    private function processPayment(Order $order)
    {
        // Configure Midtrans
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production', false);
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $transaction_details = [
            'order_id' => $order->invoice_number,
            'gross_amount' => (int) $order->gross_amount,
        ];

        $customer_details = [
            'first_name' => Auth::user()->name,
            'email' => Auth::user()->email,
        ];

        $item_details = [];
        foreach ($order->products as $product) {
            $item_details[] = [
                'id' => $product->id,
                'price' => (int) $product->pivot->price,
                'quantity' => $product->pivot->quantity,
                'name' => $product->name,
            ];
        }

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
                'amount' => $order->gross_amount,
                'status' => 'pending',
                'snap_token' => $snapToken
            ]);

            return view('cart.payment', compact('order', 'snapToken'));
        } catch (\Exception $e) {
            $order->delete();
            return redirect()->route('cart.index')->with('error', 'Payment initialization failed: ' . $e->getMessage());
        }
    }
}
