@extends('layouts.template')

@section('title', 'Laporan Obat Keluar')

@section('content')
    <div class="card">
        <h5 class="card-header">Laporan Obat Keluar</h5>
        <div class="p-3">

            {{-- Filter --}}
            <form method="GET" class="row g-2 mb-3">
                <div class="col-md-3">
                    <label>Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" value="{{ $tanggal ?? '' }}">
                </div>
                <div class="col-md-3">
                    <label>Bulan (Opsional)</label>
                    <select name="bulan" class="form-control">
                        <option value="">-- Semua Bulan --</option>
                        @foreach (range(1, 12) as $b)
                            <option value="{{ $b }}" {{ $bulan == $b ? 'selected' : '' }}>
                                {{ DateTime::createFromFormat('!m', $b)->format('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Tahun</label>
                    <input type="number" name="tahun" class="form-control" value="{{ $tahun }}">
                </div>
                <div class="col-md-2 align-self-end">
                    <button class="btn btn-primary w-100">Filter</button>
                </div>
            </form>

            {{-- Tabel Laporan --}}
            @if ($mode && $obats->count())
                <div class="table-responsive text-nowrap">
                    <table class="table table-striped" id="obatTable">
                        <thead>
                            <tr>
                                <th>Nama Obat</th>
                                <th>Satuan</th>

                                @if ($mode === 'harian_tanggal')
                                    <th>Stok Awal</th>
                                    <th>Obat Masuk</th>
                                    <th>Obat Keluar</th>
                                    <th>Obat Rusak</th>
                                    <th>Stok Sisa</th>
                                    <th>Permintaan</th>
                                @elseif($mode === 'bulanan')
                                    <th>Stok Awal Bulan</th>
                                    <th>Obat Masuk</th>
                                    @php
                                        $startDate = \Carbon\Carbon::create($tahun, $bulan, 1);
                                        $endDate = $startDate->copy()->endOfMonth();
                                        $daysInMonth = $endDate->day;
                                    @endphp
                                    @for ($d = 1; $d <= $daysInMonth; $d++)
                                        <th>{{ $d }}</th>
                                    @endfor
                                    <th>Total Keluar</th>
                                    <th>Total Rusak</th>
                                    <th>Stok Sisa</th>
                                    <th>Permintaan</th>
                                @elseif($mode === 'tahunan')
                                    <th>Stok Awal Tahun</th>
                                    <th>Obat Masuk</th>
                                    <th>Total Keluar</th>
                                    <th>Total Rusak</th>
                                    <th>Stok Sisa</th>
                                    <th>Permintaan</th>
                                @elseif($mode === 'hari_ini')
                                    <th>Stok Awal</th>
                                    <th>Obat Masuk</th>
                                    <th>Obat Keluar</th>
                                    <th>Obat Rusak</th>
                                    <th>Stok Sisa</th>
                                    <th>Permintaan</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($obats as $obat)
                                <tr>
                                    <td>{{ $obat->nama_obat }}</td>
                                    <td>{{ $obat->satuan }}</td>

                                    @if ($mode === 'harian_tanggal')
                                        <td>{{ $obat->stok_awal_tanggal }}</td>
                                        <td>{{ $obat->obat_masuk_tanggal }}</td>
                                        <td>{{ $obat->obat_keluar_tanggal }}</td>
                                        <td>{{ $obat->obat_rusak_tanggal }}</td>
                                        <td>{{ $obat->stok_sisa_tanggal }}</td>
                                        <td>{{ $obat->permintaan_tanggal }}</td>
                                    @elseif($mode === 'bulanan')
                                        <td>{{ $obat->stok_awal_bulan_ini }}</td>
                                        <td>{{ $obat->obat_masuk_bulan_ini }}</td>
                                        @for ($d = 1; $d <= $daysInMonth; $d++)
                                            @php
                                                $dateKey = $startDate->copy()->day($d)->format('Y-m-d');
                                                $jumlahKeluarHariIni = $obat->keluar_per_hari[$dateKey] ?? 0;
                                            @endphp
                                            <td>{{ $jumlahKeluarHariIni }}</td>
                                        @endfor
                                        <td>{{ $obat->total_keluar_bulan_ini }}</td>
                                        <td>{{ $obat->obat_rusak_bulan_ini }}</td>
                                        <td>{{ $obat->stok_sisa_bulan_ini }}</td>
                                        <td>{{ $obat->permintaan }}</td>
                                    @elseif($mode === 'tahunan')
                                        <td>{{ $obat->stok_awal_tahun_ini }}</td>
                                        <td>{{ $obat->obat_masuk_tahun_ini }}</td>
                                        <td>{{ $obat->total_keluar_tahun_ini }}</td>
                                        <td>{{ $obat->obat_rusak_tahun_ini }}</td>
                                        <td>{{ $obat->stok_sisa_tahun_ini }}</td>
                                        <td>{{ $obat->permintaan }}</td>
                                    @elseif($mode === 'hari_ini')
                                        <td>{{ $obat->stok_awal_hari_ini }}</td>
                                        <td>{{ $obat->obat_masuk_hari_ini }}</td>
                                        <td>{{ $obat->total_keluar_hari_ini }}</td>
                                        <td>{{ $obat->obat_rusak_hari_ini }}</td>
                                        <td>{{ $obat->stok_sisa_hari_ini }}</td>
                                        <td>{{ $obat->permintaan }}</td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-center text-muted">Silakan pilih filter untuk menampilkan data.</p>
            @endif
        </div>
    </div>
@endsection
