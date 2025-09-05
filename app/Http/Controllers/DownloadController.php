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

    public function downloadAllOrderFiles(Order $order)
    {
        // Check if user owns this order
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        // Check if order is paid
        if ($order->status !== 'paid') {
            abort(403, 'Payment not completed');
        }

        // Load products with files
        $order->load('products');
        $productsWithFiles = $order->products->filter(function ($product) {
            return $product->file_path && Storage::exists($product->file_path);
        });

        if ($productsWithFiles->isEmpty()) {
            abort(404, 'No downloadable files found');
        }

        // If only one file, download directly
        if ($productsWithFiles->count() === 1) {
            $product = $productsWithFiles->first();
            return $this->downloadProduct($order, $product);
        }

        // Multiple files - create ZIP
        return $this->createZipDownload($order, $productsWithFiles);
    }

    private function createZipDownload(Order $order, $products)
    {
        $zip = new \ZipArchive();
        $zipFileName = 'order-' . $order->invoice_number . '-files.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);

        // Create temp directory if it doesn't exist
        if (!file_exists(dirname($zipPath))) {
            mkdir(dirname($zipPath), 0755, true);
        }

        if ($zip->open($zipPath, \ZipArchive::CREATE) === TRUE) {
            foreach ($products as $product) {
                if ($product->file_path && Storage::exists($product->file_path)) {
                    $filePath = Storage::path($product->file_path);
                    $fileName = $product->name . '.' . pathinfo($product->file_path, PATHINFO_EXTENSION);
                    $zip->addFile($filePath, $fileName);
                }
            }
            $zip->close();

            // Return download response and cleanup
            return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
        } else {
            abort(500, 'Could not create download file');
        }
    }
}
