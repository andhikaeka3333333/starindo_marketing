<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Marketing;

class MarketingController extends Controller
{
    public function index(Request $request) // Tambahkan Request
    {
        $search = $request->query('search');

        // Filter data untuk tabel
        $marketings = Marketing::when($search, function ($query, $search) {
            return $query->where('nama', 'like', "%{$search}%");
        })
            ->orderBy('level', 'asc')
            ->paginate(10)
            ->withQueryString(); // Agar keyword tidak hilang saat ganti halaman

        // Statistik tetap mengambil total keseluruhan
        $totalMarketing = Marketing::count();
        $totalLevel1 = Marketing::where('level', 1)->count();
        $totalLevel2 = Marketing::where('level', 2)->count();
        $totalLevel3 = Marketing::where('level', 3)->count();

        return view('marketing.index', compact(
            'marketings',
            'totalMarketing',
            'totalLevel1',
            'totalLevel2',
            'totalLevel3'
        ));
    }

    public function store(Request $request)
    {
        $request->validate(['nama' => 'required', 'level' => 'required|in:1,2,3']);
        Marketing::create($request->all());
        return back()->with('success', 'Marketing berhasil ditambah!');
    }

    public function edit(Marketing $marketing)
    {
        return view('marketing.edit', compact('marketing'));
    }

    public function update(Request $request, Marketing $marketing)
    {
        $request->validate(['nama' => 'required', 'level' => 'required|in:1,2,3']);
        $marketing->update($request->all());
        return redirect()->route('marketing.index')->with('success', 'Data berhasil diperbarui!');
    }

    public function destroy(Marketing $marketing)
    {
        $marketing->delete();
        return back()->with('success', 'Marketing telah dihapus.');
    }
}
