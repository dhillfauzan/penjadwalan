@extends('layout.admin')

@section('content')
<div class="container-fluid">
    <!-- Action Card -->
    <div class="card border-0 shadow-sm mb-4 d-print-none" style="border-radius: 16px;">
        <div class="card-body d-flex justify-content-between align-items-center p-4">
            <div>
                <h5 class="font-weight-bold text-dark mb-1"><i class="fas fa-calendar-alt text-primary me-2"></i>Hasil Jadwal Pelajaran (Semua Kelas)</h5>
                <p class="text-muted mb-0 small">Lihat keseluruhan jadwal pelajaran untuk semua kelas yang telah digenerate oleh sistem.</p>
            </div>
            <button onclick="window.print()" class="btn btn-outline-secondary d-flex align-items-center gap-2" style="border-radius: 10px; padding: 0.6rem 1.4rem;">
                <i class="fas fa-print"></i> Cetak Jadwal
            </button>
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

    <!-- Pilih Kelas & Tampilan Matriks -->
    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-body">
            @if($jadwals->isEmpty())
                <div class="text-center py-5">
                    <img src="https://cdn-icons-png.flaticon.com/512/2693/2693507.png" alt="No Schedule" style="max-width: 130px; opacity: 0.65;" class="mb-4">
                    <h4 class="text-secondary font-weight-bold">Belum Ada Jadwal Pelajaran</h4>
                    <p class="text-muted small col-md-6 mx-auto mb-4">Sistem penjadwalan masih kosong. Silakan hubungi admin sekolah untuk menggenerate jadwal terlebih dahulu.</p>
                </div>
            @else
                <!-- Dropdown Pilih Kelas -->
                <div class="mb-4 d-flex align-items-center gap-3 d-print-none">
                    <label for="selectKelas" class="fw-bold text-dark">Pilih Kelas :</label>
                    <select id="selectKelas" class="form-select w-auto" style="border-radius: 10px;">
                        @foreach($kelasList as $kelas)
                            <option value="{{ $kelas->getKey() }}" {{ $loop->first ? 'selected' : '' }}>
                                Kelas {{ $kelas->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tabel Matriks Jadwal -->
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
                                                        <div class="fw-semibold text-dark d-print-none">{{ $jadwal->mataPelajaran->nama_mapel ?? '-' }}</div>
                                                        <div class="fw-bold text-dark d-none d-print-block print-mapel">{{ $jadwal->mataPelajaran->kode_mapel ?? '-' }}</div>
                                                        <small class="text-muted d-block mt-1 print-guru">{{ $jadwal->guru->nama_guru ?? '-' }}</small>
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
        /* Sembunyikan elemen navigasi bawaan template */
        aside.sidebar, nav.iq-navbar, .sidebar-footer, .iq-banner {
            display: none !important;
        }
        
        /* Reset struktur utama */
        .main-content {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            min-height: auto !important;
        }
        
        body {
            background-color: #ffffff !important;
        }
        
        /* Hilangkan padding dan border pada container/card */
        .container-fluid, .card, .card-body {
            padding: 0 !important;
            margin: 0 !important;
            box-shadow: none !important;
            border: none !important;
            background: transparent !important;
        }
        
        /* Cegah tabel terpotong karena overflow */
        .table-responsive {
            overflow: visible !important;
        }
        
        .table {
            width: 100% !important;
            max-width: 100% !important;
            border-collapse: collapse !important;
        }
        
        .table th, .table td {
            padding: 4px !important;
            font-size: 10px !important; /* Font dikecilkan */
            border: 1px solid #dee2e6 !important;
            vertical-align: middle !important;
            white-space: normal !important;
        }

        .print-mapel {
            font-size: 11px !important;
            margin-bottom: 2px !important;
        }

        .print-guru {
            font-size: 8.5px !important; /* Font guru sangat kecil agar muat */
            line-height: 1.1 !important;
        }
        
        /* Set orientasi dan margin kertas default */
        @page {
            size: A4 portrait;
            margin: 5mm;
        }
    }
</style>

<script>
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
