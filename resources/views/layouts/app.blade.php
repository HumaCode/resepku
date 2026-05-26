<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') — {{ config('app.name', 'Resepku') }}</title>

    <!-- Stylesheets -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"/>

    <!-- Vite Assets -->
    @vite(['resources/css/dashboard.css', 'resources/js/dashboard.js'])
</head>
<body>

    <!-- ── BACKGROUND FLOATS ── -->
    <div id="bgFloats"></div>

    <!-- ── SIDEBAR OVERLAY (mobile) ── -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeMobileSidebar()"></div>

    <!-- ══════════════════ SIDEBAR ══════════════════ -->
    <aside class="sidebar" id="sidebar">

        <!-- Brand -->
        <a class="sidebar-brand" href="{{ route('dashboard') }}">
            <div class="brand-icon">🔥</div>
            <span class="brand-name">Resep<span>Kita</span></span>
        </a>

        <!-- Nav -->
        <nav class="sidebar-nav" id="sidebarNav">

            <!-- ─ Main ─ -->
            <div class="nav-item-wrap">
                <a href="{{ route('dashboard') }}" class="nav-link-custom {{ request()->routeIs('dashboard') ? 'active' : '' }}" data-tooltip="Dashboard">
                    <div class="nav-icon"><i class="bi bi-grid-1x2"></i></div>
                    <span class="sidebar-label">Dashboard</span>
                </a>
            </div>

            <div class="menu-category">Master Data</div>

            <div class="nav-item-wrap">
                <a href="#" class="nav-link-custom" data-tooltip="Kategori">
                    <div class="nav-icon"><i class="bi bi-tag"></i></div>
                    <span class="sidebar-label">Kategori</span>
                </a>
            </div>
            <div class="nav-item-wrap">
                <a href="#" class="nav-link-custom" data-tooltip="Tags">
                    <div class="nav-icon"><i class="bi bi-hash"></i></div>
                    <span class="sidebar-label">Tags</span>
                </a>
            </div>
            <div class="nav-item-wrap">
                <a href="#" class="nav-link-custom" data-tooltip="Bahan">
                    <div class="nav-icon"><i class="bi bi-basket"></i></div>
                    <span class="sidebar-label">Bahan Makanan</span>
                </a>
            </div>

            <div class="menu-category">Konten</div>

            <div class="nav-item-wrap">
                <a href="#" class="nav-link-custom" data-tooltip="Resep">
                    <div class="nav-icon"><i class="bi bi-journal-richtext"></i></div>
                    <span class="sidebar-label">Resep</span>
                    <span class="nav-badge">12</span>
                </a>
            </div>
            <div class="nav-item-wrap">
                <a href="#" class="nav-link-custom" data-tooltip="Moderasi">
                    <div class="nav-icon"><i class="bi bi-shield-check"></i></div>
                    <span class="sidebar-label">Moderasi</span>
                    <span class="nav-badge">4</span>
                </a>
            </div>
            <div class="nav-item-wrap">
                <a href="#" class="nav-link-custom" data-tooltip="Koleksi">
                    <div class="nav-icon"><i class="bi bi-collection"></i></div>
                    <span class="sidebar-label">Koleksi</span>
                </a>
            </div>
            <div class="nav-item-wrap">
                <a href="#" class="nav-link-custom" data-tooltip="Komentar">
                    <div class="nav-icon"><i class="bi bi-chat-dots"></i></div>
                    <span class="sidebar-label">Komentar</span>
                    <span class="nav-badge">7</span>
                </a>
            </div>

            <div class="menu-category">Pengguna</div>

            <div class="nav-item-wrap">
                <a href="#" class="nav-link-custom" data-tooltip="Pengguna">
                    <div class="nav-icon"><i class="bi bi-people"></i></div>
                    <span class="sidebar-label">Pengguna</span>
                </a>
            </div>
            <div class="nav-item-wrap">
                <a href="#" class="nav-link-custom" data-tooltip="Peran">
                    <div class="nav-icon"><i class="bi bi-person-badge"></i></div>
                    <span class="sidebar-label">Peran & Akses</span>
                </a>
            </div>
            <div class="nav-item-wrap">
                <a href="#" class="nav-link-custom" data-tooltip="Laporan">
                    <div class="nav-icon"><i class="bi bi-flag"></i></div>
                    <span class="sidebar-label">Laporan User</span>
                    <span class="nav-badge">2</span>
                </a>
            </div>

            <div class="menu-category">Analitik</div>

            <div class="nav-item-wrap">
                <a href="#" class="nav-link-custom" data-tooltip="Statistik">
                    <div class="nav-icon"><i class="bi bi-bar-chart-line"></i></div>
                    <span class="sidebar-label">Statistik</span>
                </a>
            </div>
            <div class="nav-item-wrap">
                <a href="#" class="nav-link-custom" data-tooltip="Trending">
                    <div class="nav-icon"><i class="bi bi-graph-up-arrow"></i></div>
                    <span class="sidebar-label">Trending</span>
                </a>
            </div>

            <div class="menu-category">Sistem</div>

            <div class="nav-item-wrap">
                <a href="#" class="nav-link-custom" data-tooltip="Pengaturan">
                    <div class="nav-icon"><i class="bi bi-gear"></i></div>
                    <span class="sidebar-label">Pengaturan</span>
                </a>
            </div>
            <div class="nav-item-wrap">
                <a href="#" class="nav-link-custom" data-tooltip="Bantuan">
                    <div class="nav-icon"><i class="bi bi-question-circle"></i></div>
                    <span class="sidebar-label">Bantuan</span>
                </a>
            </div>

            <div style="height:.75rem"></div>
        </nav>

        <!-- Footer -->
        <div class="sidebar-footer">
            <div class="sidebar-footer-inner">
                <div class="sf-avatar">{{ substr(auth()->user()->name ?? 'A', 0, 1) }}</div>
                <div class="sf-info sidebar-footer-text">
                    <div class="sf-name">{{ auth()->user()->name ?? 'Ahmad Firdaus' }}</div>
                    <div class="sf-role">Super Admin</div>
                </div>
                <i class="bi bi-three-dots sf-dots sidebar-footer-text"></i>
            </div>
        </div>

    </aside>

    <!-- ══════════════════ MAIN ══════════════════ -->
    <div class="main-wrapper" id="mainWrapper">

        <!-- ── TOPBAR ── -->
        <header class="topbar">
            <button class="toggle-btn" id="toggleBtn" onclick="toggleSidebar()">
                <i class="bi bi-layout-sidebar-inset"></i>
            </button>

            <span class="topbar-title d-none d-sm-block">@yield('page-title', 'Dashboard')</span>

            <div class="topbar-right">
                <!-- Search -->
                <button class="tb-icon-btn d-none d-md-flex" title="Cari">
                    <i class="bi bi-search"></i>
                </button>
                <!-- Notification -->
                <button class="tb-icon-btn" title="Notifikasi">
                    <i class="bi bi-bell"></i>
                    <span class="tb-notif-dot"></span>
                </button>

                <!-- User Dropdown -->
                <div class="user-menu" id="userMenu">
                    <div class="user-btn" onclick="toggleUserMenu()">
                        <div class="user-avatar">{{ substr(auth()->user()->name ?? 'A', 0, 1) }}</div>
                        <div class="user-info">
                            <span class="user-name">{{ auth()->user()->name ?? 'Ahmad Firdaus' }}</span>
                            <span class="user-role">Super Admin</span>
                        </div>
                        <i class="bi bi-chevron-down user-caret ms-1"></i>
                    </div>
                    <div class="user-dropdown" id="userDropdown">
                        <a href="{{ route('profile.edit') }}" class="dd-item"><i class="bi bi-person-circle"></i> Profil Saya</a>
                        <a href="#" class="dd-item"><i class="bi bi-gear"></i> Pengaturan</a>
                        <div class="dd-divider"></div>
                        <div class="dd-item danger" onclick="showLogoutModal()">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- ── CONTENT ── -->
        <main class="content-area" id="mainContent">
            {{ $slot }}
        </main>

        <!-- ── FOOTER ── -->
        <footer class="main-footer">
            <div>
                <span class="footer-brand">Resep<span>Kita</span></span>
                &nbsp;— Dibuat dengan <span class="footer-heart">♥</span> untuk para pecinta masak Indonesia
            </div>
            <div class="footer-links">
                <a href="#">Tentang</a>
                <a href="#">Privasi</a>
                <a href="#">Bantuan</a>
            </div>
            <div>v1.0.0 &nbsp;•&nbsp; © 2025 ResepKita</div>
        </footer>

    </div>

    <!-- ── FAB ── -->
    <button class="fab" id="fab" onclick="scrollToTop()" title="Kembali ke atas">
        <i class="bi bi-arrow-up"></i>
    </button>

    <!-- ══ MODAL LOGOUT ══ -->
    <div class="modal fade modal-logout" id="logoutModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-body text-center p-4">
                    <div class="logout-icon-wrap">
                        <i class="bi bi-box-arrow-right"></i>
                    </div>
                    <h5 style="font-family:'Playfair Display',serif;font-weight:900;color:var(--secondary);margin-bottom:.4rem">Konfirmasi Logout</h5>
                    <p style="font-size:.83rem;color:var(--muted);line-height:1.6;margin-bottom:1.4rem">
                        Apakah kamu yakin ingin keluar dari akun <strong style="color:var(--secondary)">{{ auth()->user()->name ?? 'Ahmad Firdaus' }}</strong>?
                    </p>
                    <div class="d-flex gap-2 justify-content-center">
                        <button class="btn-logout-cancel" data-bs-dismiss="modal">Batal</button>
                        <button class="btn-logout-confirm" onclick="doLogout()">
                            <i class="bi bi-box-arrow-right me-1"></i> Ya, Logout
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Real Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
</body>
</html>
