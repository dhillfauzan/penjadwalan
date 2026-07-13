@extends('layout.admin')

@section('page-title', 'Kelas')

@section('content')
<div class="container-fluid">
    <!-- Action Card -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body d-flex justify-content-between align-items-center p-4">
            <div>
                <h5 class="font-weight-bold text-dark mb-1"><i class="fas fa-door-open text-primary me-2"></i>Daftar Kelas</h5>
                <p class="text-muted mb-0 small">Kelola data kelas yang akan digunakan dalam penjadwalan pelajaran.</p>
            </div>
            <a href="{{ route('admin.kelas.create') }}" class="btn btn-primary d-flex align-items-center gap-2" style="border-radius: 10px; padding: 0.6rem 1.2rem;">
                <i class="fas fa-plus"></i> Tambah Kelas
            </a>
        </div>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert" style="border-radius: 12px;">
            <i class="fas fa-check-circle text-success"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Table Card -->
    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary">
                        <tr>
                            <th class="ps-4 border-0" style="width: 80px;">No</th>
                            <th class="border-0">Nama Kelas</th>
                            <th class="pe-4 border-0 text-end" style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kelas as $k)
                        <tr>
                            <td class="ps-4 font-weight-bold text-secondary">{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center font-weight-bold" style="width: 42px; height: 42px;">
                                        <i class="fas fa-door-open"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 text-dark font-weight-bold">{{ $k->nama_kelas }}</h6>
                                        <span class="text-muted small">Ruang Kelas Aktif</span>
                                    </div>
                                </div>
                            </td>
                            <td class="pe-4 text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.kelas.edit', $k) }}" class="btn btn-outline-warning btn-sm d-flex align-items-center gap-1" style="border-radius: 8px;">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.kelas.destroy', $k) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus kelas ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm d-flex align-items-center gap-1" style="border-radius: 8px;">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-5">
                                <i class="fas fa-door-open fa-3x text-muted mb-3 d-block opacity-50"></i>
                                <h5 class="text-secondary font-weight-bold">Belum Ada Data Kelas</h5>
                                <p class="text-muted small">Tambahkan kelas baru untuk dihubungkan ke sistem penjadwalan.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection