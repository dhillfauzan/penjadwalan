@extends('layout.admin')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h2 class="font-weight-bold text-dark">Jadwal Mengajar Anda</h2>
                <p class="text-muted mb-0">Selamat datang, <strong>{{ $guru->nama_guru }}</strong>. Berikut adalah daftar jadwal mengajar Anda di sekolah.</p>
            </div>
            <div class="bg-white p-3 rounded-3 shadow-sm border">
                <span class="text-secondary small d-block">NIP Anda:</span>
                <span class="font-weight-bold text-dark">{{ $guru->nip ?? '-' }}</span>
            </div>
        </div>
    </div>

    <!-- Quick Stat Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-white border-0 shadow-sm p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-3">
                        <i class="fas fa-calendar-week fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Total Jam Mengajar</h6>
                        <h3 class="mb-0 font-weight-bold text-dark">{{ $jadwals->count() }} Jam</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-white border-0 shadow-sm p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-success bg-opacity-10 text-success p-3 rounded-3">
                        <i class="fas fa-book fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Mata Pelajaran Diajar</h6>
                        <h3 class="mb-0 font-weight-bold text-dark">
                            {{ $jadwals->pluck('mataPelajaran.nama_mapel')->unique()->count() }} Mapel
                        </h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-white border-0 shadow-sm p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-info bg-opacity-10 text-info p-3 rounded-3">
                        <i class="fas fa-school fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Total Kelas Diajar</h6>
                        <h3 class="mb-0 font-weight-bold text-dark">
                            {{ $jadwals->pluck('kelas.nama_kelas')->unique()->count() }} Kelas
                        </h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Schedule Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-0">
            <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-list me-2"></i>Daftar Jadwal Mingguan</h5>
            <button onclick="window.print()" class="btn btn-outline-secondary btn-sm" style="border-radius: 8px;">
                <i class="fas fa-print me-1"></i> Cetak Jadwal
            </button>
        </div>
        <div class="card-body p-0">
            @if($jadwals->isEmpty())
                <div class="text-center py-5">
                    <img src="https://cdn-icons-png.flaticon.com/512/4076/4076403.png" alt="Empty Schedule" style="max-width: 120px;" class="mb-3 opacity-75">
                    <h5 class="text-secondary font-weight-bold">Belum Ada Jadwal Mengajar</h5>
                    <p class="text-muted">Jadwal mengajar belum digenerate oleh sistem atau Anda belum memiliki alokasi mengajar.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-secondary">
                            <tr>
                                <th class="ps-4 border-0">Hari</th>
                                <th class="border-0">Jam Ke</th>
                                <th class="border-0">Waktu</th>
                                <th class="border-0">Mata Pelajaran</th>
                                <th class="border-0">Kelas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $colors = [
                                    'Senin' => 'primary',
                                    'Selasa' => 'success',
                                    'Rabu' => 'warning text-dark',
                                    'Kamis' => 'info text-dark',
                                    'Jumat' => 'danger'
                                ];
                            @endphp
                            @foreach($jadwals as $jadwal)
                                <tr>
                                    <td class="ps-4 font-weight-bold">
                                        <span class="badge bg-{{ $colors[$jadwal->jamPelajaran->hari] ?? 'secondary' }} rounded-pill px-3 py-2">
                                            {{ $jadwal->jamPelajaran->hari }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="bg-light text-dark rounded-circle d-flex align-items-center justify-content-center font-weight-bold" style="width: 32px; height: 32px; font-size: 0.95rem;">
                                                {{ $jadwal->jamPelajaran->jam_ke }}
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <i class="far fa-clock text-muted me-2"></i>
                                        <span class="text-dark">{{ \Carbon\Carbon::parse($jadwal->jamPelajaran->waktu_mulai)->format('H:i') }}</span>
                                        <span class="text-muted mx-1">-</span>
                                        <span class="text-dark">{{ \Carbon\Carbon::parse($jadwal->jamPelajaran->waktu_selesai)->format('H:i') }}</span>
                                    </td>
                                    <td>
                                        <span class="font-weight-bold text-dark">{{ $jadwal->mataPelajaran->nama_mapel }}</span>
                                        <span class="text-muted d-block small">Kode: {{ $jadwal->mataPelajaran->kode_mapel }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border px-3 py-2 rounded">
                                            <i class="fas fa-door-open me-1 text-primary"></i> {{ $jadwal->kelas->nama_kelas }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    @media print {
        .sidebar, .navbar-top, button, .btn {
            display: none !important;
        }
        .main-content {
            margin-left: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }
        body {
            background-color: #ffffff;
        }
        .card {
            box-shadow: none !important;
            border: none !important;
        }
    }
</style>
@endsection
