@extends('layout.admin')

@section('page-title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px; overflow: hidden;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="p-3 rounded-3" style="background-color: #eff6ff; color: #2563eb;">
                            <i class="fas fa-chalkboard-teacher fa-2x"></i>
                        </div>
                        <div>
                            <span class="text-muted small d-block">Total Guru</span>
                            <h3 class="font-weight-bold text-dark mb-0">{{ $totalGuru }}</h3>
                        </div>
                    </div>
                </div>
                <div style="height: 4px; background: linear-gradient(90deg, #2563eb, #60a5fa);"></div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px; overflow: hidden;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="p-3 rounded-3" style="background-color: #f0fdf4; color: #16a34a;">
                            <i class="fas fa-book-open fa-2x"></i>
                        </div>
                        <div>
                            <span class="text-muted small d-block">Mata Pelajaran</span>
                            <h3 class="font-weight-bold text-dark mb-0">{{ $totalMapel }}</h3>
                        </div>
                    </div>
                </div>
                <div style="height: 4px; background: linear-gradient(90deg, #16a34a, #4ade80);"></div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px; overflow: hidden;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="p-3 rounded-3" style="background-color: #faf5ff; color: #9333ea;">
                            <i class="fas fa-door-open fa-2x"></i>
                        </div>
                        <div>
                            <span class="text-muted small d-block">Jumlah Kelas</span>
                            <h3 class="font-weight-bold text-dark mb-0">{{ $totalKelas }}</h3>
                        </div>
                    </div>
                </div>
                <div style="height: 4px; background: linear-gradient(90deg, #9333ea, #c084fc);"></div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px; overflow: hidden;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="p-3 rounded-3" style="background-color: #fff7ed; color: #ea580c;">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                        <div>
                            <span class="text-muted small d-block">Jam Pelajaran</span>
                            <h3 class="font-weight-bold text-dark mb-0">{{ $totalJam }}</h3>
                        </div>
                    </div>
                </div>
                <div style="height: 4px; background: linear-gradient(90deg, #ea580c, #fb923c);"></div>
            </div>
        </div>
    </div>

    <!-- Quick Access Cards -->
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                <div class="card-header bg-white py-3 border-0" style="border-radius: 16px 16px 0 0;">
                    <h5 class="mb-0 font-weight-bold text-dark d-flex align-items-center gap-2">
                        <i class="fas fa-bolt text-warning"></i> Akses Cepat
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex flex-column gap-3">
                        <a href="{{ route('admin.jadwal.index') }}" class="d-flex align-items-center gap-3 p-3 rounded-3 text-decoration-none" style="background-color: #f8fafc; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#f1f5f9'" onmouseout="this.style.backgroundColor='#f8fafc'">
                            <div class="p-2 rounded-3" style="background-color: #dbeafe; color: #2563eb;">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 text-dark font-weight-bold">Lihat Hasil Jadwal</h6>
                                <small class="text-muted">Lihat jadwal pelajaran per kelas yang sudah digenerate</small>
                            </div>
                            <i class="fas fa-chevron-right text-muted ms-auto"></i>
                        </a>
                        <a href="{{ route('admin.optimasi') }}" class="d-flex align-items-center gap-3 p-3 rounded-3 text-decoration-none" style="background-color: #f8fafc; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#f1f5f9'" onmouseout="this.style.backgroundColor='#f8fafc'">
                            <div class="p-2 rounded-3" style="background-color: #fce7f3; color: #db2777;">
                                <i class="fas fa-cogs"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 text-dark font-weight-bold">Optimasi PSO</h6>
                                <small class="text-muted">Atur parameter dan jalankan algoritma penjadwalan otomatis</small>
                            </div>
                            <i class="fas fa-chevron-right text-muted ms-auto"></i>
                        </a>
                        <a href="{{ route('admin.guru.index') }}" class="d-flex align-items-center gap-3 p-3 rounded-3 text-decoration-none" style="background-color: #f8fafc; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#f1f5f9'" onmouseout="this.style.backgroundColor='#f8fafc'">
                            <div class="p-2 rounded-3" style="background-color: #d1fae5; color: #059669;">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 text-dark font-weight-bold">Kelola Tenaga Pengajar</h6>
                                <small class="text-muted">Tambah, edit, atau hapus data guru dan alokasi mengajar</small>
                            </div>
                            <i class="fas fa-chevron-right text-muted ms-auto"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                <div class="card-header bg-white py-3 border-0" style="border-radius: 16px 16px 0 0;">
                    <h5 class="mb-0 font-weight-bold text-dark d-flex align-items-center gap-2">
                        <i class="fas fa-info-circle text-primary"></i> Tentang Sistem
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="p-4 rounded-3 text-center text-white mb-3" style="background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);">
                        <i class="fas fa-school fa-3x mb-3"></i>
                        <h5 class="font-weight-bold">Sistem Penjadwalan Sekolah</h5>
                        <p class="small mb-0 opacity-90">Sistem untuk menghasilkan jadwal pelajaran bebas bentrok secara otomatis.</p>
                    </div>
                    <div class="d-flex flex-column gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-check-circle text-success"></i>
                            <span class="small text-dark">Penjadwalan otomatis tanpa bentrok guru</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-check-circle text-success"></i>
                            <span class="small text-dark">Distribusi mata pelajaran merata ke seluruh jam</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-check-circle text-success"></i>
                            <span class="small text-dark">Mendukung manajemen multi-kelas per guru</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection