<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pembayaran Gagal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto px-4">
            <div class="bg-white rounded-lg shadow-lg p-8">

                <!-- Failed Icon -->
                <div class="w-20 h-20 mx-auto mb-6 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </div>

                <!-- Failed Message -->
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">Pembayaran Gagal</h1>
                    <p class="text-gray-600 mb-6">
                        Maaf, pembayaran Anda tidak dapat diproses. Silakan coba lagi dengan metode pembayaran yang
                        berbeda.
                    </p>
                </div>

                <!-- Order Details -->
                <div class="bg-gray-50 rounded-lg p-6 mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Detail Pesanan</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Nomor Invoice:</span>
                            <span class="font-medium">{{ $order->invoice_number }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Pembayaran:</span>
                            <span class="font-medium">Rp {{ number_format($order->gross_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Status:</span>
                            <span
                                class="inline-flex px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                Gagal
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tanggal Pesanan:</span>
                            <span class="font-medium">{{ $order->created_at->format('d M Y, H:i') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Possible Reasons -->
                <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-8">
                    <h3 class="text-lg font-semibold text-red-900 mb-3">Kemungkinan Penyebab</h3>
                    <ul class="space-y-2 text-red-800">
                        <li class="flex items-start">
                            <span class="w-2 h-2 bg-red-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                            Saldo tidak mencukupi
                        </li>
                        <li class="flex items-start">
                            <span class="w-2 h-2 bg-red-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                            Kartu kredit/debit ditolak oleh bank
                        </li>
                        <li class="flex items-start">
                            <span class="w-2 h-2 bg-red-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                            Informasi pembayaran tidak valid
                        </li>
                        <li class="flex items-start">
                            <span class="w-2 h-2 bg-red-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                            Koneksi internet terputus
                        </li>
                    </ul>
                </div>

                <!-- Product Details -->
                @if ($order->products->count() > 0)
                    <div class="bg-gray-50 rounded-lg p-6 mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Produk dalam Pesanan</h3>
                        @foreach ($order->products as $product)
                            <div class="flex items-center space-x-4 mb-4 last:mb-0">
                                @if ($product->images && count($product->images) > 0)
                                    <img src="{{ asset('storage/' . $product->images[0]) }}" alt="{{ $product->name }}"
                                        class="w-16 h-16 object-cover rounded">
                                @else
                                    <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                        <span class="text-gray-400 text-sm">No Image</span>
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900">{{ $product->name }}</h4>
                                    <p class="text-sm text-gray-600">Quantity: {{ $product->pivot->quantity }}</p>
                                    <p class="text-sm font-medium text-gray-900">Rp
                                        {{ number_format($product->pivot->price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 text-center">
                    @if($order->products && $order->products->count() > 0)
                        <a href="{{ route('products.public.show', $order->products->first()) }}"
                            class="flex-1 bg-red-600 hover:bg-red-700 text-white font-medium py-3 px-6 rounded-lg transition duration-200">
                            Coba Bayar Lagi
                        </a>
                    @else
                        <a href="{{ route('products.public.index') }}"
                            class="flex-1 bg-red-600 hover:bg-red-700 text-white font-medium py-3 px-6 rounded-lg transition duration-200">
                            Belanja Lagi
                        </a>
                    @endif
                    <a href="{{ route('products.public.index') }}"
                        class="flex-1 bg-gray-600 hover:bg-gray-700 text-white font-medium py-3 px-6 rounded-lg transition duration-200">
                        Pilih Produk Lain
                    </a>
                </div>
            </div>
        </div>
    </div>
    viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
    </path>
    </svg>
    </div>
    @endif

    <div class="flex-1">
        <h4 class="font-semibold text-gray-900">{{ $item->product->name }}</h4>
        <p class="text-sm text-gray-600 mb-1">{{ $item->product->seller->store_name }}</p>
        <p class="text-sm text-gray-500 mb-2">{{ ucfirst($item->product->category) }}</p>
        <div class="flex justify-between items-center">
            <span class="text-sm text-gray-600">Qty: {{ $item->quantity }}</span>
            <span class="font-semibold text-green-600">
                Rp {{ number_format($item->price, 0, ',', '.') }}
            </span>
        </div>
    </div>
    </div>
    </div>
    @endforeach

    <!-- Order Summary -->
    <div class="border-t border-gray-200 pt-4">
        <div class="flex justify-between items-center text-lg font-semibold">
            <span>Total:</span>
            <span class="text-green-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
        </div>
    </div>
    </div>

    <!-- Next Steps -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Apa yang bisa Anda lakukan?</h3>
        <div class="space-y-3 text-sm text-gray-600">
            <div class="flex items-start">
                <span class="text-indigo-600 font-medium mr-2">1.</span>
                <span>Coba lakukan pembayaran ulang dengan metode pembayaran yang berbeda</span>
            </div>
            <div class="flex items-start">
                <span class="text-indigo-600 font-medium mr-2">2.</span>
                <span>Pastikan saldo atau limit kartu kredit/debit Anda mencukupi</span>
            </div>
            <div class="flex items-start">
                <span class="text-indigo-600 font-medium mr-2">3.</span>
                <span>Hubungi customer service jika masalah berlanjut</span>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex justify-center space-x-4">
        <a href="{{ route('products.public.show', $order->orderItems->first()->product) }}"
            class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition duration-300">
            Coba Lagi
        </a>
        <a href="{{ route('products.public.index') }}"
            class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition duration-300">
            Lihat Produk Lain
        </a>
        <a href="{{ route('dashboard') }}"
            class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition duration-300">
            Ke Dashboard
        </a>
    </div>
    </div>
    </div>
</x-app-layout>
