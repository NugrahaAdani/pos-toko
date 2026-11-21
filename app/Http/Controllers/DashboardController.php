<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $today = Carbon::today();
        $tomorrow = $today->copy()->addDay();
        $trendStart = $today->copy()->subDays(6)->startOfDay();

        $omzetHariIni = Transaction::query()
            ->where('created_at', '>=', $today->copy()->startOfDay())
            ->where('created_at', '<', $tomorrow)
            ->sum('total');
        $omzetKemarin = Transaction::query()
            ->where('created_at', '>=', $today->copy()->subDay()->startOfDay())
            ->where('created_at', '<', $today)
            ->sum('total');
        $transaksiHariIni = Transaction::query()
            ->where('created_at', '>=', $today->copy()->startOfDay())
            ->where('created_at', '<', $tomorrow)
            ->count();
        $produkMenipis = Product::query()
            ->where('stock', '<', 10)
            ->count();

        $trend = Transaction::query()
            ->where('created_at', '>=', $trendStart)
            ->where('created_at', '<', $tomorrow)
            ->selectRaw('DATE(created_at) as tanggal, SUM(total) as total')
            ->groupByRaw('DATE(created_at)')
            ->orderBy('tanggal')
            ->get();

        $labelTren = collect(range(6, 0))
            ->map(fn (int $daysAgo): string => $today->copy()->subDays($daysAgo)->format('Y-m-d'));
        $dataTren = $labelTren->map(
            fn (string $date): int => (int) ($trend->firstWhere('tanggal', $date)->total ?? 0)
        );

        $produkTerlaris = Product::query()
            ->join('transaction_details', 'transaction_details.product_id', '=', 'products.id')
            ->select('products.id', 'products.name')
            ->selectRaw('SUM(transaction_details.qty) as total_qty')
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_qty')
            ->orderBy('products.name')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'omzetHariIni',
            'omzetKemarin',
            'transaksiHariIni',
            'produkMenipis',
            'labelTren',
            'dataTren',
            'produkTerlaris',
        ));
    }
}
