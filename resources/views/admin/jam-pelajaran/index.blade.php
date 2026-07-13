@extends('layout.admin')

@section('page-title', 'Jam Pelajaran')

@section('content')
<div class="container-fluid">
    <!-- Action Card -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body d-flex justify-content-between align-items-center p-4">
            <div>
                <h5 class="font-weight-bold text-dark mb-1"><i class="fas fa-clock text-primary me-2"></i>Jam Pelajaran</h5>
                <p class="text-muted mb-0 small">Kelola slot waktu mengajar untuk setiap hari dalam seminggu.</p>
            </div>
            <a href="{{ route('admin.jam-pelajaran.create') }}" class="btn btn-primary d-flex align-items-center gap-2" style="border-radius: 10px; padding: 0.6rem 1.2rem;">
                <i class="fas fa-plus"></i> Tambah Jam
            </a>
        </div>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show d-flex align-items-center gap-2" role="alert" style="border-radius: 12px;">
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
                            <th class="ps-4 border-0" style="width: 60px;">No</th>
                            <th class="border-0">Hari</th>
                            <th class="border-0">Jam Ke</th>
                            <th class="border-0">Waktu Mulai</th>
                            <th class="border-0">Waktu Selesai</th>
                            <th class="pe-4 border-0 text-end" style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $hariColors = [
                                'Senin' => 'primary',
                                'Selasa' => 'success',
                                'Rabu' => 'warning',
                                'Kamis' => 'info',
                                'Jumat' => 'danger',
                            ];
                        @endphp
                        @forelse($jam as $j)
                        <tr>
                            <td class="ps-4 font-weight-bold text-secondary">{{ $loop->iteration }}</td>
                            <td>
                                <span class="badge bg-{{ $hariColors[$j->hari] ?? 'secondary' }} bg-opacity-10 text-{{ $hariColors[$j->hari] ?? 'secondary' }} px-3 py-2 rounded-pill font-weight-bold">
                                    {{ $j->hari }}
                                </span>
                            </td>
                            <td>
                                <span class="bg-light text-dark rounded-circle d-inline-flex align-items-center justify-content-center font-weight-bold" style="width: 32px; height: 32px; font-size: 0.9rem;">
                                    {{ $j->jam_ke }}
                                </span>
                            </td>
                            <td>
                                <i class="far fa-clock text-muted me-1"></i>
                                <span class="text-dark">{{ \Carbon\Carbon::parse($j->waktu_mulai)->format('H:i') }}</span>
                            </td>
                            <td>
                                <i class="far fa-clock text-muted me-1"></i>
                                <span class="text-dark">{{ \Carbon\Carbon::parse($j->waktu_selesai)->format('H:i') }}</span>
                            </td>
                            <td class="pe-4 text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.jam-pelajaran.edit', $j) }}" class="btn btn-outline-warning btn-sm d-flex align-items-center gap-1" style="border-radius: 8px;">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.jam-pelajaran.destroy', $j) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus jam pelajaran ini?')">
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
                            <td colspan="6" class="text-center py-5">
                                <i class="fas fa-clock fa-3x text-muted mb-3 d-block opacity-50"></i>
                                <h5 class="text-secondary font-weight-bold">Belum Ada Data Jam Pelajaran</h5>
                                <p class="text-muted small">Tambahkan jam pelajaran untuk setiap hari agar sistem dapat menyusun jadwal.</p>
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