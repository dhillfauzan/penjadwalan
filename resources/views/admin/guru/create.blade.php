@extends('layout.admin')

@section('content')
<div class="container-fluid">
    <!-- Action Card -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body d-flex justify-content-between align-items-center p-4">
            <div>
                <h5 class="font-weight-bold text-dark mb-1"><i class="fas fa-user-plus text-primary me-2"></i>Tambah Guru Baru</h5>
                <p class="text-muted mb-0 small">Lengkapi data profil guru beserta penugasan Mata Pelajaran, Kelas, dan Preferensi Jam Mengajar.</p>
            </div>
            <a href="{{ route('admin.guru.index') }}" class="btn btn-light d-flex align-items-center gap-2" style="border-radius: 10px; border: 1px solid #e2e8f0;">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Form Section -->
    <form action="{{ route('admin.guru.store') }}" method="POST">
        @csrf

        <div class="row">
            <!-- Left Side: Basic Info & Assignments -->
            <div class="col-lg-5 mb-4">
                <!-- Basic Profil Card -->
                <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 16px;">
                    <h5 class="font-weight-bold text-dark mb-3"><i class="fas fa-user-circle text-primary me-2"></i>Informasi Pribadi</h5>
                    <hr class="mt-0 mb-4 opacity-10">

                    <!-- Nama Guru -->
                    <div class="mb-3">
                        <label for="nama_guru" class="form-label font-weight-bold">Nama Guru <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_guru') is-invalid @enderror" 
                               id="nama_guru" name="nama_guru" value="{{ old('nama_guru') }}" placeholder="Masukkan nama lengkap beserta gelar" required style="border-radius: 10px;">
                        @error('nama_guru')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- NIP -->
                    <div class="mb-3">
                        <label for="nip" class="form-label font-weight-bold">NIP <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nip') is-invalid @enderror" 
                               id="nip" name="nip" value="{{ old('nip') }}" placeholder="Masukkan NIP resmi" required style="border-radius: 10px;">
                        @error('nip')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Jenis Kelamin -->
                    <div class="mb-3">
                        <label class="form-label font-weight-bold d-block">Jenis Kelamin <span class="text-danger">*</span></label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="jenis_kelamin" id="jk_l" value="L" {{ old('jenis_kelamin') == 'L' ? 'checked' : '' }} required>
                            <label class="form-check-label font-weight-500" for="jk_l">Laki-laki</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="jenis_kelamin" id="jk_p" value="P" {{ old('jenis_kelamin') == 'P' ? 'checked' : '' }}>
                            <label class="form-check-label font-weight-500" for="jk_p">Perempuan</label>
                        </div>
                        @error('jenis_kelamin')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- No. Telepon -->
                    <div class="mb-3">
                        <label for="no_telp" class="form-label font-weight-bold">No. Telepon / WhatsApp</label>
                        <input type="text" class="form-control @error('no_telp') is-invalid @enderror" 
                               id="no_telp" name="no_telp" value="{{ old('no_telp') }}" placeholder="contoh: 0812XXXXXXXX" style="border-radius: 10px;">
                        @error('no_telp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Subjects & Classes Assignment Card -->
                <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 16px;">
                    <h5 class="font-weight-bold text-dark mb-3"><i class="fas fa-graduation-cap text-indigo me-2" style="color: #4f46e5;"></i>Alokasi Mata Pelajaran & Kelas</h5>
                    <hr class="mt-0 mb-4 opacity-10">

                    <!-- Mata Pelajaran Checkboxes -->
                    <div class="mb-4">
                        <label class="form-label font-weight-bold d-block mb-2">Mata Pelajaran yang Diajar</label>
                        <div class="p-3 border rounded-3 bg-light bg-opacity-20" style="max-height: 200px; overflow-y: auto; border-radius: 10px;">
                            @php
                                $selectedMapels = old('mata_pelajarans_ids', []);
                            @endphp
                            @forelse($mataPelajarans as $mapel)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="mata_pelajarans_ids[]" value="{{ $mapel->getKey() }}" id="mapel_{{ $mapel->getKey() }}" {{ in_array($mapel->getKey(), $selectedMapels) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="mapel_{{ $mapel->getKey() }}">
                                        <strong>{{ $mapel->nama_mapel }}</strong> <span class="text-muted small">({{ $mapel->kode_mapel }})</span>
                                    </label>
                                </div>
                            @empty
                                <span class="text-muted small">Belum ada data mata pelajaran. Silakan buat mata pelajaran terlebih dahulu.</span>
                            @endforelse
                        </div>
                        @error('mata_pelajarans_ids')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Kelas Checkboxes -->
                    <div class="mb-2">
                        <label class="form-label font-weight-bold d-block mb-2">Kelas yang Diampu</label>
                        <div class="p-3 border rounded-3 bg-light bg-opacity-20" style="max-height: 200px; overflow-y: auto; border-radius: 10px;">
                            @php
                                $selectedKelas = old('kelas_ids', []);
                            @endphp
                            @forelse($kelas as $k)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="kelas_ids[]" value="{{ $k->getKey() }}" id="kelas_{{ $k->getKey() }}" {{ in_array($k->getKey(), $selectedKelas) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="kelas_{{ $k->getKey() }}">
                                        <strong>Kelas {{ $k->nama_kelas }}</strong>
                                    </label>
                                </div>
                            @empty
                                <span class="text-muted small">Belum ada data kelas. Silakan buat kelas terlebih dahulu.</span>
                            @endforelse
                        </div>
                        @error('kelas_ids')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Right Side: Weekly Availability Grid -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 16px;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="font-weight-bold text-dark mb-0"><i class="fas fa-calendar-alt text-success me-2"></i>Preferensi Ketersediaan Jam Mengajar</h5>
                        <button type="button" class="btn btn-outline-success btn-xs" id="btn-select-all" style="border-radius: 6px; padding: 0.2rem 0.6rem; font-size: 0.8rem;">
                            Pilih Semua Jam
                        </button>
                    </div>
                    <hr class="mt-0 mb-4 opacity-10">
                    <p class="text-muted small mb-3">Tentukan hari dan jam berapa saja guru ini bersedia/diizinkan untuk mengajar. Algoritma PSO akan menyesuaikan jadwal berdasarkan slot jam yang Anda centang di bawah ini.</p>

                    @php
                        // Buat grid index [jam_ke][hari]
                        $grid = [];
                        foreach($jamPelajarans as $jam) {
                            $grid[$jam->jam_ke][$jam->hari] = $jam;
                        }
                        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
                    @endphp

                   <div class="table-responsive">
    @php
        // Kelompokkan jam pelajaran berdasarkan jam_ke
        $jamGroups = [];
        foreach($jamPelajarans as $jam) {
            $jamGroups[$jam->jam_ke][$jam->hari] = $jam;
        }
        $jamNumbers = $jamPelajarans->pluck('jam_ke')->unique()->sort()->values();
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
    @endphp

    <table class="table table-bordered text-center align-middle mb-0" style="border-radius: 10px; overflow: hidden;">
        <thead class="bg-light text-secondary">
            <tr>
                <th style="width: 100px;">Jam Ke</th>
                @foreach($days as $day)
                    <th>{{ $day }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($jamNumbers as $period)
                <tr>
                    <td class="font-weight-bold bg-light" style="font-size: 0.95rem;">Jam Ke-{{ $period }}</td>
                    @foreach($days as $day)
                        <td>
                            @if(isset($jamGroups[$period][$day]))
                                @php $jam = $jamGroups[$period][$day]; @endphp
                                <div class="form-check d-flex justify-content-center m-0">
                                    <input class="form-check-input p-2 border-primary cursor-pointer checkbox-jam" 
                                           type="checkbox" name="jam_pelajarans_ids[]" value="{{ $jam->getKey() }}" id="jam_{{ $jam->getKey() }}" 
                                           {{ is_array(old('jam_pelajarans_ids')) && in_array($jam->getKey(), old('jam_pelajarans_ids')) ? 'checked' : '' }}>
                                </div>
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
                    @error('jam_pelajarans_ids')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Submit Action Card -->
                <div class="card border-0 shadow-sm p-4 text-end" style="border-radius: 16px;">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Pastikan semua data bertanda <span class="text-danger">*</span> sudah diisi dengan benar.</span>
                        <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-2" style="border-radius: 10px; padding: 0.6rem 2rem;">
                            <i class="fas fa-save"></i> Simpan Guru
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Ketersediaan jam mengajar script
    const btnSelectAll = document.getElementById('btn-select-all');
    const checkboxes = document.querySelectorAll('.checkbox-jam');

    if (btnSelectAll) {
        btnSelectAll.addEventListener('click', function() {
            checkboxes.forEach(cb => {
                cb.checked = true;
            });
        });
    }
});
</script>
@endsection