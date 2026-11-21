<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class PurchaseOrderController extends Controller
{
    public function index(): Response
    {
        $purchaseOrders = PurchaseOrder::query()
            ->with(['supplier', 'user'])
            ->withCount('items')
            ->latest('purchased_at')
            ->paginate(10);

        return response()->view('purchase-orders.index', compact('purchaseOrders'));
    }

    public function create(): Response
    {
        return response()->view('purchase-orders.create', $this->formData());
    }

    public function store(Request $request): Response
    {
        $data = $this->validatedData($request);

        $purchaseOrder = DB::transaction(function () use ($data, $request): PurchaseOrder {
            $items = $this->prepareItems($data['items']);
            $this->adjustStock($this->quantityChanges($items));

            $purchaseOrder = PurchaseOrder::create([
                'po_number' => $data['po_number'],
                'supplier_id' => $data['supplier_id'],
                'user_id' => $request->user()->id,
                'purchased_at' => $data['purchased_at'],
                'total' => array_sum(array_column($items, 'subtotal')),
                'notes' => $data['notes'] ?? null,
            ]);
            $purchaseOrder->items()->createMany($items);

            return $purchaseOrder;
        });

        return redirect()->route('purchase-orders.show', $purchaseOrder)
            ->with('success', 'Pembelian disimpan dan stok produk berhasil diperbarui.');
    }

    public function show(PurchaseOrder $purchaseOrder): Response
    {
        $purchaseOrder->load(['supplier', 'user', 'items.product']);

        return response()->view('purchase-orders.show', compact('purchaseOrder'));
    }

    public function edit(PurchaseOrder $purchaseOrder): Response
    {
        $purchaseOrder->load('items');

        return response()->view('purchase-orders.edit', $this->formData($purchaseOrder));
    }

    public function update(Request $request, PurchaseOrder $purchaseOrder): Response
    {
        $data = $this->validatedData($request, $purchaseOrder);

        DB::transaction(function () use ($data, $purchaseOrder): void {
            $lockedOrder = PurchaseOrder::query()
                ->whereKey($purchaseOrder->getKey())
                ->lockForUpdate()
                ->firstOrFail();
            $oldItems = $lockedOrder->items()->get();
            $newItems = $this->prepareItems($data['items']);
            $changes = $this->quantityChanges($newItems);

            foreach ($oldItems as $oldItem) {
                $changes[$oldItem->product_id] = ($changes[$oldItem->product_id] ?? 0) - $oldItem->qty;
            }

            $this->adjustStock($changes);

            $lockedOrder->update([
                'po_number' => $data['po_number'],
                'supplier_id' => $data['supplier_id'],
                'purchased_at' => $data['purchased_at'],
                'total' => array_sum(array_column($newItems, 'subtotal')),
                'notes' => $data['notes'] ?? null,
            ]);
            $lockedOrder->items()->delete();
            $lockedOrder->items()->createMany($newItems);
        });

        return redirect()->route('purchase-orders.show', $purchaseOrder)
            ->with('success', 'Pembelian diperbarui dan stok berhasil disesuaikan.');
    }

    public function destroy(PurchaseOrder $purchaseOrder): Response
    {
        DB::transaction(function () use ($purchaseOrder): void {
            $lockedOrder = PurchaseOrder::query()
                ->whereKey($purchaseOrder->getKey())
                ->lockForUpdate()
                ->firstOrFail();
            $items = $lockedOrder->items()->get();
            $changes = [];

            foreach ($items as $item) {
                $changes[$item->product_id] = ($changes[$item->product_id] ?? 0) - $item->qty;
            }

            $this->adjustStock($changes);
            $lockedOrder->delete();
        });

        return redirect()->route('purchase-orders.index')
            ->with('success', 'Pembelian dihapus dan stok berhasil dikembalikan.');
    }

    /**
     * @return array{products: Collection, suppliers: Collection, purchaseOrder: ?PurchaseOrder}
     */
    private function formData(?PurchaseOrder $purchaseOrder = null): array
    {
        return [
            'products' => Product::query()->orderBy('name')->get(['id', 'code', 'name', 'unit']),
            'suppliers' => Supplier::query()->orderBy('name')->get(['id', 'name']),
            'purchaseOrder' => $purchaseOrder,
        ];
    }

    /**
     * @return array{po_number: string, supplier_id: int, purchased_at: string, notes: ?string, items: array<int, array{product_id: int, qty: int, unit_cost: int}>}
     */
    private function validatedData(Request $request, ?PurchaseOrder $purchaseOrder = null): array
    {
        return $request->validate([
            'po_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('purchase_orders', 'po_number')->ignore($purchaseOrder?->getKey()),
            ],
            'supplier_id' => ['required', 'integer', 'exists:suppliers,id'],
            'purchased_at' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'items' => ['required', 'array', 'min:1', 'max:100'],
            'items.*.product_id' => [
                'required',
                'integer',
                'distinct',
                Rule::exists('products', 'id')->whereNull('deleted_at'),
            ],
            'items.*.qty' => ['required', 'integer', 'min:1'],
            'items.*.unit_cost' => ['required', 'integer', 'min:0'],
        ]);
    }

    /**
     * @param  array<int, array{product_id: int, qty: int, unit_cost: int}>  $items
     * @return array<int, array{product_id: int, qty: int, unit_cost: int, subtotal: int}>
     */
    private function prepareItems(array $items): array
    {
        return array_map(fn (array $item): array => [
            'product_id' => (int) $item['product_id'],
            'qty' => (int) $item['qty'],
            'unit_cost' => (int) $item['unit_cost'],
            'subtotal' => (int) $item['qty'] * (int) $item['unit_cost'],
        ], $items);
    }

    /**
     * @param  array<int, array{product_id: int, qty: int}>  $items
     * @return array<int, int>
     */
    private function quantityChanges(array $items): array
    {
        $changes = [];

        foreach ($items as $item) {
            $changes[$item['product_id']] = ($changes[$item['product_id']] ?? 0) + $item['qty'];
        }

        return $changes;
    }

    /**
     * @param  array<int, int>  $changes
     */
    private function adjustStock(array $changes): void
    {
        if ($changes === []) {
            return;
        }

        $products = Product::query()
            ->whereIn('id', array_keys($changes))
            ->orderBy('id')
            ->lockForUpdate()
            ->get()
            ->keyBy('id');

        if ($products->count() !== count($changes)) {
            throw ValidationException::withMessages([
                'items' => 'Salah satu produk sudah tidak tersedia.',
            ]);
        }

        foreach ($changes as $productId => $change) {
            $product = $products->get($productId);
            $newStock = $product->stock + $change;

            if ($newStock < 0) {
                throw ValidationException::withMessages([
                    'items' => "Stok {$product->name} sudah terpakai sehingga pembelian tidak dapat diubah atau dihapus.",
                ]);
            }

            $product->stock = $newStock;
            $product->save();
        }
    }
}
