<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OwnerController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'from' => ['nullable', 'date_format:Y-m-d'],
            'to' => [
                'nullable',
                'date_format:Y-m-d',
                function (string $attribute, mixed $value, Closure $fail) use ($request): void {
                    $from = $request->input('from');

                    if (
                        is_string($from)
                        && preg_match('/^\d{4}-\d{2}-\d{2}$/', $from)
                        && is_string($value)
                        && $value < $from
                    ) {
                        $fail('Tanggal akhir harus sama dengan atau setelah tanggal awal.');
                    }
                },
            ],
        ]);

        $from = $filters['from'] ?? null;
        $to = $filters['to'] ?? null;

        $orders = Pesanan::query()
            ->whereIn('status', ['diproses', 'selesai'])
            ->when($from, fn ($query) => $query->whereDate('created_at', '>=', $from))
            ->when($to, fn ($query) => $query->whereDate('created_at', '<=', $to));

        $total = (clone $orders)->sum('total_tagihan');
        $selesai = (clone $orders)->where('status', 'selesai')->count();

        $paymentTotals = (clone $orders)
            ->select('metode_bayar', DB::raw('SUM(total_tagihan) as total'))
            ->groupBy('metode_bayar')
            ->pluck('total', 'metode_bayar');

        $transactions = (clone $orders)
            ->latest('created_at')
            ->paginate(10)
            ->withQueryString();

        $topMenusQuery = DB::table('detail_pesanans as dp')
            ->join('menus as m', 'm.id', '=', 'dp.menu_id')
            ->join('pesanans as p', 'p.id', '=', 'dp.pesanan_id')
            ->whereIn('p.status', ['diproses', 'selesai'])
            ->when($from, fn ($query) => $query->whereDate('p.created_at', '>=', $from))
            ->when($to, fn ($query) => $query->whereDate('p.created_at', '<=', $to));

        $topMenus = $topMenusQuery
            ->select('m.id', 'm.nama_menu')
            ->selectRaw('SUM(dp.qty) as total_terjual')
            ->groupBy('m.id', 'm.nama_menu')
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get();

        $tunai = $paymentTotals->get('tunai', 0);
        $nonTunai = $paymentTotals->get('non-tunai', 0);

        return view('owner.dashboard', compact(
            'from',
            'to',
            'total',
            'selesai',
            'tunai',
            'nonTunai',
            'transactions',
            'topMenus',
        ));
    }
}
