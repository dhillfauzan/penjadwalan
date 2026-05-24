@extends('layout.admin')
@section('content')
<div class="container">
    <h1>Edit Mata Pelajaran</h1>
    <a href="{{ route('admin.mata-pelajaran.index') }}" class="btn btn-secondary mb-3">Kembali</a>
    <div class="card"><div class="card-body">
        <form action="{{ route('admin.mata-pelajaran.update', $mataPelajaran) }}" method="POST">
            @csrf @method('PUT')
            <div class="mb-3"><label>Kode Mapel</label><input type="text" name="kode_mapel" value="{{ $mataPelajaran->kode_mapel }}" class="form-control" required></div>
            <div class="mb-3"><label>Nama Mapel</label><input type="text" name="nama_mapel" value="{{ $mataPelajaran->nama_mapel }}" class="form-control" required></div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div></div>
</div>
@endsection