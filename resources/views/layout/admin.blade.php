<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Penjadwalan Sekolah - PSO</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind / Bootstrap & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
        }

        /* Sidebar Styling */
        .sidebar {
            width: 280px;
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
        }

        .sidebar-brand {
            padding: 1.5rem 1.25rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            font-size: 1.2rem;
            background: linear-gradient(90deg, #38bdf8, #818cf8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-link {
            color: #94a3b8;
            padding: 0.75rem 1.25rem;
            margin: 0.2rem 1rem;
            border-radius: 10px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.2s ease-in-out;
        }

        .nav-link i {
            font-size: 1.1rem;
            transition: transform 0.2s ease;
        }

        .nav-link:hover {
            color: #f1f5f9;
            background-color: rgba(255, 255, 255, 0.05);
            transform: translateX(4px);
        }

        .nav-link:hover i {
            transform: scale(1.1);
        }

        .nav-link.active {
            color: #ffffff;
            background: linear-gradient(90deg, #4f46e5 0%, #6366f1 100%);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.35);
        }

        /* Content Styling */
        .main-content {
            flex-grow: 1;
            min-height: 100vh;
            background-color: #f8fafc;
            overflow-y: auto;
        }

        .navbar-top {
            background-color: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 1rem 2rem;
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar text-white vh-100 d-flex flex-column justify-content-between">
            <div>
                <div class="sidebar-brand text-center">
                    <i class="fas fa-calendar-days me-2"></i>Sistem 
                </div>
                <hr class="mx-3 my-2 border-secondary opacity-25">
                
                <ul class="nav nav-pills flex-column mt-3">
                    @if(auth()->user()->role == 'admin')
                        <!-- Menu Admin -->
                        <li class="nav-item">
                            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                <i class="fas fa-chart-pie"></i> Dashboard Admin
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                                <i class="fas fa-users-cog"></i> Akun Pengguna
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.guru.index') }}" class="nav-link {{ request()->routeIs('admin.guru.*') ? 'active' : '' }}">
                                <i class="fas fa-chalkboard-user"></i> Tenaga Pengajar
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.jam-pelajaran.index') }}" class="nav-link {{ request()->routeIs('admin.jam-pelajaran.*') ? 'active' : '' }}">
                                <i class="fas fa-clock"></i> Jam Pelajaran
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.mata-pelajaran.index') }}" class="nav-link {{ request()->routeIs('admin.mata-pelajaran.*') ? 'active' : '' }}">
                                <i class="fas fa-book-open"></i> Mata Pelajaran
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.kelas.index') }}" class="nav-link {{ request()->routeIs('admin.kelas.*') ? 'active' : '' }}">
                                <i class="fas fa-school"></i> Kelas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.jadwal.index') }}" class="nav-link {{ request()->routeIs('admin.jadwal.*') ? 'active' : '' }}">
                                <i class="fas fa-calendar-alt"></i> Hasil Jadwal
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.optimasi') }}" class="nav-link {{ request()->routeIs('admin.optimasi') ? 'active' : '' }}">
                                <i class="fas fa-wand-magic-sparkles"></i> Optimasi PSO
                            </a>
                        </li>
                    @elseif(auth()->user()->role == 'guru')
                        <!-- Menu Guru -->
                        <li class="nav-item">
                            <a href="{{ route('guru.jadwal') }}" class="nav-link {{ request()->routeIs('guru.jadwal') ? 'active' : '' }}">
                                <i class="fas fa-calendar-day"></i> Jadwal Saya
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('guru.jadwal.mengajar') }}" class="nav-link {{ request()->routeIs('guru.jadwal.mengajar') ? 'active' : '' }}">
                                <i class="fas fa-list-check"></i> Jadwal Mengajar
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
            
            <!-- Bottom Sidebar Profile & Logout -->
            <div class="p-3">
                <div class="card bg-dark text-light border-0 mb-3" style="background-color: rgba(255, 255, 255, 0.05) !important;">
                    <div class="card-body p-3 d-flex align-items-center gap-2">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white font-weight-bold" style="width: 40px; height: 40px;">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                        <div style="min-width: 0;">
                            <h6 class="mb-0 text-truncate font-weight-bold">{{ auth()->user()->name }}</h6>
                            <small class="text-muted text-capitalize">{{ auth()->user()->role }}</small>
                        </div>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center gap-2" style="border-radius: 10px;">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="main-content d-flex flex-column">
            <!-- Top Navbar -->
            <div class="navbar-top d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    <span class="text-capitalize">{{ auth()->user()->role }} Area</span> &nbsp;&gt;&nbsp; 
                    <span class="text-dark font-weight-bold">Dashboard</span>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('profile.edit') }}" class="btn btn-light btn-sm text-secondary" style="border-radius: 8px;">
                        <i class="fas fa-user-gear"></i> Edit Profil
                    </a>
                </div>
            </div>

            <!-- Yield Content -->
            <div class="p-4 flex-grow-1">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>