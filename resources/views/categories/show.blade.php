<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold leading-tight text-gray-800">Detail Kategori</h1>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="mx-auto max-w-5xl space-y-6 px-4 sm:px-6 lg:px-8">
            <x-card>
                <div class="mb-6 flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">{{ $category->name }}</h2>
                        <p class="mt-1 text-sm text-gray-600">{{ $category->description ?: 'Tidak ada deskripsi.' }}</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <a href="{{ route('categories.edit', $category) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                            Ubah Kategori
                        </a>
                        <a href="{{ route('categories.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">
                            Kembali ke daftar
                        </a>
                    </div>
                </div>

                <div class="mb-8 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-md bg-gray-50 p-4 text-center">
                        <p class="text-xs text-gray-500">Jumlah produk</p>
                        <p class="mt-1 text-lg font-semibold text-gray-900">{{ number_format($category->products->count()) }}</p>
                    </div>
                    <div class="rounded-md bg-gray-50 p-4 text-center">
                        <p class="text-xs text-gray-500">Total stok</p>
                        <p class="mt-1 text-lg font-semibold text-gray-900">{{ number_format($totalStok) }}</p>
                    </div>
                </div>

                <h3 class="mb-3 text-base font-semibold text-gray-900">Produk dalam Kategori</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
                        <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                            <tr>
                                <th scope="col" class="px-4 py-3">Kode</th>
                                <th scope="col" class="px-4 py-3">Nama produk</th>
                                <th scope="col" class="px-4 py-3">Satuan</th>
                                <th scope="col" class="px-4 py-3 text-right">Harga</th>
                                <th scope="col" class="px-4 py-3 text-right">Stok</th>
                                <th scope="col" class="px-4 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-gray-700">
                            @forelse ($category->products as $product)
                                <tr>
                                    <td class="whitespace-nowrap px-4 py-3 font-mono text-xs">{{ $product->code }}</td>
                                    <td class="whitespace-nowrap px-4 py-3 font-medium text-gray-900">{{ $product->name }}</td>
                                    <td class="whitespace-nowrap px-4 py-3">{{ $product->unit }}</td>
                                    <td class="whitespace-nowrap px-4 py-3 text-right">
                                        Rp{{ number_format($product->price, 0, ',', '.') }}
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-3 text-right">{{ number_format($product->stock) }}</td>
                                    <td class="whitespace-nowrap px-4 py-3 text-right">
                                        <a href="{{ route('products.show', $product) }}" class="font-medium text-indigo-600 hover:text-indigo-800">
                                            Detail produk
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                        Belum ada produk dalam kategori ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>
