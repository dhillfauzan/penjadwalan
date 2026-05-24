@extends('layout.admin')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="font-weight-bold text-dark mb-1">Jadwal Pelajaran Otomatis</h2>
            <p class="text-muted mb-0">Kelola dan susun jadwal sekolah Anda menggunakan kecerdasan buatan Particle Swarm Optimization (PSO).</p>
        </div>
        <form action="{{ route('admin.jadwal.generate') }}" method="POST" class="d-inline" onsubmit="showLoadingState(event)">
            @csrf
            <button type="submit" class="btn btn-success d-flex align-items-center gap-2" id="btn-generate" style="border-radius: 10px; padding: 0.6rem 1.4rem;">
                <i class="fas fa-sync-alt" id="icon-sync"></i>
                <span id="text-generate">Generate Jadwal (PSO)</span>
            </button>
        </form>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert" style="border-radius: 12px;">
            <i class="fas fa-check-circle text-success"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert" style="border-radius: 12px;">
            <i class="fas fa-exclamation-triangle text-danger"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Loading Indicator -->
    <div id="loading-card" class="card border-0 shadow-sm mb-4 d-none" style="border-radius: 16px; background-color: #f8fafc; border-left: 5px solid #0ea5e9;">
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
    <div class="row mb-4">
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
                        <h4 class="font-weight-bold text-dark mb-0">{{ \App\Models\Guru::count() }} Guru</h4>
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
                            <option value="{{ $kelas->id }}" {{ $loop->first ? 'selected' : '' }}>
                                Kelas {{ $kelas->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tabel Matriks Jadwal -->
                <div class="table-responsive" id="timetable-container">
                    @foreach($kelasList as $kelas)
                        <div class="class-schedule" data-kelas-id="{{ $kelas->id }}" style="{{ $loop->first ? '' : 'display: none;' }}">
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
                                                    $jadwal = $scheduleByClass[$kelas->id][$hari][$jamKe] ?? null;
                                                @endphp
                                                <td class="p-2" style="vertical-align: middle;">
                                                    @if($jadwal)
                                                        <div class="fw-semibold text-dark">{{ $jadwal->mataPelajaran->nama_mapel ?? '-' }}</div>
                                                        <small class="text-muted">{{ $jadwal->guru->nama_guru ?? '-' }}</small>
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