@extends('layouts.template')

@section('title', 'Daftar Obat Rusak')

@section('content')
    <div class="card">
        <h5 class="card-header">Daftar Obat Rusak</h5>
        <div class="table-responsive text-nowrap p-3">

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <!-- Button trigger modal Tambah -->
            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambahObatRusak">
                + Tambah Obat
            </button>

            <div class="table-responsive text-nowrap">
                <table class="table table-striped" id="obatRusakTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Obat</th>
                            <th>Satuan</th>
                            <th>Jumlah</th>
                            <th>Tanggal</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($obatRusak as $index => $rusak)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $rusak->obat->nama_obat }}</td>
                                <td>{{ $rusak->obat->satuan }}</td>
                                <td>{{ $rusak->jumlah }}</td>
                                <td>{{ \Carbon\Carbon::parse($rusak->tanggal)->format('d-m-Y') }}</td>
                                <td>{{ $rusak->keterangan ?? '-' }}</td>
                                <td>
                                    <!-- Button trigger modal Edit -->
                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#modalEditObatRusak{{ $rusak->id }}">
                                        Edit
                                    </button>

                                    <!-- Modal Edit -->
                                    <div class="modal fade" id="modalEditObatRusak{{ $rusak->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <form action="{{ route('obat-rusak.update', $rusak->id) }}" method="POST"
                                                class="w-100">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Obat Rusak</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="obat_id" class="form-label">Nama Obat</label>
                                                            <select name="obat_id" class="form-control" required>
                                                                <option value="">-- Pilih Obat --</option>
                                                                @foreach ($obats as $obat)
                                                                    <option value="{{ $obat->id }}"
                                                                        {{ $obat->id == $rusak->obat_id ? 'selected' : '' }}>
                                                                        {{ $obat->nama_obat }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="jumlah" class="form-label">Jumlah</label>
                                                            <input type="number" name="jumlah" class="form-control"
                                                                value="{{ $rusak->jumlah }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="tanggal" class="form-label">Tanggal</label>
                                                            <input type="date" name="tanggal" class="form-control"
                                                                value="{{ $rusak->tanggal }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="keterangan" class="form-label">Keterangan</label>
                                                            <textarea name="keterangan" class="form-control" rows="3">{{ $rusak->keterangan }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-outline-secondary"
                                                            data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Form Hapus -->
                                    <form action="{{ route('obat-rusak.destroy', $rusak->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm"
                                            onclick="return confirm('Yakin hapus data ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- Modal Tambah Obat Rusak -->
    <div class="modal fade" id="modalTambahObatRusak" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form action="{{ route('obat-rusak.store') }}" method="POST" class="w-100">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Obat Rusak</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="obat_id" class="form-label">Nama Obat</label>
                            <select name="obat_id" class="form-control" required>
                                <option value="">-- Pilih Obat --</option>
                                @foreach ($obats as $obat)
                                    <option value="{{ $obat->id }}">{{ $obat->nama_obat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="jumlah" class="form-label">Jumlah</label>
                            <input type="number" name="jumlah" class="form-control" placeholder="Masukkan jumlah"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="tanggal" class="form-label">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="3" placeholder="Opsional"></textarea>
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
@endsection
