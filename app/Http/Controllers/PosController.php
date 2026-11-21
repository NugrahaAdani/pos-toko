<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePosRequest;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class PosController extends Controller
{
    public function index(): Response
    {
        $products = Product::query()
            ->where('stock', '>', 0)
            ->orderBy('name')
            ->get(['id', 'code', 'name', 'price', 'stock', 'unit']);

        return response()->view('pos.index', compact('products'));
    }

    public function history(): Response
    {
        $transactions = Transaction::query()
            ->with('details.product')
            ->where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return response()->view('pos.history', compact('transactions'));
    }

    public function store(StorePosRequest $request): Response
    {
        $validated = $request->validated();

        $transaction = DB::transaction(function () use ($validated): Transaction {
            $total = 0;
            $details = [];
            $lockedProducts = [];
            $items = collect($validated['items'])->sortBy('product_id');

            foreach ($items as $item) {
                $product = Product::query()
                    ->lockForUpdate()
                    ->findOrFail((int) $item['product_id']);
                $quantity = (int) $item['qty'];

                if ($product->stock < $quantity) {
                    throw ValidationException::withMessages([
                        'items' => "Stok {$product->name} tidak mencukupi.",
                    ]);
                }

                $subtotal = $product->price * $quantity;
                $total += $subtotal;
                $details[] = [
                    'product_id' => $product->id,
                    'qty' => $quantity,
                    'price' => $product->price,
                    'subtotal' => $subtotal,
                ];
                $lockedProducts[] = [$product, $quantity];
            }

            $payment = (int) $validated['pay'];

            if ($payment < $total) {
                throw ValidationException::withMessages([
                    'pay' => 'Nominal bayar kurang dari total belanja.',
                ]);
            }

            $now = now();
            $transaction = Transaction::create([
                'invoice_number' => 'TMP-'.Str::uuid(),
                'user_id' => auth()->id(),
                'total' => $total,
                'pay' => $payment,
                'change' => $payment - $total,
            ]);
            $transaction->update([
                'invoice_number' => sprintf('INV-%s-%04d', $now->format('Ymd'), $transaction->id),
            ]);
            $transaction->details()->createMany($details);

            foreach ($lockedProducts as [$product, $quantity]) {
                $product->decrement('stock', $quantity);
            }

            return $transaction;
        }, attempts: 3);

        return redirect()->route('pos.struk', $transaction)
            ->with('success', 'Transaksi berhasil disimpan.');
    }

    public function struk(Transaction $transaction): Response
    {
        if (auth()->user()->role === 'kasir' && $transaction->user_id !== auth()->id()) {
            abort(404);
        }

        $transaction->load(['details.product', 'user']);

        return response()->view('pos.struk', compact('transaction'));
    }
}
