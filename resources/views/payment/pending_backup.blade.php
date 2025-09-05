<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pembayaran Pending') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto px-4">
            <div class="bg-white rounded-lg shadow-lg p-8">

                <!-- Pending Icon -->
                <div class="w-20 h-20 mx-auto mb-6 bg-yellow-100 rounded-full flex items-center justify-center">
                    <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>

                <!-- Pending Message -->
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">Pembayaran Sedang Diproses</h1>
                    <p class="text-gray-600 mb-6">
                        Pembayaran Anda sedang dalam proses verifikasi. Silakan selesaikan pembayaran sesuai dengan
                        instruksi yang diberikan.
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
                                class="inline-flex px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                Menunggu Pembayaran
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tanggal Pesanan:</span>
                            <span class="font-medium">{{ $order->created_at->format('d M Y, H:i') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Instructions -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                    <h3 class="text-lg font-semibold text-blue-900 mb-3">Instruksi Pembayaran</h3>
                    <ul class="space-y-2 text-blue-800">
                        <li class="flex items-start">
                            <span class="w-2 h-2 bg-blue-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                            Selesaikan pembayaran sesuai dengan metode yang Anda pilih
                        </li>
                        <li class="flex items-start">
                            <span class="w-2 h-2 bg-blue-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                            Status pesanan akan otomatis terupdate setelah pembayaran dikonfirmasi
                        </li>
                        <li class="flex items-start">
                            <span class="w-2 h-2 bg-blue-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                            Jika ada kendala, hubungi customer service kami
                        </li>
                        <li class="flex items-start">
                            <span class="w-2 h-2 bg-blue-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                            Pembayaran akan kadaluarsa dalam 24 jam
                        </li>
                    </ul>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 text-center">
                    <button onclick="location.reload()"
                        class="flex-1 bg-yellow-600 hover:bg-yellow-700 text-white font-medium py-3 px-6 rounded-lg transition duration-200">
                        Refresh Status
                    </button>
                    <a href="{{ route('dashboard') }}"
                        class="flex-1 bg-gray-600 hover:bg-gray-700 text-white font-medium py-3 px-6 rounded-lg transition duration-200">
                        Kembali ke Dashboard
                    </a>
                </div>

                <!-- Auto Refresh Script -->
                <script>
                    // Auto refresh every 30 seconds to check payment status
                    setTimeout(function() {
                        location.reload();
                    }, 30000);
                </script>
            </div>
        </div>
    </div>
    </div>

    <div class="space-x-4">
        <a href="{{ route('products.public.index') }}"
            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
            Lihat Produk Lain
        </a>

        <a href="{{ route('dashboard') }}"
            class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
            Dashboard
        </a>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>
</x-app-layout>
