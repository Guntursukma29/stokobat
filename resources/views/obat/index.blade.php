@extends('layouts.template')

@section('title', 'Daftar Obat')

@section('content')
    <div class="card">
        <h5 class="card-header">Daftar Obat</h5>
        <div class="table-responsive text-nowrap p-3">

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambahObat">
                + Tambah Obat
            </button>
            <div class="table-responsive text-nowrap">
                <table class="table table-striped" id="obatTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Obat</th>
                            <th>Satuan</th>
                            {{-- <th>Stok</th> --}}
                            <th>Stok Minimum</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($obats as $index => $obat)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $obat->nama_obat }}</td>
                                <td>{{ $obat->satuan }}</td>
                                {{-- <td>{{ $obat->stok }}</td> --}}
                                <td>{{ $obat->stok_minimum }}</td>
                                <td>
                                    <!-- Button trigger modal edit -->
                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#modalEditObat{{ $obat->id }}">
                                        Edit
                                    </button>

                                    <!-- Modal Edit Obat -->
                                    <div class="modal fade" id="modalEditObat{{ $obat->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <form action="{{ route('obat.update', $obat->id) }}" method="POST"
                                                class="w-100">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Obat</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="nama_obat" class="form-label">Nama Obat</label>
                                                            <input type="text" name="nama_obat"
                                                                value="{{ $obat->nama_obat }}" class="form-control"
                                                                required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="satuan" class="form-label">Satuan</label>
                                                            <input type="text" name="satuan" value="{{ $obat->satuan }}"
                                                                class="form-control" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="stok_minimum" class="form-label">Stok
                                                                Minimum</label>
                                                            <input type="number" name="stok_minimum"
                                                                value="{{ $obat->stok_minimum }}" class="form-control"
                                                                required>
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
                                    <form action="{{ route('obat.destroy', $obat->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm"
                                            onclick="return confirm('Yakin hapus?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
    <div class="modal fade" id="modalTambahObat" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form action="{{ route('obat.store') }}" method="POST" class="w-100">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Obat</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama_obat" class="form-label">Nama Obat</label>
                            <input type="text" name="nama_obat" class="form-control" placeholder="Masukkan nama obat"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="satuan" class="form-label">Satuan</label>
                            <input type="text" name="satuan" class="form-control" placeholder="Contoh: Tablet, Botol"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="stok_minimum" class="form-label">Stok Minimum</label>
                            <input type="number" name="stok_minimum" class="form-control"
                                placeholder="Masukkan stok minimum" required>
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
