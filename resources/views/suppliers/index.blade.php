<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold leading-tight text-gray-800">Supplier</h1>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <x-card>
                @if (session('success'))
                    <div class="mb-6 rounded-md bg-green-50 px-4 py-3 text-sm text-green-800" role="status">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="mb-6 rounded-md bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">{{ session('error') }}</div>
                @endif

                <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Buku Data Supplier</h2>
                        <p class="mt-1 text-sm text-gray-600">Kelola kontak dan riwayat pemasok toko.</p>
                    </div>
                    <a href="{{ route('suppliers.create') }}" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                        Tambah Supplier
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
                        <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                            <tr>
                                <th class="px-4 py-3">Nama Supplier</th>
                                <th class="px-4 py-3">Kontak</th>
                                <th class="px-4 py-3">Telepon</th>
                                <th class="px-4 py-3 text-right">Pembelian</th>
                                <th class="px-4 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white text-gray-700">
                            @forelse ($suppliers as $supplier)
                                <tr>
                                    <td class="px-4 py-3 font-medium text-gray-900">{{ $supplier->name }}</td>
                                    <td class="px-4 py-3">{{ $supplier->contact_person ?: '—' }}</td>
                                    <td class="px-4 py-3">{{ $supplier->phone ?: '—' }}</td>
                                    <td class="px-4 py-3 text-right">{{ $supplier->purchase_orders_count }}</td>
                                    <td class="whitespace-nowrap px-4 py-3 text-right">
                                        <a href="{{ route('suppliers.show', $supplier) }}" class="font-medium text-gray-600 hover:text-gray-900">Detail</a>
                                        <a href="{{ route('suppliers.edit', $supplier) }}" class="ml-3 font-medium text-indigo-600 hover:text-indigo-800">Ubah</a>
                                        <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="ml-3 inline"
                                            onsubmit="return confirm(@js('Yakin ingin menghapus supplier '.$supplier->name.'?'))">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="font-medium text-red-600 hover:text-red-800">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-10 text-center text-gray-500">Belum ada data supplier.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($suppliers->hasPages())
                    <div class="mt-6">{{ $suppliers->links() }}</div>
                @endif
            </x-card>
        </div>
    </div>
</x-app-layout>
