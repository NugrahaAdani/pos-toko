<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold leading-tight text-gray-800">Detail Produk</h1>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="mx-auto max-w-5xl space-y-6 px-4 sm:px-6 lg:px-8">
            <x-card>
                <div class="mb-6 flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">{{ $product->name }}</h2>
                        <p class="mt-1 text-sm text-gray-600">
                            {{ $product->code }} <span aria-hidden="true">&middot;</span> {{ $product->category->name }}
                        </p>
                    </div>
                    <div class="flex items-center gap-4">
                        <a href="{{ route('products.edit', $product) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                            Ubah Produk
                        </a>
                        <a href="{{ route('products.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">
                            Kembali ke daftar
                        </a>
                    </div>
                </div>

                <div class="mb-8 grid gap-4 sm:grid-cols-3">
                    <div class="rounded-md bg-gray-50 p-4 text-center">
                        <p class="text-xs text-gray-500">Harga</p>
                        <p class="mt-1 text-lg font-semibold text-gray-900">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-md bg-gray-50 p-4 text-center">
                        <p class="text-xs text-gray-500">Stok saat ini</p>
                        <p class="mt-1 text-lg font-semibold text-gray-900">{{ number_format($product->stock) }} {{ $product->unit }}</p>
                    </div>
                    <div class="rounded-md bg-gray-50 p-4 text-center">
                        <p class="text-xs text-gray-500">Total terjual</p>
                        <p class="mt-1 text-lg font-semibold text-gray-900">{{ number_format($totalTerjual) }} {{ $product->unit }}</p>
                    </div>
                </div>

                <h3 class="mb-1 text-base font-semibold text-gray-900">5 Hari Penjualan Tertinggi</h3>
                <p class="mb-3 text-sm text-gray-600">Peringkat berdasarkan jumlah unit terjual.</p>
                <div class="mb-8 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
                        <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                            <tr>
                                <th scope="col" class="px-3 py-2">Peringkat</th>
                                <th scope="col" class="px-3 py-2">Tanggal</th>
                                <th scope="col" class="px-3 py-2 text-right">Qty terjual</th>
                                <th scope="col" class="px-3 py-2 text-right">Omzet</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-gray-700">
                            @forelse ($peringkatHarian as $index => $hari)
                                <tr>
                                    <td class="whitespace-nowrap px-3 py-2 font-medium text-gray-900">{{ $index + 1 }}</td>
                                    <td class="whitespace-nowrap px-3 py-2">
                                        {{ \Illuminate\Support\Carbon::parse($hari->tanggal)->format('d M Y') }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-2 text-right">
                                        {{ number_format($hari->total_qty) }} {{ $product->unit }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-2 text-right">
                                        Rp{{ number_format($hari->total_penjualan, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-3 py-8 text-center text-gray-500">
                                        Belum ada data penjualan untuk produk ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <h3 class="mb-3 text-base font-semibold text-gray-900">Riwayat Penjualan Terakhir</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
                        <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                            <tr>
                                <th scope="col" class="px-3 py-2">No. Invoice</th>
                                <th scope="col" class="px-3 py-2">Tanggal</th>
                                <th scope="col" class="px-3 py-2 text-right">Qty</th>
                                <th scope="col" class="px-3 py-2 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-gray-700">
                            @forelse ($riwayat as $item)
                                <tr>
                                    <td class="whitespace-nowrap px-3 py-2 font-medium text-gray-900">
                                        {{ $item->transaction->invoice_number }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-2">
                                        {{ $item->transaction->created_at->format('d M Y H:i') }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-2 text-right">{{ number_format($item->qty) }}</td>
                                    <td class="whitespace-nowrap px-3 py-2 text-right">
                                        Rp{{ number_format($item->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-3 py-8 text-center text-gray-500">
                                        Belum pernah terjual.
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
