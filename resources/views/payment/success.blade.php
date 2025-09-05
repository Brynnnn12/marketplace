<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pembayaran Berhasil') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <!-- Success Message -->
            <div class="bg-white rounded-lg shadow-sm p-8 text-center mb-8">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-6">
                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>

                <h1 class="text-3xl font-bold text-gray-900 mb-4">Pembayaran Berhasil!</h1>
                <p class="text-lg text-gray-600 mb-6">
                    Terima kasih telah berbelanja. Pesanan Anda sedang diproses.
                </p>

                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <p class="text-sm text-gray-600">Nomor Pesanan</p>
                    <p class="text-xl font-bold text-gray-900">{{ $order->invoice_number }}</p>
                </div>

                @if ($order->status === 'paid')
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                        <p class="text-green-800 font-medium mb-2">🎉 Pembayaran berhasil dikonfirmasi!</p>
                        <p class="text-green-600 text-sm">Terima kasih telah berbelanja dengan kami.</p>
                    </div>
                @else
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                        <p class="text-yellow-800 font-medium mb-2">⏳ Pembayaran sedang diproses</p>
                        <p class="text-yellow-600 text-sm">Status pembayaran akan diperbarui setelah dikonfirmasi.</p>
                    </div>
                @endif
            </div>

            <!-- Order Details -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Detail Pesanan</h3>

                @if ($order->products && $order->products->count() > 0)
                    @foreach ($order->products as $product)
                        <div class="border border-gray-200 rounded-lg p-4 mb-4">
                            <div class="flex items-start space-x-4">
                                @if ($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                        class="w-16 h-16 object-cover rounded-lg">
                                @else
                                    <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                @endif

                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900">{{ $product->name }}</h4>
                                    <p class="text-sm text-gray-600 mb-1">{{ $product->seller->store_name }}</p>
                                    <p class="text-sm text-gray-500 mb-2">{{ $product->description }}</p>
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-sm text-gray-600">Qty: {{ $product->pivot->quantity }}</span>
                                        <span class="font-semibold text-green-600">
                                            Rp {{ number_format($product->pivot->price, 0, ',', '.') }}
                                        </span>
                                    </div>

                                    <!-- Download Section -->
                                    @if ($product->file_path && $order->status === 'paid')
                                        <div class="mt-3 p-3 bg-green-50 rounded-lg border border-green-200">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center">
                                                    <svg class="w-5 h-5 text-green-600 mr-2" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                        </path>
                                                    </svg>
                                                    <div>
                                                        <p class="text-sm font-medium text-green-800">File siap
                                                            didownload</p>
                                                        <p class="text-xs text-green-600">
                                                            {{ basename($product->file_path) }}</p>
                                                    </div>
                                                </div>
                                                <a href="{{ route('download.product', [$order, $product]) }}"
                                                    class="inline-flex items-center px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition duration-200">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4">
                                                        </path>
                                                    </svg>
                                                    Download
                                                </a>
                                            </div>
                                        </div>
                                    @elseif($product->file_path)
                                        <div class="mt-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                            <div class="flex items-center">
                                                <svg class="w-5 h-5 text-gray-400 mr-2" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                                    </path>
                                                </svg>
                                                <p class="text-sm text-gray-600">File akan tersedia setelah pembayaran
                                                    dikonfirmasi</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-500">Tidak ada detail produk yang ditemukan.</p>
                    </div>
                @endif

                <!-- Order Summary -->
                <div class="border-t border-gray-200 pt-4">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-600">Subtotal:</span>
                        <span>Rp {{ number_format($order->gross_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-600">Biaya Admin:</span>
                        <span>Rp 0</span>
                    </div>
                    <div class="flex justify-between items-center text-lg font-semibold pt-2 border-t border-gray-200">
                        <span>Total:</span>
                        <span class="text-green-600">Rp {{ number_format($order->gross_amount, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Order Info -->
                <div class="mt-6 pt-6 border-t border-gray-200 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-600">Status Pesanan:</span>
                        <span
                            class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                   {{ $order->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    <div>
                        <span class="text-gray-600">Tanggal Pesanan:</span>
                        <span class="ml-2">{{ $order->created_at->format('d M Y, H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-8 flex flex-col sm:flex-row justify-center gap-4">
                @php
                    $hasDownloadableFiles = $order->products->where('file_path', '!=', null)->count() > 0;
                @endphp

                @if ($hasDownloadableFiles && $order->status === 'paid')
                    <a href="{{ route('download.order', $order) }}"
                        class="inline-flex items-center justify-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition duration-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Download Semua File
                    </a>
                @endif

                <a href="{{ route('products.public.index') }}"
                    class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition duration-300">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    Belanja Lagi
                </a>
                <a href="{{ route('dashboard') }}"
                    class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition duration-300">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 5a2 2 0 012-2h2a2 2 0 012 2v2H8V5z"></path>
                    </svg>
                    Ke Dashboard
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
