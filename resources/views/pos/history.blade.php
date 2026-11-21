<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold leading-tight text-gray-800">Riwayat Transaksi Saya</h1>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <x-card>
                <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Transaksi yang Saya Proses</h2>
                        <p class="mt-1 text-sm text-gray-600">Transaksi terbaru ditampilkan lebih dahulu.</p>
                    </div>
                    <a href="{{ route('pos.index') }}"
                        class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                        Transaksi Baru
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
                        <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                            <tr>
                                <th scope="col" class="px-4 py-3">No. Invoice</th>
                                <th scope="col" class="px-4 py-3">Tanggal</th>
                                <th scope="col" class="px-4 py-3">Produk</th>
                                <th scope="col" class="px-4 py-3 text-right">Total</th>
                                <th scope="col" class="px-4 py-3 text-right">Bayar</th>
                                <th scope="col" class="px-4 py-3 text-right">Kembalian</th>
                                <th scope="col" class="px-4 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-gray-700">
                            @forelse ($transactions as $transaction)
                                <tr>
                                    <td class="whitespace-nowrap px-4 py-3 font-mono text-xs font-medium text-gray-900">
                                        {{ $transaction->invoice_number }}
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-3">
                                        {{ $transaction->created_at->format('d M Y H:i') }}
                                    </td>
                                    <td class="min-w-56 px-4 py-3">
                                        <ul class="space-y-1">
                                            @foreach ($transaction->details as $detail)
                                                <li>{{ $detail->product->name }} × {{ number_format($detail->qty) }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-3 text-right font-medium">
                                        Rp{{ number_format($transaction->total, 0, ',', '.') }}
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-3 text-right">
                                        Rp{{ number_format($transaction->pay, 0, ',', '.') }}
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-3 text-right">
                                        Rp{{ number_format($transaction->change, 0, ',', '.') }}
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-3 text-right">
                                        <a href="{{ route('pos.struk', $transaction) }}"
                                            class="font-medium text-indigo-600 hover:text-indigo-800">
                                            Lihat struk
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-10 text-center text-gray-500">
                                        Belum ada transaksi yang Anda proses.
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
