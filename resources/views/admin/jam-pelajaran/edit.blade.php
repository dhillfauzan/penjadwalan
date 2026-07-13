@extends('layout.admin')

@section('page-title', 'Edit Jam Pelajaran')

@section('content')
<div class="container-fluid">
    <!-- Action Card -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body d-flex justify-content-between align-items-center p-4">
            <div>
                <h5 class="font-weight-bold text-dark mb-1"><i class="fas fa-edit text-warning me-2"></i>Edit Jam Pelajaran</h5>
                <p class="text-muted mb-0 small">Perbarui data slot jam pelajaran yang sudah ada.</p>
            </div>
            <a href="{{ route('admin.jam-pelajaran.index') }}" class="btn btn-light d-flex align-items-center gap-2" style="border-radius: 10px; border: 1px solid #e2e8f0;">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Form Card -->
    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-body p-4">
            <form action="{{ route('admin.jam-pelajaran.update', $jamPelajaran) }}" method="POST">
                @csrf @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label font-weight-bold">Hari <span class="text-danger">*</span></label>
                        <select name="hari" class="form-select" required style="border-radius: 10px; padding: 0.6rem 1rem;">
                            <option value="Senin" {{ $jamPelajaran->hari == 'Senin' ? 'selected' : '' }}>Senin</option>
                            <option value="Selasa" {{ $jamPelajaran->hari == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                            <option value="Rabu" {{ $jamPelajaran->hari == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                            <option value="Kamis" {{ $jamPelajaran->hari == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                            <option value="Jumat" {{ $jamPelajaran->hari == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label font-weight-bold">Jam Ke- <span class="text-danger">*</span></label>
                        <input type="number" name="jam_ke" class="form-control" value="{{ $jamPelajaran->jam_ke }}" required style="border-radius: 10px; padding: 0.6rem 1rem;">
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label font-weight-bold">Waktu Mulai <span class="text-danger">*</span></label>
                        <input type="time" name="waktu_mulai" class="form-control" value="{{ $jamPelajaran->waktu_mulai }}" required style="border-radius: 10px; padding: 0.6rem 1rem;">
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label font-weight-bold">Waktu Selesai <span class="text-danger">*</span></label>
                        <input type="time" name="waktu_selesai" class="form-control" value="{{ $jamPelajaran->waktu_selesai }}" required style="border-radius: 10px; padding: 0.6rem 1rem;">
                    </div>
                </div>
                <div class="text-end mt-2">
                    <button type="submit" class="btn btn-primary px-4 py-2" style="border-radius: 10px;">
                        <i class="fas fa-save me-1"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection