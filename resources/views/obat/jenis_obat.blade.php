@extends('layouts.template')

@section('title', 'Daftar Jenis Obat')

@section('content')
    <div class="card">
        <h5 class="card-header">Daftar Jenis Obat</h5>
        <div class="table-responsive text-nowrap p-3">

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <!-- Button trigger modal Tambah -->
            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambahJenis">
                + Tambah Jenis Obat
            </button>

            <div class="table-responsive text-nowrap">
                <table class="table table-striped" id="jenisObatTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Jenis Obat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jenisObat as $index => $jenis)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $jenis->nama }}</td>
                                <td>
                                    <!-- Button trigger modal Edit -->
                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#modalEditJenis{{ $jenis->id }}">
                                        Edit
                                    </button>

                                    <!-- Modal Edit -->
                                    <div class="modal fade" id="modalEditJenis{{ $jenis->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <form action="{{ route('jenis-obat.update', $jenis->id) }}" method="POST"
                                                class="w-100">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Jenis Obat</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="nama" class="form-label">Nama Jenis Obat</label>
                                                            <input type="text" name="nama" class="form-control"
                                                                value="{{ $jenis->nama }}" required>
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
                                    <form action="{{ route('jenis-obat.destroy', $jenis->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm"
                                            onclick="return confirm('Yakin hapus jenis obat ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- Modal Tambah Jenis Obat -->
    <div class="modal fade" id="modalTambahJenis" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form action="{{ route('jenis-obat.store') }}" method="POST" class="w-100">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Jenis Obat</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Jenis Obat</label>
                            <input type="text" name="nama" class="form-control" placeholder="Masukkan jenis obat"
                                required>
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
