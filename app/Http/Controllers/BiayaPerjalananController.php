<?php

// app/Http/Controllers/BiayaPerjalananController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{BiayaPerjalanan, BiayaPerjalananTemp, Marketing};
use DB;

class BiayaPerjalananController extends Controller
{

    // public function index() {
    //     $finalData = BiayaPerjalanan::with('marketing')->latest()->get();
    //     return view('biaya_perjalanan.index', compact('finalData'));
    // }

    public function index(Request $request)
    {
        $search = $request->query('search');

        $finalData = BiayaPerjalanan::with('marketing')
            ->when($search, function ($query, $search) {
                return $query->where('customer_nama', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%")
                    ->orWhere('kategori', 'like', "%{$search}%")
                    ->orWhere('customer_cp', 'like', "%{$search}%")
                    ->orWhereHas('marketing', function ($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%");
                    });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $temps = BiayaPerjalananTemp::all();

        return view('biaya_perjalanan.index', compact('finalData', 'temps'));
    }
    public function create()
    {
        $marketings = Marketing::all();
        $temps = BiayaPerjalananTemp::with('marketing')->get();
        return view('biaya_perjalanan.create', compact('marketings', 'temps'));
    }

    public function storeTemp(Request $request)
    {
        $data = $request->all();
        if (in_array($request->kategori, ['Hotel', 'UM'])) {
            $data['nominal'] = $this->calculateRate($request->kategori, $request->level, $request->wilayah);
        }
        BiayaPerjalananTemp::create($data);
        return back()->with('success', 'Item berhasil masuk draf.');
    }

    public function editTemp($id)
    {
        $temp = BiayaPerjalananTemp::findOrFail($id);
        $marketings = Marketing::all();
        return view('biaya_perjalanan.edit_temp', compact('temp', 'marketings'));
    }

    public function updateTemp(Request $request, $id)
    {
        $temp = BiayaPerjalananTemp::findOrFail($id);
        $data = $request->all();
        if (in_array($request->kategori, ['Hotel', 'UM'])) {
            $data['nominal'] = $this->calculateRate($request->kategori, $request->level, $request->wilayah);
        }
        $temp->update($data);
        return redirect()->route('biaya-perjalanan.create')->with('success', 'Draf diperbarui.');
    }

    public function destroyTemp($id)
    {
        BiayaPerjalananTemp::destroy($id);
        return back()->with('success', 'Draf dihapus.');
    }

    public function finalize()
    {
        $temps = BiayaPerjalananTemp::all();
        DB::transaction(function () use ($temps) {
            foreach ($temps as $item) {
                BiayaPerjalanan::create($item->toArray());
                $item->delete();
            }
        });
        return redirect()->route('biaya-perjalanan.index')->with('success', 'Data resmi disimpan!');
    }

    private function calculateRate($kat, $lvl, $wil)
    {
        $rates = [
            'UM' => ['Jabotabek' => [1 => 300000, 2 => 250000, 3 => 200000], 'Lainnya' => [1 => 200000, 2 => 150000, 3 => 100000]],
            'Hotel' => ['Jabotabek' => [1 => 900000, 2 => 750000, 3 => 600000], 'Lainnya' => [1 => 750000, 2 => 600000, 3 => 400000]]
        ];
        return $rates[$kat][$wil][$lvl] ?? 0;
    }

    public function destroy($id)
    {
        \App\Models\BiayaPerjalanan::destroy($id);

        return redirect()->route('biaya-perjalanan.index')
            ->with('success', 'Data resmi berhasil dihapus dari arsip.');
    }

    public function edit($id)
    {
        // Ambil data dari tabel UTAMA
        $biaya = BiayaPerjalanan::findOrFail($id);
        $marketings = Marketing::all();
        return view('biaya_perjalanan.edit', compact('biaya', 'marketings'));
    }

    public function update(Request $request, $id)
    {
        $biaya = BiayaPerjalanan::findOrFail($id);
        $data = $request->all();

        // Re-calculate nominal jika kategori Hotel/UM diubah
        if (in_array($request->kategori, ['Hotel', 'UM'])) {
            $data['nominal'] = $this->calculateRate($request->kategori, $request->level, $request->wilayah);
        }

        $biaya->update($data);

        return redirect()->route('biaya-perjalanan.index')
            ->with('success', 'Data Biaya Perjalanan berhasil diperbarui.');
    }
}
