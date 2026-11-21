<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold leading-tight text-gray-800">Kategori</h1>
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
                        <h2 class="text-lg font-semibold text-gray-900">Daftar Kategori</h2>
                        <p class="mt-1 text-sm text-gray-600">Kategori barang yang tersedia di toko.</p>
                    </div>
                    <a href="{{ route('categories.create') }}"
                        class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                        Tambah Kategori
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
                        <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                            <tr>
                                <th scope="col" class="px-4 py-3">Nama kategori</th>
                                <th scope="col" class="px-4 py-3">Deskripsi</th>
                                <th scope="col" class="px-4 py-3 text-right">Jumlah produk</th>
                                <th scope="col" class="px-4 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white text-gray-700">
                            @forelse ($categories as $category)
                                <tr>
                                    <td class="whitespace-nowrap px-4 py-3 font-medium text-gray-900">
                                        {{ $category->name }}
                                    </td>
                                    <td class="px-4 py-3">{{ $category->description ?: '—' }}</td>
                                    <td class="px-4 py-3 text-right">{{ $category->products_count }}</td>
                                    <td class="whitespace-nowrap px-4 py-3 text-right">
                                        <a href="{{ route('categories.show', $category) }}" class="font-medium text-gray-600 hover:text-gray-900">
                                            Detail
                                        </a>
                                        <a href="{{ route('categories.edit', $category) }}" class="font-medium text-indigo-600 hover:text-indigo-800">
                                            Ubah
                                        </a>
                                        <form action="{{ route('categories.destroy', $category) }}" method="POST" class="ml-3 inline"
                                            onsubmit="return confirm(@js('Yakin ingin menghapus kategori '.$category->name.'?'))">
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
                                    <td colspan="4" class="px-4 py-10 text-center text-gray-500">
                                        Belum ada kategori. Jalankan seeder kategori untuk mengisi data contoh.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($categories->hasPages())
                    <div class="mt-6">{{ $categories->links() }}</div>
                @endif
            </x-card>
        </div>
    </div>
</x-app-layout>
