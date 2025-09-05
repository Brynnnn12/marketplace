<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;

class DebugPaymentIssue extends Command
{
    protected $signature = 'debug:payment-issue {--create-test-order}';
    protected $description = 'Debug payment pending issue and route problems';

    public function handle()
    {
        $this->info('=== PAYMENT DEBUG ANALYSIS ===');

        if ($this->option('create-test-order')) {
            $this->createTestOrder();
            return;
        }

        // Check total orders
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();

        $this->info("Total Orders: {$totalOrders}");
        $this->info("Pending Orders: {$pendingOrders}");

        if ($totalOrders === 0) {
            $this->warn('No orders found in database!');
            if ($this->confirm('Create test order?')) {
                $this->createTestOrder();
                return;
            }
            return;
        }

        // Show recent orders
        $this->info("\n=== RECENT ORDERS ===");
        $orders = Order::with('payment', 'user')->latest()->take(5)->get();

        foreach ($orders as $order) {
            $this->line("Invoice: {$order->invoice_number}");
            $this->line("  - Order Status: {$order->status}");
            $this->line("  - Payment Status: " . ($order->payment ? $order->payment->status : 'NO PAYMENT'));
            $this->line("  - Amount: Rp " . number_format($order->gross_amount));
            $this->line("  - User ID: {$order->user_id}");
            $this->line("  - Created: {$order->created_at->format('Y-m-d H:i:s')}");

            // Test route generation
            try {
                $pendingRoute = route('payment.pending', $order);
                $this->line("  - Pending Route: {$pendingRoute}");
            } catch (\Exception $e) {
                $this->error("  - Route Error: " . $e->getMessage());
            }

            $this->line('');
        }

        // Check for specific order if user provides invoice
        if ($this->confirm('Test specific order by invoice number?')) {
            $invoice = $this->ask('Enter invoice number:');
            $this->testSpecificOrder($invoice);
        }
    }

    private function createTestOrder()
    {
        $this->info('Creating test order...');

        // Get first user or create one
        $user = \App\Models\User::first();
        if (!$user) {
            $user = \App\Models\User::create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now()
            ]);
            $this->info("Created test user: {$user->email}");
        }

        $order = Order::create([
            'user_id' => $user->id,
            'invoice_number' => 'INV-TEST-' . time(),
            'gross_amount' => 100000,
            'status' => 'pending'
        ]);

        Payment::create([
            'order_id' => $order->id,
            'amount' => 100000,
            'status' => 'pending',
            'snap_token' => 'test-token-' . time()
        ]);

        $this->info("Created test order: {$order->invoice_number}");
        $this->info("Test routes:");

        try {
            $this->line("- Pending: " . route('payment.pending', $order));
            $this->line("- Success: " . route('payment.success', $order));
            $this->line("- Failed: " . route('payment.failed', $order));
        } catch (\Exception $e) {
            $this->error("Route generation failed: " . $e->getMessage());
        }
    }

    private function testSpecificOrder($invoice)
    {
        $order = Order::where('invoice_number', $invoice)->with('payment', 'user')->first();

        if (!$order) {
            $this->error("Order not found: {$invoice}");
            return;
        }

        $this->info("\n=== ORDER DETAILS ===");
        $this->line("Invoice: {$order->invoice_number}");
        $this->line("Order Status: {$order->status}");
        $this->line("User ID: {$order->user_id}");
        $this->line("Amount: Rp " . number_format($order->gross_amount));

        if ($order->payment) {
            $this->line("Payment ID: {$order->payment->id}");
            $this->line("Payment Status: {$order->payment->status}");
            $this->line("Snap Token: {$order->payment->snap_token}");
        } else {
            $this->warn("No payment record found!");
        }

        // Test route generation
        $this->info("\n=== ROUTE TESTING ===");
        $routes = ['pending', 'success', 'failed', 'check-status'];

        foreach ($routes as $routeName) {
            try {
                $route = route("payment.{$routeName}", $order);
                $this->line("{$routeName}: {$route}");
            } catch (\Exception $e) {
                $this->error("{$routeName}: ERROR - " . $e->getMessage());
            }
        }

        // Check Midtrans status
        if ($this->confirm('Check Midtrans status for this order?')) {
            $this->checkMidtransStatus($order);
        }
    }

    private function checkMidtransStatus($order)
    {
        $this->info("\n=== MIDTRANS STATUS CHECK ===");

        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production', false);

        try {
            $status = \Midtrans\Transaction::status($order->invoice_number);

            $this->info("Midtrans Status:");
            $this->line("- Transaction Status: {$status->transaction_status}");
            $this->line("- Payment Type: " . ($status->payment_type ?? 'N/A'));
            $this->line("- Fraud Status: " . ($status->fraud_status ?? 'N/A'));
            $this->line("- Gross Amount: " . ($status->gross_amount ?? 'N/A'));

            // Compare with database
            if ($order->payment) {
                $this->line("\nComparison:");
                $this->line("Database Payment Status: {$order->payment->status}");
                $this->line("Midtrans Transaction Status: {$status->transaction_status}");

                if ($status->transaction_status !== $order->payment->status) {
                    $this->warn("STATUS MISMATCH DETECTED!");

                    if ($this->confirm('Update database with Midtrans status?')) {
                        $this->updateOrderStatus($order, $status);
                    }
                }
            }
        } catch (\Exception $e) {
            $this->error("Failed to check Midtrans: " . $e->getMessage());
        }
    }

    private function updateOrderStatus($order, $status)
    {
        $payment = $order->payment;
        $oldPaymentStatus = $payment->status;
        $oldOrderStatus = $order->status;

        $transaction_status = $status->transaction_status;
        $fraud_status = $status->fraud_status ?? null;

        if ($transaction_status == 'capture' && $fraud_status == 'accept') {
            $payment->status = 'settlement';
            $order->status = 'paid';
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

        $this->info("Status updated:");
        $this->info("Payment: {$oldPaymentStatus} → {$payment->status}");
        $this->info("Order: {$oldOrderStatus} → {$order->status}");
    }
}
