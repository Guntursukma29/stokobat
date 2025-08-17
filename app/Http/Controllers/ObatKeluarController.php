<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\Pasien;
use PDF;
use App\Models\ObatKeluar;
use Illuminate\Http\Request;
use App\Models\ObatKeluarDetail;
use Illuminate\Support\Facades\DB;

class ObatKeluarController extends Controller
{
    public function index()
    {
        $obatKeluar = ObatKeluar::with('pasien', 'detail.obat')->latest()->get();
        $obats = Obat::all();
        $pasiens = Pasien::all();
        return view('obat.keluar', compact('obatKeluar', 'obats', 'pasiens'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_keluar' => 'required|date',
            'pasien_id' => 'required|exists:pasiens,id',
            'obat_id' => 'required|array',
            'obat_id.*' => 'exists:obats,id',
            'jumlah.*' => 'required|integer|min:1'
        ]);

        DB::transaction(function () use ($request) {
            // Simpan transaksi obat keluar
            $keluar = ObatKeluar::create([
                'tanggal_keluar' => $request->tanggal_keluar,
                'pasien_id' => $request->pasien_id
            ]);

            // Simpan detail obat keluar & kurangi stok
            foreach ($request->obat_id as $key => $obatId) {
                $jumlah = $request->jumlah[$key];

                ObatKeluarDetail::create([
                    'obat_keluar_id' => $keluar->id,
                    'obat_id' => $obatId,
                    'jumlah' => $jumlah
                ]);

                // Update stok obat
                $obat = Obat::find($obatId);
                $obat->stok -= $jumlah;
                $obat->save();
            }
        });

        return redirect()->route('obat-keluar.index')->with('success', 'Obat keluar berhasil dicatat');
    }

    public function edit($id)
    {
        $keluar = ObatKeluar::with('details.obat')->findOrFail($id);
        $obats = Obat::all();
        $pasiens = Pasien::all();
        return view('obat.keluar_edit', compact('keluar', 'obats', 'pasiens'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal_keluar' => 'required|date',
            'pasien_id' => 'required|exists:pasiens,id',
            'obat_id' => 'required|array',
            'obat_id.*' => 'exists:obats,id',
            'jumlah.*' => 'required|integer|min:1'
        ]);

        $keluar = ObatKeluar::with('details')->findOrFail($id);

        DB::transaction(function () use ($request, $keluar) {
            // Kembalikan stok lama
            foreach ($keluar->details as $detail) {
                $obat = Obat::find($detail->obat_id);
                $obat->stok += $detail->jumlah;
                $obat->save();
            }

            // Hapus detail lama
            $keluar->details()->delete();

            // Update data utama
            $keluar->update([
                'tanggal_keluar' => $request->tanggal_keluar,
                'pasien_id' => $request->pasien_id
            ]);

            // Simpan detail baru & kurangi stok
            foreach ($request->obat_id as $key => $obatId) {
                $jumlah = $request->jumlah[$key];

                ObatKeluarDetail::create([
                    'obat_keluar_id' => $keluar->id,
                    'obat_id' => $obatId,
                    'jumlah' => $jumlah
                ]);

                $obat = Obat::find($obatId);
                $obat->stok -= $jumlah;
                $obat->save();
            }
        });

        return redirect()->route('obat-keluar.index')->with('success', 'Obat keluar berhasil diperbarui');
    }

    public function destroy($id)
    {
        $keluar = ObatKeluar::with('details')->findOrFail($id);
        foreach ($keluar->details as $detail) {
            $obat = Obat::find($detail->obat_id);
            $obat->stok += $detail->jumlah;
            $obat->save();
        }
        $keluar->details()->delete();
        $keluar->delete();
        return redirect()->route('obat-keluar.index')->with('success', 'Obat keluar berhasil dihapus');
    }  // alias Facade di bagian atas controller

    public function exportPdf($id)
    {
        // Pastikan relasi 'details' (plural) sesuai di model ObatKeluar
        $keluar = ObatKeluar::with('pasien', 'detail.obat')->findOrFail($id);

        $pdf = PDF::loadView('obat.keluar_invoice', compact('keluar'));

        $filename = 'Resep_Obat_' . $keluar->pasien->nama_pasien . '.pdf';

        return $pdf->download($filename);
    }
}
