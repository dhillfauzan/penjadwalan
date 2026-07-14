@extends('layout.admin')

@section('page-title', 'Jadwal Seluruh Guru')

@section('content')
<div class="container-fluid">
    <!-- Action Card -->
    <div class="card border-0 shadow-sm mb-4 d-print-none" style="border-radius: 16px;">
        <div class="card-body d-flex justify-content-between align-items-center p-4">
            <div>
                <h5 class="font-weight-bold text-dark mb-1"><i class="fas fa-users-viewfinder text-primary me-2"></i>Jadwal Mengajar Semua Guru</h5>
                <p class="text-muted mb-0 small">Lihat hasil jadwal mengajar secara lengkap untuk setiap guru.</p>
            </div>
            <div>
                <button onclick="window.print()" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2" style="border-radius: 10px; padding: 0.6rem 1.4rem;">
                    <i class="fas fa-print"></i> Cetak Jadwal
                </button>
            </div>
        </div>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show d-flex align-items-center gap-2 mb-4 d-print-none" role="alert" style="border-radius: 12px;">
            <i class="fas fa-check-circle text-success"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm alert-dismissible fade show d-flex align-items-center gap-2 mb-4 d-print-none" role="alert" style="border-radius: 12px;">
            <i class="fas fa-exclamation-triangle text-danger"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="row mb-4 d-print-none">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100 p-3" style="border-radius: 16px;">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-3 rounded-3" style="background-color: #f0fdf4; color: #16a34a;">
                        <i class="fas fa-calendar-check fa-2x"></i>
                    </div>
                    <div>
                        <span class="text-muted small d-block">Total Slot Terjadwal</span>
                        <h4 class="font-weight-bold text-dark mb-0">{{ $jadwals->count() }} Slot</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100 p-3" style="border-radius: 16px;">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-3 rounded-3" style="background-color: #eff6ff; color: #2563eb;">
                        <i class="fas fa-user-tie fa-2x"></i>
                    </div>
                    <div>
                        <span class="text-muted small d-block">Guru Aktif</span>
                        <h4 class="font-weight-bold text-dark mb-0">{{ \App\Models\Guru::distinct('nip')->count('nip') }} Guru</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100 p-3" style="border-radius: 16px;">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-3 rounded-3" style="background-color: #faf5ff; color: #9333ea;">
                        <i class="fas fa-door-open fa-2x"></i>
                    </div>
                    <div>
                        <span class="text-muted small d-block">Jumlah Kelas</span>
                        <h4 class="font-weight-bold text-dark mb-0">{{ \App\Models\Kelas::count() }} Kelas</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100 p-3" style="border-radius: 16px;">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-3 rounded-3" style="background-color: #fff7ed; color: #ea580c;">
                        <i class="fas fa-book-open fa-2x"></i>
                    </div>
                    <div>
                        <span class="text-muted small d-block">Mata Pelajaran</span>
                        <h4 class="font-weight-bold text-dark mb-0">{{ \App\Models\MataPelajaran::count() }} Mapel</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Web View -->
    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-body">
            @if($jadwals->isEmpty())
                <div class="text-center py-5 d-print-none">
                    <img src="https://cdn-icons-png.flaticon.com/512/2693/2693507.png" alt="No Schedule" style="max-width: 130px; opacity: 0.65;" class="mb-4">
                    <h4 class="text-secondary font-weight-bold">Belum Ada Jadwal Pelajaran</h4>
                    <p class="text-muted small col-md-6 mx-auto mb-4">Sistem penjadwalan masih kosong. Silakan generate jadwal terlebih dahulu melalui menu Optimasi PSO.</p>
                </div>
            @else
                <!-- Dropdown Pilih Guru -->
                <div class="mb-4 d-flex align-items-center gap-3 d-print-none">
                    <label for="selectGuru" class="fw-bold text-dark">Pilih Guru :</label>
                    <select id="selectGuru" class="form-select w-auto" style="border-radius: 10px;">
                        @foreach($uniqueGurus as $ug)
                            <option value="{{ $ug->id }}" {{ $loop->first ? 'selected' : '' }}>
                                {{ $ug->nama_guru }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tabel Matriks Jadwal per Guru -->
                <div class="table-responsive" id="timetable-container">
                    @foreach($uniqueGurus as $ug)
                        <div class="guru-schedule" data-guru-id="{{ $ug->id }}" style="{{ $loop->first ? '' : 'display: none;' }}">
                            <h5 class="font-weight-bold text-dark mb-3">
                                <i class="fas fa-user-tie me-2"></i> Jadwal Mengajar {{ $ug->nama_asli }}
                            </h5>
                            <table class="table table-bordered text-center align-middle bg-white" style="border-radius: 12px; overflow: hidden;">
                                <thead class="table-primary">
                                    <tr>
                                        <th style="width: 80px;">Jam Ke</th>
                                        <th>Senin</th>
                                        <th>Selasa</th>
                                        <th>Rabu</th>
                                        <th>Kamis</th>
                                        <th>Jumat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($jamKeList as $jamKe)
                                        <tr>
                                            <td class="fw-bold bg-light">Jam Ke-{{ $jamKe }}</td>
                                            @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] as $hari)
                                                @php
                                                    $jadwal = $scheduleByGuru[$ug->id][$hari][$jamKe] ?? null;
                                                @endphp
                                                <td class="p-2" style="vertical-align: middle;">
                                                    @if($jadwal)
                                                        <div class="fw-bold text-dark mb-1">{{ $jadwal->mataPelajaran->kode_mapel ?? '-' }}</div>
                                                        <small class="text-muted d-block">Kelas {{ $jadwal->kelas->nama_kelas ?? '-' }}</small>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    @media print {
        aside.sidebar, nav.iq-navbar, .sidebar-footer, .iq-banner,
        .iq-navbar-header, .d-print-none {
            display: none !important;
        }

        .main-content {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            min-height: auto !important;
        }

        body {
            background-color: #ffffff !important;
        }

        .container-fluid, .card, .card-body {
            padding: 0 !important;
            margin: 0 !important;
            box-shadow: none !important;
            border: none !important;
            background: transparent !important;
        }

        .table-responsive {
            overflow: visible !important;
        }

        /* Show ALL guru schedules when printing */
        .guru-schedule {
            display: block !important;
            page-break-after: always;
            margin-bottom: 20px;
        }
        .guru-schedule:last-child {
            page-break-after: avoid;
        }

        .table {
            width: 100% !important;
            border-collapse: collapse !important;
        }

        .table th, .table td {
            padding: 4px !important;
            font-size: 10px !important;
            border: 1px solid #dee2e6 !important;
            vertical-align: middle !important;
        }

        @page {
            size: A4 portrait;
            margin: 8mm;
        }
    }
</style>

<script>
    // Event change dropdown guru
    document.addEventListener('DOMContentLoaded', function() {
        const selectGuru = document.getElementById('selectGuru');
        if (selectGuru) {
            selectGuru.addEventListener('change', function() {
                const selectedId = this.value;
                document.querySelectorAll('.guru-schedule').forEach(el => {
                    if (el.dataset.guruId == selectedId) {
                        el.style.display = 'block';
                    } else {
                        el.style.display = 'none';
                    }
                });
            });
        }
    });
</script>
@endsection