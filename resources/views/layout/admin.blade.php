{{-- resources/views/layouts/hope-ui.blade.php --}}
<!doctype html>
<html lang="id" dir="ltr" data-bs-theme="light" data-bs-theme-color="theme-color-default">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title', 'Sistem Penjadwalan Sekolah') - Hope UI</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('template/html/assets/images/favicon.ico') }}">

    <!-- Library / Plugin Css Build -->
    <link rel="stylesheet" href="{{ asset('template/html/assets/css/core/libs.min.css') }}">

    <!-- Aos Animation Css -->
    <link rel="stylesheet" href="{{ asset('template/html/assets/vendor/aos/dist/aos.css') }}">

    <!-- Hope Ui Design System Css -->
    <link rel="stylesheet" href="{{ asset('template/html/assets/css/hope-ui.min.css') }}">

    <!-- Custom Css -->
    <link rel="stylesheet" href="{{ asset('template/html/assets/css/custom.min.css') }}">

    <!-- Customizer Css -->
    <link rel="stylesheet" href="{{ asset('template/html/assets/css/customizer.min.css') }}">

    <!-- RTL Css -->
    <link rel="stylesheet" href="{{ asset('template/html/assets/css/rtl.min.css') }}">

    @stack('styles')
    <style>
        /* Memastikan background putih sidebar full ke bawah */
        .sidebar {
            background-color: #ffffff !important;
            min-height: 100vh !important;
        }

        /* Menyesuaikan ukuran logo dan merapatkan ke kiri */
        .sidebar-header {
            padding-left: 10px !important; /* Mentok ke kiri dengan sedikit jarak bernapas */
        }
        .sidebar .logo-main svg {
            width: 32px !important; /* Ukuran logo dikurangi sedikit agar pas */
            height: 32px !important;
        }
        .sidebar .logo-title {
            font-size: 1.4rem !important; /* Ukuran font judul sedikit disesuaikan */
            font-weight: bold;
            margin-left: 8px !important;
        }

        /* Memperbesar ukuran teks, icon, dan spasi menu sidebar agar lebih penuh */
        .sidebar-list .nav-item .nav-link {
            padding: 18px 25px !important; /* Spasi dalam menu diperbesar lebih lanjut */
        }
        .sidebar-list .nav-item .item-name {
            font-size: 1.2rem !important; /* Ukuran teks menu diperbesar */
        }
        .sidebar-list .nav-item .icon svg {
            width: 26px !important; /* Ukuran icon diperbesar */
            height: 26px !important;
        }
        .sidebar-list .nav-item {
            margin-bottom: 20px !important; /* Jarak antar menu ditingkatkan secara signifikan */
        }
    </style>
</head>
<body>

<!-- loader Start -->
<div id="loading">
    <div class="loader simple-loader">
        <div class="loader-body"></div>
    </div>
</div>
<!-- loader END -->

<!-- SIDEBAR -->
<aside class="sidebar sidebar-default sidebar-white sidebar-base navs-rounded-all">
    <div class="sidebar-header d-flex align-items-center justify-content-start">
        <a href="{{ route('dashboard') }}" class="navbar-brand">
            <!-- Logo start -->
            <div class="logo-main">
                <div class="logo-normal">
                    <svg class="icon-30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="-0.757324" y="19.2427" width="28" height="4" rx="2" transform="rotate(-45 -0.757324 19.2427)" fill="currentColor"/>
                        <rect x="7.72803" y="27.728" width="28" height="4" rx="2" transform="rotate(-45 7.72803 27.728)" fill="currentColor"/>
                        <rect x="10.5366" y="16.3945" width="16" height="4" rx="2" transform="rotate(45 10.5366 16.3945)" fill="currentColor"/>
                        <rect x="10.5562" y="-0.556152" width="28" height="4" rx="2" transform="rotate(45 10.5562 -0.556152)" fill="currentColor"/>
                    </svg>
                </div>
                <div class="logo-mini">
                    <svg class="icon-30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="-0.757324" y="19.2427" width="28" height="4" rx="2" transform="rotate(-45 -0.757324 19.2427)" fill="currentColor"/>
                        <rect x="7.72803" y="27.728" width="28" height="4" rx="2" transform="rotate(-45 7.72803 27.728)" fill="currentColor"/>
                        <rect x="10.5366" y="16.3945" width="16" height="4" rx="2" transform="rotate(45 10.5366 16.3945)" fill="currentColor"/>
                        <rect x="10.5562" y="-0.556152" width="28" height="4" rx="2" transform="rotate(45 10.5562 -0.556152)" fill="currentColor"/>
                    </svg>
                </div>
            </div>
            <!-- logo End -->
            <h4 class="logo-title">Penjadwalan</h4>
        </a>
        <div class="sidebar-toggle" data-toggle="sidebar" data-active="true">
            <i class="icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.25 12.2744L19.25 12.2744" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M10.2998 18.2988L4.2498 12.2748L10.2998 6.24976" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </i>
        </div>
    </div>
    <div class="sidebar-body pt-0 data-scrollbar">
        <div class="sidebar-list">
            <ul class="navbar-nav iq-main-menu" id="sidebar-menu">
                {{-- Menu Dinamis Berdasarkan Role --}}
                @if(auth()->user()->role == 'admin')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                            <i class="icon">
                                <svg width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.4" d="M16.0756 2H19.4616C20.8639 2 22.0001 3.14585 22.0001 4.55996V7.97452C22.0001 9.38864 20.8639 10.5345 19.4616 10.5345H16.0756C14.6734 10.5345 13.5371 9.38864 13.5371 7.97452V4.55996C13.5371 3.14585 14.6734 2 16.0756 2Z" fill="currentColor"/>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M4.53852 2H7.92449C9.32676 2 10.463 3.14585 10.463 4.55996V7.97452C10.463 9.38864 9.32676 10.5345 7.92449 10.5345H4.53852C3.13626 10.5345 2 9.38864 2 7.97452V4.55996C2 3.14585 3.13626 2 4.53852 2ZM4.53852 13.4655H7.92449C9.32676 13.4655 10.463 14.6114 10.463 16.0255V19.44C10.463 20.8532 9.32676 22 7.92449 22H4.53852C3.13626 22 2 20.8532 2 19.44V16.0255C2 14.6114 3.13626 13.4655 4.53852 13.4655ZM19.4615 13.4655H16.0755C14.6732 13.4655 13.537 14.6114 13.537 16.0255V19.44C13.537 20.8532 14.6732 22 16.0755 22H19.4615C20.8637 22 22 20.8532 22 19.44V16.0255C22 14.6114 20.8637 13.4655 19.4615 13.4655Z" fill="currentColor"/>
                                </svg>
                            </i>
                            <span class="item-name">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                            <i class="icon">
                                <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M11.9488 14.54C8.49884 14.54 5.58789 15.1038 5.58789 17.2795C5.58789 19.4562 8.51765 20.0001 11.9488 20.0001C15.3988 20.0001 18.3098 19.4364 18.3098 17.2606C18.3098 15.084 15.38 14.54 11.9488 14.54Z" fill="currentColor"></path>
                                    <path opacity="0.4" d="M11.949 12.467C14.2851 12.467 16.1583 10.5831 16.1583 8.23351C16.1583 5.88306 14.2851 4 11.949 4C9.61293 4 7.73975 5.88306 7.73975 8.23351C7.73975 10.5831 9.61293 12.467 11.949 12.467Z" fill="currentColor"></path>
                                </svg>
                            </i>
                            <span class="item-name">Akun Pengguna</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.kelas.*') ? 'active' : '' }}" href="{{ route('admin.kelas.index') }}">
                            <i class="icon">
                                <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.4" d="M12 3L2 9L12 15L22 9L12 3Z" fill="currentColor"/>
                                    <path d="M12 13.5L4.5 9L12 4.5L19.5 9L12 13.5Z" fill="currentColor"/>
                                </svg>
                            </i>
                            <span class="item-name">Kelas</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.jam-pelajaran.*') ? 'active' : '' }}" href="{{ route('admin.jam-pelajaran.index') }}">
                            <i class="icon">
                                <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.4" d="M12 2C6.49 2 2 6.49 2 12C2 17.51 6.49 22 12 22C17.51 22 22 17.51 22 12C22 6.49 17.51 2 12 2Z" fill="currentColor"/>
                                    <path d="M15.5 13.5H12.5V8.5C12.5 8.22 12.28 8 12 8C11.72 8 11.5 8.22 11.5 8.5V13.5C11.5 13.78 11.72 14 12 14H15.5C15.78 14 16 13.78 16 13.5C16 13.22 15.78 13.5 15.5 13.5Z" fill="currentColor"/>
                                </svg>
                            </i>
                            <span class="item-name">Jam Pelajaran</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.mata-pelajaran.*') ? 'active' : '' }}" href="{{ route('admin.mata-pelajaran.index') }}">
                            <i class="icon">
                                <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.4" d="M16.191 2H7.81C4.77 2 3 3.78 3 6.83V17.16C3 20.26 4.77 22 7.81 22H16.191C19.28 22 21 20.26 21 17.16V6.83C21 3.78 19.28 2 16.191 2Z" fill="currentColor"/>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M8.07996 6.6499V6.6599C7.64896 6.6599 7.29996 7.0099 7.29996 7.4399C7.29996 7.8699 7.64896 8.2199 8.07996 8.2199H11.069C11.5 8.2199 11.85 7.8699 11.85 7.4289C11.85 6.9999 11.5 6.6499 11.069 6.6499H8.07996Z" fill="currentColor"/>
                                </svg>
                            </i>
                            <span class="item-name">Mata Pelajaran</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.guru.*') ? 'active' : '' }}" href="{{ route('admin.guru.index') }}">
                            <i class="icon">
                                <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M8.89526 2H15.0695C17.7773 2 19.9735 3.06605 20 5.79337V20.9668C19.9989 21.1374 19.9565 21.3051 19.8765 21.4554C19.7479 21.7007 19.5259 21.8827 19.2615 21.9598C18.997 22.0368 18.7128 22.0023 18.4741 21.8641L11.9912 18.6215L5.47299 15.3701C4.40573 14.8726 4 14.4284 4 13.7088V5.79337C4 3.06605 6.19625 2 8.89526 2Z" fill="currentColor"/>
                                </svg>
                            </i>
                            <span class="item-name">Tenaga Pengajar</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.jadwal.index') ? 'active' : '' }}" href="{{ route('admin.jadwal.index') }}">
                            <i class="icon">
                                <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.4" d="M19 4H18V2H16V4H8V2H6V4H5C3.89 4 3.01 4.9 3.01 6L3 20C3 21.1 3.89 22 5 22H19C20.1 22 21 21.1 21 20V6C21 4.9 20.1 4 19 4ZM19 20H5V10H19V20Z" fill="currentColor"/>
                                    <path d="M8 12H10V14H8V12ZM14 12H16V14H14V12Z" fill="currentColor"/>
                                </svg>
                            </i>
                            <span class="item-name">Hasil Jadwal</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.jadwal.semua-guru') ? 'active' : '' }}" href="{{ route('admin.jadwal.semua-guru') }}">
                            <i class="icon">
                                <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.4" d="M11.9488 14.54C8.49884 14.54 5.58789 15.1038 5.58789 17.2795C5.58789 19.4562 8.51765 20.0001 11.9488 20.0001C15.3988 20.0001 18.3098 19.4364 18.3098 17.2606C18.3098 15.084 15.38 14.54 11.9488 14.54Z" fill="currentColor"></path>
                                    <path opacity="0.4" d="M11.949 12.467C14.2851 12.467 16.1583 10.5831 16.1583 8.23351C16.1583 5.88306 14.2851 4 11.949 4C9.61293 4 7.73975 5.88306 7.73975 8.23351C7.73975 10.5831 9.61293 12.467 11.949 12.467Z" fill="currentColor"></path>
                                </svg>
                            </i>
                            <span class="item-name">Jadwal Guru</span>
                        </a>
                    </li>
                    <!--li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.optimasi') ? 'active' : '' }}" href="{{ route('admin.optimasi') }}">
                            <i class="icon">
                                <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.4" d="M21.4274 2.5783C20.9274 2.0673 20.1874 1.8783 19.4974 2.0783L3.40742 6.7273C2.67942 6.9293 2.16342 7.5063 2.02442 8.2383C1.88242 8.9843 2.37842 9.9323 3.02642 10.3283L8.05742 13.4003C8.57342 13.7163 9.23942 13.6373 9.66642 13.2093L15.4274 7.4483C15.7174 7.1473 16.1974 7.1473 16.4874 7.4483C16.7774 7.7373 16.7774 8.2083 16.4874 8.5083L10.7164 14.2693C10.2884 14.6973 10.2084 15.3613 10.5234 15.8783L13.5974 20.9283C13.9574 21.5273 14.5774 21.8683 15.2574 21.8683C15.3374 21.8683 15.4274 21.8683 15.5074 21.8573C16.2874 21.7583 16.9074 21.2273 17.1374 20.4773L21.9074 4.5083C22.1174 3.8283 21.9274 3.0883 21.4274 2.5783Z" fill="currentColor"/>
                                </svg>
                            </i>
                            <span class="item-name">Optimasi PSO</span>
                        </a>
                    </li-->
                @elseif(auth()->user()->role == 'guru')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('guru.jadwal') ? 'active' : '' }}" href="{{ route('guru.jadwal') }}">
                            <i class="icon">
                                <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.4" d="M19 4H18V2H16V4H8V2H6V4H5C3.89 4 3.01 4.9 3.01 6L3 20C3 21.1 3.89 22 5 22H19C20.1 22 21 21.1 21 20V6C21 4.9 20.1 4 19 4Z" fill="currentColor"/>
                                    <path d="M8 12H10V14H8V12ZM14 12H16V14H14V12Z" fill="currentColor"/>
                                </svg>
                            </i>
                            <span class="item-name">Jadwal Saya</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('guru.jadwal.mengajar') ? 'active' : '' }}" href="{{ route('guru.jadwal.mengajar') }}">
                            <i class="icon">
                                <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M8.89526 2H15.0695C17.7773 2 19.9735 3.06605 20 5.79337V20.9668C19.9989 21.1374 19.9565 21.3051 19.8765 21.4554C19.7479 21.7007 19.5259 21.8827 19.2615 21.9598C18.997 22.0368 18.7128 22.0023 18.4741 21.8641L11.9912 18.6215L5.47299 15.3701C4.40573 14.8726 4 14.4284 4 13.7088V5.79337C4 3.06605 6.19625 2 8.89526 2Z" fill="currentColor"/>
                                </svg>
                            </i>
                            <span class="item-name">Jadwal Pelajaran</span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
    <div class="sidebar-footer"></div>
</aside>

<main class="main-content" style="background-color: #f5f6fa;">
    <!-- Bagian Banner dan Navbar dibuat Sticky di atas -->
    <div class="position-sticky iq-banner" style="top: 0; z-index: 10;">
        <!-- Navbar Top -->
        <nav class="nav navbar navbar-expand-xl navbar-light iq-navbar">
            <div class="container-fluid navbar-inner">
                <a href="{{ route('dashboard') }}" class="navbar-brand">
                    <div class="logo-main">
                        <div class="logo-normal">
                            <svg class="text-primary icon-30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="-0.757324" y="19.2427" width="28" height="4" rx="2" transform="rotate(-45 -0.757324 19.2427)" fill="currentColor"/>
                                <rect x="7.72803" y="27.728" width="28" height="4" rx="2" transform="rotate(-45 7.72803 27.728)" fill="currentColor"/>
                                <rect x="10.5366" y="16.3945" width="16" height="4" rx="2" transform="rotate(45 10.5366 16.3945)" fill="currentColor"/>
                                <rect x="10.5562" y="-0.556152" width="28" height="4" rx="2" transform="rotate(45 10.5562 -0.556152)" fill="currentColor"/>
                            </svg>
                        </div>
                        <div class="logo-mini">
                            <svg class="text-primary icon-30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="-0.757324" y="19.2427" width="28" height="4" rx="2" transform="rotate(-45 -0.757324 19.2427)" fill="currentColor"/>
                                <rect x="7.72803" y="27.728" width="28" height="4" rx="2" transform="rotate(-45 7.72803 27.728)" fill="currentColor"/>
                                <rect x="10.5366" y="16.3945" width="16" height="4" rx="2" transform="rotate(45 10.5366 16.3945)" fill="currentColor"/>
                                <rect x="10.5562" y="-0.556152" width="28" height="4" rx="2" transform="rotate(45 10.5562 -0.556152)" fill="currentColor"/>
                            </svg>
                        </div>
                    </div>
                    <h4 class="logo-title">Penjadwalan</h4>
                </a>
                <div class="sidebar-toggle" data-toggle="sidebar" data-active="true">
                    <i class="icon">
                        <svg width="20px" class="icon-20" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M4,11V13H16L10.5,18.5L11.92,19.92L19.84,12L11.92,4.08L10.5,5.5L16,11H4Z" />
                        </svg>
                    </i>
                </div>
                <div class="input-group search-input">
                    <span class="input-group-text" id="search-input">
                        <svg class="icon-18" width="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="11.7669" cy="11.7666" r="8.98856" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></circle>
                            <path d="M18.0186 18.4851L21.5426 22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </span>
                    <input type="search" class="form-control" placeholder="Search...">
                </div>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon">
                        <span class="mt-2 navbar-toggler-bar bar1"></span>
                        <span class="navbar-toggler-bar bar2"></span>
                        <span class="navbar-toggler-bar bar3"></span>
                    </span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="mb-2 navbar-nav ms-auto align-items-center navbar-list mb-lg-0">
                        <li class="nav-item dropdown custom-drop">
                            <a class="py-0 nav-link d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="avatar avatar-50 avatar-rounded">
                                    <span class="avatar-initials rounded-circle bg-primary d-flex align-items-center justify-content-center text-white" style="width: 50px; height: 50px; font-size: 1.2rem;">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                    </span>
                                </div>
                                <div class="caption ms-3 d-none d-md-block">
                                    <h6 class="mb-0 caption-title">{{ auth()->user()->name }}</h6>
                                    <p class="mb-0 caption-sub-title text-capitalize">{{ auth()->user()->role }}</p>
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fas fa-user-gear me-2"></i> Edit Profil</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt me-2"></i> Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        
        <!-- Nav Header Component (Banner) -->
        <div class="iq-navbar-header" style="height: 220px;">
            <div class="container-fluid iq-container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="flex-wrap d-flex justify-content-between align-items-start" style="padding-top: 18px;">
                            <div class="ps-4">
                                @php
                                    $routeName = Route::currentRouteName() ?? '';
                                    $defaultTitle = 'Dashboard';
                                    
                                    if (str_contains($routeName, 'users')) {
                                        $defaultTitle = 'Akun Pengguna';
                                    } elseif (str_contains($routeName, 'guru.jadwal.mengajar')) {
                                        $defaultTitle = 'Jadwal Mengajar';
                                    } elseif (str_contains($routeName, 'guru.jadwal')) {
                                        $defaultTitle = 'Jadwal Saya';
                                    } elseif (str_contains($routeName, 'guru')) {
                                        $defaultTitle = 'Tenaga Pengajar';
                                    } elseif (str_contains($routeName, 'kelas')) {
                                        $defaultTitle = 'Kelas';
                                    } elseif (str_contains($routeName, 'mata-pelajaran')) {
                                        $defaultTitle = 'Mata Pelajaran';
                                    } elseif (str_contains($routeName, 'jam-pelajaran')) {
                                        $defaultTitle = 'Jam Pelajaran';
                                    } elseif (str_contains($routeName, 'jadwal.semua-guru') || request()->is('admin/jadwal/semua-guru*')) {
                                        $defaultTitle = 'Jadwal Guru';
                                    } elseif (str_contains($routeName, 'jadwal')) {
                                        $defaultTitle = 'Hasil Jadwal';
                                    } elseif (str_contains($routeName, 'optimasi')) {
                                        $defaultTitle = 'Optimasi Penjadwalan';
                                    }
                                @endphp
                                <h1 class="text-white">@yield('page-title', $defaultTitle)</h1>
                                <p class="text-white text-capitalize mb-0">{{ auth()->user()->role }} Area</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="iq-header-img">
                <img src="{{ asset('template/html/assets/images/dashboard/11.jpg') }}" alt="header" class="theme-color-default-img img-fluid w-100 h-100 animated-scaleX" style="object-fit: cover;">
                <img src="{{ asset('template/html/assets/images/dashboard/top-header.png') }}" alt="header" class="theme-color-purple-img img-fluid w-100 h-100 animated-scaleX">
                <img src="{{ asset('template/html/assets/images/dashboard/top-header1.png') }}" alt="header" class="theme-color-blue-img img-fluid w-100 h-100 animated-scaleX">
                <img src="{{ asset('template/html/assets/images/dashboard/top-header2.png') }}" alt="header" class="theme-color-green-img img-fluid w-100 h-100 animated-scaleX">
                <img src="{{ asset('template/html/assets/images/dashboard/top-header3.png') }}" alt="header" class="theme-color-yellow-img img-fluid w-100 h-100 animated-scaleX">
                <img src="{{ asset('template/html/assets/images/dashboard/top-header4.png') }}" alt="header" class="theme-color-pink-img img-fluid w-100 h-100 animated-scaleX">
            </div>
        </div>
    </div>

    <!-- Dynamic Content -->
    <div class="container-fluid content-inner mt-n3 py-0" style="position: relative; z-index: 11; background-color: #f5f6fa; padding-top: 20px !important;">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-body">
            <ul class="left-panel list-inline mb-0 p-0">
                <li class="list-inline-item"><a href="#">Privacy Policy</a></li>
                <li class="list-inline-item"><a href="#">Terms of Use</a></li>
            </ul>
            <div class="right-panel">
                © {{ date('Y') }} Sistem Penjadwalan Sekolah, Made with
                <span>
                    <svg class="icon-15" width="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M15.85 2.50065C16.481 2.50065 17.111 2.58965 17.71 2.79065C21.401 3.99065 22.731 8.04065 21.62 11.5806C20.99 13.3896 19.96 15.0406 18.611 16.3896C16.68 18.2596 14.561 19.9196 12.28 21.3496L12.03 21.5006L11.77 21.3396C9.48102 19.9196 7.35002 18.2596 5.40102 16.3796C4.06102 15.0306 3.03002 13.3896 2.39002 11.5806C1.26002 8.04065 2.59002 3.99065 6.32102 2.76965C6.61102 2.66965 6.91002 2.59965 7.21002 2.56065H7.33002C7.61102 2.51965 7.89002 2.50065 8.17002 2.50065H8.28002C8.91002 2.51965 9.52002 2.62965 10.111 2.83065H10.17C10.21 2.84965 10.24 2.87065 10.26 2.88965C10.481 2.96065 10.69 3.04065 10.89 3.15065L11.27 3.32065C11.3618 3.36962 11.4649 3.44445 11.554 3.50912C11.6104 3.55009 11.6612 3.58699 11.7 3.61065C11.7163 3.62028 11.7329 3.62996 11.7496 3.63972C11.8354 3.68977 11.9247 3.74191 12 3.79965C13.111 2.95065 14.46 2.49065 15.85 2.50065Z" fill="currentColor"></path>
                    </svg>
                </span>
                by <a href="#">Mahasiswa Universitas BSI Margonda</a>.
            </div>
        </div>
    </footer>
</main>

<!-- Offcanvas Settings (Opsional) -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasExample" data-bs-scroll="true" data-bs-backdrop="true" aria-labelledby="offcanvasExampleLabel">
    <div class="offcanvas-header">
        <div class="d-flex align-items-center">
            <h3 class="offcanvas-title me-3" id="offcanvasExampleLabel">Settings</h3>
        </div>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body data-scrollbar">
        {{-- Konten setting tema bisa diisi --}}
        <p>Pengaturan tema (dark/light) dapat diaktifkan nanti.</p>
    </div>
</div>

<!-- Scripts -->
<script src="{{ asset('template/html/assets/js/core/libs.min.js') }}"></script>
<script src="{{ asset('template/html/assets/js/core/external.min.js') }}"></script>
<script src="{{ asset('template/html/assets/js/charts/widgetcharts.js') }}"></script>
<script src="{{ asset('template/html/assets/js/charts/vectore-chart.js') }}"></script>
<script src="{{ asset('template/html/assets/js/charts/dashboard.js') }}"></script>
<script src="{{ asset('template/html/assets/js/plugins/fslightbox.js') }}"></script>
<script src="{{ asset('template/html/assets/js/plugins/setting.js') }}"></script>
<script src="{{ asset('template/html/assets/js/plugins/slider-tabs.js') }}"></script>
<script src="{{ asset('template/html/assets/js/plugins/form-wizard.js') }}"></script>
<script src="{{ asset('template/html/assets/vendor/aos/dist/aos.js') }}"></script>
<script src="{{ asset('template/html/assets/js/hope-ui.js') }}" defer></script>
@stack('scripts')
</body>
</html>