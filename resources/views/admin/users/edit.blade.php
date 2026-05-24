@extends('layout.admin')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="font-weight-bold text-dark mb-1">Edit Akun Pengguna</h2>
            <p class="text-muted mb-0">Ubah kredensial atau hubungkan akun dengan profil Tenaga Pengajar.</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-light d-flex align-items-center gap-2" style="border-radius: 10px; border: 1px solid #e2e8f0;">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="card border-0 shadow-sm col-lg-8 mx-auto" style="border-radius: 16px;">
        <div class="card-body p-4">
            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Name Field -->
                <div class="mb-4">
                    <label for="name" class="form-label font-weight-bold text-dark">Nama Lengkap <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted border-end-0"><i class="fas fa-user"></i></span>
                        <input type="text" class="form-control border-start-0 @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $user->name) }}" required style="border-radius: 0 10px 10px 0;">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Email Field -->
                <div class="mb-4">
                    <label for="email" class="form-label font-weight-bold text-dark">Alamat Email <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted border-end-0"><i class="far fa-envelope"></i></span>
                        <input type="email" class="form-control border-start-0 @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email', $user->email) }}" required style="border-radius: 0 10px 10px 0;">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Password Field -->
                <div class="mb-4">
                    <label for="password" class="form-label font-weight-bold text-dark">Password Baru <span class="text-muted">(Kosongkan jika tidak diubah)</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted border-end-0"><i class="fas fa-key"></i></span>
                        <input type="password" class="form-control border-start-0 @error('password') is-invalid @enderror" 
                               id="password" name="password" placeholder="Biarkan kosong jika tidak ingin mengubah password" style="border-radius: 0 10px 10px 0;">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Password Confirmation Field -->
                <div class="mb-4">
                    <label for="password_confirmation" class="form-label font-weight-bold text-dark">Konfirmasi Password Baru</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted border-end-0"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control border-start-0" 
                               id="password_confirmation" name="password_confirmation" placeholder="Ulangi password baru" style="border-radius: 0 10px 10px 0;">
                    </div>
                </div>

                <!-- Link to Guru (Optional) -->
                <div class="mb-4">
                    <label for="guru_id" class="form-label font-weight-bold text-dark">Hubungkan ke Data Guru</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted border-end-0"><i class="fas fa-link"></i></span>
                        <select class="form-select border-start-0 @error('guru_id') is-invalid @enderror" id="guru_id" name="guru_id" style="border-radius: 0 10px 10px 0;">
                            <option value="">-- Pilih Guru (Opsional) --</option>
                            @foreach($gurus as $guru)
                                <option value="{{ $guru->id }}" {{ old('guru_id', $user->guru->id ?? '') == $guru->id ? 'selected' : '' }}>
                                    {{ $guru->nama_guru }} (NIP: {{ $guru->nip }})
                                </option>
                            @endforeach
                        </select>
                        @error('guru_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <small class="form-text text-muted mt-2 d-block">Menghubungkan akun ini akan memberi hak akses bagi guru terkait untuk melihat jadwal pribadinya setelah masuk.</small>
                </div>

                <!-- Submit Button -->
                <div class="text-end pt-3">
                    <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-2" style="border-radius: 10px; padding: 0.6rem 1.5rem;">
                        <i class="fas fa-save"></i> Perbarui Akun
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
