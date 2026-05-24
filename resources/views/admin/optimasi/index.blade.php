@extends('layout.admin')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="font-weight-bold text-dark mb-1">Optimasi Swarm Intelligence (PSO)</h2>
            <p class="text-muted mb-0">Sesuaikan hyperparameter algoritma Particle Swarm Optimization untuk meracik jadwal bebas bentrok.</p>
        </div>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert" style="border-radius: 12px;">
             <i class="fas fa-check-circle text-success"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Alert Error -->
    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert" style="border-radius: 12px;">
            <i class="fas fa-exclamation-triangle text-danger"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Loading Indicator Card (Hidden by default) -->
    <div id="loading-card" class="card border-0 shadow-sm mb-4 d-none animate__animated animate__fadeIn" style="border-radius: 16px; background-color: #f0fdfa; border-left: 5px solid #0d9488;">
        <div class="card-body p-4 d-flex align-items-center gap-3">
            <div class="spinner-border text-teal" role="status" style="width: 2.5rem; height: 2.5rem; color: #0d9488;">
                <span class="visually-hidden">Loading...</span>
            </div>
            <div>
                <h5 class="font-weight-bold text-dark mb-1">Algoritma PSO Sedang Mengoptimasi Jadwal...</h5>
                <p class="text-secondary small mb-0">Sistem sedang merangsang pencarian swarm partikel berdasarkan parameter input. Harap tunggu sebentar, halaman akan otomatis dialihkan ke Jadwal Utama setelah selesai.</p>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Input Parameters Card -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                <div class="card-header bg-white py-3 border-0" style="border-radius: 16px 16px 0 0;">
                    <h5 class="mb-0 font-weight-bold text-dark d-flex align-items-center gap-2">
                        <i class="fas fa-sliders-h text-primary"></i> Pengaturan Parameter Swarm
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.optimasi.run') }}" method="POST" id="form-optimasi" onsubmit="return showSwarmLoading()">
                        @csrf

                        <!-- Swarm Size Parameter -->
                        <div class="mb-4">
                            <label for="partikel" class="form-label font-weight-bold text-dark mb-1">
                                Jumlah Partikel (Swarm Size) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0" style="border-radius: 10px 0 0 10px;"><i class="fas fa-users text-muted"></i></span>
                                <input type="number" class="form-control border-start-0 @error('partikel') is-invalid @enderror" 
                                       id="partikel" name="partikel" value="{{ old('partikel', 30) }}" 
                                       min="10" max="100" step="1" required style="border-radius: 0 10px 10px 0; padding: 0.6rem 1rem;">
                            </div>
                            <div class="form-text text-muted small mt-2">
                                Menentukan jumlah kandidat solusi yang aktif mencari secara paralel. Swarm yang lebih besar mengeksplorasi ruang pencarian dengan lebih baik, namun membutuhkan waktu komputasi sedikit lebih lama. <span class="text-primary font-weight-bold">(Rekomendasi: 20 - 50)</span>
                            </div>
                            @error('partikel')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Iteration Count Parameter -->
                        <div class="mb-4">
                            <label for="iterasi" class="form-label font-weight-bold text-dark mb-1">
                                Jumlah Iterasi (Generasi Pembaruan) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0" style="border-radius: 10px 0 0 10px;"><i class="fas fa-redo-alt text-muted"></i></span>
                                <input type="number" class="form-control border-start-0 @error('iterasi') is-invalid @enderror" 
                                       id="iterasi" name="iterasi" value="{{ old('iterasi', 100) }}" 
                                       min="10" max="500" step="1" required style="border-radius: 0 10px 10px 0; padding: 0.6rem 1rem;">
                            </div>
                            <div class="form-text text-muted small mt-2">
                                Jumlah siklus pembaruan posisi partikel untuk meminimalisasi konflik jadwal. Semakin besar nilai iterasi, semakin matang tingkat konvergensi dan tingkat fitness jadwal yang dihasilkan. <span class="text-primary font-weight-bold">(Rekomendasi: 50 - 200)</span>
                            </div>
                            @error('iterasi')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Fixed Hyperparameters Notice -->
                        <div class="p-3 bg-light rounded-3 mb-4 border">
                            <span class="small font-weight-bold text-secondary d-block mb-2"><i class="fas fa-lock me-1"></i> Hyperparameter Pendukung (Tetap):</span>
                            <div class="row text-center text-dark">
                                <div class="col-4">
                                    <div class="bg-white p-2 rounded border">
                                        <code class="font-weight-bold text-indigo" style="font-size: 0.95rem;">c1 = 1.5</code>
                                        <div class="text-muted small" style="font-size: 0.75rem;">Kognitif</div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="bg-white p-2 rounded border">
                                        <code class="font-weight-bold text-indigo" style="font-size: 0.95rem;">c2 = 1.5</code>
                                        <div class="text-muted small" style="font-size: 0.75rem;">Sosial</div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="bg-white p-2 rounded border">
                                        <code class="font-weight-bold text-indigo" style="font-size: 0.95rem;">w = 0.5</code>
                                        <div class="text-muted small" style="font-size: 0.75rem;">Inersia</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-2 px-4 py-2.5" id="btn-run" style="border-radius: 10px;">
                                <i class="fas fa-play" id="icon-run"></i> <span id="text-run">Jalankan Optimasi Jadwal</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Side: Info & Guidelines Card -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                <div class="card-header bg-white py-3 border-0" style="border-radius: 16px 16px 0 0;">
                    <h5 class="mb-0 font-weight-bold text-dark d-flex align-items-center gap-2">
                        <i class="fas fa-graduation-cap text-indigo"></i> Panduan & Cara Kerja Swarm
                    </h5>
                </div>
                <div class="card-body p-4">
                    <!-- Swarm Intelligence Animation Graphic placeholder -->
                    <div class="p-4 rounded-3 text-center mb-4 text-white" style="background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);">
                        <i class="fas fa-project-diagram fa-3x mb-3 animate__animated animate__pulse animate__infinite"></i>
                        <h5 class="font-weight-bold">Particle Swarm Optimization</h5>
                        <p class="small mb-0 opacity-90">Algoritma optimasi berbasis kecerdasan kawanan (swarm intelligence) yang meniru gerakan sekawanan burung saat mencari makanan secara kolaboratif.</p>
                    </div>

                    <!-- Flow Information List -->
                    <div class="d-flex flex-column gap-3 mb-4">
                        <div class="d-flex align-items-start gap-3">
                            <div class="bg-primary bg-opacity-10 text-primary p-2.5 rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; flex-shrink: 0;">
                                <span class="font-weight-bold" style="font-size: 0.9rem;">1</span>
                            </div>
                            <div>
                                <h6 class="font-weight-bold text-dark mb-1">Inisialisasi Swarm</h6>
                                <p class="text-secondary small mb-0">Swarm menghasilkan sekumpulan partikel (kandidat jadwal) secara acak berdasarkan total mata pelajaran, kelas, dan jam pelajaran sekolah.</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start gap-3">
                            <div class="bg-success bg-opacity-10 text-success p-2.5 rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; flex-shrink: 0;">
                                <span class="font-weight-bold" style="font-size: 0.9rem;">2</span>
                            </div>
                            <div>
                                <h6 class="font-weight-bold text-dark mb-1">Evaluasi Fitness (Bebas Konflik)</h6>
                                <p class="text-secondary small mb-0">Setiap partikel dinilai tingkat kelayakannya. Konflik guru mengajar ganda, kelas terjadwal ganda, atau penempatan di luar jam mengajar guru akan diberikan penalti.</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start gap-3">
                            <div class="bg-purple bg-opacity-10 text-purple p-2.5 rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; flex-shrink: 0; background-color: #f3e8ff; color: #a855f7;">
                                <span class="font-weight-bold" style="font-size: 0.9rem;">3</span>
                            </div>
                            <div>
                                <h6 class="font-weight-bold text-dark mb-1">Konvergensi Global Best (G-Best)</h6>
                                <p class="text-secondary small mb-0">Partikel bertukar informasi untuk menyesuaikan kecepatan dan posisinya menuju titik G-Best (solusi jadwal paling optimal dengan konflik terkecil).</p>
                            </div>
                        </div>
                    </div>

                    <!-- Warning Notice -->
                    <div class="alert alert-warning border-0 p-3 small mb-0 d-flex gap-2" style="border-radius: 12px; background-color: #fffbeb; color: #b45309;">
                        <i class="fas fa-exclamation-circle text-warning mt-0.5" style="font-size: 1.05rem;"></i>
                        <div>
                            <strong>Penting:</strong> Menjalankan proses optimasi akan langsung menghapus rancangan jadwal lama yang tersimpan pada menu <strong>Jadwal Pelajaran</strong> dan menyimpannya secara otomatis berdasarkan hasil pencarian swarm terbaik.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function showSwarmLoading() {
        if (!confirm('Apakah Anda yakin ingin menjalankan optimasi jadwal? Proses ini akan mengatur ulang jadwal lama dan menyusun jadwal baru secara otomatis.')) {
            return false;
        }

        // Get elements
        const btn = document.getElementById('btn-run');
        const icon = document.getElementById('icon-run');
        const text = document.getElementById('text-run');
        const loadingCard = document.getElementById('loading-card');

        // Display loading card
        if (loadingCard) {
            loadingCard.classList.remove('d-none');
            loadingCard.scrollIntoView({ behavior: 'smooth' });
        }

        // Delay changing the button state slightly to let the browser submit the form first
        setTimeout(() => {
            if (btn) {
                btn.classList.add('disabled');
                btn.style.opacity = '0.7';
            }
            if (icon) {
                icon.className = 'fas fa-spinner fa-spin';
            }
            if (text) {
                text.textContent = 'Menghitung Swarm...';
            }
        }, 50);

        return true;
    }
</script>
@endsection