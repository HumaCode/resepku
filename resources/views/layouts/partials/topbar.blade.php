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
