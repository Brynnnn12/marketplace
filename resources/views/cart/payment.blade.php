<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pembayaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Konfirmasi Pembayaran</h3>

                    <div class="border border-gray-200 rounded-lg p-6 mb-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h4 class="font-semibold text-lg">{{ $order->invoice_number }}</h4>
                                <p class="text-gray-600">{{ $order->created_at->format('d M Y, H:i') }}</p>
                            </div>
                            <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">
                                Menunggu Pembayaran
                            </span>
                        </div>

                        <div class="mb-4">
                            <h5 class="font-medium text-gray-900 mb-2">Produk:</h5>
                            @foreach ($order->products as $product)
                                <div
                                    class="flex justify-between items-center py-2 border-b border-gray-100 last:border-b-0">
                                    <div>
                                        <span class="font-medium">{{ $product->name }}</span>
                                        <span class="text-gray-600 ml-2">({{ $product->pivot->quantity }}x)</span>
                                    </div>
                                    <span class="font-medium">Rp
                                        {{ number_format($product->pivot->price * $product->pivot->quantity, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="border-t pt-4">
                            <div class="flex justify-between items-center text-xl font-bold">
                                <span>Total Pembayaran:</span>
                                <span class="text-green-600">Rp
                                    {{ number_format($order->gross_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <button onclick="payNow('{{ $snapToken }}')"
                            class="px-8 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-lg rounded-lg transition duration-300">
                            <svg class="w-6 h-6 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                            Bayar Sekarang
                        </button>

                        <p class="mt-4 text-sm text-gray-600">
                            Klik tombol di atas untuk melanjutkan ke halaman pembayaran yang aman
                        </p>
                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="flex gap-4 justify-center">
                            <a href="{{ route('orders.show', $order) }}"
                                class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition duration-300">
                                Lihat Detail Pesanan
                            </a>
                            <a href="{{ route('cart.index') }}"
                                class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition duration-300">
                                Kembali ke Keranjang
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Midtrans Snap Script -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
    </script>
    <script>
        function payNow(snapToken) {
            snap.pay(snapToken, {
                onSuccess: function(result) {
                    console.log('Payment success:', result);
                    // Use the same pattern as product show
                    checkPaymentStatusAndRedirect('{{ $order->invoice_number }}');
                },
                onPending: function(result) {
                    console.log('Payment pending:', result);
                    // Check payment status and redirect
                    checkPaymentStatusAndRedirect('{{ $order->invoice_number }}');
                },
                onError: function(result) {
                    console.log('Payment error:', result);
                    // Redirect to error page
                    window.location.href = `/payment/error/{{ $order->invoice_number }}`;
                },
                onClose: function() {
                    console.log('Payment popup closed');
                    // Check payment status in case user completed payment before closing
                    setTimeout(() => {
                        checkPaymentStatusAndRedirect('{{ $order->invoice_number }}', true);
                    }, 2000);
                }
            });
        }

        // Function to check payment status and redirect accordingly
        // Same function as in product show page
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
