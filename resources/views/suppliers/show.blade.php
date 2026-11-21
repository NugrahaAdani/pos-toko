<x-app-layout>
    <x-slot name="header"><h1 class="text-xl font-semibold leading-tight text-gray-800">Detail Supplier</h1></x-slot>
    <div class="py-8 sm:py-12">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <x-card>
                <div class="mb-5 flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">{{ $supplier->name }}</h2>
                        <p class="mt-1 text-sm text-gray-600">Kontak: {{ $supplier->contact_person ?: '—' }} · {{ $supplier->phone ?: '—' }}</p>
                        <p class="text-sm text-gray-600">{{ $supplier->email ?: '—' }}</p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('suppliers.edit', $supplier) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">Ubah</a>
                        <a href="{{ route('suppliers.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Kembali</a>
                    </div>
                </div>
                <p class="whitespace-pre-line text-sm text-gray-700">{{ $supplier->address ?: 'Alamat belum diisi.' }}</p>
            </x-card>

            <x-card>
                <h2 class="mb-4 text-lg font-semibold text-gray-900">Riwayat Pembelian</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
                        <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                            <tr>
                                <th class="px-4 py-3">No. Pembelian</th>
                                <th class="px-4 py-3">Tanggal</th>
                                <th class="px-4 py-3">Dicatat oleh</th>
                                <th class="px-4 py-3 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($purchaseOrders as $purchaseOrder)
                                <tr>
                                    <td class="px-4 py-3"><a class="font-medium text-indigo-600 hover:text-indigo-800" href="{{ route('purchase-orders.show', $purchaseOrder) }}">{{ $purchaseOrder->po_number }}</a></td>
                                    <td class="px-4 py-3">{{ $purchaseOrder->purchased_at->format('d M Y H:i') }}</td>
                                    <td class="px-4 py-3">{{ $purchaseOrder->user->name }}</td>
                                    <td class="px-4 py-3 text-right">Rp{{ number_format($purchaseOrder->total, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">Belum ada riwayat pembelian.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($purchaseOrders->hasPages()) <div class="mt-6">{{ $purchaseOrders->links() }}</div> @endif
            </x-card>
        </div>
    </div>
</x-app-layout>
