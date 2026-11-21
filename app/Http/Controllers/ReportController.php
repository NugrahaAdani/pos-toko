<?php

namespace App\Http\Controllers;

use App\Exports\SalesExport;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;

class ReportController extends Controller
{
    public function sales(Request $request): Response
    {
        [$start, $end] = $this->dateRange($request);
        $cashierId = $this->cashierId($request);
        $cashiers = User::query()
            ->where('role', 'kasir')
            ->orderBy('name')
            ->get(['id', 'name']);

        $transactions = Transaction::query()
            ->with('user')
            ->whereBetween('created_at', [$start, $end])
            ->when($cashierId !== null, fn ($query) => $query->where('user_id', $cashierId))
            ->latest()
            ->paginate(25)
            ->withQueryString();

        $summary = Transaction::query()
            ->whereBetween('created_at', [$start, $end])
            ->when($cashierId !== null, fn ($query) => $query->where('user_id', $cashierId))
            ->selectRaw('COALESCE(SUM(total), 0) as total_penjualan, COUNT(*) as jumlah_transaksi')
            ->first();
        $totalPenjualan = (int) $summary->total_penjualan;
        $jumlahTransaksi = (int) $summary->jumlah_transaksi;

        return response()->view('reports.sales', compact(
            'transactions',
            'totalPenjualan',
            'jumlahTransaksi',
            'cashierId',
            'cashiers',
            'start',
            'end',
        ));
    }

    public function export(Request $request): Response
    {
        [$start, $end] = $this->dateRange($request);
        $cashierId = $this->cashierId($request);
        $filename = 'laporan-penjualan-'.$start->format('Ymd').'-'.$end->format('Ymd').'.xlsx';

        return Excel::download(new SalesExport($start, $end, $cashierId), $filename);
    }

    private function cashierId(Request $request): ?int
    {
        $filters = $request->validate([
            'cashier_id' => ['nullable', 'integer', Rule::exists('users', 'id')->where('role', 'kasir')],
        ]);

        return filled($filters['cashier_id'] ?? null) ? (int) $filters['cashier_id'] : null;
    }

    /**
     * @return array{Carbon, Carbon}
     */
    private function dateRange(Request $request): array
    {
        $dates = $request->validate([
            'start' => ['nullable', 'date_format:Y-m-d'],
            'end' => ['nullable', 'date_format:Y-m-d'],
        ]);

        $start = filled($dates['start'] ?? null)
            ? Carbon::parse($dates['start'])->startOfDay()
            : now()->subDays(6)->startOfDay();
        $end = filled($dates['end'] ?? null)
            ? Carbon::parse($dates['end'])->endOfDay()
            : now()->endOfDay();

        if ($end->lt($start)) {
            throw ValidationException::withMessages([
                'end' => 'Tanggal akhir harus sama dengan atau setelah tanggal awal.',
            ]);
        }

        return [$start, $end];
    }
}
