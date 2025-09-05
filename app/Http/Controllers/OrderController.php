<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function show(Order $order)
    {
        $payment = $order->payment->last();
        $snap_token = '';

        if ($payment == null) {
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = false;
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
                    'price' => $product->price,
                    'quantity' => $product->quantity,
                    'name' => $product->name,
                ];
            }

            $transaction = array(
                'transaction_details' => $transaction_details,
                'customer_details' => $customer_details,
                'item_details' => $item_details,
            );

            try {
                $snapToken = \Midtrans\Snap::getSnapToken($transaction);
                Payment::updateOrCreate(
                    ['order_id' => $order->id],
                    [
                        'amount' => $order->gross_amount,
                        'status' => 'pending',
                    ]
                );
            } catch (\Exception $e) {
                return back()->with('error', 'Terjadi kesalahan saat membuat transaksi: ' . $e->getMessage());
            }
        }

        return view('orders.show', compact('order', 'payment', 'snap_token'));
    }
}
