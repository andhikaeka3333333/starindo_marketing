<?php

namespace App\Http\Controllers;

use App\Models\KategoriPengajuan;
use Illuminate\Http\Request;

class KategoriPengajuanController extends Controller
{
    public function index()
    {
        $kategoris = KategoriPengajuan::orderBy('nama_kategori', 'asc')->get();
        return view('kategori.index', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori_pengajuans,nama_kategori',
        ]);

        KategoriPengajuan::create($request->all());
        return redirect()->route('kategori.index')->with('success', 'Kategori baru berhasil ditambahkan!');
    }

    public function edit(KategoriPengajuan $kategori)
    {
        return view('kategori.edit', compact('kategori'));
    }

    public function update(Request $request, KategoriPengajuan $kategori)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori_pengajuans,nama_kategori,' . $kategori->id,
        ]);

        $kategori->update($request->all());
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diupdate!');
    }

    public function destroy(KategoriPengajuan $kategori)
    {
        if ($kategori->pengajuans()->count() > 0) {
            return back()->with('error', 'Gagal! Kategori ini masih dipakai di data pengajuan.');
        }

        $kategori->delete();
        return back()->with('success', 'Kategori berhasil dihapus.');
    }
}
