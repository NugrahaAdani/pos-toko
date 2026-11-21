<x-app-layout>
    <x-slot name="header"><h1 class="text-xl font-semibold leading-tight text-gray-800">Detail Pembelian Stok</h1></x-slot>
    <div class="py-8 sm:py-12">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <x-card>
                @if (session('success'))
                    <div class="mb-6 rounded-md bg-green-50 px-4 py-3 text-sm text-green-800" role="status">{{ session('success') }}</div>
                @endif
                @if ($errors->any())
                    <div class="mb-6 rounded-md bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">{{ $errors->first() }}</div>
                @endif
                <div class="mb-6 flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">{{ $purchaseOrder->po_number }}</h2>
                        <p class="mt-1 text-sm text-gray-600">Supplier: {{ $purchaseOrder->supplier->name }}</p>
                        <p class="text-sm text-gray-600">Tanggal: {{ $purchaseOrder->purchased_at->format('d M Y H:i') }} · Dicatat oleh {{ $purchaseOrder->user->name }}</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <a href="{{ route('purchase-orders.edit', $purchaseOrder) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">Ubah</a>
                        <a href="{{ route('purchase-orders.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Kembali</a>
                    </div>
                </div>

                @if ($purchaseOrder->notes)
                    <p class="mb-5 whitespace-pre-line text-sm text-gray-600">{{ $purchaseOrder->notes }}</p>
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
                        <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                            <tr>
                                <th class="px-4 py-3">Produk</th>
                                <th class="px-4 py-3 text-right">Qty</th>
                                <th class="px-4 py-3 text-right">Harga Beli</th>
                                <th class="px-4 py-3 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white text-gray-700">
                            @foreach ($purchaseOrder->items as $item)
                                <tr>
                                    <td class="px-4 py-3 font-medium text-gray-900">{{ $item->product?->name ?? 'Produk tidak tersedia' }}</td>
                                    <td class="px-4 py-3 text-right">{{ number_format($item->qty) }}</td>
                                    <td class="px-4 py-3 text-right">Rp{{ number_format($item->unit_cost, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-right">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <th colspan="3" class="px-4 py-3 text-right text-sm font-semibold text-gray-900">Total pembelian</th>
                                <td class="px-4 py-3 text-right text-sm font-semibold text-gray-900">Rp{{ number_format($purchaseOrder->total, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>
