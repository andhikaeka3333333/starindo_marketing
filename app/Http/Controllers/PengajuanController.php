<?php

namespace App\Http\Controllers;

use App\Models\{Pengajuan, Marketing};
use Illuminate\Http\Request;

class PengajuanController extends Controller
{
    // 1. Halaman Utama: Hanya List & Button Aksi
    public function index()
    {
        return view('pengajuan.index', [
            'pengajuans' => Pengajuan::with('marketing')->latest()->paginate(10)
        ]);
    }

    // 2. Halaman Create: Form Tambah Baru
    public function create()
    {
        $marketings = Marketing::orderBy('nama')->get();
        return view('pengajuan.create', compact('marketings'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'marketing_id'    => 'required',
            'tanggal'         => 'required|date',
            'customer_nama'   => 'required|string',
            'customer_cp'     => 'nullable',
            'customer_alamat' => 'nullable',
            'jenis_pengajuan' => 'required',
            'nominal_value'   => 'required|numeric',
            'alamat'          => 'nullable',
        ]);
        Pengajuan::create($data);
        return redirect()->route('pengajuan.index')->with('success', 'Pengajuan berhasil dibuat.');
    }

    // 3. Halaman Edit: Form Perbarui Data
    public function edit(Pengajuan $pengajuan)
    {
        $marketings = Marketing::orderBy('nama')->get();
        return view('pengajuan.edit', compact('pengajuan', 'marketings'));
    }

    public function update(Request $request, Pengajuan $pengajuan)
    {
        $data = $request->validate([
            'marketing_id'    => 'required',
            'tanggal'         => 'required|date',
            'customer_nama'   => 'required|string',
            'jenis_pengajuan' => 'required',
            'nominal_value'   => 'required|numeric',
        ]);
        $pengajuan->update($request->all());
        return redirect()->route('pengajuan.index')->with('success', 'Data diperbarui.');
    }

    public function destroy(Pengajuan $pengajuan)
    {
        $pengajuan->delete();
        return back()->with('success', 'Data dihapus.');
    }
}
