<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Obat;
use App\Models\ObatMasuk;
use App\Models\ObatKeluarDetail;
use App\Models\ObatRusak;

class ObatKeluarDetailController extends Controller
{
    public function index(Request $request)
    {
        $tahun   = $request->get('tahun');
        $bulan   = $request->get('bulan');
        $stokMin = $request->get('stok_minimum');
        $tanggal = $request->get('tanggal');

        // Tentukan mode laporan
        if ($tanggal) {
            $mode = 'harian_tanggal';
            $tanggalObj   = Carbon::parse($tanggal);
            $tanggalAcuan = $tanggalObj->copy()->subDay()->toDateString();
        } else {
            if (!$tahun && !$bulan && !$stokMin) {
                $tahun = now()->year;
                $bulan = now()->month;
            }

            if ($bulan && $tahun) {
                $mode = 'bulanan';
            } elseif ($tahun && !$bulan) {
                $mode = 'tahunan';
            } else {
                $mode = 'hari_ini';
            }

            if ($mode === 'bulanan') {
                $tanggalAcuan = Carbon::create($tahun, $bulan, 1)->subDay()->toDateString();
            } elseif ($mode === 'tahunan') {
                $tanggalAcuan = Carbon::create($tahun, 1, 1)->subDay()->toDateString();
            } else {
                $tanggalAcuan = now()->subDay()->toDateString();
            }
        }

        // Hitung stok akhir periode sebelumnya (stok awal)
        $stokAwal = Obat::select('id', 'nama_obat')->get()->mapWithKeys(function ($obat) use ($tanggalAcuan) {
            $totalMasuk = ObatMasuk::where('obat_id', $obat->id)
                ->whereDate('tanggal_masuk', '<=', $tanggalAcuan)
                ->sum('jumlah');

            $totalKeluar = ObatKeluarDetail::where('obat_id', $obat->id)
                ->whereDate('created_at', '<=', $tanggalAcuan)
                ->sum('jumlah');

            $totalRusak = ObatRusak::where('obat_id', $obat->id)
                ->whereDate('tanggal', '<=', $tanggalAcuan)
                ->sum('jumlah');

            return [$obat->id => $totalMasuk - $totalKeluar - $totalRusak];
        });

        // Ambil data obat + hitung stok sesuai mode
        $obats = Obat::all()->map(function ($obat) use ($stokAwal, $mode, $tahun, $bulan, $tanggal) {
            $stokAkhirSebelumnya = $stokAwal[$obat->id] ?? 0;

            if ($mode === 'harian_tanggal') {
                // === Mode Harian Berdasarkan Tanggal Pilihan ===
                $masukPeriode = ObatMasuk::where('obat_id', $obat->id)
                    ->whereDate('tanggal_masuk', $tanggal)
                    ->sum('jumlah');

                $keluarPeriode = ObatKeluarDetail::where('obat_id', $obat->id)
                    ->whereDate('created_at', $tanggal)
                    ->sum('jumlah');

                $rusakPeriode = ObatRusak::where('obat_id', $obat->id)
                    ->whereDate('tanggal', $tanggal)
                    ->sum('jumlah');

                $stokAwalPeriode = $stokAkhirSebelumnya;
                $stokSisa = $stokAwalPeriode + $masukPeriode - $keluarPeriode - $rusakPeriode;

                $permintaan = (!is_null($obat->stok_minimum) && $stokSisa < $obat->stok_minimum)
                    ? $obat->stok_minimum - $stokSisa : 0;

                $obat->stok_awal_tanggal   = $stokAwalPeriode;
                $obat->obat_masuk_tanggal  = $masukPeriode;
                $obat->obat_keluar_tanggal = $keluarPeriode;
                $obat->obat_rusak_tanggal  = $rusakPeriode;
                $obat->stok_sisa_tanggal   = $stokSisa;
                $obat->permintaan_tanggal  = $permintaan;
            } elseif ($mode === 'bulanan') {
                // === Mode Bulanan ===
                $startDate = Carbon::create($tahun, $bulan, 1);
                $endDate   = $startDate->copy()->endOfMonth();
                $periode   = CarbonPeriod::create($startDate, $endDate);

                $masukPeriode = ObatMasuk::where('obat_id', $obat->id)
                    ->whereYear('tanggal_masuk', $tahun)
                    ->whereMonth('tanggal_masuk', $bulan)
                    ->sum('jumlah');

                $keluarPerHariRaw = ObatKeluarDetail::where('obat_id', $obat->id)
                    ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
                    ->get()
                    ->groupBy(fn($item) => $item->created_at->format('Y-m-d'));

                $keluarPerHari = [];
                foreach ($periode as $date) {
                    $dateStr = $date->format('Y-m-d');
                    $keluarPerHari[$dateStr] = isset($keluarPerHariRaw[$dateStr])
                        ? $keluarPerHariRaw[$dateStr]->sum('jumlah') : 0;
                }

                $totalKeluar = array_sum($keluarPerHari);

                $rusakPeriode = ObatRusak::where('obat_id', $obat->id)
                    ->whereYear('tanggal', $tahun)
                    ->whereMonth('tanggal', $bulan)
                    ->sum('jumlah');

                $stokAwalPeriode = $stokAkhirSebelumnya;
                $stokSisa = $stokAwalPeriode + $masukPeriode - $totalKeluar - $rusakPeriode;

                $permintaan = (!is_null($obat->stok_minimum) && $stokSisa < $obat->stok_minimum)
                    ? $obat->stok_minimum - $stokSisa : 0;

                $obat->stok_awal_bulan_ini   = $stokAwalPeriode;
                $obat->obat_masuk_bulan_ini  = $masukPeriode;
                $obat->total_keluar_bulan_ini = $totalKeluar;
                $obat->obat_rusak_bulan_ini  = $rusakPeriode;
                $obat->stok_sisa_bulan_ini   = $stokSisa;
                $obat->permintaan            = $permintaan;
                $obat->keluar_per_hari       = $keluarPerHari;
            } elseif ($mode === 'tahunan') {
                // === Mode Tahunan ===
                $masukPeriode = ObatMasuk::where('obat_id', $obat->id)
                    ->whereYear('tanggal_masuk', $tahun)
                    ->sum('jumlah');

                $keluarPeriode = ObatKeluarDetail::where('obat_id', $obat->id)
                    ->whereYear('created_at', $tahun)
                    ->sum('jumlah');

                $rusakPeriode = ObatRusak::where('obat_id', $obat->id)
                    ->whereYear('tanggal', $tahun)
                    ->sum('jumlah');

                $stokAwalPeriode = $stokAkhirSebelumnya;
                $stokSisa = $stokAwalPeriode + $masukPeriode - $keluarPeriode - $rusakPeriode;

                $permintaan = (!is_null($obat->stok_minimum) && $stokSisa < $obat->stok_minimum)
                    ? $obat->stok_minimum - $stokSisa : 0;

                $obat->stok_awal_tahun_ini   = $stokAwalPeriode;
                $obat->obat_masuk_tahun_ini  = $masukPeriode;
                $obat->total_keluar_tahun_ini = $keluarPeriode;
                $obat->obat_rusak_tahun_ini  = $rusakPeriode;
                $obat->stok_sisa_tahun_ini   = $stokSisa;
                $obat->permintaan            = $permintaan;
            } else {
                // === Mode Hari Ini ===
                $masukPeriode = ObatMasuk::where('obat_id', $obat->id)
                    ->whereDate('tanggal_masuk', now()->toDateString())
                    ->sum('jumlah');

                $keluarPeriode = ObatKeluarDetail::where('obat_id', $obat->id)
                    ->whereDate('created_at', now()->toDateString())
                    ->sum('jumlah');

                $rusakPeriode = ObatRusak::where('obat_id', $obat->id)
                    ->whereDate('tanggal', now()->toDateString())
                    ->sum('jumlah');

                $stokAwalPeriode = $stokAkhirSebelumnya;
                $stokSisa = $stokAwalPeriode + $masukPeriode - $keluarPeriode - $rusakPeriode;

                $permintaan = (!is_null($obat->stok_minimum) && $stokSisa < $obat->stok_minimum)
                    ? $obat->stok_minimum - $stokSisa : 0;

                $obat->stok_awal_hari_ini   = $stokAwalPeriode;
                $obat->obat_masuk_hari_ini  = $masukPeriode;
                $obat->total_keluar_hari_ini = $keluarPeriode;
                $obat->obat_rusak_hari_ini  = $rusakPeriode;
                $obat->stok_sisa_hari_ini   = $stokSisa;
                $obat->permintaan           = $permintaan;
            }

            // Properti umum utk filter stok minimum
            $obat->stok_sisa = $stokSisa;

            return $obat;
        });

        // Filter stok minimum (jika ada)
        if (!is_null($stokMin)) {
            $obats = $obats->filter(fn($o) => $o->stok_sisa <= $stokMin);
        }

        return view('obat.keluar_detail', compact('obats', 'tahun', 'bulan', 'stokMin', 'mode', 'tanggal'));
    }
}
