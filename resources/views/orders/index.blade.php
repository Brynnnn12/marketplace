<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Riwayat Pesanan') }}
            </h2>
            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition duration-300">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Filter & Search -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('orders.index') }}" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Search -->
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                                    Cari Nomor Invoice
                                </label>
                                <input type="text" name="search" id="search" value="{{ request('search') }}"
                                    placeholder="Masukkan nomor invoice..."
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <!-- Status Filter -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                    Filter Status
                                </label>
                                <select name="status" id="status"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Semua Status</option>
                                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Berhasil
                                    </option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                        Pending</option>
                                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Gagal
                                    </option>
                                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>
                                        Kadaluarsa</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                                        Dibatalkan</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Cari
                            </button>

                            @if (request()->hasAny(['search', 'status']))
                                <a href="{{ route('orders.index') }}"
                                    class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition ease-in-out duration-150">
                                    Reset
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Status Tabs -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('orders.index') }}"
                            class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium transition duration-300 
                                {{ !request('status') ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            Semua ({{ $allCount }})
                        </a>
                        <a href="{{ route('orders.index', ['status' => 'paid']) }}"
                            class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium transition duration-300 
                                {{ request('status') == 'paid' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            Berhasil ({{ $paidCount }})
                        </a>
                        <a href="{{ route('orders.index', ['status' => 'pending']) }}"
                            class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium transition duration-300 
                                {{ request('status') == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            Pending ({{ $pendingCount }})
                        </a>
                        <a href="{{ route('orders.index', ['status' => 'failed']) }}"
                            class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium transition duration-300 
                                {{ in_array(request('status'), ['failed', 'expired', 'cancelled']) ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            Gagal ({{ $failedCount }})
                        </a>
                    </div>
                </div>
            </div>

            <!-- Orders List -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if ($orders->count() > 0)
                        <div class="space-y-4">
                            @foreach ($orders as $order)
                                <div
                                    class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition duration-200">
                                    <!-- Order Header -->
                                    <div class="flex flex-col md:flex-row md:justify-between md:items-start mb-4">
                                        <div class="mb-2 md:mb-0">
                                            <h3 class="font-bold text-lg text-gray-900">{{ $order->invoice_number }}
                                            </h3>
                                            <p class="text-sm text-gray-600">
                                                {{ $order->created_at->format('d M Y, H:i') }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-bold text-xl text-gray-900 mb-2">
                                                Rp {{ number_format($order->gross_amount, 0, ',', '.') }}
                                            </p>
                                            @if ($order->status == 'paid')
                                                <span
                                                    class="inline-flex px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                    Berhasil
                                                </span>
                                            @elseif($order->status == 'pending')
                                                <span
                                                    class="inline-flex px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                                    Pending
                                                </span>
                                            @elseif($order->status == 'failed')
                                                <span
                                                    class="inline-flex px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                                    Gagal
                                                </span>
                                            @elseif($order->status == 'expired')
                                                <span
                                                    class="inline-flex px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                                                    Kadaluarsa
                                                </span>
                                            @elseif($order->status == 'cancelled')
                                                <span
                                                    class="inline-flex px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                                    Dibatalkan
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Order Products -->
                                    @if ($order->products && $order->products->count() > 0)
                                        <div class="border-t border-gray-200 pt-4">
                                            <h4 class="font-medium text-gray-900 mb-3">Produk yang dibeli:</h4>
                                            <div class="space-y-3">
                                                @foreach ($order->products as $product)
                                                    <div class="flex items-center space-x-4">
                                                        @if ($product->image)
                                                            <img src="{{ Storage::url($product->image) }}"
                                                                alt="{{ $product->name }}"
                                                                class="w-16 h-16 object-cover rounded-lg">
                                                        @else
                                                            <div
                                                                class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                                                <svg class="w-6 h-6 text-gray-400" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                                    </path>
                                                                </svg>
                                                            </div>
                                                        @endif

                                                        <div class="flex-1">
                                                            <h5 class="font-medium text-gray-900">{{ $product->name }}
                                                            </h5>
                                                            <p class="text-sm text-gray-600">
                                                                {{ $product->seller->store_name }}
                                                            </p>
                                                            <p class="text-sm text-gray-600">
                                                                Qty: {{ $product->pivot->quantity }}
                                                            </p>
                                                        </div>

                                                        <div class="text-right">
                                                            <p class="font-medium text-gray-900">
                                                                Rp
                                                                {{ number_format($product->pivot->price, 0, ',', '.') }}
                                                            </p>

                                                            <!-- Download button if order is paid -->
                                                            @if ($order->status == 'paid' && $product->file_path)
                                                                <a href="{{ route('download.product', [$order, $product]) }}"
                                                                    class="inline-flex items-center mt-2 px-3 py-1 bg-blue-100 hover:bg-blue-200 text-blue-800 text-xs font-medium rounded-full transition duration-200">
                                                                    <svg class="w-3 h-3 mr-1" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                                        </path>
                                                                    </svg>
                                                                    Download
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Action Buttons -->
                                    <div class="border-t border-gray-200 pt-4 mt-4 flex flex-wrap gap-2">
                                        <a href="{{ route('orders.show', $order) }}"
                                            class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 text-sm font-medium rounded-lg transition duration-200">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                            Detail
                                        </a>

                                        @if ($order->status == 'pending')
                                            <a href="{{ route('payment.pending', $order) }}"
                                                class="inline-flex items-center px-4 py-2 bg-yellow-100 hover:bg-yellow-200 text-yellow-800 text-sm font-medium rounded-lg transition duration-200">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Bayar Sekarang
                                            </a>
                                        @elseif($order->status == 'paid')
                                            <a href="{{ route('payment.success', $order) }}"
                                                class="inline-flex items-center px-4 py-2 bg-green-100 hover:bg-green-200 text-green-800 text-sm font-medium rounded-lg transition duration-200">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Lihat Receipt
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        @if ($orders->hasPages())
                            <div class="mt-6">
                                {{ $orders->appends(request()->query())->links() }}
                            </div>
                        @endif
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">
                                @if (request()->hasAny(['search', 'status']))
                                    Tidak ada pesanan yang sesuai
                                @else
                                    Belum Ada Pesanan
                                @endif
                            </h3>
                            <p class="text-gray-600 mb-6">
                                @if (request()->hasAny(['search', 'status']))
                                    Coba ubah kriteria pencarian atau filter Anda.
                                @else
                                    Mulai berbelanja untuk melihat riwayat pesanan Anda disini.
                                @endif
                            </p>

                            @if (request()->hasAny(['search', 'status']))
                                <a href="{{ route('orders.index') }}"
                                    class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition ease-in-out duration-150">
                                    Lihat Semua Pesanan
                                </a>
                            @else
                                <a href="{{ route('products.public.index') }}"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition ease-in-out duration-150">
                                    Mulai Belanja
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
