<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Pesanan #{{ $order->invoice_number }}
            </h2>
            <a href="{{ route('orders.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition duration-300">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Riwayat
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Order Status -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row md:justify-between md:items-center">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $order->invoice_number }}</h3>
                            <p class="text-gray-600">Dibuat pada {{ $order->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div class="mt-4 md:mt-0 text-right">
                            @if ($order->status == 'paid')
                                <span
                                    class="inline-flex px-4 py-2 rounded-full text-lg font-medium bg-green-100 text-green-800">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Berhasil
                                </span>
                            @elseif($order->status == 'pending')
                                <span
                                    class="inline-flex px-4 py-2 rounded-full text-lg font-medium bg-yellow-100 text-yellow-800">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Menunggu Pembayaran
                                </span>
                            @elseif($order->status == 'failed')
                                <span
                                    class="inline-flex px-4 py-2 rounded-full text-lg font-medium bg-red-100 text-red-800">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Gagal
                                </span>
                            @elseif($order->status == 'expired')
                                <span
                                    class="inline-flex px-4 py-2 rounded-full text-lg font-medium bg-orange-100 text-orange-800">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Kadaluarsa
                                </span>
                            @elseif($order->status == 'cancelled')
                                <span
                                    class="inline-flex px-4 py-2 rounded-full text-lg font-medium bg-gray-100 text-gray-800">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728">
                                        </path>
                                    </svg>
                                    Dibatalkan
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Actions -->
            @if ($order->status == 'pending' && $snap_token)
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-semibold text-yellow-800 mb-2">Pembayaran Belum Selesai</h3>
                            <p class="text-yellow-700">Silakan selesaikan pembayaran untuk pesanan ini.</p>
                        </div>
                        <button onclick="payNow('{{ $snap_token }}')"
                            class="inline-flex items-center px-6 py-3 bg-yellow-600 hover:bg-yellow-700 text-white font-bold rounded-lg transition duration-300">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                            Bayar Sekarang
                        </button>
                    </div>
                </div>
            @endif

            <!-- Order Summary -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Ringkasan Pesanan</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Customer Info -->
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-3">Informasi Pembeli</h4>
                            <div class="space-y-2 text-sm">
                                <p><span class="font-medium">Nama:</span> {{ $order->user->name }}</p>
                                <p><span class="font-medium">Email:</span> {{ $order->user->email }}</p>
                            </div>
                        </div>

                        <!-- Payment Info -->
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-3">Informasi Pembayaran</h4>
                            <div class="space-y-2 text-sm">
                                <p><span class="font-medium">Total:</span>
                                    <span class="text-lg font-bold text-green-600">
                                        Rp {{ number_format($order->gross_amount, 0, ',', '.') }}
                                    </span>
                                </p>
                                @if ($payment)
                                    <p><span class="font-medium">Status Pembayaran:</span>
                                        <span class="capitalize">{{ $payment->status }}</span>
                                    </p>
                                @endif
                                @if ($order->updated_at != $order->created_at)
                                    <p><span class="font-medium">Terakhir Diupdate:</span>
                                        {{ $order->updated_at->format('d M Y, H:i') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Produk yang Dibeli</h3>

                    @if ($order->products && $order->products->count() > 0)
                        <div class="space-y-4">
                            @foreach ($order->products as $product)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-start space-x-4">
                                        <!-- Product Image -->
                                        @if ($product->image)
                                            <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                                                class="w-20 h-20 object-cover rounded-lg flex-shrink-0">
                                        @else
                                            <div
                                                class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                            </div>
                                        @endif

                                        <!-- Product Details -->
                                        <div class="flex-1">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <h4 class="font-semibold text-lg text-gray-900">
                                                        {{ $product->name }}</h4>
                                                    <p class="text-gray-600 mt-1">{{ $product->seller->store_name }}
                                                    </p>
                                                    <p class="text-sm text-gray-500 mt-2">
                                                        Kuantitas: {{ $product->pivot->quantity }}
                                                    </p>
                                                    @if ($product->file_path)
                                                        <div class="mt-2">
                                                            <span
                                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                                <svg class="w-3 h-3 mr-1" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                                    </path>
                                                                </svg>
                                                                File Digital
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="text-right">
                                                    <p class="text-xl font-bold text-gray-900">
                                                        Rp {{ number_format($product->pivot->price, 0, ',', '.') }}
                                                    </p>

                                                    <!-- Download Button -->
                                                    @if ($order->status == 'paid' && $product->file_path)
                                                        <a href="{{ route('download.product', [$order, $product]) }}"
                                                            class="inline-flex items-center mt-3 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition duration-300">
                                                            <svg class="w-4 h-4 mr-2" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                                </path>
                                                            </svg>
                                                            Download File
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Product Description -->
                                            @if ($product->description)
                                                <div class="mt-3 pt-3 border-t border-gray-200">
                                                    <p class="text-sm text-gray-700">
                                                        {{ Str::limit($product->description, 200) }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Total -->
                        <div class="border-t border-gray-200 pt-4 mt-6">
                            <div class="flex justify-between items-center">
                                <span class="text-xl font-bold text-gray-900">Total Pembayaran:</span>
                                <span class="text-2xl font-bold text-green-600">
                                    Rp {{ number_format($order->gross_amount, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex flex-wrap gap-3">
                        @if ($order->status == 'pending')
                            <a href="{{ route('payment.pending', $order) }}"
                                class="inline-flex items-center px-6 py-3 bg-yellow-600 hover:bg-yellow-700 text-white font-bold rounded-lg transition duration-300">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Lihat Status Pembayaran
                            </a>
                        @elseif($order->status == 'paid')
                            <a href="{{ route('payment.success', $order) }}"
                                class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg transition duration-300">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Lihat Receipt
                            </a>
                        @elseif(in_array($order->status, ['failed', 'expired', 'cancelled']))
                            <a href="{{ route('payment.failed', $order) }}"
                                class="inline-flex items-center px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-lg transition duration-300">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.728-.833-2.498 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z">
                                    </path>
                                </svg>
                                Detail Error
                            </a>
                        @endif

                        <a href="{{ route('orders.index') }}"
                            class="inline-flex items-center px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition duration-300">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Kembali ke Riwayat
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Midtrans Snap Script -->
    @if ($order->status == 'pending' && $snap_token)
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
        </script>
        <script>
            function payNow(snapToken) {
                snap.pay(snapToken, {
                    onSuccess: function(result) {
                        console.log('Payment success:', result);
                        window.location.href = "{{ route('payment.check-status', $order) }}";
                    },
                    onPending: function(result) {
                        console.log('Payment pending:', result);
                        window.location.href = "{{ route('payment.check-status', $order) }}";
                    },
                    onError: function(result) {
                        console.log('Payment error:', result);
                        window.location.href = "{{ route('payment.error', $order) }}";
                    },
                    onClose: function() {
                        console.log('Payment popup closed');
                        // Check status after popup closed
                        setTimeout(() => {
                            window.location.href = "{{ route('payment.check-status', $order) }}";
                        }, 2000);
                    }
                });
            }
        </script>
    @endif
</x-app-layout>
