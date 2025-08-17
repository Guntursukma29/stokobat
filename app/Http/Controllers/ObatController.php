<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use Illuminate\Http\Request;

class ObatController extends Controller
{
    /**
     * Menampilkan daftar obat
     */
    public function index()
    {
        $obats = Obat::orderBy('nama_obat')->get();
        return view('obat.index', compact('obats'));
    }

    /**
     * Form tambah obat
     */
    public function create()
    {
        return view('obat.create');
    }

    /**
     * Simpan obat baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_obat' => 'required|string|max:255|unique:obats',
            'satuan' => 'required|string|max:50',
            'stok_minimum' => 'required|integer|min:0'
        ]);

        Obat::create([
            'nama_obat' => $request->nama_obat,
            'satuan' => $request->satuan,
            // 'stok' => 0,
            'stok_minimum' => $request->stok_minimum
        ]);

        return redirect()->route('obat.index')->with('success', 'Obat berhasil ditambahkan.');
    }

    /**
     * Form edit obat
     */
    public function edit(Obat $obat)
    {
        return view('obat.edit', compact('obat'));
    }

    /**
     * Update obat
     */
    public function update(Request $request, Obat $obat)
    {
        $request->validate([
            'nama_obat' => 'required|string|max:255|unique:obats,nama_obat,' . $obat->id,
            'satuan' => 'required|string|max:50',
            'stok_minimum' => 'required|integer|min:0'
        ]);

        $obat->update($request->only('nama_obat', 'satuan', 'stok_minimum'));

        return redirect()->route('obat.index')->with('success', 'Obat berhasil diperbarui.');
    }

    /**
     * Hapus obat
     */
    public function destroy(Obat $obat)
    {
        $obat->delete();
        return redirect()->route('obat.index')->with('success', 'Obat berhasil dihapus.');
    }
}
