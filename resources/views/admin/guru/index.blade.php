@extends('layout.admin')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="font-weight-bold text-dark mb-1">Tenaga Pengajar (Guru)</h2>
            <p class="text-muted mb-0">Kelola profil guru beserta mata pelajaran, kelas, dan preferensi jam mengajar mereka.</p>
        </div>
        <a href="{{ route('admin.guru.create') }}" class="btn btn-primary d-flex align-items-center gap-2" style="border-radius: 10px; padding: 0.6rem 1.2rem;">
            <i class="fas fa-plus"></i> Tambah Guru Baru
        </a>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show d-flex align-items-center gap-2" role="alert" style="border-radius: 12px;">
            <i class="fas fa-check-circle text-success"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Gurus Table Card -->
    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary">
                        <tr>
                            <th class="ps-4 border-0" style="width: 60px;">No</th>
                            <th class="border-0">Nama Guru / NIP</th>
                            <th class="border-0">Kontak</th>
                            <th class="border-0">Mata Pelajaran</th>
                            <th class="border-0">Kelas Diampu</th>
                            <th class="border-0">Akun Login</th>
                            <th class="pe-4 border-0 text-end" style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($gurus as $guru)
                        <tr>
                            <td class="ps-4 font-weight-bold text-secondary">{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center font-weight-bold" style="width: 42px; height: 42px; background-color: #e0f2fe; color: #0284c7;">
                                        {{ strtoupper(substr($guru->nama_guru, 0, 2)) }}
                                    </div>
                                    <div>
                                        <h6 class="mb-0 text-dark font-weight-bold">{{ $guru->nama_guru }}</h6>
                                        <small class="text-muted"><i class="fas fa-id-card me-1"></i>NIP: {{ $guru->nip }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="text-dark small"><i class="fas fa-venus-mars text-muted me-1"></i> {{ $guru->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                                    <span class="text-secondary small mt-1"><i class="fas fa-phone text-muted me-1"></i> {{ $guru->no_telp ?? '-' }}</span>
                                </div>
                            </td>
                            <td>
                                @forelse($guru->mataPelajarans as $mapel)
                                    <span class="badge bg-indigo bg-opacity-10 text-indigo px-2.5 py-1.5 rounded-pill font-weight-bold text-xs" style="background-color: #e0e7ff; color: #4f46e5;">
                                        {{ $mapel->nama_mapel }}
                                    </span>
                                @empty
                                    <span class="text-muted small"><em>Belum diset</em></span>
                                @endforelse
                            </td>
                            <td>
                                @forelse($guru->kelas as $k)
                                    <span class="badge bg-secondary bg-opacity-10 text-dark px-2.5 py-1.5 rounded border small mb-1 d-inline-block">
                                        <i class="fas fa-door-open me-1 text-primary"></i> {{ $k->nama_kelas }}
                                    </span>
                                @empty
                                    <span class="text-muted small"><em>Belum diset</em></span>
                                @endforelse
                            </td>
                            <td>
                                @if($guru->user)
                                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill font-weight-bold">
                                        <i class="far fa-envelope-open me-1"></i> {{ $guru->user->email }}
                                    </span>
                                @else
                                    <span class="badge bg-light text-secondary border px-2 py-1.5 rounded small">
                                        <i class="fas fa-exclamation-triangle text-warning me-1"></i> Belum Terhubung
                                    </span>
                                @endif
                            </td>
                            <td class="pe-4 text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.guru.edit', $guru) }}" class="btn btn-outline-warning btn-sm d-flex align-items-center gap-1" style="border-radius: 8px;">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.guru.destroy', $guru) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus guru ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm d-flex align-items-center gap-1" style="border-radius: 8px;">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <img src="https://cdn-icons-png.flaticon.com/512/3588/3588658.png" alt="Empty Teachers" style="max-width: 100px; opacity: 0.6;" class="mb-3">
                                <h5 class="text-secondary font-weight-bold">Belum Ada Data Guru</h5>
                                <p class="text-muted small">Tambahkan data guru baru untuk dihubungkan ke sistem jadwal.</p>
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