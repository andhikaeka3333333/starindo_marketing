<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Marketing;

class MarketingController extends Controller
{
    public function index()
    {
        $marketings = Marketing::orderBy('level', 'asc')->get();
        return view('marketing.index', compact('marketings'));
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
