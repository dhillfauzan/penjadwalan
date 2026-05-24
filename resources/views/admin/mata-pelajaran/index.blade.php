@extends('layout.admin')
@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-4">
        <h1>Mata Pelajaran</h1>
        <a href="{{ route('admin.mata-pelajaran.create') }}" class="btn btn-primary">Tambah</a>
    </div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead><tr><th>#</th><th>Kode Mapel</th><th>Nama Mapel</th><th>Aksi</th></tr></thead>
                <tbody>
                    @forelse($mapel as $m)
                    <tr><td>{{ $loop->iteration }}</td><td>{{ $m->kode_mapel }}</td><td>{{ $m->nama_mapel }}</td>
                    <td>
                        <a href="{{ route('admin.mata-pelajaran.edit', $m) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.mata-pelajaran.destroy', $m) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                     </td>
                    </tr>
                    @empty <tr><td colspan="4">Tidak ada data</td></tr> @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection