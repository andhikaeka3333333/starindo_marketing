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

        // 1. Query Akomodasi
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

        // 2. Query Operasional
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

        // 3. Query Tol (Ditambah pencarian nama_gerbang)
        $tol = BiayaTol::with('marketing')
            ->when($search, function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query->where('customer_nama', 'like', "%{$search}%")
                        ->orWhere('customer_cp', 'like', "%{$search}%")
                        ->orWhere('kategori', 'like', "%{$search}%")
                        ->orWhere('keterangan', 'like', "%{$search}%")
                        ->orWhere('nama_gerbang', 'like', "%{$search}%")
                        ->orWhereHas('marketing', fn($m) => $m->where('nama', 'like', "%{$search}%"));
                });
            })
            ->latest()->paginate(10, ['*'], 'page_tol');

        // 4. Query Bensin
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
     * Menyimpan data ke tabel Temp berdasarkan kategori.
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
            // Modifikasi: Tambahkan nama_gerbang
            TempTol::create([
                'marketing_id' => $request->marketing_id,
                'tanggal' => $request->tanggal, // format dari view: Y-m-d\TH:i
                'customer_nama' => $request->customer_nama,
                'customer_cp' => $request->customer_cp,
                'kategori' => $kat,
                'nama_gerbang' => $kat === 'Pemakaian Tol' ? $request->nama_gerbang : null,
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

    public function finalize()
    {
        DB::transaction(function () {
            $categories = [
                ['temp' => \App\Models\TempAkomodasi::class, 'resmi' => \App\Models\BiayaAkomodasi::class],
                ['temp' => \App\Models\TempOperasional::class, 'resmi' => \App\Models\BiayaOperasional::class],
                ['temp' => \App\Models\TempTol::class, 'resmi' => \App\Models\BiayaTol::class],
                ['temp' => \App\Models\TempBensin::class, 'resmi' => \App\Models\BiayaBensin::class],
            ];

            $now = now();

            foreach ($categories as $cat) {
                $tempModel = $cat['temp'];
                $resmiModel = $cat['resmi'];

                $data = $tempModel::all();

                if ($data->isNotEmpty()) {
                    foreach ($data as $item) {
                        $attr = $item->getAttributes();
                        unset($attr['id']);

                        $attr['created_at'] = $attr['created_at'] ?? $now;
                        $attr['updated_at'] = $attr['updated_at'] ?? $now;

                        // LOGIKA UPDATE SALDO TOL MARKETING
                        if ($tempModel === \App\Models\TempTol::class) {
                            $marketing = Marketing::find($item->marketing_id);
                            if ($marketing) {
                                if ($item->kategori === 'Top-Up Tol') {
                                    $marketing->increment('sisa_saldo_tol', $item->nominal);
                                } elseif ($item->kategori === 'Pemakaian Tol') {
                                    $marketing->decrement('sisa_saldo_tol', $item->nominal);
                                }
                            }
                        }

                        // Gunakan create agar tetap melewati mass assignment protection jika diperlukan
                        $resmiModel::create($attr);
                    }

                    // Hapus data temp setelah dipindahkan
                    $tempModel::query()->delete();
                }
            }
        });

        return redirect()->route('biaya-perjalanan.index')
            ->with('success', 'Finalisasi sukses! Data dipindahkan dan saldo tol marketing telah diperbarui.');
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
            // Catatan: Jika update data resmi, idealnya ada penyesuaian saldo ulang,
            // namun di sini saya fokus pada update kolom sesuai request.
            $data->update(array_merge($request->all(), [
                'nominal' => $cleanNominal,
                'nama_gerbang' => $request->kategori === 'Pemakaian Tol' ? $request->nama_gerbang : null
            ]));
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

    /**
     * Menampilkan halaman edit untuk draf (Temp) dengan semua kolom.
     */
    public function editTemp($type, $id)
    {
        $marketings = Marketing::all();
        $rates = DB::table('tarif_perjalanan')->get();

        $modelMap = [
            'akomodasi' => TempAkomodasi::class,
            'operasional' => TempOperasional::class,
            'tol' => TempTol::class,
            'bensin' => TempBensin::class,
        ];

        if (!isset($modelMap[$type])) abort(404);

        $data = $modelMap[$type]::with('marketing')->findOrFail($id);

        return view('biaya_perjalanan.edit_temp', compact('data', 'type', 'marketings', 'rates'));
    }

    /**
     * Update semua kolom draf (Temp).
     */
    public function updateTemp(Request $request, $type, $id)
    {
        $cleanNominal = (int) str_replace('.', '', $request->nominal_value ?? $request->nominal ?? 0);

        $modelMap = [
            'akomodasi' => TempAkomodasi::class,
            'operasional' => TempOperasional::class,
            'tol' => TempTol::class,
            'bensin' => TempBensin::class,
        ];

        $data = $modelMap[$type]::findOrFail($id);

        if ($type === 'akomodasi') {
            $tarif = DB::table('tarif_perjalanan')
                ->where('kategori', $request->kategori)
                ->where('level', $request->level)
                ->where('wilayah', $request->wilayah)->first();

            $nominalBaru = ($tarif->nominal ?? 0) * ($request->durasi ?? 1);

            $data->update([
                'marketing_id'  => $request->marketing_id,
                'tanggal'       => $request->tanggal,
                'customer_nama' => $request->customer_nama,
                'customer_cp'   => $request->customer_cp,
                'kategori'      => $request->kategori,
                'level'         => $request->level,
                'wilayah'       => $request->wilayah,
                'durasi'        => $request->durasi,
                'nominal'       => $nominalBaru,
            ]);
        }
        elseif ($type === 'bensin') {
            $data->update([
                'marketing_id'  => $request->marketing_id,
                'tanggal'       => $request->tanggal,
                'customer_nama' => $request->customer_nama,
                'customer_cp'   => $request->customer_cp,
                'km'            => $request->km,
                'keterangan'    => $request->keterangan,
                'nominal'       => $cleanNominal,
            ]);
        }
        elseif ($type === 'tol') {
            // Modifikasi: Update Nama Gerbang di tabel Temp
            $data->update([
                'marketing_id'  => $request->marketing_id,
                'tanggal'       => $request->tanggal,
                'customer_nama' => $request->customer_nama,
                'customer_cp'   => $request->customer_cp,
                'kategori'      => $request->kategori,
                'nama_gerbang'  => $request->kategori === 'Pemakaian Tol' ? $request->nama_gerbang : null,
                'keterangan'    => $request->keterangan,
                'nominal'       => $cleanNominal,
            ]);
        }
        else {
            $data->update([
                'marketing_id'  => $request->marketing_id,
                'tanggal'       => $request->tanggal,
                'customer_nama' => $request->customer_nama,
                'customer_cp'   => $request->customer_cp,
                'keterangan'    => $request->keterangan,
                'nominal'       => $cleanNominal,
            ]);
        }

        return redirect()->route('biaya-perjalanan.create')->with('success', 'Draf berhasil diperbarui secara lengkap.');
    }
}
