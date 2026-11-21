<?php

namespace App\Exports;

use App\Models\Transaction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class SalesTransactionsSheet implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    public function __construct(protected Carbon $start, protected Carbon $end, protected ?int $cashierId = null) {}

    public function collection(): Collection
    {
        return Transaction::query()
            ->with('user')
            ->whereBetween('created_at', [$this->start, $this->end])
            ->when($this->cashierId !== null, fn ($query) => $query->where('user_id', $this->cashierId))
            ->latest()
            ->get();
    }

    public function headings(): array
    {
        return ['No. Invoice', 'Tanggal', 'Kasir', 'Total', 'Bayar', 'Kembalian'];
    }

    public function title(): string
    {
        return 'Transaksi';
    }

    /**
     * @param  Transaction  $transaction
     * @return array<int, int|string>
     */
    public function map(mixed $transaction): array
    {
        return [
            $transaction->invoice_number,
            $transaction->created_at->format('d-m-Y H:i'),
            $transaction->user->name,
            $transaction->total,
            $transaction->pay,
            $transaction->change,
        ];
    }
}
