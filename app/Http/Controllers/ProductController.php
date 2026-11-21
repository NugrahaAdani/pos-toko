<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    public function index(Request $request): Response
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:150'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'stock_status' => ['nullable', 'in:menipis,habis'],
            'per_page' => ['nullable', 'integer', 'in:10,25,50'],
        ]);

        $perPage = (int) ($filters['per_page'] ?? 10);
        $lowStockThreshold = (int) config('inventory.low_stock_threshold', 10);
        $query = Product::query()
            ->with('category');

        if (filled($filters['search'] ?? null)) {
            $keyword = $filters['search'];

            $query->where(function ($query) use ($keyword): void {
                $query->where('name', 'like', "%{$keyword}%")
                    ->orWhere('code', 'like', "%{$keyword}%");
            });
        }

        if (filled($filters['category_id'] ?? null)) {
            $query->where('category_id', $filters['category_id']);
        }

        if (($filters['stock_status'] ?? null) === 'menipis') {
            $query->where('stock', '>', 0)
                ->where('stock', '<', $lowStockThreshold);
        } elseif (($filters['stock_status'] ?? null) === 'habis') {
            $query->where('stock', 0);
        }

        $products = $query->latest()->paginate($perPage)->withQueryString();
        $categories = Category::query()->orderBy('name')->get();

        return response()->view('products.index', compact('products', 'categories', 'lowStockThreshold', 'perPage'));
    }

    public function create(): Response
    {
        $categories = Category::query()->orderBy('name')->get();

        return response()->view('products.create', compact('categories'));
    }

    public function store(StoreProductRequest $request): Response
    {
        Product::create($request->validated());

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show(Product $product): Response
    {
        $product->load(['category', 'transactionDetails.transaction']);

        $totalTerjual = $product->transactionDetails->sum('qty');
        $riwayat = $product->transactionDetails()
            ->with('transaction')
            ->latest()
            ->take(10)
            ->get();
        $peringkatHarian = $product->transactionDetails()
            ->join('transactions', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->selectRaw('DATE(transactions.created_at) as tanggal, SUM(transaction_details.qty) as total_qty, SUM(transaction_details.subtotal) as total_penjualan')
            ->groupByRaw('DATE(transactions.created_at)')
            ->orderByDesc('total_qty')
            ->orderByDesc('tanggal')
            ->limit(5)
            ->get();

        return response()->view('products.show', compact('product', 'totalTerjual', 'riwayat', 'peringkatHarian'));
    }

    public function edit(Product $product): Response
    {
        $categories = Category::query()->orderBy('name')->get();

        return response()->view('products.edit', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product): Response
    {
        $product->update($request->validated());

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product): Response
    {
        if ($product->transactionDetails()->exists() || $product->purchaseOrderItems()->exists()) {
            return redirect()->route('products.index')
                ->with('error', 'Produk tidak dapat dihapus karena sudah digunakan dalam transaksi atau riwayat pembelian.');
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }
}
