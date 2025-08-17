@extends('layouts.template')

@section('title', 'Obat Keluar')

@section('content')
    <div class="card">
        <h5 class="card-header">Daftar Obat Keluar</h5>
        <div class="p-3">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambahKeluar">
                + Tambah Obat Keluar
            </button>

            <table class="table table-striped" id="obatTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tanggal</th>
                        <th>Pasien</th>
                        <th>Detail Obat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($obatKeluar as $index => $keluar)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $keluar->tanggal_keluar }}</td>
                            <td>{{ $keluar->pasien->nama_pasien }}</td>
                            <td>
                                <ul>
                                    @foreach ($keluar->detail as $detail)
                                        <li>{{ $detail->obat->nama_obat }} ({{ $detail->jumlah }})</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                <a href="{{ route('obat-keluar.edit', $keluar->id) }}"
                                    class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('obat-keluar.destroy', $keluar->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm"
                                        onclick="return confirm('Yakin hapus?')">Hapus</button>
                                </form>
                                <a href="{{ route('obat-keluar.export-pdf', $keluar->id) }}" class="btn btn-danger btn-sm"
                                    target="_blank">
                                    Export PDF
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div class="modal fade" id="modalTambahKeluar" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form method="POST" action="{{ route('obat-keluar.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Obat Keluar</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Tanggal Keluar</label>
                            <input type="date" name="tanggal_keluar" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Pasien</label>
                            <select name="pasien_id" class="form-control" required>
                                <option value="">-- Pilih Pasien --</option>
                                @foreach ($pasiens as $pasien)
                                    <option value="{{ $pasien->id }}">{{ $pasien->nama_pasien }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="obatContainer">
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <select name="obat_id[]" class="form-control" required>
                                        <option value="">-- Pilih Obat --</option>
                                        @foreach ($obats as $obat)
                                            <option value="{{ $obat->id }}">{{ $obat->nama_obat }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <input type="number" name="jumlah[]" class="form-control" placeholder="Jumlah"
                                        required>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-success" onclick="tambahObat()">+</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function tambahObat() {
            let html = `
    <div class="row mb-2">
        <div class="col-md-6">
            <select name="obat_id[]" class="form-control" required>
                <option value="">-- Pilih Obat --</option>
                @foreach ($obats as $obat)
                    <option value="{{ $obat->id }}">{{ $obat->nama_obat }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <input type="number" name="jumlah[]" class="form-control" placeholder="Jumlah" required>
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-danger" onclick="this.closest('.row').remove()">-</button>
        </div>
    </div>`;
            document.getElementById('obatContainer').insertAdjacentHTML('beforeend', html);
        }
    </script>
@endsection
