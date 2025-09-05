<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Produk') }}
            </h2>
            <a href="{{ route('products.public.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition duration-300">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Produk
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">

                <!-- Product Image -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    @if ($product->image)
                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                            class="w-full h-96 object-cover">
                    @else
                        <div class="w-full h-96 bg-gray-200 flex items-center justify-center">
                            <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                    @endif
                </div>

                <!-- Product Info -->
                <div class="bg-white rounded-lg shadow-sm p-6">

                    <!-- Category & Date -->
                    <div class="flex justify-between items-center mb-4">

                        <span class="text-sm text-gray-500">{{ $product->created_at->format('d M Y') }}</span>
                    </div>

                    <!-- Product Name -->
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $product->name }}</h1>

                    <!-- Price -->
                    <div class="text-4xl font-bold text-green-600 mb-6">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </div>

                    <!-- Seller Info -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <h3 class="font-semibold text-gray-900 mb-2">Dijual oleh</h3>
                        <div class="flex items-center">
                            @if ($product->seller->store_logo)
                                <img src="{{ Storage::url($product->seller->store_logo) }}"
                                    alt="{{ $product->seller->store_name }}"
                                    class="w-12 h-12 rounded-full object-cover mr-3">
                            @else
                                <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                    </svg>
                                </div>
                            @endif
                            <div>
                                <h4 class="font-medium text-gray-900">{{ $product->seller->store_name }}</h4>
                                <p class="text-sm text-gray-600">Penjual Terpercaya</p>
                            </div>
                        </div>
                    </div>

                    <!-- File Info -->
                    @if ($product->file_path)
                        <div class="bg-blue-50 rounded-lg p-4 mb-6">
                            <h3 class="font-semibold text-gray-900 mb-2">Informasi File</h3>
                            <div class="space-y-1">
                                <p class="text-sm text-gray-600">
                                    <span class="font-medium">Nama File:</span> {{ basename($product->file_path) }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    <span class="font-medium">Format:</span>
                                    {{ strtoupper(pathinfo($product->file_path, PATHINFO_EXTENSION)) }}
                                </p>

                            </div>
                        </div>
                    @endif

                    <!-- Purchase Button -->
                    @auth
                        @if (Auth::id() === $product->seller->user_id)
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                                <p class="text-yellow-800 font-medium">Ini adalah produk Anda sendiri</p>
                            </div>
                        @else
                            <div class="space-y-3 mb-6">
                                <!-- Add to Cart Button -->
                                <button onclick="addToCart({{ $product->id }})"
                                    class="w-full bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300"
                                    id="addToCartBtn">
                                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m0 0h5.5M12 21a1 1 0 100-2 1 1 0 000 2zm7 0a1 1 0 100-2 1 1 0 000 2z">
                                        </path>
                                    </svg>
                                    Masukkan ke Keranjang
                                </button>

                                <!-- Buy Now Button -->
                                <button onclick="purchaseProduct({{ $product->id }})"
                                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 px-6 rounded-lg text-lg transition duration-300">
                                    Beli Sekarang - Rp {{ number_format($product->price, 0, ',', '.') }}
                                </button>
                            </div>
                        @endif
                    @else
                        <div class="space-y-3 mb-6">
                            <a href="{{ route('login') }}"
                                class="block w-full bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-lg text-center transition duration-300">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m0 0h5.5M12 21a1 1 0 100-2 1 1 0 000 2zm7 0a1 1 0 100-2 1 1 0 000 2z">
                                    </path>
                                </svg>
                                Masukkan ke Keranjang
                            </a>
                            <a href="{{ route('login') }}"
                                class="block w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 px-6 rounded-lg text-lg text-center transition duration-300">
                                Login untuk Membeli
                            </a>
                        </div>
                    @endauth <!-- Product Features -->
                    <div class="border-t border-gray-200 pt-4">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div class="flex items-center text-gray-600">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Download Selamanya
                            </div>
                            <div class="flex items-center text-gray-600">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Link Aman
                            </div>
                            <div class="flex items-center text-gray-600">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Update Gratis
                            </div>
                            <div class="flex items-center text-gray-600">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Support 24/7
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Description -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Deskripsi Produk</h2>
                <div class="prose max-w-none text-gray-700">
                    {{ $product->description }}
                </div>
            </div>

            <!-- Related Products -->
            @if ($relatedProducts->count() > 0)
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Produk Serupa</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach ($relatedProducts as $related)
                            <div
                                class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition duration-300">
                                <a href="{{ route('products.public.show', $related) }}">
                                    @if ($related->image)
                                        <img src="{{ Storage::url($related->image) }}" alt="{{ $related->name }}"
                                            class="w-full h-32 object-cover">
                                    @else
                                        <div class="w-full h-32 bg-gray-200 flex items-center justify-center">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </div>
                                    @endif
                                </a>

                                <div class="p-4">
                                    <a href="{{ route('products.public.show', $related) }}">
                                        <h3
                                            class="font-semibold text-gray-900 text-sm mb-2 line-clamp-2 hover:text-indigo-600">
                                            {{ $related->name }}
                                        </h3>
                                    </a>
                                    <p class="text-lg font-bold text-green-600 mb-2">
                                        Rp {{ number_format($related->price, 0, ',', '.') }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $related->seller->store_name }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Midtrans Snap Script -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
    </script>
    <script type="text/javascript">
        function purchaseProduct(productId) {
            // Show loading
            const button = event.target;
            const originalText = button.innerText;
            button.innerHTML = 'Processing...';
            button.disabled = true;

            // Make AJAX request to create order and get snap token
            fetch(`/products/${productId}/purchase`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        throw new Error(data.error);
                    }

                    // Open Midtrans payment popup
                    snap.pay(data.snap_token, {
                        onSuccess: function(result) {
                            console.log('Payment success:', result);
                            // data.order_id is the invoice_number
                            checkPaymentStatusAndRedirect(data.order_id);
                        },
                        onPending: function(result) {
                            console.log('Payment pending:', result);
                            // Check payment status and redirect
                            checkPaymentStatusAndRedirect(data.order_id);
                        },
                        onError: function(result) {
                            console.log('Payment error:', result);
                            // Redirect to error page
                            window.location.href = `/payment/error/${data.order_id}`;
                        },
                        onClose: function() {
                            console.log('Payment popup closed');
                            // Reset button when payment popup is closed without completing payment
                            button.innerHTML = originalText;
                            button.disabled = false;
                            // Check payment status in case user completed payment before closing
                            setTimeout(() => {
                                checkPaymentStatusAndRedirect(data.order_id, true);
                            }, 2000);
                        }
                    });
                })
                .catch(error => {
                    alert('Terjadi kesalahan: ' + error.message);
                    button.innerHTML = originalText;
                    button.disabled = false;
                });
        }

        // Function to add product to cart
        function addToCart(productId) {
            const button = document.getElementById('addToCartBtn');
            const originalText = button.innerHTML;
            button.innerHTML =
                '<svg class="w-5 h-5 inline mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Menambahkan...';
            button.disabled = true;

            fetch(`/cart/add/${productId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        quantity: 1
                    })
                })
                .then(response => response.json())
                .then(data => {
                    button.innerHTML = originalText;
                    button.disabled = false;

                    if (data.success) {
                        // Update cart count in navigation
                        window.dispatchEvent(new CustomEvent('cart-updated', {
                            detail: {
                                count: data.cart_count
                            }
                        }));

                        // Show success message
                        const successDiv = document.createElement('div');
                        successDiv.className =
                            'fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-50';
                        successDiv.innerHTML = `
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            ${data.message}
                        </div>
                    `;
                        document.body.appendChild(successDiv);

                        // Remove success message after 3 seconds
                        setTimeout(() => {
                            successDiv.remove();
                        }, 3000);
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    button.innerHTML = originalText;
                    button.disabled = false;
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menambahkan ke keranjang');
                });
        }

        // Function to check payment status and redirect accordingly
        // orderId parameter is actually the invoice_number
        function checkPaymentStatusAndRedirect(orderId, skipIfPending = false) {
            fetch(`/payment/check-status/${orderId}`, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.redirect_url && (!skipIfPending || data.status !== 'pending')) {
                        window.location.href = data.redirect_url;
                    }
                })
                .catch(error => {
                    console.error('Status check failed:', error);
                    // Fallback to pending page if status check fails
                    if (!skipIfPending) {
                        window.location.href = `/payment/pending/${orderId}`;
                    }
                });
        }
    </script>
</x-app-layout>
