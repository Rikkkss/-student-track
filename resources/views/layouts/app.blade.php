
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Student Track') - Student Track</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- CSS Student Track -->
    <link rel="stylesheet" href="{{ asset('css/student-track.css') }}">

    @stack('styles')
</head>

<body>

<div class="app-wrapper">

    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebar">

        <div class="brand">
            <div class="brand-icon">
                <i class="bi bi-mortarboard-fill"></i>
            </div>

            <div>
                <div class="brand-name">Student Track</div>
                <div class="brand-subtitle">STUDENT MANAGEMENT</div>
            </div>
        </div>

        <div class="sidebar-label">MENU UTAMA</div>

        <nav class="sidebar-menu">

            <a href="{{ route('dashboard') }}"
               class="menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('mata-kuliah.index') }}"
               class="menu-link {{ request()->routeIs('mata-kuliah.*') ? 'active' : '' }}">
                <i class="bi bi-book-fill"></i>
                <span>Mata Kuliah</span>
            </a>

            <a href="{{ route('nilai.index') }}"
               class="menu-link {{ request()->routeIs('nilai.*') ? 'active' : '' }}">
                <i class="bi bi-journal-check"></i>
                <span>Data Nilai</span>
            </a>

            <a href="{{ route('tugas.index') }}"
               class="menu-link {{ request()->routeIs('tugas.*') ? 'active' : '' }}">
                <i class="bi bi-list-task"></i>
                <span>Daftar Tugas</span>
            </a>

            <div class="sidebar-label secondary-label">ANALISIS AKADEMIK</div>

            <a href="{{ route('grafik.index') }}"
               class="menu-link {{ request()->routeIs('grafik.*') ? 'active' : '' }}">
                <i class="bi bi-bar-chart-line-fill"></i>
                <span>Grafik Nilai</span>
            </a>

            <a href="{{ route('excel.index') }}"
               class="menu-link {{ request()->routeIs('excel.*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-excel-fill"></i>
                <span>Import / Export</span>
            </a>

        </nav>

        <div class="sidebar-bottom">
            <div class="sidebar-help">
                <div class="help-icon">
                    <i class="bi bi-info-circle-fill"></i>
                </div>
                <div>
                    <strong>Student Track</strong>
                    <small>Kelola akademik lebih mudah.</small>
                </div>
            </div>

            <div class="sidebar-version">
                Student Track v1.0
            </div>
        </div>

    </aside>

    <!-- MAIN AREA -->
    <main class="main-area">

        <!-- TOPBAR -->
        <header class="topbar">

            <div class="topbar-left">
                <button class="sidebar-toggle" id="sidebarToggle" type="button">
                    <i class="bi bi-list"></i>
                </button>

                <div class="breadcrumb-text">
                    <span>Workspace</span>
                    <i class="bi bi-chevron-right"></i>
                    <strong>@yield('page-name', 'Dashboard')</strong>
                </div>
            </div>

            <div class="topbar-right">
                <div class="date-display">
                    <i class="bi bi-calendar3"></i>
                    <span id="currentDate"></span>
                </div>

                <div class="profile">
                    <div class="profile-avatar">ST</div>
                    <div class="profile-info">
                        <strong>Mahasiswa</strong>
                        <small>Student Account</small>
                    </div>
                </div>
            </div>

        </header>

        <!-- PAGE CONTENT -->
        <section class="page-content">

            @if(session('success'))
                <div class="alert alert-success custom-alert">
                    <i class="bi bi-check-circle-fill"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger custom-alert">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')

        </section>

        <footer class="app-footer">
            <span>© {{ date('Y') }} Student Track</span>
            <span>Academic Management System</span>
        </footer>

    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Tanggal pada topbar
    const dateElement = document.getElementById('currentDate');

    if (dateElement) {
        dateElement.textContent = new Intl.DateTimeFormat('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        }).format(new Date());
    }

    // Sidebar responsive
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');

    sidebarToggle.addEventListener('click', function () {
        sidebar.classList.toggle('show');
    });
</script>

@stack('scripts')

</body>
</html>
