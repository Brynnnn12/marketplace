<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $seller = Auth::user()->seller;

        if (!$seller || $seller->status !== 'approved') {
            return redirect()->route('seller.application')
                ->with('error', 'Anda perlu menjadi penjual yang disetujui untuk mengakses halaman ini.');
        }

        $products = $seller->products()->latest()->paginate(12);

        return view('seller.products.index', compact('products', 'seller'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $seller = $user->seller()->first();

        if (!$seller || trim(strtolower($seller->status)) !== 'approved') {
            return redirect()->route('seller.application')
                ->with('error', 'Anda perlu menjadi penjual yang disetujui untuk menambah produk.');
        }

        return view('seller.products.create', compact('seller'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $seller = Auth::user()->seller;

        if (!$seller || $seller->status !== 'approved') {
            return redirect()->route('seller.application')
                ->with('error', 'Anda perlu menjadi penjual yang disetujui untuk menambah produk.');
        }

        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            'price' => 'required|numeric|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'file_path' => 'required|file|mimes:pdf,zip,rar,mp4,mp3,doc,docx,xls,xlsx,ppt,pptx|max:51200' // 50MB max
        ]);

        $product = new Product();
        $product->seller_id = $seller->id;
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products/images', 'public');
            $product->image = $imagePath;
        }

        // Handle file upload - store securely
        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $fileName = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('products/files', $fileName, 'private');
            $product->file_path = $filePath;
        }

        $product->save();

        return redirect()->route('seller.products.index')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $seller = Auth::user()->seller;

        if (!$seller || $product->seller_id !== $seller->id) {
            abort(403, 'Tidak memiliki akses ke produk ini.');
        }

        return view('seller.products.edit', compact('product', 'seller'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $seller = Auth::user()->seller;

        if (!$seller || $product->seller_id !== $seller->id) {
            abort(403, 'Tidak memiliki akses ke produk ini.');
        }

        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'file' => 'nullable|file|mimes:pdf,zip,rar,mp4,mp3,doc,docx,xls,xlsx,ppt,pptx|max:51200'
        ]);

        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            $imagePath = $request->file('image')->store('products/images', 'public');
            $product->image = $imagePath;
        }

        // Handle file upload
        if ($request->hasFile('file')) {
            // Delete old file
            if ($product->file_path && Storage::disk('private')->exists($product->file_path)) {
                Storage::disk('private')->delete($product->file_path);
            }

            $file = $request->file('file');
            $fileName = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('products/files', $fileName, 'private');
            $product->file_path = $filePath;
        }

        $product->save();

        return redirect()->route('seller.products.index')
            ->with('success', 'Produk berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $seller = Auth::user()->seller;

        if (!$seller || $product->seller_id !== $seller->id) {
            abort(403, 'Tidak memiliki akses ke produk ini.');
        }

        // Delete associated files
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        if ($product->file_path && Storage::disk('private')->exists($product->file_path)) {
            Storage::disk('private')->delete($product->file_path);
        }

        $product->delete();

        return redirect()->route('seller.products.index')
            ->with('success', 'Produk berhasil dihapus!');
    }
}
