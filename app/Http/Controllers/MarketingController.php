<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Marketing;
use App\Models\TarifPerjalanan;

class MarketingController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $marketings = Marketing::when($search, function ($query, $search) {
                return $query->where('nama', 'like', "%{$search}%")
                             ->orWhere('no_kartu_tol', 'like', "%{$search}%");
            })
            ->orderBy('level', 'asc')
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => Marketing::count(),
            'lvl1'  => Marketing::where('level', 1)->count(),
            'lvl2'  => Marketing::where('level', 2)->count(),
            'lvl3'  => Marketing::where('level', 3)->count(),
        ];

        $daftarTarif = TarifPerjalanan::orderBy('kategori', 'asc')
            ->orderBy('wilayah', 'asc')
            ->orderBy('level', 'asc')
            ->get();

        return view('marketing.index', compact('marketings', 'stats', 'daftarTarif'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'level' => 'required|in:1,2,3',
            'no_kartu_tol' => 'nullable|string',
            'sisa_saldo_tol' => 'nullable|numeric'
        ]);

        Marketing::create($request->all());
        return back()->with('success', 'Marketing baru berhasil didaftarkan!');
    }

    // METHOD EDIT: Menampilkan halaman edit terpisah
    public function edit($id)
    {
        $marketing = Marketing::findOrFail($id);
        return view('marketing.edit', compact('marketing'));
    }

    // METHOD UPDATE: Proses update data
    public function update(Request $request, $id)
    {
        $marketing = Marketing::findOrFail($id);
        $request->validate([
            'nama' => 'required|string|max:255',
            'level' => 'required|in:1,2,3',
            'no_kartu_tol' => 'nullable|string',
            'sisa_saldo_tol' => 'nullable|numeric'
        ]);

        $marketing->update($request->all());
        return redirect()->route('marketing.index')->with('success', 'Data Marketing berhasil diperbarui!');
    }

    public function destroy(Marketing $marketing)
    {
        $marketing->delete();
        return back()->with('success', 'Marketing telah dihapus.');
    }

    // --- LOGIKA TARIF ---
    public function storeTarif(Request $request)
    {
        $request->validate(['kategori' => 'required', 'wilayah' => 'required', 'level' => 'required', 'nominal' => 'required']);
        TarifPerjalanan::create($request->all());
        return back()->with('success', 'Tarif baru ditambahkan!');
    }

    public function updateTarif(Request $request)
    {
        foreach ($request->tarif as $id => $nominal) {
            TarifPerjalanan::where('id', $id)->update(['nominal' => $nominal]);
        }
        return back()->with('success', 'Seluruh tarif diperbarui!');
    }

    public function destroyTarif($id)
    {
        TarifPerjalanan::destroy($id);
        return back()->with('success', 'Tarif dihapus.');
    }
}
