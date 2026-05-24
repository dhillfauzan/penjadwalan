@extends('layout.admin')
@section('content')
<div class="container">
    <h1>Tambah Jam Pelajaran</h1>
    <a href="{{ route('admin.jam-pelajaran.index') }}" class="btn btn-secondary mb-3">Kembali</a>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.jam-pelajaran.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label>Hari</label>
                    <select name="hari" class="form-control" required>
                        <option value="">-- Pilih --</option>
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Jam ke-</label>
                    <input type="number" name="jam_ke" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Waktu Mulai</label>
                    <input type="time" name="waktu_mulai" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Waktu Selesai</label>
                    <input type="time" name="waktu_selesai" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
</div>
@endsection