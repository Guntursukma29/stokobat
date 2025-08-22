<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\ObatMasuk;
use Illuminate\Http\Request;

class ObatMasukController extends Controller
{
    public function index()
    {
        $obatMasuks = ObatMasuk::with('obat')->latest()->get();
        $obats = Obat::all();
        return view('obat.masuk', compact('obatMasuks', 'obats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'obat_id' => 'required|exists:obats,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal_masuk' => 'required|date',
            'supplier' => 'nullable|string|max:255',
        ]);

        ObatMasuk::create($request->all());

        return redirect()->route('obat-masuk.index')->with('success', 'Obat masuk berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'obat_id' => 'required|exists:obats,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal_masuk' => 'required|date',
            'supplier' => 'nullable|string|max:255',
        ]);

        $obatMasuk = ObatMasuk::findOrFail($id);
        $obatMasuk->update($request->all());

        return redirect()->route('obat-masuk.index')->with('success', 'Obat masuk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $obatMasuk = ObatMasuk::findOrFail($id);
        $obatMasuk->delete();

        return redirect()->route('obat-masuk.index')->with('success', 'Obat masuk berhasil dihapus.');
    }
    public function search(Request $request)
    {
        $search = $request->q;
        $obats = \App\Models\Obat::where('nama_obat', 'like', "%$search%")
            ->select('id', 'nama_obat')
            ->limit(20)
            ->get();

        return response()->json(
            $obats->map(fn($o) => [
                'id' => $o->id,
                'text' => $o->nama_obat
            ])
        );
    }
}
