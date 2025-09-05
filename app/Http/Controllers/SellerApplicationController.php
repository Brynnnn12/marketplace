<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class SellerApplicationController extends Controller
{
    public function showApplication()
    {
        // Check if user already has seller application
        $existingSeller = Seller::where('user_id', Auth::id())->first();

        return view('seller.application', compact('existingSeller'));
    }

    public function submitApplication(Request $request)
    {
        // Check if user already has seller application
        $existingSeller = Seller::where('user_id', Auth::id())->first();

        if ($existingSeller) {
            return redirect()->back()->with('error', 'Anda sudah mengajukan aplikasi menjadi penjual.');
        }

        $request->validate([
            'store_name' => 'required|string|max:255|unique:sellers,store_name',
            'store_description' => 'nullable|string|max:1000',
            'store_logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $logoPath = null;
        if ($request->hasFile('store_logo')) {
            $logoPath = $request->file('store_logo')->store('store-logos', 'public');
        }

        Seller::create([
            'user_id' => Auth::id(),
            'store_name' => $request->store_name,
            'store_description' => $request->store_description,
            'store_logo' => $logoPath,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Aplikasi Anda telah berhasil disubmit! Menunggu persetujuan admin.');
    }

    public function dashboard()
    {
        $seller = Seller::where('user_id', Auth::id())->first();

        if (!$seller || $seller->status !== 'approved') {
            return redirect()->route('seller.application')->with('error', 'Anda belum menjadi penjual yang disetujui.');
        }

        $products = $seller->products()->latest()->get();
        $totalProducts = $products->count();

        // Get product IDs for this seller
        $productIds = $products->pluck('id');

        // Calculate total earnings from paid orders only
        $totalEarnings = DB::table('order_details')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->whereIn('order_details.product_id', $productIds)
            ->where('orders.status', 'paid')
            ->sum(DB::raw('order_details.quantity * order_details.price'));

        // Calculate monthly earnings (current month)
        $monthlyEarnings = DB::table('order_details')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->whereIn('order_details.product_id', $productIds)
            ->where('orders.status', 'paid')
            ->whereMonth('orders.created_at', now()->month)
            ->whereYear('orders.created_at', now()->year)
            ->sum(DB::raw('order_details.quantity * order_details.price'));

        // Calculate total orders (paid orders only)
        $totalOrders = DB::table('order_details')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->whereIn('order_details.product_id', $productIds)
            ->where('orders.status', 'paid')
            ->distinct('orders.id')
            ->count('orders.id');

        // Calculate total sold products quantity
        $totalSoldQuantity = DB::table('order_details')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->whereIn('order_details.product_id', $productIds)
            ->where('orders.status', 'paid')
            ->sum('order_details.quantity');

        // Get recent orders for this seller's products (last 10)
        $recentOrders = DB::table('order_details')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->whereIn('order_details.product_id', $productIds)
            ->where('orders.status', 'paid')
            ->select(
                'orders.invoice_number',
                'orders.created_at',
                'products.name as product_name',
                'users.name as customer_name',
                'order_details.quantity',
                'order_details.price',
                DB::raw('order_details.quantity * order_details.price as total')
            )
            ->orderBy('orders.created_at', 'desc')
            ->limit(10)
            ->get();

        // Get daily earnings for the last 7 days (for chart)
        $dailyEarnings = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $earnings = DB::table('order_details')
                ->join('orders', 'order_details.order_id', '=', 'orders.id')
                ->whereIn('order_details.product_id', $productIds)
                ->where('orders.status', 'paid')
                ->whereDate('orders.created_at', $date->format('Y-m-d'))
                ->sum(DB::raw('order_details.quantity * order_details.price'));

            $dailyEarnings[] = [
                'date' => $date->format('d/m'),
                'earnings' => $earnings ?? 0
            ];
        }

        return view('seller.dashboard', compact(
            'seller',
            'products',
            'totalProducts',
            'totalEarnings',
            'monthlyEarnings',
            'totalOrders',
            'totalSoldQuantity',
            'recentOrders',
            'dailyEarnings'
        ));
    }
}
