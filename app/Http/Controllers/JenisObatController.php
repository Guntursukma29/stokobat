<?php

namespace App\Http\Controllers;

use App\Models\JenisObat;
use Illuminate\Http\Request;

class JenisObatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jenisObat = JenisObat::all();
        return view('obat.jenis_obat', compact('jenisObat'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('jenis_obat.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:jenis_obat',
        ]);

        JenisObat::create([
            'nama' => $request->nama,
        ]);

        return redirect()->route('jenis-obat.index')->with('success', 'Jenis obat berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $jenisObat = JenisObat::findOrFail($id);
        return view('jenis_obat.edit', compact('jenisObat'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $jenisObat = JenisObat::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255|unique:jenis_obat,nama,' . $jenisObat->id,
        ]);

        $jenisObat->update([
            'nama' => $request->nama,
        ]);

        return redirect()->route('jenis-obat.index')->with('success', 'Jenis obat berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $jenisObat = JenisObat::findOrFail($id);
        $jenisObat->delete();

        return redirect()->route('jenis-obat.index')->with('success', 'Jenis obat berhasil dihapus.');
    }
}
