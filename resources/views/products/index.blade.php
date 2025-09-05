<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Produk Digital') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Filter & Search -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6">
                    <form method="GET" action="{{ route('products.public.index') }}"
                        class="grid grid-cols-1 md:grid-cols-4 gap-4">

                        <!-- Search -->
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Cari
                                Produk</label>
                            <input type="text" id="search" name="search" value="{{ request('search') }}"
                                placeholder="Nama produk..."
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>


                        <!-- Sort -->
                        <div>
                            <label for="sort" class="block text-sm font-medium text-gray-700 mb-2">Urutkan</label>
                            <select id="sort" name="sort"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Terbaru
                                </option>
                                <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Harga
                                    Terendah</option>
                                <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>
                                    Harga Tertinggi</option>
                                <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Nama A-Z
                                </option>
                            </select>
                        </div>

                        <!-- Submit -->
                        <div class="flex items-end">
                            <button type="submit"
                                class="w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition duration-300">
                                Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Results Count -->
            <div class="mb-6">
                <p class="text-gray-600">
                    Menampilkan {{ $products->count() }} dari {{ $products->total() }} produk
                    @if (request('search'))
                        untuk "<strong>{{ request('search') }}</strong>"
                    @endif

                </p>
            </div>

            <!-- Products Grid -->
            @if ($products->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                    @foreach ($products as $product)
                        <div
                            class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition duration-300">
                            <a href="{{ route('products.public.show', $product) }}">
                                @if ($product->image)
                                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                                        class="w-full h-48 object-cover">
                                @else
                                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                @endif
                            </a>

                            <div class="p-4">
                                <a href="{{ route('products.public.show', $product) }}">
                                    <h3
                                        class="font-semibold text-gray-900 text-sm mb-2 line-clamp-2 hover:text-indigo-600">
                                        {{ $product->name }}
                                    </h3>
                                </a>

                                <p class="text-gray-600 text-xs mb-2 line-clamp-2">{{ $product->description }}</p>

                                <div class="flex justify-between items-center mb-3">
                                    <span class="text-lg font-bold text-green-600">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </span>

                                </div>

                                <div class="flex justify-between items-center text-xs text-gray-500 mb-4">
                                    <span>{{ $product->seller->store_name }}</span>
                                    <span>{{ $product->created_at->format('d/m/Y') }}</span>
                                </div>

                                <a href="{{ route('products.public.show', $product) }}"
                                    class="block text-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded transition duration-300">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="bg-white rounded-lg">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            @else
                <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                    <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.29-1.26-5.417-3.041C6.105 10.5 6 9.275 6 8s.105-2.5.583-3.959C7.71 2.26 9.66 1 12 1s4.29 1.26 5.417 3.041C17.895 5.5 18 6.725 18 8s-.105 2.5-.583 3.959A7.96 7.96 0 0112 15z">
                        </path>
                    </svg>
                    <h3 class="text-xl font-medium text-gray-900 mb-2">Tidak ada produk ditemukan</h3>
                    <p class="text-gray-600">Coba ubah filter atau kata kunci pencarian Anda.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
