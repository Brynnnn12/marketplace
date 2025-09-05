<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} - Digital Marketplace</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 font-sans antialiased">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <h1 class="text-2xl font-bold text-indigo-600">{{ config('app.name') }}</h1>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('dashboard') }}"
                            class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">
                            Dashboard
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit"
                                class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                            class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">
                            Login
                        </a>
                        <a href="{{ route('register') }}"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            Register
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold mb-6">
                    Digital Marketplace
                    <span class="block text-indigo-200">for Everyone</span>
                </h1>
                <p class="text-xl md:text-2xl mb-8 max-w-3xl mx-auto">
                    Jual dan beli produk digital dengan mudah dan aman. Dari e-course, template, software, hingga konten
                    kreatif.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    @guest
                        <a href="{{ route('products.public.index') }}"
                            class="bg-white text-indigo-600 hover:bg-gray-100 px-8 py-3 rounded-lg text-lg font-semibold transition duration-300">
                            Mulai Berbelanja
                        </a>
                        <a href="#seller-section"
                            class="border-2 border-white text-white hover:bg-white hover:text-indigo-600 px-8 py-3 rounded-lg text-lg font-semibold transition duration-300">
                            Jadi Penjual
                        </a>
                    @else
                        <a href="{{ route('products.public.index') }}"
                            class="bg-white text-indigo-600 hover:bg-gray-100 px-8 py-3 rounded-lg text-lg font-semibold transition duration-300">
                            Lihat Produk
                        </a>
                        <a href="{{ route('seller.application') }}"
                            class="border-2 border-white text-white hover:bg-white hover:text-indigo-600 px-8 py-3 rounded-lg text-lg font-semibold transition duration-300">
                            Jadi Penjual
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Mengapa Memilih Kami?
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Platform terpercaya untuk transaksi produk digital dengan keamanan terjamin
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="text-center p-6">
                    <div class="bg-indigo-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Pembayaran Aman</h3>
                    <p class="text-gray-600">Integrasi dengan Midtrans untuk pembayaran yang aman dan terpercaya dengan
                        berbagai metode pembayaran.</p>
                </div>

                <!-- Feature 2 -->
                <div class="text-center p-6">
                    <div class="bg-green-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Download Otomatis</h3>
                    <p class="text-gray-600">Setelah pembayaran berhasil, dapatkan link download yang aman dan dapat
                        diakses kapan saja.</p>
                </div>

                <!-- Feature 3 -->
                <div class="text-center p-6">
                    <div class="bg-purple-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Verifikasi Penjual</h3>
                    <p class="text-gray-600">Semua penjual melalui proses verifikasi admin untuk memastikan kualitas dan
                        kepercayaan.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Cara Kerja Platform
                </h2>
                <p class="text-xl text-gray-600">
                    Proses yang mudah dan transparan untuk pembeli dan penjual
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-12">
                <!-- For Buyers -->
                <div class="bg-white rounded-lg p-8 shadow-lg">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6 text-center">Untuk Pembeli</h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div
                                class="bg-indigo-600 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3 mt-1">
                                1</div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Daftar Akun</h4>
                                <p class="text-gray-600">Buat akun gratis dan verifikasi email Anda</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div
                                class="bg-indigo-600 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3 mt-1">
                                2</div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Pilih Produk</h4>
                                <p class="text-gray-600">Jelajahi berbagai produk digital berkualitas</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div
                                class="bg-indigo-600 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3 mt-1">
                                3</div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Bayar Aman</h4>
                                <p class="text-gray-600">Lakukan pembayaran melalui Midtrans</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div
                                class="bg-indigo-600 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3 mt-1">
                                4</div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Download</h4>
                                <p class="text-gray-600">Dapatkan link download aman setelah pembayaran</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- For Sellers -->
                <div class="bg-white rounded-lg p-8 shadow-lg" id="seller-section">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6 text-center">Untuk Penjual</h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div
                                class="bg-green-600 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3 mt-1">
                                1</div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Daftar Akun</h4>
                                <p class="text-gray-600">Buat akun dan ajukan menjadi penjual</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div
                                class="bg-green-600 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3 mt-1">
                                2</div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Verifikasi Admin</h4>
                                <p class="text-gray-600">Tunggu persetujuan dari admin</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div
                                class="bg-green-600 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3 mt-1">
                                3</div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Upload Produk</h4>
                                <p class="text-gray-600">Upload produk digital Anda</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div
                                class="bg-green-600 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3 mt-1">
                                4</div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Terima Pembayaran</h4>
                                <p class="text-gray-600">Monitor transaksi dan terima pembayaran</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Product Categories -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Kategori Produk Digital
                </h2>
                <p class="text-xl text-gray-600">
                    Berbagai jenis produk digital yang tersedia di platform kami
                </p>
            </div>

            <div class="grid md:grid-cols-4 gap-6">
                <div
                    class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-6 text-center hover:shadow-lg transition duration-300">
                    <div class="text-4xl mb-4">📚</div>
                    <h3 class="font-semibold text-gray-900 mb-2">E-Course</h3>
                    <p class="text-gray-600 text-sm">Kursus online, tutorial, dan materi pembelajaran</p>
                </div>

                <div
                    class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-6 text-center hover:shadow-lg transition duration-300">
                    <div class="text-4xl mb-4">🎨</div>
                    <h3 class="font-semibold text-gray-900 mb-2">Template</h3>
                    <p class="text-gray-600 text-sm">Template website, desain grafis, dan presentasi</p>
                </div>

                <div
                    class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-6 text-center hover:shadow-lg transition duration-300">
                    <div class="text-4xl mb-4">💻</div>
                    <h3 class="font-semibold text-gray-900 mb-2">Software</h3>
                    <p class="text-gray-600 text-sm">Aplikasi, plugin, dan tools digital</p>
                </div>

                <div
                    class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg p-6 text-center hover:shadow-lg transition duration-300">
                    <div class="text-4xl mb-4">🎵</div>
                    <h3 class="font-semibold text-gray-900 mb-2">Media</h3>
                    <p class="text-gray-600 text-sm">Audio, video, dan konten multimedia</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-indigo-600 py-16">
        <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                Siap Memulai?
            </h2>
            <p class="text-xl text-indigo-200 mb-8">
                Bergabunglah dengan ribuan pengguna yang sudah mempercayai platform kami
            </p>
            @guest
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('register') }}"
                        class="bg-white text-indigo-600 hover:bg-gray-100 px-8 py-3 rounded-lg text-lg font-semibold transition duration-300">
                        Daftar Sekarang
                    </a>
                    <a href="{{ route('login') }}"
                        class="border-2 border-white text-white hover:bg-white hover:text-indigo-600 px-8 py-3 rounded-lg text-lg font-semibold transition duration-300">
                        Masuk
                    </a>
                </div>
            @else
                <a href="{{ route('dashboard') }}"
                    class="bg-white text-indigo-600 hover:bg-gray-100 px-8 py-3 rounded-lg text-lg font-semibold transition duration-300">
                    Mulai Jual Beli
                </a>
            @endguest
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-white text-lg font-semibold mb-4">{{ config('app.name') }}</h3>
                    <p class="text-gray-400">
                        Platform digital marketplace terpercaya untuk jual beli produk digital dengan aman dan mudah.
                    </p>
                </div>

                <div>
                    <h4 class="text-white font-semibold mb-4">Untuk Pembeli</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-white transition duration-300">Cara Berbelanja</a>
                        </li>
                        <li><a href="#" class="hover:text-white transition duration-300">Keamanan Pembayaran</a>
                        </li>
                        <li><a href="#" class="hover:text-white transition duration-300">Download Produk</a>
                        </li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-semibold mb-4">Untuk Penjual</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-white transition duration-300">Cara Menjual</a></li>
                        <li><a href="#" class="hover:text-white transition duration-300">Panduan Upload</a></li>
                        <li><a href="#" class="hover:text-white transition duration-300">Kelola Transaksi</a>
                        </li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-semibold mb-4">Bantuan</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-white transition duration-300">FAQ</a></li>
                        <li><a href="#" class="hover:text-white transition duration-300">Kontak Support</a></li>
                        <li><a href="#" class="hover:text-white transition duration-300">Syarat & Ketentuan</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-8 pt-8 text-center">
                <p class="text-gray-400">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                </p>
            </div>
        </div>
    </footer>
</body>

</html>
