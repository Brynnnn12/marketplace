<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Auth::user()->orders()->with(['products.seller', 'payment']);

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Search by invoice number
        if ($request->has('search') && $request->search != '') {
            $query->where('invoice_number', 'like', '%' . $request->search . '%');
        }

        // Sort by created_at desc by default
        $orders = $query->orderBy('created_at', 'desc')->paginate(10);

        // Get status counts for tabs
        $allCount = Auth::user()->orders()->count();
        $paidCount = Auth::user()->orders()->where('status', 'paid')->count();
        $pendingCount = Auth::user()->orders()->where('status', 'pending')->count();
        $failedCount = Auth::user()->orders()->whereIn('status', ['failed', 'expired', 'cancelled'])->count();

        return view('orders.index', compact('orders', 'allCount', 'paidCount', 'pendingCount', 'failedCount'));
    }
    public function show(Order $order)
    {
        // Check if user owns this order
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        // Load relationships
        $order->load(['products.seller', 'payment', 'user']);

        $payment = $order->payment;
        $snap_token = '';

        // Only generate snap token if payment is still pending and no token exists
        if (!$payment || ($payment->status === 'pending' && !$payment->snap_token)) {
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = config('midtrans.is_production', false);
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            $transaction_details = array(
                'order_id' => $order->invoice_number,
                'gross_amount' => $order->gross_amount,
            );

            $customer_details = array(
                'first_name' => $order->user->name,
                'email' => $order->user->email,
            );

            $item_details = [];
            foreach ($order->products as $product) {
                $item_details[] = [
                    'id' => $product->id,
                    'price' => $product->pivot->price,
                    'quantity' => $product->pivot->quantity,
                    'name' => $product->name,
                ];
            }

            $transaction = array(
                'transaction_details' => $transaction_details,
                'customer_details' => $customer_details,
                'item_details' => $item_details,
            );

            try {
                $snap_token = \Midtrans\Snap::getSnapToken($transaction);

                // Update or create payment record
                if ($payment) {
                    $payment->update(['snap_token' => $snap_token]);
                } else {
                    Payment::create([
                        'order_id' => $order->id,
                        'amount' => $order->gross_amount,
                        'status' => 'pending',
                        'snap_token' => $snap_token
                    ]);
                    $payment = $order->payment;
                }
            } catch (\Exception $e) {
                return back()->with('error', 'Terjadi kesalahan saat membuat transaksi: ' . $e->getMessage());
            }
        } else {
            $snap_token = $payment ? $payment->snap_token : '';
        }

        return view('orders.show', compact('order', 'payment', 'snap_token'));
    }
}
