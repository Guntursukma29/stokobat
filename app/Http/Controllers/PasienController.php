<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use Illuminate\Http\Request;

class PasienController extends Controller
{
    public function index()
    {
        $pasiens = Pasien::latest()->get();
        return view('pasien', compact('pasiens'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|max:20|unique:pasiens,nik',
            'nama_pasien' => 'required|string|max:255',
        ]);

        Pasien::create($request->only('nik', 'nama_pasien'));

        return redirect()->route('pasiens.index')->with('success', 'Pasien berhasil ditambahkan');
    }

    public function edit($id)
    {
        $pasien = Pasien::findOrFail($id);
        return view('pasien.edit', compact('pasien'));
    }

    public function update(Request $request, $id)
    {
        $pasien = Pasien::findOrFail($id);

        $request->validate([
            'nik' => 'required|string|max:20|unique:pasiens,nik,' . $pasien->id,
            'nama_pasien' => 'required|string|max:255',
        ]);

        $pasien->update($request->only('nik', 'nama_pasien'));

        return redirect()->route('pasiens.index')->with('success', 'Pasien berhasil diperbarui');
    }

    public function destroy($id)
    {
        Pasien::findOrFail($id)->delete();
        return redirect()->route('pasiens.index')->with('success', 'Pasien berhasil dihapus');
    }
}
