<?php

namespace App\Exports;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class BestSellingProductsSheet implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    private int $rank = 0;

    public function __construct(protected Carbon $start, protected Carbon $end, protected ?int $cashierId = null) {}

    public function collection(): Collection
    {
        return DB::table('transaction_details')
            ->join('transactions', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->join('products', 'products.id', '=', 'transaction_details.product_id')
            ->whereBetween('transactions.created_at', [$this->start, $this->end])
            ->when($this->cashierId !== null, fn ($query) => $query->where('transactions.user_id', $this->cashierId))
            ->selectRaw(
                'products.code as product_code, products.name as product_name, '
                .'SUM(transaction_details.qty) as total_qty, '
                .'SUM(transaction_details.subtotal) as total_sales'
            )
            ->groupBy('products.id', 'products.code', 'products.name')
            ->orderByDesc('total_qty')
            ->orderByDesc('total_sales')
            ->orderBy('products.name')
            ->get();
    }

    public function headings(): array
    {
        return ['Peringkat', 'Kode Produk', 'Nama Produk', 'Total Qty Terjual', 'Total Penjualan'];
    }

    public function title(): string
    {
        return 'Produk Terlaris';
    }

    /**
     * @param  object{product_code: string, product_name: string, total_qty: int|string, total_sales: int|string}  $product
     * @return array<int, int|string>
     */
    public function map(mixed $product): array
    {
        return [
            ++$this->rank,
            $product->product_code,
            $product->product_name,
            (int) $product->total_qty,
            (int) $product->total_sales,
        ];
    }
}
