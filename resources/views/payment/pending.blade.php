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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>

                <!-- Pending Message -->
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">Pembayaran Sedang Diproses</h1>
                    <p class="text-gray-600 mb-6">
                        Pembayaran Anda sedang dalam proses verifikasi. Silakan selesaikan pembayaran sesuai dengan instruksi yang diberikan.
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
                            <span class="inline-flex px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
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
                    <button onclick="checkPaymentStatus()" id="checkStatusBtn"
                        class="flex-1 bg-yellow-600 hover:bg-yellow-700 text-white font-medium py-3 px-6 rounded-lg transition duration-200">
                        Periksa Status
                    </button>
                    <a href="{{ route('dashboard') }}"
                        class="flex-1 bg-gray-600 hover:bg-gray-700 text-white font-medium py-3 px-6 rounded-lg transition duration-200">
                        Kembali ke Dashboard
                    </a>
                </div>

                <!-- Status indicator -->
                <div id="status-indicator" class="mt-6 text-center">
                    <div class="inline-flex items-center text-sm text-gray-600">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Memeriksa status pembayaran...
                    </div>
                </div>

                <!-- Payment Status Check Script -->
                <script>
                    let statusCheckInterval;
                    let checkCount = 0;

                    function checkPaymentStatus() {
                        const btn = document.getElementById('checkStatusBtn');
                        const indicator = document.getElementById('status-indicator');
                        
                        btn.disabled = true;
                        btn.innerHTML = 'Memeriksa...';
                        indicator.style.display = 'block';

                        fetch(`{{ route('payment.check-status', $order) }}`, {
                            method: 'GET',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log('Payment status:', data);
                            
                            if (data.status !== 'pending') {
                                // Clear interval and redirect if status changed
                                clearInterval(statusCheckInterval);
                                indicator.innerHTML = '<div class="text-green-600">Status berubah! Mengalihkan...</div>';
                                
                                setTimeout(() => {
                                    window.location.href = data.redirect_url;
                                }, 1500);
                            } else {
                                // Reset button
                                btn.disabled = false;
                                btn.innerHTML = 'Periksa Status';
                                indicator.style.display = 'none';
                            }
                        })
                        .catch(error => {
                            console.error('Status check failed:', error);
                            btn.disabled = false;
                            btn.innerHTML = 'Periksa Status';
                            indicator.innerHTML = '<div class="text-red-600">Gagal memeriksa status</div>';
                        });
                    }

                    // Auto check status every 15 seconds (max 20 times = 5 minutes)
                    statusCheckInterval = setInterval(() => {
                        checkCount++;
                        if (checkCount < 20) {
                            checkPaymentStatus();
                        } else {
                            clearInterval(statusCheckInterval);
                            document.getElementById('status-indicator').innerHTML = '<div class="text-gray-600">Pemeriksaan otomatis dihentikan. Silakan periksa manual.</div>';
                        }
                    }, 15000);
                    
                    // Initial check after 3 seconds
                    setTimeout(() => {
                        checkPaymentStatus();
                    }, 3000);

                    // Hide initial indicator after 5 seconds if no response
                    setTimeout(() => {
                        if (document.getElementById('status-indicator').style.display !== 'none') {
                            document.getElementById('status-indicator').style.display = 'none';
                        }
                    }, 8000);
                </script>
            </div>
        </div>
    </div>
</x-app-layout>
