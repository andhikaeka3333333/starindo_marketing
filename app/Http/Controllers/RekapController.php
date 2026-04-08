<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Marketing, BiayaAkomodasi, BiayaOperasional, BiayaTol, BiayaBensin, Pengajuan, Omset};
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RekapController extends Controller
{
    public function index(Request $request)
    {
        $marketings = Marketing::orderBy('nama', 'asc')->get();

        $marketingId = $request->query('marketing_id');
        $from = $request->query('from', date('Y-m-01'));
        $to = $request->query('to', date('Y-m-d'));

        $data = null;

        if ($marketingId) {
            $selectedMarketing = Marketing::findOrFail($marketingId);
            $startDate = Carbon::parse($from)->startOfDay();
            $endDate = Carbon::parse($to)->endOfDay();

            // --- 1. QUERY UNTUK PAGINATION (Tampilan Tabel) ---
            $omsets = DB::table('omsets')
                ->where('marketing_id', $marketingId)
                ->where(function ($q) use ($from, $to) {
                    $q->where('periode_dari', '<=', $to)
                        ->where('periode_sampai', '>=', $from);
                })
                ->paginate(10, ['*'], 'p_omset')->appends($request->query());

            $pengajuans = Pengajuan::where('marketing_id', $marketingId)
                ->whereBetween('tanggal', [$from, $to])
                ->paginate(10, ['*'], 'p_pengajuan')->appends($request->query());

            $akomodasi = BiayaAkomodasi::where('marketing_id', $marketingId)
                ->whereBetween('tanggal', [$from, $to])
                ->paginate(10, ['*'], 'p_akom')->appends($request->query());

            $tol = BiayaTol::where('marketing_id', $marketingId)
                ->where('kategori', 'Top-Up Tol')
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->paginate(10, ['*'], 'p_tol')->appends($request->query());

            $bensin = BiayaBensin::where('marketing_id', $marketingId)
                ->whereBetween('tanggal', [$from, $to])
                ->paginate(10, ['*'], 'p_bensin')->appends($request->query());

            $operasional = BiayaOperasional::where('marketing_id', $marketingId)
                ->whereBetween('tanggal', [$from, $to])
                ->paginate(10, ['*'], 'p_operasional')->appends($request->query());

            // --- 2. QUERY UNTUK SUMMARY (Total Seluruh Data) ---
            // Kita hitung ulang sum-nya agar tidak terpengaruh limit pagination
            $totalOmset = DB::table('omsets')
                ->where('marketing_id', $marketingId)
                ->where('periode_dari', '<=', $to)
                ->where('periode_sampai', '>=', $from)
                ->sum('nominal');

            $totalPengajuan = Pengajuan::where('marketing_id', $marketingId)
                ->whereBetween('tanggal', [$from, $to])
                ->sum('nominal_value');

            $totalBiayaJalan = BiayaAkomodasi::where('marketing_id', $marketingId)->whereBetween('tanggal', [$from, $to])->sum('nominal') +
                BiayaTol::where('marketing_id', $marketingId)->where('kategori', 'Top-Up Tol')->whereBetween('tanggal', [$startDate, $endDate])->sum('nominal') +
                BiayaBensin::where('marketing_id', $marketingId)->whereBetween('tanggal', [$from, $to])->sum('nominal') +
                BiayaOperasional::where('marketing_id', $marketingId)->whereBetween('tanggal', [$from, $to])->sum('nominal');
            $totalPengeluaran = $totalBiayaJalan + $totalPengajuan;
            $labaBersih = $totalOmset - $totalPengeluaran;
            $persenKeuntungan = $totalOmset > 0 ? ($labaBersih / $totalOmset) * 100 : 0;

            $data = (object) [
                'marketing' => $selectedMarketing,
                'omsets' => $omsets,
                'pengajuans' => $pengajuans,
                'akomodasi' => $akomodasi,
                'tol' => $tol,
                'bensin' => $bensin,
                'operasional' => $operasional,
                'summary' => [
                    'total_omset' => $totalOmset,
                    'total_pengajuan' => $totalPengajuan,
                    'total_biaya' => $totalBiayaJalan,
                    'total_pengeluaran' => $totalPengeluaran,
                    'persen_keuntungan' => $persenKeuntungan,
                    'laba_bersih' => $labaBersih
                ]
            ];
        }

        return view('rekap.index', compact('marketings', 'data', 'from', 'to', 'marketingId'));
    }
}
