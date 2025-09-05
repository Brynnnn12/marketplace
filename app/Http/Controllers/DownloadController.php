<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class DownloadController extends Controller
{
    public function downloadProduct(Order $order, Product $product)
    {
        // Check if user owns this order
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        // Check if order is paid
        if ($order->status !== 'paid') {
            abort(403, 'Payment not completed');
        }

        // Check if product is in this order
        $orderHasProduct = $order->products()->where('product_id', $product->id)->exists();
        if (!$orderHasProduct) {
            abort(404, 'Product not found in this order');
        }

        // Check if product has file
        if (!$product->file_path || !Storage::exists($product->file_path)) {
            abort(404, 'File not found');
        }

        // Get file info
        $fileName = basename($product->file_path);
        $originalName = $product->name . '.' . pathinfo($fileName, PATHINFO_EXTENSION);

        // Return file download response
        return Storage::download($product->file_path, $originalName, [
            'Content-Type' => Storage::mimeType($product->file_path),
        ]);
    }
}
