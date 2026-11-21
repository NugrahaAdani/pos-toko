<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold leading-tight text-gray-800">Laporan Penjualan</h1>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="mx-auto max-w-6xl space-y-6 px-4 sm:px-6 lg:px-8">
            <x-card>
                <form method="GET" action="{{ route('report.sales') }}" class="mb-6 flex flex-wrap items-end gap-3">
                    <div>
                        <label for="start" class="block text-sm font-medium text-gray-700">Dari tanggal</label>
                        <input id="start" type="date" name="start" value="{{ $start->format('Y-m-d') }}" required
                            class="mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('start')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="end" class="block text-sm font-medium text-gray-700">Sampai tanggal</label>
                        <input id="end" type="date" name="end" value="{{ $end->format('Y-m-d') }}" required
                            class="mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('end')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="cashier_id" class="block text-sm font-medium text-gray-700">Kasir</label>
                        <select id="cashier_id" name="cashier_id"
                            class="mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Semua kasir</option>
                            @foreach ($cashiers as $cashier)
                                <option value="{{ $cashier->id }}" @selected($cashierId === (int) $cashier->id)>
                                    {{ $cashier->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('cashier_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit"
                        class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                        Tampilkan
                    </button>
                    <a href="{{ route('report.export', ['start' => $start->format('Y-m-d'), 'end' => $end->format('Y-m-d'), 'cashier_id' => $cashierId]) }}"
                        class="rounded-md bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700">
                        Export Excel
                    </a>
                </form>

                <div class="mb-6 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-md bg-gray-50 p-4">
                        <p class="text-xs text-gray-500">Total penjualan</p>
                        <p class="mt-1 text-xl font-semibold text-gray-900">Rp{{ number_format($totalPenjualan, 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-md bg-gray-50 p-4">
                        <p class="text-xs text-gray-500">Jumlah transaksi</p>
                        <p class="mt-1 text-xl font-semibold text-gray-900">{{ number_format($jumlahTransaksi) }}</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
                        <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                            <tr>
                                <th scope="col" class="px-3 py-2">No. Invoice</th>
                                <th scope="col" class="px-3 py-2">Tanggal</th>
                                <th scope="col" class="px-3 py-2">Kasir</th>
                                <th scope="col" class="px-3 py-2 text-right">Total</th>
                                <th scope="col" class="px-3 py-2 text-right">Bayar</th>
                                <th scope="col" class="px-3 py-2 text-right">Kembalian</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-gray-700">
                            @forelse ($transactions as $transaction)
                                <tr>
                                    <td class="whitespace-nowrap px-3 py-2 font-mono text-xs font-medium text-gray-900">
                                        {{ $transaction->invoice_number }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-2">
                                        {{ $transaction->created_at->format('d M Y H:i') }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-2">{{ $transaction->user->name }}</td>
                                    <td class="whitespace-nowrap px-3 py-2 text-right">
                                        Rp{{ number_format($transaction->total, 0, ',', '.') }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-2 text-right">
                                        Rp{{ number_format($transaction->pay, 0, ',', '.') }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-2 text-right">
                                        Rp{{ number_format($transaction->change, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-3 py-10 text-center text-gray-500">
                                        Tidak ada transaksi pada rentang tanggal ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($transactions->hasPages())
                    <div class="mt-6">{{ $transactions->links() }}</div>
                @endif
            </x-card>
        </div>
    </div>
</x-app-layout>
