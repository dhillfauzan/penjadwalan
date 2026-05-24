@extends('layout.admin')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="font-weight-bold text-dark mb-1">Daftar Akun Pengguna</h2>
            <p class="text-muted mb-0">Kelola kredensial login untuk Tenaga Pengajar (Guru) di sekolah Anda.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary d-flex align-items-center gap-2" style="border-radius: 10px; padding: 0.6rem 1.2rem;">
            <i class="fas fa-plus"></i> Tambah Akun Baru
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

    <!-- Users Table Card -->
    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary">
                        <tr>
                            <th class="ps-4 border-0" style="width: 80px;">No</th>
                            <th class="border-0">Nama Pengguna</th>
                            <th class="border-0">Email</th>
                            <th class="border-0">Guru Terkait</th>
                            <th class="pe-4 border-0 text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td class="ps-4 font-weight-bold text-secondary">{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-indigo-50 text-indigo-600 rounded-circle d-flex align-items-center justify-content-center font-weight-bold" style="width: 42px; height: 42px; background-color: #e0e7ff; color: #4f46e5;">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <h6 class="mb-0 text-dark font-weight-bold">{{ $user->name }}</h6>
                                        <span class="badge bg-success bg-opacity-10 text-success text-capitalize small">Guru</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="text-secondary"><i class="far fa-envelope me-2"></i>{{ $user->email }}</span>
                            </td>
                            <td>
                                @if($user->guru)
                                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill font-weight-bold">
                                        <i class="fas fa-user-tie me-1"></i> {{ $user->guru->nama_guru }}
                                    </span>
                                @else
                                    <span class="text-muted small"><em>Belum dihubungkan</em></span>
                                @endif
                            </td>
                            <td class="pe-4 text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-warning btn-sm d-flex align-items-center gap-1" style="border-radius: 8px;">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
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
                            <td colspan="5" class="text-center py-5">
                                <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" alt="Empty Users" style="max-width: 100px; opacity: 0.6;" class="mb-3">
                                <h5 class="text-secondary font-weight-bold">Tidak Ada Data Akun</h5>
                                <p class="text-muted small">Belum ada akun guru yang didaftarkan ke sistem.</p>
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
