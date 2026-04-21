<?php

namespace App\Http\Controllers;

use App\Models\Bensin;
use Illuminate\Http\Request;

class BensinController extends Controller
{
    public function index()
    {
        $data = Bensin::orderBy('tanggal', 'desc')->paginate(10);
        return view('bensin.index', compact('data'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'nominal' => 'required|numeric',
            'km' => 'required|integer',
            'keterangan' => 'nullable|string'
        ]);

        Bensin::create($validated);
        return redirect()->back()->with('success', 'Data bensin berhasil dicatat');
    }

    public function update(Request $request, $id)
    {
        $bensin = Bensin::findOrFail($id);
        $bensin->update($request->all());
        return redirect()->back()->with('success', 'Data bensin berhasil diperbarui');
    }

    public function destroy($id)
    {
        Bensin::destroy($id);
        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }
}
