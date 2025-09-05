<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Produk') }}: {{ $product->name }}
            </h2>
            <a href="{{ route('seller.products.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition duration-300">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    @if ($errors->any())
                        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('seller.products.update', $product) }}" method="POST"
                        enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Basic Info -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama Produk <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="name" name="name"
                                    value="{{ old('name', $product->name) }}" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                    placeholder="Masukkan nama produk">
                            </div>

                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                                    Kategori <span class="text-red-500">*</span>
                                </label>
                                <select id="category" name="category" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    <option value="">Pilih Kategori</option>
                                    <option value="ebook"
                                        {{ old('category', $product->category) === 'ebook' ? 'selected' : '' }}>E-Book
                                    </option>
                                    <option value="template"
                                        {{ old('category', $product->category) === 'template' ? 'selected' : '' }}>
                                        Template</option>
                                    <option value="plugin"
                                        {{ old('category', $product->category) === 'plugin' ? 'selected' : '' }}>Plugin
                                    </option>
                                    <option value="course"
                                        {{ old('category', $product->category) === 'course' ? 'selected' : '' }}>
                                        Course/Tutorial</option>
                                    <option value="audio"
                                        {{ old('category', $product->category) === 'audio' ? 'selected' : '' }}>Audio
                                    </option>
                                    <option value="video"
                                        {{ old('category', $product->category) === 'video' ? 'selected' : '' }}>Video
                                    </option>
                                    <option value="software"
                                        {{ old('category', $product->category) === 'software' ? 'selected' : '' }}>
                                        Software</option>
                                    <option value="other"
                                        {{ old('category', $product->category) === 'other' ? 'selected' : '' }}>Lainnya
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Deskripsi Produk <span class="text-red-500">*</span>
                            </label>
                            <textarea id="description" name="description" rows="4" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                placeholder="Deskripsikan produk Anda secara detail...">{{ old('description', $product->description) }}</textarea>
                        </div>

                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                                Harga (Rupiah) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                                <input type="number" id="price" name="price"
                                    value="{{ old('price', $product->price) }}" min="0" step="1000" required
                                    class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                    placeholder="0">
                            </div>
                        </div>

                        <!-- Current Media -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Gambar Saat Ini
                                </label>
                                @if ($product->image)
                                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                                        class="w-full max-w-xs h-auto rounded-lg border border-gray-300 mb-2">
                                @else
                                    <div class="w-32 h-32 bg-gray-200 rounded-lg flex items-center justify-center mb-2">
                                        <span class="text-gray-500 text-sm">Tidak ada gambar</span>
                                    </div>
                                @endif
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    File Saat Ini
                                </label>
                                @if ($product->file_path)
                                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                                        <p class="text-sm font-medium text-gray-900">{{ $product->file_name }}</p>
                                        <p class="text-xs text-gray-500">
                                            Ukuran: {{ number_format($product->file_size / 1024 / 1024, 1) }} MB
                                        </p>
                                    </div>
                                @else
                                    <p class="text-sm text-gray-500">Tidak ada file</p>
                                @endif
                            </div>
                        </div>

                        <!-- New Media Upload -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                                    Ganti Gambar (Opsional)
                                </label>
                                <div class="relative">
                                    <input type="file" id="image" name="image" accept="image/*"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, GIF. Max: 2MB</p>
                            </div>

                            <div>
                                <label for="file" class="block text-sm font-medium text-gray-700 mb-2">
                                    Ganti File (Opsional)
                                </label>
                                <div class="relative">
                                    <input type="file" id="file" name="file"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Format: PDF, ZIP, RAR, MP4, MP3, DOC, XLS, PPT.
                                    Max: 50MB</p>
                            </div>
                        </div>

                        <!-- Preview -->
                        <div id="imagePreview" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Preview Gambar Baru</label>
                            <img id="previewImg" src="" alt="Preview"
                                class="max-w-xs h-auto rounded-lg border border-gray-300">
                        </div>

                        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('seller.products.index') }}"
                                class="px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition duration-300">
                                Batal
                            </a>
                            <button type="submit"
                                class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition duration-300">
                                Update Produk
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Image preview
        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImg').src = e.target.result;
                    document.getElementById('imagePreview').classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                document.getElementById('imagePreview').classList.add('hidden');
            }
        });

        // Format price input
        document.getElementById('price').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            e.target.value = value;
        });
    </script>
</x-app-layout>
