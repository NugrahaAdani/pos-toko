<nav class="border-b border-gray-200 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex min-h-16 flex-wrap items-center justify-between gap-4 py-3">
            <div class="flex flex-wrap items-center gap-x-6 gap-y-2">
                <a href="{{ route('dashboard') }}"
                    @class([
                        'font-semibold',
                        'text-indigo-600' => request()->routeIs('dashboard'),
                        'text-gray-700' => ! request()->routeIs('dashboard'),
                    ])>
                    Dashboard
                </a>

                @if (auth()->user()->role === 'admin')
                    <a href="{{ route('categories.index') }}"
                        @class([
                            'text-sm',
                            'text-indigo-600' => request()->routeIs('categories.*'),
                            'text-gray-500 hover:text-gray-900' => ! request()->routeIs('categories.*'),
                        ])>
                        Kategori
                    </a>
                    <a href="{{ route('products.index') }}"
                        @class([
                            'text-sm',
                            'text-indigo-600' => request()->routeIs('products.*'),
                            'text-gray-500 hover:text-gray-900' => ! request()->routeIs('products.*'),
                        ])>
                        Produk
                    </a>
                    <a href="{{ route('suppliers.index') }}"
                        @class([
                            'text-sm',
                            'text-indigo-600' => request()->routeIs('suppliers.*'),
                            'text-gray-500 hover:text-gray-900' => ! request()->routeIs('suppliers.*'),
                        ])>
                        Supplier
                    </a>
                    <a href="{{ route('purchase-orders.index') }}"
                        @class([
                            'text-sm',
                            'text-indigo-600' => request()->routeIs('purchase-orders.*'),
                            'text-gray-500 hover:text-gray-900' => ! request()->routeIs('purchase-orders.*'),
                        ])>
                        Pembelian Stok
                    </a>
                    <a href="{{ route('report.sales') }}"
                        @class([
                            'text-sm',
                            'text-indigo-600' => request()->routeIs('report.sales'),
                            'text-gray-500 hover:text-gray-900' => ! request()->routeIs('report.sales'),
                        ])>
                        Laporan
                    </a>
                @endif

                <a href="{{ route('pos.index') }}"
                    @class([
                        'text-sm',
                        'text-indigo-600' => request()->routeIs('pos.index'),
                        'text-gray-500 hover:text-gray-900' => ! request()->routeIs('pos.index'),
                    ])>
                    Transaksi
                </a>

                @if (auth()->user()->role === 'kasir')
                    <a href="{{ route('pos.history') }}"
                        @class([
                            'text-sm',
                            'text-indigo-600' => request()->routeIs('pos.history'),
                            'text-gray-500 hover:text-gray-900' => ! request()->routeIs('pos.history'),
                        ])>
                        Riwayat Transaksi Saya
                    </a>
                @endif
            </div>

            <div class="flex items-center gap-4 text-sm">
                <span class="text-gray-600">{{ auth()->user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-gray-500 hover:text-red-600">
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
