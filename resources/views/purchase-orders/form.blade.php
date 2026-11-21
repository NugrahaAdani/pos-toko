@php
    $purchaseItems = old('items');
    if ($purchaseItems === null) {
        $purchaseItems = $purchaseOrder
            ? $purchaseOrder->items->map(fn ($item) => ['product_id' => $item->product_id, 'qty' => $item->qty, 'unit_cost' => $item->unit_cost])->all()
            : [['product_id' => '', 'qty' => 1, 'unit_cost' => '']];
    }
@endphp

<form action="{{ $purchaseOrder ? route('purchase-orders.update', $purchaseOrder) : route('purchase-orders.store') }}" method="POST" class="space-y-6">
    @csrf
    @if ($purchaseOrder)
        @method('PUT')
    @endif

    @if ($suppliers->isEmpty())
        <div class="rounded-md bg-amber-50 px-4 py-3 text-sm text-amber-800" role="alert">Tambahkan supplier terlebih dahulu sebelum mencatat pembelian.</div>
    @endif
    @if ($products->isEmpty())
        <div class="rounded-md bg-amber-50 px-4 py-3 text-sm text-amber-800" role="alert">Belum ada produk aktif yang dapat dibeli.</div>
    @endif

    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label for="po_number" class="block text-sm font-medium text-gray-700">Nomor pembelian / faktur</label>
            <input id="po_number" name="po_number" value="{{ old('po_number', $purchaseOrder?->po_number) }}" required maxlength="50"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('po_number') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="supplier_id" class="block text-sm font-medium text-gray-700">Supplier</label>
            <select id="supplier_id" name="supplier_id" required @disabled($suppliers->isEmpty())
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Pilih supplier</option>
                @foreach ($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" @selected(old('supplier_id', $purchaseOrder?->supplier_id) == $supplier->id)>{{ $supplier->name }}</option>
                @endforeach
            </select>
            @error('supplier_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>

    <div>
        <label for="purchased_at" class="block text-sm font-medium text-gray-700">Tanggal pembelian</label>
        <input id="purchased_at" name="purchased_at" type="datetime-local"
            value="{{ old('purchased_at', $purchaseOrder?->purchased_at?->format('Y-m-d\TH:i') ?? now()->format('Y-m-d\TH:i')) }}" required
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        @error('purchased_at') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <div class="mb-3 flex flex-wrap items-center justify-between gap-3">
            <h2 class="text-base font-semibold text-gray-900">Produk yang dibeli</h2>
            <button type="button" id="add-purchase-item" @disabled($products->isEmpty())
                class="rounded-md border border-indigo-600 px-3 py-2 text-sm font-medium text-indigo-600 hover:bg-indigo-50 disabled:opacity-50">Tambah Produk</button>
        </div>
        @error('items') <p class="mb-3 text-sm text-red-600">{{ $message }}</p> @enderror
        <div id="purchase-items" class="space-y-4">
            @foreach ($purchaseItems as $index => $item)
                <div class="grid gap-3 rounded-md border border-gray-200 p-4 sm:grid-cols-12" data-purchase-item>
                    <div class="sm:col-span-6">
                        <label for="items_{{ $index }}_product_id" class="block text-sm font-medium text-gray-700">Produk</label>
                        <select id="items_{{ $index }}_product_id" name="items[{{ $index }}][product_id]" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Pilih produk</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}" @selected(($item['product_id'] ?? '') == $product->id)>
                                    {{ $product->name }} ({{ $product->code }})
                                </option>
                            @endforeach
                        </select>
                        @error("items.$index.product_id") <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label for="items_{{ $index }}_qty" class="block text-sm font-medium text-gray-700">Qty</label>
                        <input id="items_{{ $index }}_qty" name="items[{{ $index }}][qty]" type="number" min="1" step="1" required value="{{ $item['qty'] ?? 1 }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error("items.$index.qty") <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="sm:col-span-3">
                        <label for="items_{{ $index }}_unit_cost" class="block text-sm font-medium text-gray-700">Harga beli / unit (Rp)</label>
                        <input id="items_{{ $index }}_unit_cost" name="items[{{ $index }}][unit_cost]" type="number" min="0" step="1" required value="{{ $item['unit_cost'] ?? '' }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error("items.$index.unit_cost") <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex items-end sm:col-span-1">
                        <button type="button" data-remove-purchase-item class="w-full rounded-md px-2 py-2 text-sm text-red-600 hover:bg-red-50">Hapus</button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <template id="purchase-item-template">
        <div class="grid gap-3 rounded-md border border-gray-200 p-4 sm:grid-cols-12" data-purchase-item>
            <div class="sm:col-span-6">
                <label for="items___INDEX___product_id" class="block text-sm font-medium text-gray-700">Produk</label>
                <select id="items___INDEX___product_id" name="items[__INDEX__][product_id]" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Pilih produk</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->code }})</option>
                    @endforeach
                </select>
            </div>
            <div class="sm:col-span-2">
                <label for="items___INDEX___qty" class="block text-sm font-medium text-gray-700">Qty</label>
                <input id="items___INDEX___qty" name="items[__INDEX__][qty]" type="number" min="1" step="1" required value="1"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div class="sm:col-span-3">
                <label for="items___INDEX___unit_cost" class="block text-sm font-medium text-gray-700">Harga beli / unit (Rp)</label>
                <input id="items___INDEX___unit_cost" name="items[__INDEX__][unit_cost]" type="number" min="0" step="1" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div class="flex items-end sm:col-span-1">
                <button type="button" data-remove-purchase-item class="w-full rounded-md px-2 py-2 text-sm text-red-600 hover:bg-red-50">Hapus</button>
            </div>
        </div>
    </template>

    <div>
        <label for="notes" class="block text-sm font-medium text-gray-700">Catatan</label>
        <textarea id="notes" name="notes" rows="3" maxlength="2000"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes', $purchaseOrder?->notes) }}</textarea>
        @error('notes') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="flex items-center gap-3">
        <button type="submit" @disabled($suppliers->isEmpty() || $products->isEmpty())
            class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-50">Simpan Pembelian</button>
        <a href="{{ route('purchase-orders.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Batal</a>
    </div>
</form>

<script>
    (() => {
        const container = document.getElementById('purchase-items');
        const template = document.getElementById('purchase-item-template');
        const addButton = document.getElementById('add-purchase-item');
        let nextIndex = {{ $purchaseItems === [] ? 0 : max(array_map('intval', array_keys($purchaseItems))) + 1 }};

        addButton?.addEventListener('click', () => {
            const row = template.content.cloneNode(true);
            row.querySelectorAll('[id], [for], [name]').forEach((element) => {
                for (const attribute of ['id', 'for', 'name']) {
                    if (element.hasAttribute(attribute)) {
                        element.setAttribute(attribute, element.getAttribute(attribute).replaceAll('__INDEX__', nextIndex));
                    }
                }
            });
            container.append(row);
            nextIndex += 1;
        });

        container?.addEventListener('click', (event) => {
            if (event.target.closest('[data-remove-purchase-item]')) {
                event.target.closest('[data-purchase-item]').remove();
            }
        });
    })();
</script>
