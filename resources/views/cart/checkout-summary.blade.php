<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ringkasan Checkout') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Checkout Berhasil!</h3>

                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <p class="text-green-800 font-medium">Pesanan berhasil dibuat!</p>
                                <p class="text-green-700 text-sm">{{ count($orders) }} pesanan telah dibuat dari
                                    keranjang Anda.</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        @foreach ($orders as $order)
                            <div class="border border-gray-200 rounded-lg p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h4 class="font-semibold text-lg">{{ $order->invoice_number }}</h4>
                                        <p class="text-gray-600">{{ $order->created_at->format('d M Y, H:i') }}</p>
                                    </div>
                                    <span
                                        class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">
                                        Menunggu Pembayaran
                                    </span>
                                </div>

                                <div class="mb-4">
                                    <h5 class="font-medium text-gray-900 mb-2">Produk:</h5>
                                    @foreach ($order->products as $product)
                                        <div class="flex justify-between items-center py-2">
                                            <span>{{ $product->name }} ({{ $product->pivot->quantity }}x)</span>
                                            <span class="font-medium">Rp
                                                {{ number_format($product->pivot->price * $product->pivot->quantity, 0, ',', '.') }}</span>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="border-t pt-4 flex justify-between items-center">
                                    <span class="text-lg font-bold">Total: Rp
                                        {{ number_format($order->gross_amount, 0, ',', '.') }}</span>
                                    <a href="{{ route('payment.pending', $order) }}"
                                        class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg transition duration-300">
                                        Bayar Sekarang
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-8 flex gap-4">
                        <a href="{{ route('orders.index') }}"
                            class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition duration-300">
                            Lihat Semua Pesanan
                        </a>
                        <a href="{{ route('products.public.index') }}"
                            class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition duration-300">
                            Lanjut Belanja
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
