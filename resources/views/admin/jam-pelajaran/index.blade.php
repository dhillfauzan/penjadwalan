@extends('layout.admin')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Jam Pelajaran</h1>
        <a href="{{ route('admin.jam-pelajaran.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Jam
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr><th>#</th><th>Hari</th><th>Jam ke-</th><th>Waktu Mulai</th><th>Waktu Selesai</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($jam as $j)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $j->hari }}</td>
                        <td>{{ $j->jam_ke }}</td>
                        <td>{{ $j->waktu_mulai }}</td>
                        <td>{{ $j->waktu_selesai }}</td>
                        <td>
                            <a href="{{ route('admin.jam-pelajaran.edit', $j) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('admin.jam-pelajaran.destroy', $j) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                         </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center">Tidak ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection