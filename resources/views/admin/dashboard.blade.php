@extends('layout.admin')

@section('page-title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Stats Cards -->
    <div class="row mb-3">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100 stat-card" style="border-radius: 12px; overflow: hidden;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="p-2 rounded-3" style="background-color: #eff6ff; color: #2563eb;">
                            <i class="fas fa-chalkboard-teacher fa-lg" style="width: 24px; text-align: center;"></i>
                        </div>
                        <div>
                            <span class="text-muted d-block" style="font-size: 0.75rem; font-weight: 500;">Total Guru</span>
                            <h4 class="font-weight-bold text-dark mb-0">{{ $totalGuru }}</h4>
                        </div>
                    </div>
                </div>
                <div style="height: 3px; background: linear-gradient(90deg, #2563eb, #60a5fa);"></div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100 stat-card" style="border-radius: 12px; overflow: hidden;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="p-2 rounded-3" style="background-color: #f0fdf4; color: #16a34a;">
                            <i class="fas fa-book-open fa-lg" style="width: 24px; text-align: center;"></i>
                        </div>
                        <div>
                            <span class="text-muted d-block" style="font-size: 0.75rem; font-weight: 500;">Mata Pelajaran</span>
                            <h4 class="font-weight-bold text-dark mb-0">{{ $totalMapel }}</h4>
                        </div>
                    </div>
                </div>
                <div style="height: 3px; background: linear-gradient(90deg, #16a34a, #4ade80);"></div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100 stat-card" style="border-radius: 12px; overflow: hidden;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="p-2 rounded-3" style="background-color: #faf5ff; color: #9333ea;">
                            <i class="fas fa-door-open fa-lg" style="width: 24px; text-align: center;"></i>
                        </div>
                        <div>
                            <span class="text-muted d-block" style="font-size: 0.75rem; font-weight: 500;">Jumlah Kelas</span>
                            <h4 class="font-weight-bold text-dark mb-0">{{ $totalKelas }}</h4>
                        </div>
                    </div>
                </div>
                <div style="height: 3px; background: linear-gradient(90deg, #9333ea, #c084fc);"></div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100 stat-card" style="border-radius: 12px; overflow: hidden;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="p-2 rounded-3" style="background-color: #fff7ed; color: #ea580c;">
                            <i class="fas fa-clock fa-lg" style="width: 24px; text-align: center;"></i>
                        </div>
                        <div>
                            <span class="text-muted d-block" style="font-size: 0.75rem; font-weight: 500;">Jam Pelajaran</span>
                            <h4 class="font-weight-bold text-dark mb-0">{{ $totalJam }}</h4>
                        </div>
                    </div>
                </div>
                <div style="height: 3px; background: linear-gradient(90deg, #ea580c, #fb923c);"></div>
            </div>
        </div>
    </div>

    <!-- Quick Access Cards -->
    <div class="row">
        <div class="col-lg-6 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius:16px;">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="fw-bold mb-0 d-flex align-items-center">
                        <i class="fas fa-bolt text-warning me-2"></i>
                        Akses Cepat
                    </h6>
                </div>
                <div class="card-body px-3 pb-3 pt-0">
                    <a href="{{ route('admin.jadwal.index') }}" class="quick-menu text-decoration-none mb-2">
                        <div class="icon-box bg-primary-subtle text-primary">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="fw-bold text-dark mb-0" style="font-size: 0.9rem;">Hasil Jadwal</h6>
                            <small class="text-muted" style="font-size: 0.75rem;">Lihat jadwal pelajaran yang dihasilkan.</small>
                        </div>
                        <i class="fas fa-chevron-right text-muted small"></i>
                    </a>

                    <a href="{{ route('admin.jadwal.semua-guru') }}" class="quick-menu text-decoration-none mb-2">
                        <div class="icon-box bg-info-subtle text-info">
                            <i class="fas fa-users-viewfinder"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="fw-bold text-dark mb-0" style="font-size: 0.9rem;">Jadwal Semua Guru</h6>
                            <small class="text-muted" style="font-size: 0.75rem;">Lihat jadwal mengajar seluruh guru.</small>
                        </div>
                        <i class="fas fa-chevron-right text-muted small"></i>
                    </a>

                    <a href="{{ route('admin.guru.index') }}" class="quick-menu text-decoration-none">
                        <div class="icon-box bg-success-subtle text-success">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="fw-bold text-dark mb-0" style="font-size: 0.9rem;">Data Guru</h6>
                            <small class="text-muted" style="font-size: 0.75rem;">Kelola data guru dan bebannya.</small>
                        </div>
                        <i class="fas fa-chevron-right text-muted small"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius:16px;">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="fw-bold mb-0 d-flex align-items-center">
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        Tentang Sistem
                    </h6>
                </div>
                <div class="card-body px-3 pb-3 pt-0">
                    <div class="text-center text-white p-3 mb-3 about-banner d-flex align-items-center text-start gap-3" style="border-radius:12px;">
                        <span class="d-inline-flex justify-content-center align-items-center rounded-circle flex-shrink-0" style="width:45px;height:45px;background:rgba(255,255,255,.2);">
                            <i class="fas fa-school text-white"></i>
                        </span>
                        <div>
                            <h6 class="fw-bold mb-1" style="font-size: 0.95rem;">Sistem Penjadwalan</h6>
                            <p class="mb-0" style="opacity:.9; font-size: 0.75rem;">Membantu menyusun jadwal pelajaran bebas bentrok dan efisien.</p>
                        </div>
                    </div>

                    <div class="list-group list-group-flush">
                        <div class="list-group-item border-0 px-0 py-2 d-flex align-items-center">
                            <div class="me-3">
                                <span class="bg-success text-white rounded-circle d-flex justify-content-center align-items-center" style="width:24px;height:24px;font-size:10px;">
                                    <i class="fas fa-check"></i>
                                </span>
                            </div>
                            <div>
                                <strong style="font-size:0.85rem;">Penjadwalan Otomatis</strong><br>
                                <small class="text-muted" style="font-size: 0.7rem;">Menghindari bentrok jadwal guru dan mata pelajaran.</small>
                            </div>
                        </div>
                        <div class="list-group-item border-0 px-0 py-2 d-flex align-items-center">
                            <div class="me-3">
                                <span class="bg-success text-white rounded-circle d-flex justify-content-center align-items-center" style="width:24px;height:24px;font-size:10px;">
                                    <i class="fas fa-check"></i>
                                </span>
                            </div>
                            <div>
                                <strong style="font-size:0.85rem;">Distribusi Jam Merata</strong><br>
                                <small class="text-muted" style="font-size: 0.7rem;">Mata pelajaran tersebar seimbang pada setiap hari.</small>
                            </div>
                        </div>
                        <div class="list-group-item border-0 px-0 py-2 d-flex align-items-center">
                            <div class="me-3">
                                <span class="bg-success text-white rounded-circle d-flex justify-content-center align-items-center" style="width:24px;height:24px;font-size:10px;">
                                    <i class="fas fa-check"></i>
                                </span>
                            </div>
                            <div>
                                <strong style="font-size:0.85rem;">Multi Kelas</strong><br>
                                <small class="text-muted" style="font-size: 0.7rem;">Mendukung guru yang mengajar di beberapa kelas.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.stat-card {
    transition: all 0.3s ease;
}
.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.08) !important;
}

.quick-menu {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 14px;
    border-radius: 12px;
    background: #f8fafc;
    transition: all 0.25s ease;
    border: 1px solid transparent;
}
.quick-menu:hover {
    background: #ffffff;
    border-color: #e5e7eb;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
}
.icon-box {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    flex-shrink: 0;
    transition: all 0.3s ease;
}
.quick-menu:hover .icon-box {
    transform: scale(1.1);
}
.quick-menu .fa-chevron-right {
    transition: 0.25s;
}
.quick-menu:hover .fa-chevron-right {
    transform: translateX(4px);
}

.about-banner {
    background: linear-gradient(135deg, #4f46e5, #6366f1); 
    position: relative;
    overflow: hidden;
}
.about-banner::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 60%);
    transform: rotate(30deg);
    pointer-events: none;
}
</style>
@endpush