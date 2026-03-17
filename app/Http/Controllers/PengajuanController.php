<?php

namespace App\Http\Controllers;

use App\Models\{Pengajuan, Marketing, KategoriPengajuan};
use Illuminate\Http\Request;

class PengajuanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $pengajuans = Pengajuan::with(['marketing', 'kategori'])
            ->when($search, function ($query, $search) {
                return $query->where('customer_nama', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%")
                    ->orWhere('customer_cp', 'like', "%{$search}%")
                    ->orWhereHas('marketing', function ($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%");
                    })
                    ->orWhereHas('kategori', function ($q) use ($search) {
                        $q->where('nama_kategori', 'like', "%{$search}%");
                    });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pengajuan.index', compact('pengajuans'));
    }

    public function create()
    {
        $marketings = Marketing::orderBy('nama')->get();
        $kategoris = KategoriPengajuan::orderBy('nama_kategori')->get();
        return view('pengajuan.create', compact('marketings', 'kategoris'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'marketing_id'          => 'required|exists:marketings,id',
            'kategori_pengajuan_id' => 'required|exists:kategori_pengajuans,id',
            'tanggal'               => 'required|date',
            'customer_nama'         => 'required|string',
            'nominal_value'         => 'required|numeric',
            'customer_cp'           => 'nullable',
            'customer_alamat'       => 'nullable',
            'alamat'                => 'nullable',
        ]);

        Pengajuan::create($data);
        return redirect()->route('pengajuan.index')->with('success', 'Pengajuan berhasil dibuat.');
    }

    public function edit(Pengajuan $pengajuan)
    {
        $marketings = Marketing::orderBy('nama')->get();
        $kategoris = KategoriPengajuan::orderBy('nama_kategori')->get();
        return view('pengajuan.edit', compact('pengajuan', 'marketings', 'kategoris'));
    }

    public function update(Request $request, Pengajuan $pengajuan)
    {
        $data = $request->validate([
            'marketing_id'          => 'required|exists:marketings,id',
            'kategori_pengajuan_id' => 'required|exists:kategori_pengajuans,id',
            'tanggal'               => 'required|date',
            'customer_nama'         => 'required|string',
            'nominal_value'         => 'required|numeric',
            'customer_cp'           => 'nullable',
            'customer_alamat'       => 'nullable',
            'alamat'                => 'nullable',
        ]);

        $pengajuan->update($data);
        return redirect()->route('pengajuan.index')->with('success', 'Data diperbarui.');
    }
}
