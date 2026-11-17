<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;

class OwnerController extends Controller
{
    public function index()
    {
        $orders = Pesanan::query()
            ->whereIn('status', ['diproses', 'selesai']);

        $total = (clone $orders)->sum('total_tagihan');
        $selesai = (clone $orders)->where('status', 'selesai')->count();

        $transactions = (clone $orders)
            ->latest('created_at')
            ->paginate(10);

        return view('owner.dashboard', compact(
            'total',
            'selesai',
            'transactions',
        ));
    }
}