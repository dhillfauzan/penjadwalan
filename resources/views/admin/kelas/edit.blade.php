@extends('layout.admin')
@section('content')
<div class="container">
    <h1>Edit Kelas</h1>
    <a href="{{ route('admin.kelas.index') }}" class="btn btn-secondary mb-3">Kembali</a>
    <div class="card"><div class="card-body">
        <form action="{{ route('admin.kelas.update', $kelas) }}" method="POST">
            @csrf @method('PUT')
            <div class="mb-3"><label>Nama Kelas</label><input type="text" name="nama_kelas" value="{{ $kelas->nama_kelas }}" class="form-control" required></div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div></div>
</div>
@endsection