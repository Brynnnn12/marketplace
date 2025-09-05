<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Welcome Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Selamat Datang, {{ Auth::user()->name }}!</h3>
                    <p class="text-gray-600">Kelola pesanan dan akun Anda dari dashboard ini.</p>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @php
                    $orders = Auth::user()->orders ?? collect();
                    $totalOrders = $orders->count();
                    $totalSpent = $orders->where('status', 'paid')->sum('gross_amount');
                    $pendingOrders = $orders->where('status', 'pending')->count();
                @endphp

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-2 bg-blue-500 rounded-lg mr-4">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                            </div>
                            <div>
                                @if ($totalOrders > 0)
                                    <p class="text-2xl font-bold text-gray-900">{{ $totalOrders }}</p>
                                    <p class="text-gray-600">Total Pesanan</p>
                                @else
                                    <p class="text-2xl font-bold text-gray-900">0</p>
                                    <p class="text-gray-600">Total Pesanan</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-2 bg-green-500 rounded-lg mr-4">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-gray-900">Rp
                                    {{ number_format($totalSpent, 0, ',', '.') }}</p>
                                <p class="text-gray-600">Total Belanja</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-2 bg-yellow-500 rounded-lg mr-4">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-gray-900">{{ $pendingOrders }}</p>
                                <p class="text-gray-600">Pesanan Pending</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-gray-900">Riwayat Pesanan Terbaru</h3>
                        <a href="#" class="text-blue-600 hover:text-blue-800 font-medium">Lihat Semua</a>
                    </div>

                    @if ($orders->count() > 0)
                        <div class="space-y-4">
                            @foreach ($orders->take(5) as $order)
                                <div
                                    class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition duration-200">
                                    <div class="flex justify-between items-start mb-3">
                                        <div>
                                            <h4 class="font-semibold text-gray-900">{{ $order->invoice_number }}</h4>
                                            <p class="text-sm text-gray-600">
                                                {{ $order->created_at->format('d M Y, H:i') }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-bold text-gray-900">Rp
                                                {{ number_format($order->gross_amount, 0, ',', '.') }}</p>
                                            @if ($order->status == 'paid')
                                                <span
                                                    class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Berhasil</span>
                                            @elseif($order->status == 'pending')
                                                <span
                                                    class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span>
                                            @elseif($order->status == 'failed')
                                                <span
                                                    class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">Gagal</span>
                                            @else
                                                <span
                                                    class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ ucfirst($order->status) }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Products in Order -->
                                    @if ($order->products && $order->products->count() > 0)
                                        <div class="space-y-2">
                                            @foreach ($order->products->take(2) as $product)
                                                <div class="flex items-center space-x-3">
                                                    @if ($product->images && count($product->images) > 0)
                                                        <img src="{{ asset('storage/' . $product->images[0]) }}"
                                                            alt="{{ $product->name }}"
                                                            class="w-10 h-10 object-cover rounded">
                                                    @else
                                                        <div
                                                            class="w-10 h-10 bg-gray-200 rounded flex items-center justify-center">
                                                            <svg class="w-4 h-4 text-gray-400" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                                </path>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                    <div class="flex-1">
                                                        <p class="text-sm font-medium text-gray-900">
                                                            {{ Str::limit($product->name, 30) }}</p>
                                                        <p class="text-xs text-gray-600">Qty:
                                                            {{ $product->pivot->quantity }}</p>
                                                    </div>
                                                    <p class="text-sm font-medium text-gray-900">Rp
                                                        {{ number_format($product->pivot->price, 0, ',', '.') }}</p>
                                                </div>
                                            @endforeach
                                            @if ($order->products && $order->products->count() > 2)
                                                <p class="text-xs text-gray-500">+{{ $order->products->count() - 2 }}
                                                    produk lainnya</p>
                                            @endif
                                        </div>
                                    @endif

                                    <!-- Action Buttons -->
                                    <div class="mt-4 pt-3 border-t border-gray-200 flex gap-2 flex-wrap">
                                        @if ($order->status == 'pending')
                                            <a href="{{ route('payment.pending', $order) }}"
                                                class="text-xs bg-yellow-100 hover:bg-yellow-200 text-yellow-800 px-3 py-1 rounded-full transition duration-200">
                                                Lihat Status
                                            </a>
                                        @elseif($order->status == 'paid')
                                            <a href="{{ route('payment.success', $order) }}"
                                                class="text-xs bg-green-100 hover:bg-green-200 text-green-800 px-3 py-1 rounded-full transition duration-200">
                                                Lihat Detail
                                            </a>

                                            @php
                                                $hasDownloadableFiles =
                                                    $order->products &&
                                                    $order->products->where('file_path', '!=', null)->count() > 0;
                                            @endphp

                                            @if ($hasDownloadableFiles)
                                                <a href="{{ route('download.order', $order) }}"
                                                    class="text-xs bg-blue-100 hover:bg-blue-200 text-blue-800 px-3 py-1 rounded-full transition duration-200">
                                                    Download File
                                                </a>
                                            @endif
                                        @elseif($order->status == 'failed')
                                            <a href="{{ route('payment.failed', $order) }}"
                                                class="text-xs bg-red-100 hover:bg-red-200 text-red-800 px-3 py-1 rounded-full transition duration-200">
                                                Lihat Detail
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Pesanan</h3>
                            <p class="text-gray-600 mb-6">Mulai berbelanja untuk melihat riwayat pesanan Anda disini.
                            </p>
                            <a href="{{ route('products.public.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition ease-in-out duration-150">
                                Mulai Belanja
                            </a>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
