<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold leading-tight text-gray-800">Transaksi Penjualan</h1>
    </x-slot>

    <div class="py-8 sm:py-12" x-data="posCart(@js($products))">
        <div class="mx-auto grid max-w-7xl grid-cols-1 gap-6 px-4 sm:px-6 lg:px-8 lg:grid-cols-3">
            <section class="space-y-4 lg:col-span-2" aria-labelledby="available-products-heading">
                <x-card>
                    <h2 id="available-products-heading" class="mb-4 text-lg font-semibold text-gray-900">Pilih Produk</h2>
                    <label for="product-search" class="sr-only">Cari produk</label>
                    <input id="product-search" type="search" x-model="keyword"
                        placeholder="Cari nama atau kode produk..."
                        class="mb-4 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">

                    <div class="grid max-h-[520px] grid-cols-1 gap-3 overflow-y-auto sm:grid-cols-2 xl:grid-cols-3">
                        <template x-for="product in filteredProducts()" :key="product.id">
                            <button type="button" @click="addItem(product)"
                                class="rounded-md border border-gray-200 p-3 text-left hover:border-indigo-300 hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <p class="font-medium text-gray-900" x-text="product.name"></p>
                                <p class="mt-1 text-xs text-gray-500" x-text="product.code + ' · Stok: ' + product.stock + ' ' + product.unit"></p>
                                <p class="mt-2 text-sm font-semibold text-indigo-600" x-text="formatRupiah(product.price)"></p>
                            </button>
                        </template>
                        <p x-show="filteredProducts().length === 0" class="col-span-full py-8 text-center text-sm text-gray-500">
                            Tidak ada produk tersedia yang cocok.
                        </p>
                    </div>
                </x-card>
            </section>

            <section aria-labelledby="cart-heading">
                <x-card>
                    <h2 id="cart-heading" class="mb-4 text-lg font-semibold text-gray-900">Keranjang</h2>

                    @if ($errors->any())
                        <div class="mb-4 rounded-md bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">
                            <ul class="list-inside list-disc space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('pos.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="max-h-[360px] space-y-3 overflow-y-auto">
                            <template x-for="(item, index) in cart" :key="item.id">
                                <div class="rounded-md border border-gray-200 p-3">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <p class="truncate text-sm font-medium text-gray-900" x-text="item.name"></p>
                                            <p class="mt-1 text-xs text-gray-500" x-text="formatRupiah(item.price) + ' / ' + item.unit"></p>
                                        </div>
                                        <button type="button" @click="removeItem(index)" :aria-label="'Hapus ' + item.name"
                                            class="text-lg leading-none text-red-600 hover:text-red-800">&times;</button>
                                    </div>
                                    <div class="mt-3 flex items-center justify-between gap-3">
                                        <label :for="'quantity-' + item.id" class="text-xs text-gray-600">Jumlah</label>
                                        <input :id="'quantity-' + item.id" type="number" min="1" step="1" :max="item.stock"
                                            x-model.number="item.qty" @input="clampQty(item)"
                                            class="w-24 rounded-md border-gray-300 text-center text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    <input type="hidden" :name="'items[' + index + '][product_id]'" :value="item.id">
                                    <input type="hidden" :name="'items[' + index + '][qty]'" :value="item.qty">
                                </div>
                            </template>

                            <p x-show="cart.length === 0" class="rounded-md bg-gray-50 px-3 py-8 text-center text-sm text-gray-500">
                                Keranjang masih kosong.
                            </p>
                        </div>

                        <div class="space-y-3 border-t border-gray-200 pt-4">
                            <div class="flex items-center justify-between font-semibold text-gray-900">
                                <span>Total</span>
                                <span x-text="formatRupiah(total())"></span>
                            </div>

                            <div>
                                <label for="payment" class="block text-sm font-medium text-gray-700">Bayar</label>
                                <input id="payment" type="number" name="pay" x-model.number="pay" min="0" step="1" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('pay')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center justify-between text-sm text-gray-600">
                                <span>Kembalian</span>
                                <span x-text="formatRupiah(change())"></span>
                            </div>
                        </div>

                        <button type="submit" :disabled="cart.length === 0 || pay < total()"
                            class="w-full rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-50">
                            Simpan Transaksi
                        </button>
                    </form>
                </x-card>
            </section>
        </div>
    </div>

    @push('scripts')
        <script>
            function posCart(products) {
                return {
                    products,
                    cart: [],
                    keyword: '',
                    pay: 0,
                    filteredProducts() {
                        const keyword = this.keyword.trim().toLowerCase();

                        if (!keyword) {
                            return this.products;
                        }

                        return this.products.filter((product) =>
                            product.name.toLowerCase().includes(keyword)
                            || product.code.toLowerCase().includes(keyword)
                        );
                    },
                    addItem(product) {
                        const existingItem = this.cart.find((item) => item.id === product.id);

                        if (existingItem) {
                            if (existingItem.qty < product.stock) {
                                existingItem.qty++;
                            }

                            return;
                        }

                        this.cart.push({ ...product, qty: 1 });
                    },
                    clampQty(item) {
                        item.qty = Number.parseInt(item.qty, 10) || 1;
                        item.qty = Math.max(1, Math.min(item.qty, item.stock));
                    },
                    removeItem(index) {
                        this.cart.splice(index, 1);
                    },
                    total() {
                        return this.cart.reduce((sum, item) => sum + item.price * item.qty, 0);
                    },
                    change() {
                        return Math.max(this.pay - this.total(), 0);
                    },
                    formatRupiah(value) {
                        return 'Rp' + Number(value || 0).toLocaleString('id-ID');
                    },
                };
            }
        </script>
    @endpush
</x-app-layout>
