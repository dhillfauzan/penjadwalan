@extends('layout.admin')

@section('page-title', 'Profil Pengguna')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Update Profile Information -->
        <div class="col-xl-9 col-lg-10 mb-4 mx-auto">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-header d-flex justify-content-between align-items-center" style="border-bottom: 1px solid #f0f0f0; background: transparent;">
                    <div class="header-title">
                        <h4 class="card-title font-weight-bold text-dark mb-0">Informasi Profil</h4>
                        <p class="text-muted small mb-0">Perbarui informasi profil dan alamat email akun Anda.</p>
                    </div>
                    <!-- Tombol Kembali ke Dashboard -->
                    <a href="{{ route('dashboard') }}" class="btn btn-soft-secondary d-flex align-items-center gap-2" style="border-radius: 10px; padding: 0.5rem 1rem;">
                        <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                    </a>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('profile.update') }}">
                        @csrf
                        @method('patch')
                        
                        <div class="form-group mb-3">
                            <label class="form-label" for="name">Nama</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" style="border-radius: 10px;">
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label" for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="username" style="border-radius: 10px;">
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex align-items-center gap-3">
                            <button type="submit" class="btn btn-primary" style="border-radius: 10px;">Simpan Perubahan</button>
                            
                            @if (session('status') === 'profile-updated')
                                <span class="text-success small fw-bold"><i class="fas fa-check-circle"></i> Berhasil disimpan.</span>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Update Password -->
        <div class="col-xl-9 col-lg-10 mb-4 mx-auto">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-header d-flex justify-content-between align-items-center" style="border-bottom: 1px solid #f0f0f0; background: transparent;">
                    <div class="header-title">
                        <h4 class="card-title font-weight-bold text-dark mb-0">Perbarui Password</h4>
                        <p class="text-muted small mb-0">Pastikan akun Anda menggunakan kata sandi yang panjang dan acak agar tetap aman.</p>
                    </div>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('password.update') }}">
                        @csrf
                        @method('put')

                        <div class="form-group mb-3">
                            <label class="form-label" for="current_password">Password Saat Ini</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" autocomplete="current-password" style="border-radius: 10px;">
                            @error('current_password', 'updatePassword')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label" for="password">Password Baru</label>
                            <input type="password" class="form-control" id="password" name="password" autocomplete="new-password" style="border-radius: 10px;">
                            @error('password', 'updatePassword')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label" for="password_confirmation">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" autocomplete="new-password" style="border-radius: 10px;">
                            @error('password_confirmation', 'updatePassword')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex align-items-center gap-3">
                            <button type="submit" class="btn btn-primary" style="border-radius: 10px;">Simpan Password</button>

                            @if (session('status') === 'password-updated')
                                <span class="text-success small fw-bold"><i class="fas fa-check-circle"></i> Password diperbarui.</span>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Delete Account -->
        <div class="col-xl-9 col-lg-10 mx-auto">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-header d-flex justify-content-between align-items-center" style="border-bottom: 1px solid #f0f0f0; background: transparent;">
                    <div class="header-title">
                        <h4 class="card-title font-weight-bold text-danger mb-0">Hapus Akun</h4>
                        <p class="text-muted small mb-0">Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen.</p>
                    </div>
                </div>
                <div class="card-body">
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmUserDeletion" style="border-radius: 10px;">
                        Hapus Akun
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Hapus Akun -->
<div class="modal fade" id="confirmUserDeletion" tabindex="-1" aria-labelledby="confirmUserDeletionLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="post" action="{{ route('profile.destroy') }}" class="modal-content" style="border-radius: 16px;">
            @csrf
            @method('delete')
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title font-weight-bold text-dark" id="confirmUserDeletionLabel">Apakah Anda yakin ingin menghapus akun Anda?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small mb-3">Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen. Silakan masukkan password Anda untuk mengonfirmasi bahwa Anda ingin menghapus akun Anda secara permanen.</p>
                <div class="form-group mb-0">
                    <input type="password" class="form-control" name="password" placeholder="Password" required style="border-radius: 10px;">
                    @error('password', 'userDeletion')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 10px;">Batal</button>
                <button type="submit" class="btn btn-danger" style="border-radius: 10px;">Hapus Akun</button>
            </div>
        </form>
    </div>
</div>

<!-- Tampilkan modal secara otomatis jika ada error saat penghapusan akun -->
@if($errors->userDeletion->isNotEmpty())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if(typeof bootstrap !== 'undefined') {
            var modal = new bootstrap.Modal(document.getElementById('confirmUserDeletion'));
            modal.show();
        }
    });
</script>
@endif

@endsection
