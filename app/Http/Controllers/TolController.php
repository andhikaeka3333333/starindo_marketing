<?php

namespace App\Http\Controllers;

use App\Models\Tol;
use Illuminate\Http\Request;

class TolController extends Controller
{
    public function index()
    {
        $history = Tol::latest()->paginate(10);
        $current_balance = Tol::latest()->first()->saldo_akhir ?? 0;

        // Statistik untuk Dashboard
        $total_topup = Tol::where('tipe', 'topup')->sum('nominal');
        $total_out = Tol::where('tipe', 'out')->sum('nominal');

        return view('toll.index', compact('history', 'current_balance', 'total_topup', 'total_out'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'keterangan' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:1',
            'tipe' => 'required|in:topup,out'
        ]);

        $last_balance = Tol::latest()->first()->saldo_akhir ?? 0;

        if ($request->tipe == 'topup') {
            $new_balance = $last_balance + $request->nominal;
        } else {
            if ($last_balance < $request->nominal) {
                return redirect()->back()->with('error', 'Saldo tidak cukup untuk pembayaran ini!');
            }
            $new_balance = $last_balance - $request->nominal;
        }

        Tol::create([
            'keterangan' => $request->keterangan,
            'tipe' => $request->tipe,
            'nominal' => $request->nominal,
            'saldo_akhir' => $new_balance
        ]);

        return redirect()->back()->with('success', 'Transaksi berhasil dicatat!');
    }
}
