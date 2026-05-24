@extends('layout.admin')
@section('content')
<div class="container">
    <h1>Tambah Kelas</h1>
    <a href="{{ route('admin.kelas.index') }}" class="btn btn-secondary mb-3">Kembali</a>
    <div class="card"><div class="card-body">
        <form action="{{ route('admin.kelas.store') }}" method="POST">
            @csrf
            <div class="mb-3"><label>Nama Kelas</label><input type="text" name="nama_kelas" class="form-control" required></div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div></div>
</div>
@endsection