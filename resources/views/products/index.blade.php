<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold leading-tight text-gray-800">Produk</h1>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <x-card>
                @if (session('success'))
                    <div class="mb-6 rounded-md bg-green-50 px-4 py-3 text-sm text-green-800" role="status">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-6 rounded-md bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Daftar Produk</h2>
                        <p class="mt-1 text-sm text-gray-600">
                            Stok di bawah {{ config('inventory.low_stock_threshold') }} ditandai menipis; stok 0 ditandai habis.
                        </p>
                        <div class="mt-3 flex flex-wrap items-center gap-2 text-sm">
                            <span class="mr-1 text-gray-600">Status stok:</span>
                            <x-badge status="Aman" />
                            <x-badge status="Menipis" />
                            <x-badge status="Habis" />
                        </div>
                    </div>
                    <a href="{{ route('products.create') }}"
                        class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                        Tambah Produk
                    </a>
                </div>

                <form method="GET" action="{{ route('products.index') }}" class="mb-6 flex flex-col gap-3 sm:flex-row">
                    <label for="search" class="sr-only">Cari nama atau kode produk</label>
                    <input id="search" type="search" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama atau kode produk..."
                        class="min-w-0 flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">

                    <label for="category_id" class="sr-only">Filter kategori</label>
                    <select id="category_id" name="category_id"
                        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected((string) request('category_id') === (string) $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>

                    <label for="stock_status" class="sr-only">Filter status stok</label>
                    <select id="stock_status" name="stock_status"
                        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="" @selected(request('stock_status') === null || request('stock_status') === '')>Semua</option>
                        <option value="menipis" @selected(request('stock_status') === 'menipis')>Menipis</option>
                        <option value="habis" @selected(request('stock_status') === 'habis')>Habis</option>
                    </select>

                    <label for="per_page" class="sr-only">Jumlah produk per halaman</label>
                    <select id="per_page" name="per_page"
                        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="10" @selected($perPage === 10)>10 per halaman</option>
                        <option value="25" @selected($perPage === 25)>25 per halaman</option>
                        <option value="50" @selected($perPage === 50)>50 per halaman</option>
                    </select>

                    <div class="flex gap-2">
                        <button type="submit"
                            class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                            Cari
                        </button>
                        <a href="{{ route('products.index') }}"
                            class="rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Reset
                        </a>
                    </div>
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
                        <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                            <tr>
                                <th scope="col" class="px-4 py-3">Kode</th>
                                <th scope="col" class="px-4 py-3">Nama produk</th>
                                <th scope="col" class="px-4 py-3">Kategori</th>
                                <th scope="col" class="px-4 py-3 text-right">Harga</th>
                                <th scope="col" class="px-4 py-3 text-right">Stok</th>
                                <th scope="col" class="px-4 py-3 text-right">Nilai stok</th>
                                <th scope="col" class="px-4 py-3">Status</th>
                                <th scope="col" class="px-4 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white text-gray-700">
                            @forelse ($products as $product)
                                @php
                                    $stockStatus = $product->stock === 0
                                        ? 'Habis'
                                        : ($product->stock < $lowStockThreshold ? 'Menipis' : 'Aman');
                                @endphp
                                <tr @class([
                                    'bg-red-50' => $product->stock === 0,
                                    'bg-yellow-50' => $product->stock > 0 && $product->stock < $lowStockThreshold,
                                ])>
                                    <td class="whitespace-nowrap px-4 py-3 font-mono text-xs">{{ $product->code }}</td>
                                    <td class="whitespace-nowrap px-4 py-3 font-medium text-gray-900">{{ $product->name }}</td>
                                    <td class="whitespace-nowrap px-4 py-3">{{ $product->category->name }}</td>
                                    <td class="whitespace-nowrap px-4 py-3 text-right">
                                        Rp{{ number_format($product->price, 0, ',', '.') }}
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-3 text-right">
                                        {{ number_format($product->stock) }} {{ $product->unit }}
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-3 text-right">
                                        Rp{{ number_format($product->stock * $product->price, 0, ',', '.') }}
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-3">
                                        <x-badge :status="$stockStatus" />
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-3 text-right">
                                        <a href="{{ route('products.show', $product) }}" class="font-medium text-gray-600 hover:text-gray-900">
                                            Detail
                                        </a>
                                        <a href="{{ route('products.edit', $product) }}" class="font-medium text-indigo-600 hover:text-indigo-800">
                                            Ubah
                                        </a>
                                        <form action="{{ route('products.destroy', $product) }}" method="POST" class="ml-3 inline"
                                            onsubmit="return confirm(@js('Yakin ingin menghapus produk '.$product->name.'?'))">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="font-medium text-red-600 hover:text-red-800">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-10 text-center text-gray-500">
                                        @if (request()->filled('search') || request()->filled('category_id') || request()->filled('stock_status'))
                                            Tidak ada produk yang cocok dengan pencarian atau kategori ini.
                                        @else
                                            Belum ada produk untuk ditampilkan.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($products->hasPages())
                    <div class="mt-6">{{ $products->links() }}</div>
                @endif
            </x-card>
        </div>
    </div>
</x-app-layout>
