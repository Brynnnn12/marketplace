<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Aplikasi Menjadi Penjual') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if (session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($existingSeller)
                        <!-- Existing Application Status -->
                        <div class="text-center">
                            <div class="mb-6">
                                <h3 class="text-2xl font-bold text-gray-900 mb-4">Status Aplikasi Anda</h3>

                                <div class="bg-gray-50 rounded-lg p-6 mb-6">
                                    <div class="flex items-center justify-center mb-4">
                                        @if ($existingSeller->store_logo)
                                            <img src="{{ Storage::url($existingSeller->store_logo) }}"
                                                alt="{{ $existingSeller->store_name }}"
                                                class="w-20 h-20 rounded-full object-cover">
                                        @else
                                            <div
                                                class="w-20 h-20 bg-gray-300 rounded-full flex items-center justify-center">
                                                <svg class="w-10 h-10 text-gray-600" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z">
                                                    </path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>

                                    <h4 class="text-xl font-semibold text-gray-900 mb-2">
                                        {{ $existingSeller->store_name }}</h4>

                                    @if ($existingSeller->store_description)
                                        <p class="text-gray-600 mb-4">{{ $existingSeller->store_description }}</p>
                                    @endif

                                    <div
                                        class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                                        @if ($existingSeller->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($existingSeller->status === 'approved') bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800 @endif">

                                        @if ($existingSeller->status === 'pending')
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Menunggu Persetujuan
                                        @elseif($existingSeller->status === 'approved')
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Disetujui
                                        @else
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z">
                                                </path>
                                            </svg>
                                            Ditolak
                                        @endif
                                    </div>
                                </div>

                                @if ($existingSeller->status === 'approved')
                                    <div class="space-y-4">
                                        <a href="{{ route('seller.dashboard') }}"
                                            class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition duration-300">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                                                </path>
                                            </svg>
                                            Dashboard Penjual
                                        </a>
                                    </div>
                                @elseif($existingSeller->status === 'pending')
                                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                        <p class="text-blue-800">
                                            <strong>Aplikasi Anda sedang dalam proses review.</strong><br>
                                            Tim admin kami akan meninjau aplikasi Anda dalam 1-3 hari kerja. Anda akan
                                            mendapat notifikasi email setelah aplikasi disetujui atau ditolak.
                                        </p>
                                    </div>
                                @else
                                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                        <p class="text-red-800">
                                            <strong>Aplikasi Anda ditolak.</strong><br>
                                            Silakan hubungi tim support untuk informasi lebih lanjut atau ajukan ulang
                                            dengan informasi yang lebih lengkap.
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <!-- Application Form -->
                        <div class="max-w-2xl mx-auto">
                            <div class="text-center mb-8">
                                <h3 class="text-2xl font-bold text-gray-900 mb-4">Ajukan Menjadi Penjual</h3>
                                <p class="text-gray-600">
                                    Bergabunglah dengan ribuan penjual lainnya dan mulai jual produk digital Anda hari
                                    ini!
                                </p>
                            </div>

                            <form method="POST" action="{{ route('seller.application.submit') }}"
                                enctype="multipart/form-data" class="space-y-6">
                                @csrf

                                <!-- Store Name -->
                                <div>
                                    <label for="store_name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nama Toko <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="store_name" name="store_name"
                                        value="{{ old('store_name') }}" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                        placeholder="Masukkan nama toko Anda">
                                    @error('store_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Store Description -->
                                <div>
                                    <label for="store_description" class="block text-sm font-medium text-gray-700 mb-2">
                                        Deskripsi Toko
                                    </label>
                                    <textarea id="store_description" name="store_description" rows="4"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                        placeholder="Ceritakan tentang toko Anda, jenis produk yang akan dijual, dan keunggulan Anda">{{ old('store_description') }}</textarea>
                                    @error('store_description')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Store Logo -->
                                <div>
                                    <label for="store_logo" class="block text-sm font-medium text-gray-700 mb-2">
                                        Logo Toko
                                    </label>
                                    <input type="file" id="store_logo" name="store_logo"
                                        accept="image/jpeg,image/png,image/jpg"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    <p class="mt-1 text-sm text-gray-500">
                                        Format: JPG, PNG (Max: 2MB). Logo akan ditampilkan di halaman toko Anda.
                                    </p>
                                    @error('store_logo')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Terms and Conditions -->
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <h4 class="font-medium text-gray-900 mb-2">Syarat dan Ketentuan:</h4>
                                    <ul class="text-sm text-gray-600 space-y-1">
                                        <li>• Semua produk yang dijual harus berupa konten digital original atau
                                            memiliki lisensi yang sah</li>
                                        <li>• Penjual bertanggung jawab atas kualitas dan legalitas produk yang dijual
                                        </li>
                                        <li>• Komisi platform adalah 10% dari setiap transaksi yang berhasil</li>
                                        <li>• Pembayaran akan ditransfer ke rekening penjual setiap minggu</li>
                                        <li>• Admin berhak menolak atau menonaktifkan toko yang melanggar aturan</li>
                                    </ul>
                                </div>

                                <!-- Submit Button -->
                                <div class="text-center">
                                    <button type="submit"
                                        class="w-full sm:w-auto px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition duration-300">
                                        Ajukan Aplikasi
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
