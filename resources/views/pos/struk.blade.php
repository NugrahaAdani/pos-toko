<x-app-layout>
    <style>
        @page {
            size: 58mm auto;
            margin: 0;
        }

        @media print {
            html,
            body {
                width: 58mm !important;
                min-width: 58mm !important;
                min-height: 0 !important;
                margin: 0 !important;
                padding: 0 !important;
                background: #fff !important;
            }

            nav,
            header,
            .no-print {
                display: none !important;
            }

            main,
            .receipt-shell {
                width: 58mm !important;
                max-width: none !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .receipt-page {
                padding-top: 0 !important;
                padding-bottom: 0 !important;
            }

            .receipt-card {
                box-sizing: border-box !important;
                width: 58mm !important;
                max-width: none !important;
                margin: 0 !important;
                padding: 3mm !important;
                overflow: visible !important;
                border: 0 !important;
                border-radius: 0 !important;
                box-shadow: none !important;
                color: #000 !important;
                font-family: Arial, sans-serif !important;
                font-size: 9pt !important;
                line-height: 1.3 !important;
            }

            .receipt-card p,
            .receipt-card dt,
            .receipt-card dd {
                color: #000 !important;
            }

            .receipt-card hr {
                border-color: #000 !important;
            }

            .receipt-item-name {
                overflow-wrap: anywhere;
            }
        }
    </style>

    <x-slot name="header">
        <h1 class="text-xl font-semibold leading-tight text-gray-800">Struk Transaksi</h1>
    </x-slot>

    <div class="receipt-page py-8 sm:py-12">
        <div class="receipt-shell mx-auto max-w-md px-4 sm:px-6 lg:px-8">
            <x-card class="receipt-card">
                @if (session('success'))
                    <div class="no-print mb-5 rounded-md bg-green-50 px-4 py-3 text-sm text-green-800" role="status">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="text-center">
                    <p class="font-semibold text-gray-900">POS Barokah Mart</p>
                    <p class="mt-1 text-xs text-gray-500">{{ $transaction->invoice_number }}</p>
                    <p class="mt-1 text-xs text-gray-500">
                        {{ $transaction->created_at->format('d M Y H:i') }}
                        <span aria-hidden="true">&middot;</span> Kasir: {{ $transaction->user->name }}
                    </p>
                </div>

                <hr class="my-4 border-gray-200">

                <div class="space-y-3">
                    @foreach ($transaction->details as $detail)
                        <div class="flex justify-between gap-4 text-sm">
                            <div>
                                <p class="receipt-item-name font-medium text-gray-900">{{ $detail->product->name }} × {{ number_format($detail->qty) }}</p>
                                <p class="text-xs text-gray-500">Rp{{ number_format($detail->price, 0, ',', '.') }} per item</p>
                            </div>
                            <p class="whitespace-nowrap text-gray-700">Rp{{ number_format($detail->subtotal, 0, ',', '.') }}</p>
                        </div>
                    @endforeach
                </div>

                <hr class="my-4 border-gray-200">

                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between font-semibold text-gray-900">
                        <dt>Total</dt>
                        <dd>Rp{{ number_format($transaction->total, 0, ',', '.') }}</dd>
                    </div>
                    <div class="flex justify-between text-gray-700">
                        <dt>Bayar</dt>
                        <dd>Rp{{ number_format($transaction->pay, 0, ',', '.') }}</dd>
                    </div>
                    <div class="flex justify-between text-gray-700">
                        <dt>Kembalian</dt>
                        <dd>Rp{{ number_format($transaction->change, 0, ',', '.') }}</dd>
                    </div>
                </dl>

                <div class="no-print mt-6 flex gap-3">
                    <button type="button" onclick="window.print()"
                        class="flex-1 rounded-md bg-indigo-600 px-4 py-2 text-center text-sm font-medium text-white hover:bg-indigo-700">
                        Cetak Struk
                    </button>
                    <a href="{{ route('pos.index') }}"
                        class="flex-1 rounded-md border border-gray-300 px-4 py-2 text-center text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Transaksi Baru
                    </a>
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>
