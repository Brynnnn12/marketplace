<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SellerApplicationController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PublicProductController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DownloadController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('marketplace-landing');
});

// Public product routes
Route::get('/products', [PublicProductController::class, 'index'])->name('products.public.index');
Route::get('/products/{product}', [PublicProductController::class, 'show'])->name('products.public.show');
Route::post('/products/{product}/purchase', [PublicProductController::class, 'purchase'])->name('products.public.purchase')->middleware('auth');

// Payment routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/payment/{product}', [PaymentController::class, 'createPayment'])->name('payment.create');
    Route::get('/payment/success/{order}', [PaymentController::class, 'paymentSuccess'])->name('payment.success');
    Route::get('/payment/failed/{order}', [PaymentController::class, 'paymentFailed'])->name('payment.failed');
    Route::get('/payment/pending/{order}', [PaymentController::class, 'paymentPending'])->name('payment.pending');
    Route::get('/payment/error/{order}', [PaymentController::class, 'paymentError'])->name('payment.error');
    Route::get('/payment/check-status/{order}', [PaymentController::class, 'checkPaymentStatus'])->name('payment.check-status');
});

// Download routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/download/{order}/{product}', [DownloadController::class, 'downloadProduct'])->name('download.product');
    Route::get('/download/{order}/all', [DownloadController::class, 'downloadAllOrderFiles'])->name('download.order');
});

// Note: Midtrans webhook moved to routes/api.php (no CSRF protection needed)


// Dashboard untuk user biasa (menggunakan Breeze)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'user'])->name('dashboard');

// Route untuk admin - redirect ke Filament admin panel
Route::get('/admin-dashboard', function () {
    return redirect('/admin');
})->middleware(['auth', 'admin'])->name('admin.dashboard');

// Seller Application Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/seller/application', [SellerApplicationController::class, 'showApplication'])->name('seller.application');
    Route::post('/seller/application', [SellerApplicationController::class, 'submitApplication'])->name('seller.application.submit');
    Route::get('/seller/dashboard', [SellerApplicationController::class, 'dashboard'])->name('seller.dashboard');
});

// Seller Product Management Routes
Route::middleware(['auth', 'verified'])->prefix('seller')->name('seller.')->group(function () {
    Route::resource('products', ProductController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
