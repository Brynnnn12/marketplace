<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Keranjang Belanja') }}
            </h2>
            <a href="{{ route('products.public.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition duration-300">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Lanjut Belanja
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            @if ($cartItems->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Cart Items -->
                    <div class="lg:col-span-2">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-6">Item dalam Keranjang</h3>

                                <div class="space-y-4" x-data="cartManager()">
                                    @foreach ($cartItems as $item)
                                        <div class="border border-gray-200 rounded-lg p-4" x-data="{ quantity: {{ $item->quantity }} }">
                                            <div class="flex items-start space-x-4">
                                                <!-- Product Image -->
                                                @if ($item->product->image)
                                                    <img src="{{ Storage::url($item->product->image) }}"
                                                        alt="{{ $item->product->name }}"
                                                        class="w-20 h-20 object-cover rounded-lg flex-shrink-0">
                                                @else
                                                    <div
                                                        class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                                        <svg class="w-8 h-8 text-gray-400" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                            </path>
                                                        </svg>
                                                    </div>
                                                @endif

                                                <!-- Product Details -->
                                                <div class="flex-1">
                                                    <div class="flex justify-between items-start">
                                                        <div>
                                                            <h4 class="font-semibold text-lg text-gray-900">
                                                                <a href="{{ route('products.public.show', $item->product) }}"
                                                                    class="hover:text-indigo-600">
                                                                    {{ $item->product->name }}
                                                                </a>
                                                            </h4>
                                                            <p class="text-gray-600 mt-1">
                                                                {{ $item->product->seller->store_name }}</p>
                                                            <p class="text-lg font-bold text-gray-900 mt-2">
                                                                Rp
                                                                {{ number_format($item->product->price, 0, ',', '.') }}
                                                            </p>
                                                        </div>

                                                        <!-- Remove Button -->
                                                        <button @click="removeItem({{ $item->id }})"
                                                            class="text-red-600 hover:text-red-800 transition duration-200">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                                </path>
                                                            </svg>
                                                        </button>
                                                    </div>

                                                    <!-- Quantity Controls -->
                                                    <div class="flex items-center mt-4 space-x-3">
                                                        <span class="text-sm text-gray-600">Jumlah:</span>
                                                        <div class="flex items-center border border-gray-300 rounded">
                                                            <button
                                                                @click="updateQuantity({{ $item->id }}, quantity - 1)"
                                                                :disabled="quantity <= 1"
                                                                class="px-2 py-1 text-gray-600 hover:bg-gray-100 disabled:opacity-50">
                                                                -
                                                            </button>
                                                            <span class="px-4 py-1 border-l border-r border-gray-300"
                                                                x-text="quantity"></span>
                                                            <button
                                                                @click="updateQuantity({{ $item->id }}, quantity + 1)"
                                                                class="px-2 py-1 text-gray-600 hover:bg-gray-100">
                                                                +
                                                            </button>
                                                        </div>
                                                        <span class="text-sm text-gray-600">
                                                            Subtotal: <span class="font-semibold">Rp <span
                                                                    x-text="(quantity * {{ $item->product->price }}).toLocaleString('id-ID')"></span></span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="lg:col-span-1">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg sticky top-4">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-6">Ringkasan Pesanan</h3>

                                <div class="space-y-4">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Subtotal ({{ $cartItems->count() }} item)</span>
                                        <span class="font-semibold">Rp {{ number_format($total, 0, ',', '.') }}</span>
                                    </div>

                                    <div class="border-t border-gray-200 pt-4">
                                        <div class="flex justify-between text-lg font-bold">
                                            <span>Total</span>
                                            <span class="text-green-600">Rp
                                                {{ number_format($total, 0, ',', '.') }}</span>
                                        </div>
                                    </div>

                                    <form action="{{ route('cart.checkout') }}" method="POST" class="mt-6">
                                        @csrf
                                        <button type="submit"
                                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300">
                                            Checkout Sekarang
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Empty Cart -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center">
                        <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m0 0h5.5M12 21a1 1 0 100-2 1 1 0 000 2zm7 0a1 1 0 100-2 1 1 0 000 2z">
                            </path>
                        </svg>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Keranjang Anda Kosong</h3>
                        <p class="text-gray-600 mb-6">Belum ada produk di keranjang. Mulai berbelanja sekarang!</p>
                        <a href="{{ route('products.public.index') }}"
                            class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg transition duration-300">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            Mulai Belanja
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </div>

    <script>
        function cartManager() {
            return {
                updateQuantity(cartId, newQuantity) {
                    if (newQuantity < 1) return;

                    fetch(`/cart/${cartId}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            },
                            body: JSON.stringify({
                                quantity: newQuantity
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                location.reload(); // Reload to update totals
                            }
                        })
                        .catch(error => console.error('Error:', error));
                },

                removeItem(cartId) {
                    if (confirm('Apakah Anda yakin ingin menghapus item ini dari keranjang?')) {
                        fetch(`/cart/${cartId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                        'content')
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    location.reload();
                                }
                            })
                            .catch(error => console.error('Error:', error));
                    }
                }
            }
        }
    </script>
</x-app-layout>
