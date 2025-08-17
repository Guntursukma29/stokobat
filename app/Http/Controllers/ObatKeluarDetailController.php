<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Obat;
use Carbon\CarbonPeriod;
use App\Models\ObatMasuk;
use Illuminate\Http\Request;
use App\Models\ObatKeluarDetail;
use Illuminate\Support\Facades\DB;

class ObatKeluarDetailController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->get('tahun');
        $bulan = $request->get('bulan');
        $stokMin = $request->get('stok_minimum');
        $tanggal = $request->get('tanggal'); // === TAMBAHAN FILTER TANGGAL ===

        // Jika filter tanggal dipilih, kita abaikan tahun/bulan supaya mode khusus harian_tanggal
        if ($tanggal) {
            $mode = 'harian_tanggal';
            $tanggalObj = Carbon::parse($tanggal);
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

            return [$obat->id => $totalMasuk - $totalKeluar];
        });

        $obats = Obat::all()->map(function ($obat) use ($stokMin, $stokAwal, $mode, $tahun, $bulan, $tanggal) {
            $stokAkhirSebelumnya = $stokAwal[$obat->id] ?? 0;

            if ($mode === 'harian_tanggal') {
                $masukPeriode = ObatMasuk::where('obat_id', $obat->id)
                    ->whereDate('tanggal_masuk', $tanggal)
                    ->sum('jumlah');

                $keluarPeriode = ObatKeluarDetail::where('obat_id', $obat->id)
                    ->whereDate('created_at', $tanggal)
                    ->sum('jumlah');

                $stokAwalPeriode = $stokAkhirSebelumnya;
                $stokSisa = $stokAwalPeriode + $masukPeriode - $keluarPeriode;

                $permintaan = 0;
                if (!is_null($obat->stok_minimum) && $stokSisa < $obat->stok_minimum) {
                    $permintaan = $obat->stok_minimum - $stokSisa;
                }

                $obat->stok_awal_tanggal = $stokAwalPeriode;
                $obat->obat_masuk_tanggal = $masukPeriode;
                $obat->obat_keluar_tanggal = $keluarPeriode;
                $obat->stok_sisa_tanggal = $stokSisa;
                $obat->permintaan_tanggal = $permintaan;
            } elseif ($mode === 'bulanan') {
                // Inisialisasi tanggal awal dan akhir bulan
                $startDate = Carbon::create($tahun, $bulan, 1);
                $endDate = $startDate->copy()->endOfMonth();

                // Buat periode tanggal dari tgl 1 sampai akhir bulan
                $periode = CarbonPeriod::create($startDate, $endDate);

                // Hitung total obat masuk di bulan itu
                $masukPeriode = ObatMasuk::where('obat_id', $obat->id)
                    ->whereYear('tanggal_masuk', $tahun)
                    ->whereMonth('tanggal_masuk', $bulan)
                    ->sum('jumlah');

                // Ambil data keluar per hari, dikelompokkan per tanggal (format Y-m-d)
                $keluarPerHariRaw = ObatKeluarDetail::where('obat_id', $obat->id)
                    ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
                    ->get()
                    ->groupBy(function ($item) {
                        return $item->created_at->format('Y-m-d');
                    });

                // Buat array lengkap keluar per hari untuk tiap tanggal di bulan tersebut
                $keluarPerHari = [];
                foreach ($periode as $date) {
                    $dateStr = $date->format('Y-m-d');
                    $keluarPerHari[$dateStr] = isset($keluarPerHariRaw[$dateStr]) ? $keluarPerHariRaw[$dateStr]->sum('jumlah') : 0;
                }

                // Hitung stok sisa bulan ini:
                // stok awal + masuk - total keluar
                $stokAwalPeriode = $stokAkhirSebelumnya; // harus sudah ada stok akhir bulan sebelumnya
                $totalKeluar = array_sum($keluarPerHari); // total keluar dari tanggal 1 sampai akhir bulan
                $stokSisa = $stokAwalPeriode + $masukPeriode - $totalKeluar;

                // Hitung permintaan jika stok sisa kurang dari stok minimum
                $permintaan = 0;
                if (!is_null($obat->stok_minimum) && $stokSisa < $obat->stok_minimum) {
                    $permintaan = $obat->stok_minimum - $stokSisa;
                }

                // Simpan ke properti obat untuk nanti dipakai di view
                $obat->stok_awal_bulan_ini = $stokAwalPeriode;
                $obat->obat_masuk_bulan_ini = $masukPeriode;
                $obat->total_keluar_bulan_ini = $totalKeluar;  // total keluar sudah dihitung disini
                $obat->stok_sisa_bulan_ini = $stokSisa;
                $obat->permintaan = $permintaan;
                $obat->keluar_per_hari = $keluarPerHari;
            } elseif ($mode === 'tahunan') {
                $masukPeriode = ObatMasuk::where('obat_id', $obat->id)
                    ->whereYear('tanggal_masuk', $tahun)
                    ->sum('jumlah');

                $keluarPeriode = ObatKeluarDetail::where('obat_id', $obat->id)
                    ->whereYear('created_at', $tahun)
                    ->sum('jumlah');

                $stokAwalPeriode = $stokAkhirSebelumnya;
                $stokSisa = $stokAwalPeriode + $masukPeriode - $keluarPeriode;

                $obat->stok_awal_tahun_ini = $stokAwalPeriode;
                $obat->obat_masuk_tahun_ini = $masukPeriode;
                $obat->total_keluar_tahun_ini = $keluarPeriode;
            } else { // hari_ini
                $masukPeriode = ObatMasuk::where('obat_id', $obat->id)
                    ->whereDate('tanggal_masuk', now()->toDateString())
                    ->sum('jumlah');

                $keluarPeriode = ObatKeluarDetail::where('obat_id', $obat->id)
                    ->whereDate('created_at', now()->toDateString())
                    ->sum('jumlah');

                $stokAwalPeriode = $stokAkhirSebelumnya + $masukPeriode;
                $stokSisa = $stokAwalPeriode - $keluarPeriode;

                $obat->stok_awal_hari_ini = $stokAwalPeriode;
                $obat->obat_masuk_hari_ini = $masukPeriode;
                $obat->total_keluar_hari_ini = $keluarPeriode;
            }

            return $obat;
        });

        if (!is_null($stokMin)) {
            $obats = $obats->filter(fn($o) => $o->stok <= $stokMin);
        }

        return view('obat.keluar_detail', compact('obats', 'tahun', 'bulan', 'stokMin', 'mode', 'tanggal'));
    }
}
