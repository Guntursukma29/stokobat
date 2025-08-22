<?php

namespace App\Http\Controllers;

use App\Models\ObatRusak;
use App\Models\Obat;
use Illuminate\Http\Request;

class ObatRusakController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $obatRusak = ObatRusak::with('obat')->get();
        $obats = Obat::all();
        return view('obat.rusak', compact('obatRusak', 'obats'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'obat_id' => 'required|exists:obats,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        ObatRusak::create($request->all());

        return redirect()->route('obat-rusak.index')->with('success', 'Data obat rusak berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $obatRusak = ObatRusak::findOrFail($id);

        $request->validate([
            'obat_id' => 'required|exists:obats,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        $obatRusak->update($request->all());

        return redirect()->route('obat-rusak.index')->with('success', 'Data obat rusak berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $obatRusak = ObatRusak::findOrFail($id);
        $obatRusak->delete();

        return redirect()->route('obat-rusak.index')->with('success', 'Data obat rusak berhasil dihapus.');
    }
}
