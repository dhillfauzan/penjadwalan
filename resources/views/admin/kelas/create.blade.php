@extends('layout.admin')

@section('page-title', 'Tambah Kelas')

@section('content')
<div class="container-fluid">
    <!-- Action Card -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body d-flex justify-content-between align-items-center p-4">
            <div>
                <h5 class="font-weight-bold text-dark mb-1"><i class="fas fa-door-open text-primary me-2"></i>Tambah Kelas Baru</h5>
                <p class="text-muted mb-0 small">Masukkan data ruang kelas baru ke dalam sistem penjadwalan.</p>
            </div>
            <a href="{{ route('admin.kelas.index') }}" class="btn btn-light d-flex align-items-center gap-2" style="border-radius: 10px; border: 1px solid #e2e8f0;">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Form Card -->
    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-body p-4">
            <form action="{{ route('admin.kelas.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-12 mb-4">
                        <label class="form-label font-weight-bold">Nama Kelas <span class="text-danger">*</span></label>
                        <input type="text" name="nama_kelas" class="form-control" required placeholder="Contoh: VII-A, X IPA 1" style="border-radius: 10px; padding: 0.6rem 1rem;">
                    </div>
                </div>
                <div class="text-end mt-2">
                    <button type="submit" class="btn btn-primary px-4 py-2" style="border-radius: 10px;">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection