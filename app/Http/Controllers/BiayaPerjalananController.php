<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Marketing, BiayaAkomodasi, BiayaOperasional, BiayaTol, BiayaBensin, TempAkomodasi, TempOperasional, TempTol, TempBensin};
use Illuminate\Support\Facades\DB;

class BiayaPerjalananController extends Controller
{
    /**
     * Menampilkan semua data resmi dengan fitur pencarian global.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        // 1. Query Akomodasi (Search: Marketing, Customer, CP, Kategori, Wilayah)
        $akomodasi = BiayaAkomodasi::with('marketing')
            ->when($search, function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query->where('customer_nama', 'like', "%{$search}%")
                        ->orWhere('customer_cp', 'like', "%{$search}%")
                        ->orWhere('kategori', 'like', "%{$search}%")
                        ->orWhere('wilayah', 'like', "%{$search}%")
                        ->orWhereHas('marketing', fn($m) => $m->where('nama', 'like', "%{$search}%"));
                });
            })
            ->latest()->paginate(10, ['*'], 'page_akom');

        // 2. Query Operasional (Search: Marketing, Customer, CP, Kategori, Keterangan)
        $operasional = BiayaOperasional::with('marketing')
            ->when($search, function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query->where('customer_nama', 'like', "%{$search}%")
                        ->orWhere('customer_cp', 'like', "%{$search}%")
                        ->orWhere('kategori', 'like', "%{$search}%")
                        ->orWhere('keterangan', 'like', "%{$search}%")
                        ->orWhereHas('marketing', fn($m) => $m->where('nama', 'like', "%{$search}%"));
                });
            })
            ->latest()->paginate(10, ['*'], 'page_oper');

        // 3. Query Tol (Search: Marketing, Customer, CP, Kategori, Keterangan)
        $tol = BiayaTol::with('marketing')
            ->when($search, function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query->where('customer_nama', 'like', "%{$search}%")
                        ->orWhere('customer_cp', 'like', "%{$search}%")
                        ->orWhere('kategori', 'like', "%{$search}%")
                        ->orWhere('keterangan', 'like', "%{$search}%")
                        ->orWhereHas('marketing', fn($m) => $m->where('nama', 'like', "%{$search}%"));
                });
            })
            ->latest()->paginate(10, ['*'], 'page_tol');

        // 4. Query Bensin (Search: Marketing, Customer, CP, Keterangan, KM)
        $bensin = BiayaBensin::with('marketing')
            ->when($search, function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query->where('customer_nama', 'like', "%{$search}%")
                        ->orWhere('customer_cp', 'like', "%{$search}%")
                        ->orWhere('keterangan', 'like', "%{$search}%")
                        ->orWhere('km', 'like', "%{$search}%")
                        ->orWhereHas('marketing', fn($m) => $m->where('nama', 'like', "%{$search}%"));
                });
            })
            ->latest()->paginate(10, ['*'], 'page_bensin');

        return view('biaya_perjalanan.index', compact('akomodasi', 'operasional', 'tol', 'bensin'));
    }

    /**
     * Halaman pembuatan draf (Temp).
     */
    public function create()
    {
        $marketings = Marketing::all();
        $tempAkomodasi = TempAkomodasi::with('marketing')->get();
        $tempOperasional = TempOperasional::with('marketing')->get();
        $tempTol = TempTol::with('marketing')->get();
        $tempBensin = TempBensin::with('marketing')->get();
        $rates = DB::table('tarif_perjalanan')->get();

        return view('biaya_perjalanan.create', compact('marketings', 'tempAkomodasi', 'tempOperasional', 'tempTol', 'tempBensin', 'rates'));
    }

    /**
     * Menyimpan data ke tabel Temp berdasarkan kategori (Amfibi logic).
     */
    public function storeTemp(Request $request)
    {
        $kat = $request->kategori;
        $cleanNominal = (int) str_replace('.', '', $request->nominal_value ?? $request->nominal ?? 0);

        if (in_array($kat, ['Hotel', 'UM'])) {
            $tarif = DB::table('tarif_perjalanan')
                ->where('kategori', $kat)->where('level', $request->level)
                ->where('wilayah', $request->wilayah)->first();

            TempAkomodasi::create([
                'marketing_id' => $request->marketing_id,
                'tanggal' => $request->tanggal,
                'customer_nama' => $request->customer_nama,
                'customer_cp' => $request->customer_cp,
                'kategori' => $kat,
                'level' => $request->level,
                'wilayah' => $request->wilayah,
                'durasi' => $request->durasi ?? 1,
                'nominal' => ($tarif->nominal ?? 0) * ($request->durasi ?? 1),
            ]);
        } elseif (in_array($kat, ['Top-Up Tol', 'Pemakaian Tol'])) {
            TempTol::create([
                'marketing_id' => $request->marketing_id,
                'tanggal' => $request->tanggal,
                'customer_nama' => $request->customer_nama,
                'customer_cp' => $request->customer_cp,
                'kategori' => $kat,
                'keterangan' => $request->keterangan,
                'nominal' => $cleanNominal,
            ]);
        } elseif ($kat === 'Bensin') {
            TempBensin::create([
                'marketing_id' => $request->marketing_id,
                'tanggal' => $request->tanggal,
                'customer_nama' => $request->customer_nama,
                'customer_cp' => $request->customer_cp,
                'kategori' => $kat,
                'km' => $request->km,
                'keterangan' => $request->keterangan,
                'nominal' => $cleanNominal,
            ]);
        } else {
            TempOperasional::create([
                'marketing_id' => $request->marketing_id,
                'tanggal' => $request->tanggal,
                'customer_nama' => $request->customer_nama,
                'customer_cp' => $request->customer_cp,
                'kategori' => $kat,
                'keterangan' => $request->keterangan,
                'nominal' => $cleanNominal,
            ]);
        }
        return back()->with('success', 'Data draf berhasil ditambahkan.');
    }

    /**
     * Memindahkan semua data dari Temp ke Tabel Resmi (Finalize).
     */
    public function finalize()
    {
        DB::transaction(function () {
            foreach (TempAkomodasi::all() as $t) { BiayaAkomodasi::create($t->toArray()); $t->delete(); }
            foreach (TempOperasional::all() as $t) { BiayaOperasional::create($t->toArray()); $t->delete(); }
            foreach (TempTol::all() as $t) { BiayaTol::create($t->toArray()); $t->delete(); }
            foreach (TempBensin::all() as $t) { BiayaBensin::create($t->toArray()); $t->delete(); }
        });
        return redirect()->route('biaya-perjalanan.index')->with('success', 'Finalisasi sukses! Data sudah resmi.');
    }

    /**
     * Halaman Edit untuk data resmi.
     */
    public function edit($type, $id)
    {
        $marketings = Marketing::all();
        $rates = DB::table('tarif_perjalanan')->get();

        if ($type === 'akomodasi') $data = BiayaAkomodasi::findOrFail($id);
        elseif ($type === 'operasional') $data = BiayaOperasional::findOrFail($id);
        elseif ($type === 'tol') $data = BiayaTol::findOrFail($id);
        elseif ($type === 'bensin') $data = BiayaBensin::findOrFail($id);

        return view('biaya_perjalanan.edit', compact('data', 'type', 'marketings', 'rates'));
    }

    /**
     * Proses Update untuk data resmi.
     */
    public function update(Request $request, $type, $id)
    {
        $cleanNominal = (int) str_replace('.', '', $request->nominal ?? 0);

        if ($type === 'akomodasi') {
            $data = BiayaAkomodasi::findOrFail($id);
            $tarif = DB::table('tarif_perjalanan')
                ->where('kategori', $request->kategori)
                ->where('level', $request->level)
                ->where('wilayah', $request->wilayah)->first();

            $nominalBaru = ($tarif->nominal ?? 0) * ($request->durasi ?? 1);
            $data->update(array_merge($request->all(), ['nominal' => $nominalBaru]));
        } elseif ($type === 'tol') {
            $data = BiayaTol::findOrFail($id);
            $data->update(array_merge($request->all(), ['nominal' => $cleanNominal]));
        } elseif ($type === 'bensin') {
            $data = BiayaBensin::findOrFail($id);
            $data->update(array_merge($request->all(), ['nominal' => $cleanNominal]));
        } else {
            $data = BiayaOperasional::findOrFail($id);
            $data->update(array_merge($request->all(), ['nominal' => $cleanNominal]));
        }

        return redirect()->route('biaya-perjalanan.index')->with('success', 'Data berhasil diperbarui.');
    }

    /**
     * Menghapus data resmi.
     */
    public function destroy($type, $id)
    {
        if ($type === 'akomodasi') BiayaAkomodasi::destroy($id);
        elseif ($type === 'operasional') BiayaOperasional::destroy($id);
        elseif ($type === 'tol') BiayaTol::destroy($id);
        elseif ($type === 'bensin') BiayaBensin::destroy($id);
        return back()->with('success', 'Data resmi berhasil dihapus.');
    }

    /**
     * Menghapus data draf (Temp).
     */
    public function destroyTemp($type, $id)
    {
        if ($type === 'akomodasi') TempAkomodasi::destroy($id);
        elseif ($type === 'operasional') TempOperasional::destroy($id);
        elseif ($type === 'tol') TempTol::destroy($id);
        elseif ($type === 'bensin') TempBensin::destroy($id);
        return back()->with('success', 'Draf berhasil dihapus.');
    }
}
