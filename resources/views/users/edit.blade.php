@extends('layouts.template')

@section('title', 'Edit User')

@section('content')
    <div class="card p-4">
        <h5>Edit User</h5>

        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Nama</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
                @error('name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required>
                @error('email')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password <small>(kosongkan jika tidak ingin ganti)</small></label>
                <input type="password" name="password" class="form-control">
                @error('password')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control">
            </div>

            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                @if (auth()->user()->role == 'admin')
                    <select name="role" class="form-select" required>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="poli" {{ old('role', $user->role) == 'poli' ? 'selected' : '' }}>Poli</option>
                    </select>
                @else
                    <!-- Kalau bukan admin, tampilkan role tapi disabled agar tidak bisa diubah -->
                    <input type="text" class="form-control" value="{{ ucfirst($user->role) }}" disabled>
                    <input type="hidden" name="role" value="{{ $user->role }}">
                @endif

                @error('role')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>


            <button class="btn btn-primary" type="submit">Update</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
@endsection
