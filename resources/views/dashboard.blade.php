<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold leading-tight text-gray-800">Dashboard</h1>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <x-card>
                    <p class="text-sm text-gray-500">Omzet Hari Ini</p>
                    <p class="mt-2 text-2xl font-semibold text-gray-900">
                        Rp{{ number_format($omzetHariIni, 0, ',', '.') }}
                    </p>
                </x-card>
                <x-card>
                    <p class="text-sm text-gray-500">Omzet Kemarin</p>
                    <p class="mt-2 text-2xl font-semibold text-gray-900">
                        Rp{{ number_format($omzetKemarin, 0, ',', '.') }}
                    </p>
                </x-card>
                <x-card>
                    <p class="text-sm text-gray-500">Transaksi Hari Ini</p>
                    <p class="mt-2 text-2xl font-semibold text-gray-900">{{ number_format($transaksiHariIni) }}</p>
                </x-card>
                <x-card>
                    <p class="text-sm text-gray-500">Produk Stok Menipis</p>
                    <p class="mt-2 text-2xl font-semibold text-yellow-600">{{ number_format($produkMenipis) }}</p>
                </x-card>
            </div>

            <div class="grid gap-6 xl:grid-cols-3">
                <x-card class="xl:col-span-2">
                    <h2 class="mb-4 text-lg font-semibold text-gray-900">Tren Penjualan 7 Hari Terakhir</h2>
                    <div class="relative h-72">
                        <canvas id="chartTren" data-labels="{{ $labelTren->toJson() }}"
                            data-values="{{ $dataTren->toJson() }}" aria-label="Grafik tren penjualan tujuh hari terakhir"
                            role="img"></canvas>
                    </div>
                </x-card>

                <x-card>
                    <h2 class="mb-4 text-lg font-semibold text-gray-900">5 Produk Terlaris</h2>
                    @if ($produkTerlaris->isNotEmpty())
                        <ol class="list-inside list-decimal space-y-3 text-sm text-gray-700">
                            @foreach ($produkTerlaris as $produk)
                                <li class="font-medium text-gray-900">
                                    {{ $produk->name }}
                                    <span class="font-normal text-gray-500">({{ number_format($produk->total_qty) }} terjual)</span>
                                </li>
                            @endforeach
                        </ol>
                    @else
                        <p class="text-sm text-gray-500">Belum ada penjualan produk.</p>
                    @endif
                </x-card>
            </div>
        </div>
    </div>
</x-app-layout>
