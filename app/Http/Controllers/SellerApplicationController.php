<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

        // Get recent orders for this seller's products
        $recentOrders = collect(); // Will be implemented when order system is ready

        return view('seller.dashboard', compact('seller', 'products', 'totalProducts', 'recentOrders'));
    }
}
