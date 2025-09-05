<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Error Pembayaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto px-4">
            <div class="bg-white rounded-lg shadow-lg p-8">

                <!-- Error Icon -->
                <div class="w-20 h-20 mx-auto mb-6 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z">
                        </path>
                    </svg>
                </div>

                <!-- Error Message -->
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">Terjadi Kesalahan</h1>
                    <p class="text-gray-600 mb-6">
                        Maaf, terjadi kesalahan teknis dalam proses pembayaran. Tim kami sedang menangani masalah ini.
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
                                Error
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tanggal Pesanan:</span>
                            <span class="font-medium">{{ $order->created_at->format('d M Y, H:i') }}</span>
                        </div>
                    </div>
                </div>

                <!-- What to do -->
                <div class="bg-orange-50 border border-orange-200 rounded-lg p-6 mb-8">
                    <h3 class="text-lg font-semibold text-orange-900 mb-3">Apa yang harus dilakukan?</h3>
                    <ul class="space-y-2 text-orange-800">
                        <li class="flex items-start">
                            <span class="w-2 h-2 bg-orange-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                            Tunggu beberapa menit, lalu coba lagi
                        </li>
                        <li class="flex items-start">
                            <span class="w-2 h-2 bg-orange-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                            Periksa koneksi internet Anda
                        </li>
                        <li class="flex items-start">
                            <span class="w-2 h-2 bg-orange-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                            Hubungi customer service jika masalah berlanjut
                        </li>
                        <li class="flex items-start">
                            <span class="w-2 h-2 bg-orange-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                            Simpan nomor invoice untuk referensi
                        </li>
                    </ul>
                </div>

                <!-- Contact Support -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                    <h3 class="text-lg font-semibold text-blue-900 mb-3">Butuh Bantuan?</h3>
                    <p class="text-blue-800 mb-3">
                        Tim customer service kami siap membantu Anda 24/7:
                    </p>
                    <div class="text-blue-800">
                        <p>📧 Email: support@marketplace.com</p>
                        <p>📱 WhatsApp: +62 812-3456-7890</p>
                        <p>💬 Live Chat: Tersedia di website</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 text-center">
                    @if ($order->products && $order->products->count() > 0)
                        <a href="{{ route('products.public.show', $order->products->first()) }}"
                            class="flex-1 bg-red-600 hover:bg-red-700 text-white font-medium py-3 px-6 rounded-lg transition duration-200">
                            Coba Lagi
                        </a>
                    @else
                        <a href="{{ route('products.public.index') }}"
                            class="flex-1 bg-red-600 hover:bg-red-700 text-white font-medium py-3 px-6 rounded-lg transition duration-200">
                            Coba Lagi
                        </a>
                    @endif
                    <a href="{{ route('dashboard') }}"
                        class="flex-1 bg-gray-600 hover:bg-gray-700 text-white font-medium py-3 px-6 rounded-lg transition duration-200">
                        Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
