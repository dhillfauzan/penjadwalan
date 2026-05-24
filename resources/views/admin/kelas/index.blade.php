@extends('layout.admin')
@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-4">
        <h1>Kelas</h1>
        <a href="{{ route('admin.kelas.create') }}" class="btn btn-primary">Tambah Kelas</a>
    </div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="card"><div class="card-body">
        <table class="table table-bordered">
            <thead><tr><th>#</th><th>Nama Kelas</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($kelas as $k)
                <tr><td>{{ $loop->iteration }}</td><td>{{ $k->nama_kelas }}</td>
                <td>
                    <a href="{{ route('admin.kelas.edit', $k) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('admin.kelas.destroy', $k) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </td></tr>
                @empty <tr><td colspan="3">Tidak ada data</td></tr> @endforelse
            </tbody>
        </table>
    </div></div>
</div>
@endsection