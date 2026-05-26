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
