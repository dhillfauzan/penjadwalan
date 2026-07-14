@extends('layout.admin')

@section('page-title', 'Hasil Jadwal')

@section('content')
<div class="container-fluid">
    <!-- Action Card -->
    <div class="card border-0 shadow-sm mb-4 d-print-none" style="border-radius: 16px;">
        <div class="card-body d-flex justify-content-between align-items-center p-4">
            <div>
                <h5 class="font-weight-bold text-dark mb-1"><i class="fas fa-magic text-primary me-2"></i>Jadwal Pelajaran Otomatis</h5>
                <p class="text-muted mb-0 small">Kelola dan susun jadwal sekolah Anda menggunakan kecerdasan buatan Particle Swarm Optimization (PSO).</p>
            </div>
            <div>
                <button onclick="window.print()" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2 me-2" style="border-radius: 10px; padding: 0.6rem 1.4rem;">
                    <i class="fas fa-print"></i> Cetak Jadwal
                </button>
                <form action="{{ route('admin.jadwal.generate') }}" method="POST" class="d-inline" onsubmit="showLoadingState(event)">
                    @csrf
                    <button type="submit" class="btn btn-success d-inline-flex align-items-center gap-2" id="btn-generate" style="border-radius: 10px; padding: 0.6rem 1.4rem;">
                        <i class="fas fa-sync-alt" id="icon-sync"></i>
                        <span id="text-generate">Generate Jadwal (PSO)</span>
                    </button>
                </form>
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

    <!-- Loading Indicator -->
    <div id="loading-card" class="card border-0 shadow-sm mb-4 d-none d-print-none" style="border-radius: 16px; background-color: #f8fafc; border-left: 5px solid #0ea5e9;">
        <div class="card-body p-4 d-flex align-items-center gap-3">
            <div class="spinner-border text-info" role="status" style="width: 2.5rem; height: 2.5rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
            <div>
                <h5 class="font-weight-bold text-dark mb-1">Sedang Menyusun Jadwal...</h5>
                <p class="text-secondary small mb-0">Algoritma PSO sedang berjalan mencari kombinasi jadwal terbaik. Proses ini membutuhkan waktu beberapa detik.</p>
            </div>
        </div>
    </div>

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

    <!-- ============ WEB VIEW (hidden on print) ============ -->
    <div class="card border-0 shadow-sm d-print-none" style="border-radius: 16px;">
        <div class="card-body">
            @if($jadwals->isEmpty())
                <div class="text-center py-5">
                    <img src="https://cdn-icons-png.flaticon.com/512/2693/2693507.png" alt="No Schedule" style="max-width: 130px; opacity: 0.65;" class="mb-4">
                    <h4 class="text-secondary font-weight-bold">Belum Ada Jadwal Pelajaran</h4>
                    <p class="text-muted small col-md-6 mx-auto mb-4">Sistem penjadwalan masih kosong. Silakan klik tombol <strong>Generate Jadwal (PSO)</strong> di atas untuk membuat jadwal sekolah otomatis dengan optimasi bebas bentrok.</p>
                    <form action="{{ route('admin.jadwal.generate') }}" method="POST" onsubmit="showLoadingState(event)">
                        @csrf
                        <button type="submit" class="btn btn-primary px-4 py-2" style="border-radius: 10px;">
                            <i class="fas fa-sync-alt me-1"></i> Mulai Generate Sekarang
                        </button>
                    </form>
                </div>
            @else
                <!-- Dropdown Pilih Kelas -->
                <div class="mb-4 d-flex align-items-center gap-3">
                    <label for="selectKelas" class="fw-bold text-dark">Pilih Kelas :</label>
                    <select id="selectKelas" class="form-select w-auto" style="border-radius: 10px;">
                        @foreach($kelasList as $kelas)
                            <option value="{{ $kelas->getKey() }}" {{ $loop->first ? 'selected' : '' }}>
                                Kelas {{ $kelas->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tabel Matriks Jadwal per Kelas -->
                <div class="table-responsive" id="timetable-container">
                    @foreach($kelasList as $kelas)
                        <div class="class-schedule" data-kelas-id="{{ $kelas->getKey() }}" style="{{ $loop->first ? '' : 'display: none;' }}">
                            <h5 class="font-weight-bold text-dark mb-3">
                                <i class="fas fa-chalkboard-user me-2"></i> Jadwal Kelas {{ $kelas->nama_kelas }}
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
                                                    $jadwal = $scheduleByClass[$kelas->getKey()][$hari][$jamKe] ?? null;
                                                @endphp
                                                <td class="p-2" style="vertical-align: middle;">
                                                    @if($jadwal)
                                                        <div class="fw-semibold text-dark">{{ $jadwal->mataPelajaran->nama_mapel ?? '-' }}</div>
                                                        <small class="text-muted d-block mt-1">{{ $jadwal->guru->nama_guru ?? '-' }}</small>
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

    <!-- ============ PRINT VIEW - Format Excel (hidden on screen) ============ -->
    @if(!$jadwals->isEmpty())
    <div id="print-area">
        <!-- Header Sekolah -->
        <div class="print-header">
            <div style="font-size: 12px; font-weight: bold; text-transform: uppercase;">Sistem Penjadwalan Sekolah</div>
            <div style="font-size: 18px; font-weight: bold; text-transform: uppercase; letter-spacing: 2px; margin: 4px 0;">Jadwal Pelajaran</div>
            <div style="font-size: 11px;">Tahun Pelajaran {{ date('Y') }} / {{ date('Y') + 1 }}</div>
        </div>

        <!-- Tabel Master Jadwal (Format Excel) -->
        <table class="print-master-table">
            <thead>
                <tr>
                    <th rowspan="2" class="col-hari">HARI</th>
                    <th rowspan="2" class="col-waktu">WAKTU</th>
                    <th rowspan="2" class="col-jam">JAM<br>KE</th>
                    @php
                        $kelasGrouped = $kelasList->groupBy(function($k) {
                            preg_match('/(\d+)/', $k->nama_kelas, $m);
                            return $m[1] ?? '';
                        });
                    @endphp
                    @foreach($kelasGrouped as $tingkat => $kelasGroup)
                        <th colspan="{{ $kelasGroup->count() }}" class="col-kelas-header">KELAS {{ $tingkat }}</th>
                    @endforeach
                </tr>
                <tr>
                    @foreach($kelasList as $kelas)
                        <th class="col-kelas">{{ $kelas->nama_kelas }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @php 
                    $hariList = ['Senin','Selasa','Rabu','Kamis','Jumat']; 
                @endphp
                @foreach($hariList as $hariIdx => $hari)
                    @php 
                        $jamList = $jamPelajaranByHari[$hari] ?? collect(); 
                        $hariRowCount = count($jamList);
                    @endphp
                    @if($hariRowCount == 0) @continue @endif

                    @foreach($jamList as $jIdx => $jam)
                        <tr class="{{ $hariIdx % 2 == 0 ? 'row-even-day' : 'row-odd-day' }}">
                            @if($jIdx === 0)
                                <td rowspan="{{ $hariRowCount }}" class="cell-hari {{ $hariIdx % 2 == 0 ? 'hari-purple' : 'hari-green' }}">
                                    <div class="hari-text">{{ strtoupper($hari) }}</div>
                                </td>
                            @endif

                            <td class="cell-waktu">{{ \Carbon\Carbon::parse($jam->waktu_mulai)->format('H.i') }}-{{ \Carbon\Carbon::parse($jam->waktu_selesai)->format('H.i') }}</td>
                            <td class="cell-jam">{{ $jam->jam_ke }}</td>

                            @foreach($kelasList as $kelas)
                                @php
                                    $jadwal = $scheduleByClass[$kelas->getKey()][$hari][$jam->jam_ke] ?? null;
                                    $cellContent = '';
                                    $isFilled = false;

                                    if ($jadwal && $jadwal->guru) {
                                        $guruId = $jadwal->guru->getKey();
                                        $mapData = $guruMapping[$guruId] ?? null;
                                        if ($mapData) {
                                            $isFilled = true;
                                            $cellContent = $mapData['no'];
                                            if ($mapData['is_multiple']) {
                                                $cellContent .= '(' . ($jadwal->mataPelajaran->kode_mapel ?? '') . ')';
                                            }
                                        }
                                    }
                                @endphp
                                <td class="cell-mapel {{ $isFilled ? 'cell-filled' : '' }}">
                                    {{ $cellContent }}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>

        <div style="display: flex; gap: 40px; margin-top: 15px;">
            <!-- Tabel Daftar Guru -->
            <div style="flex: 1;">
                <table class="print-guru-table">
                    <thead>
                        <tr>
                            <th class="col-no-guru">NO</th>
                            <th class="col-nama-guru">NAMA GURU</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($uniqueGurus as $guru)
                            <tr>
                                <td style="text-align: center; font-weight: bold;">{{ $guru['no'] }}</td>
                                <td style="text-align: left; padding-left: 4px;">{{ $guru['nama_format'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Keterangan Waktu Cetak -->
            <div style="flex: 1; text-align: right;">
                <p style="font-size: 10px;">Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
    /* ====== PRINT TABLE STYLES (always defined, only visible on print) ====== */
    .print-master-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 7px;
        line-height: 1.2;
    }
    .print-master-table th,
    .print-master-table td {
        border: 1px solid #333;
        padding: 1px 2px;
        text-align: center;
        vertical-align: middle;
    }
    .print-master-table thead th {
        background-color: #d9e2f3;
        font-weight: bold;
        font-size: 7px;
        padding: 3px 1px;
    }
    .col-hari { width: 20px; }
    .col-waktu { width: 50px; }
    .col-jam { width: 20px; }
    .col-kelas-header { background-color: #b4c6e7 !important; font-size: 7.5px !important; }
    .col-kelas { width: 22px; font-size: 6.5px; background-color: #d6dce4 !important; }

    .cell-hari { font-weight: bold; font-size: 7px; width: 20px; }
    .hari-purple { background-color: #e2d0f0; }
    .hari-green { background-color: #d5e8d4; }
    .cell-waktu { font-size: 5.5px !important; white-space: nowrap; }
    .cell-jam { font-weight: bold; }
    .cell-mapel { font-size: 6px; min-width: 18px; }
    .cell-filled { background-color: #fff2cc; font-weight: bold; font-size: 6.5px; }

    .print-guru-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 7px;
    }
    .print-guru-table th, .print-guru-table td {
        border: 1px solid #333;
        padding: 2px;
    }
    .print-guru-table th {
        background-color: #d9e2f3;
        font-weight: bold;
    }
    .col-no-guru { width: 30px; }
    .col-nama-guru { text-align: left; }

    .hari-text {
        writing-mode: vertical-rl;
        text-orientation: mixed;
        transform: rotate(180deg);
        letter-spacing: 1px;
        font-size: 7px;
        white-space: nowrap;
    }

    .print-header {
        text-align: center;
        margin-bottom: 10px;
        border-bottom: 3px double #000;
        padding-bottom: 8px;
    }

    .print-footer {
        margin-top: 15px;
        font-size: 8px;
    }
    .keterangan-list {
        display: flex;
        flex-wrap: wrap;
        gap: 4px 12px;
        margin-top: 4px;
    }
    .keterangan-item {
        font-size: 7.5px;
    }

    /* ====== Screen: hide print area ====== */
    @media screen {
        #print-area {
            display: none !important;
        }
    }

    /* ====== Print styles ====== */
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
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .container-fluid, .card, .card-body {
            padding: 0 !important;
            margin: 0 !important;
            box-shadow: none !important;
            border: none !important;
            background: transparent !important;
        }

        #print-area {
            display: block !important;
        }

        @page {
            size: A4 landscape;
            margin: 4mm;
        }

        .print-master-table {
            page-break-inside: auto;
        }
        .print-master-table tr {
            page-break-inside: avoid;
        }
    }
</style>

<script>
    function showLoadingState(event) {
        if (!confirm('Generate jadwal baru akan menghapus jadwal lama yang terdaftar. Lanjutkan?')) {
            event.preventDefault();
            return false;
        }
        const btn = document.getElementById('btn-generate');
        const icon = document.getElementById('icon-sync');
        const text = document.getElementById('text-generate');
        btn.classList.add('disabled');
        btn.style.opacity = '0.75';
        icon.classList.add('fa-spin');
        text.textContent = 'Menghitung PSO...';
        const loadingCard = document.getElementById('loading-card');
        if (loadingCard) {
            loadingCard.classList.remove('d-none');
            loadingCard.scrollIntoView({ behavior: 'smooth' });
        }
    }

    // Event change dropdown kelas
    document.addEventListener('DOMContentLoaded', function() {
        const selectKelas = document.getElementById('selectKelas');
        if (selectKelas) {
            selectKelas.addEventListener('change', function() {
                const selectedId = this.value;
                document.querySelectorAll('.class-schedule').forEach(el => {
                    if (el.dataset.kelasId == selectedId) {
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