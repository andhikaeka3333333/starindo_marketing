<?php

namespace App\Http\Controllers;

use App\Models\Omset;
use App\Models\Marketing;
use Illuminate\Http\Request;

class OmsetController extends Controller
{
    public function index(Request $request) // Tambahkan Request $request
    {
        $search = $request->query('search');

        $omsets = Omset::with('marketing')
            ->when($search, function ($query, $search) {
                // Karena Omset utamanya mencari berdasarkan Nama Marketing
                return $query->whereHas('marketing', function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString(); // Agar pencarian tidak hilang saat pindah halaman

        return view('omset.index', compact('omsets'));
    }

    public function create()
    {
        $marketings = Marketing::all();
        return view('omset.create', compact('marketings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'marketing_id' => 'required',
            'periode_dari' => 'required|date',
            'periode_sampai' => 'required|date|after_or_equal:periode_dari',
            'nominal' => 'required|numeric',
        ]);

        Omset::create($request->all());
        return redirect()->route('omset.index')->with('success', 'Data omset berhasil disimpan');
    }

    public function edit(Omset $omset)
    {
        $marketings = Marketing::all();
        return view('omset.edit', compact('omset', 'marketings'));
    }

    public function update(Request $request, Omset $omset)
    {
        $request->validate([
            'marketing_id' => 'required',
            'periode_dari' => 'required|date',
            'periode_sampai' => 'required|date|after_or_equal:periode_dari',
            'nominal' => 'required|numeric',
        ]);

        $omset->update($request->all());
        return redirect()->route('omset.index')->with('success', 'Data omset berhasil diperbarui');
    }

    public function destroy(Omset $omset)
    {
        $omset->delete();
        return redirect()->route('omset.index');
    }
}
