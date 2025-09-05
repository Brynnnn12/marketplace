<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function handleNotification(Request $request)
    {
        // Configure Midtrans
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production', false);

        try {
            $notification = new \Midtrans\Notification();

            $order = Order::where('invoice_number', $notification->order_id)->first();

            if (!$order) {
                Log::error('Order not found for invoice: ' . $notification->order_id);
                return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
            }

            $payment = $order->payment;

            if (!$payment) {
                Log::error('Payment not found for order: ' . $order->id);
                return response()->json(['status' => 'error', 'message' => 'Payment not found'], 404);
            }

            $transaction_status = $notification->transaction_status;
            $fraud_status = $notification->fraud_status ?? null;

            if ($transaction_status == 'capture') {
                if ($fraud_status == 'accept') {
                    $payment->status = 'settlement';
                    $order->status = 'paid';
                }
            } elseif ($transaction_status == 'settlement') {
                $payment->status = 'settlement';
                $order->status = 'paid';
            } elseif ($transaction_status == 'pending') {
                $payment->status = 'pending';
                $order->status = 'pending';
            } elseif ($transaction_status == 'deny') {
                $payment->status = 'deny';
                $order->status = 'failed';
            } elseif ($transaction_status == 'expire') {
                $payment->status = 'expire';
                $order->status = 'expired';
            } elseif ($transaction_status == 'cancel') {
                $payment->status = 'cancel';
                $order->status = 'cancelled';
            }

            $payment->save();
            $order->save();

            Log::info('Payment notification processed', [
                'order_id' => $order->invoice_number,
                'status' => $transaction_status
            ]);

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Payment notification error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    public function paymentSuccess(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Load products with seller relationship
        $order->load(['products.seller']);

        return view('payment.success', compact('order'));
    }

    public function paymentFailed(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Load products with seller relationship
        $order->load(['products.seller']);

        return view('payment.failed', compact('order'));
    }

    public function paymentPending(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Load products with seller relationship
        $order->load(['products.seller']);

        return view('payment.pending', compact('order'));
    }

    public function paymentError(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Load products with seller relationship
        $order->load(['products.seller']);

        return view('payment.error', compact('order'));
    }

    public function checkPaymentStatus(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Configure Midtrans
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production', false);

        try {
            $status = \Midtrans\Transaction::status($order->invoice_number);

            $payment = $order->payment;

            if ($payment) {
                $transaction_status = $status->transaction_status;
                $fraud_status = $status->fraud_status ?? null;

                if ($transaction_status == 'capture') {
                    if ($fraud_status == 'accept') {
                        $payment->status = 'settlement';
                        $order->status = 'paid';
                    }
                } elseif ($transaction_status == 'settlement') {
                    $payment->status = 'settlement';
                    $order->status = 'paid';
                } elseif ($transaction_status == 'pending') {
                    $payment->status = 'pending';
                    $order->status = 'pending';
                } elseif ($transaction_status == 'deny') {
                    $payment->status = 'deny';
                    $order->status = 'failed';
                } elseif ($transaction_status == 'expire') {
                    $payment->status = 'expire';
                    $order->status = 'expired';
                } elseif ($transaction_status == 'cancel') {
                    $payment->status = 'cancel';
                    $order->status = 'cancelled';
                }

                $payment->save();
                $order->save();
            }

            return response()->json([
                'status' => $order->status,
                'transaction_status' => $transaction_status,
                'redirect_url' => $this->getRedirectUrl($order)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function getRedirectUrl($order)
    {
        switch ($order->status) {
            case 'paid':
                return route('payment.success', $order);
            case 'failed':
                return route('payment.failed', $order);
            case 'expired':
            case 'cancelled':
                return route('payment.failed', $order);
            case 'pending':
            default:
                return route('payment.pending', $order);
        }
    }
}
