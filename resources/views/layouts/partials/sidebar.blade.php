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

        @foreach (menus() as $category => $items)
            @php
                // Filter menu berdasarkan permission user
                $filtered = $items->filter(function ($menu) {
                    return user('can', 'menu ' . $menu->url);
                });
            @endphp

            @if ($filtered->count())
                <div class="menu-category">{{ strtoupper($category) }}</div>

                @foreach ($filtered as $menu)
                    @php
                        $isActive = request()->is($menu->url . '*') || request()->is(ltrim($menu->url, '/') . '*');
                        // Handle redirect alias/mismatch for tim vs teams
                        if ($menu->url === 'tim' && request()->is('teams*')) {
                            $isActive = true;
                        }
                    @endphp
                    <div class="nav-item-wrap">
                        <a href="{{ url($menu->url) }}" class="nav-link-custom {{ $isActive ? 'active' : '' }}" data-tooltip="{{ $menu->name }}">
                            <div class="nav-icon"><i class="{{ $menu->icon }}"></i></div>
                            <span class="sidebar-label">{{ $menu->name }}</span>
                            @if (isset($menu->badge_count) && $menu->badge_count > 0)
                                <span class="nav-badge">{{ $menu->badge_count }}</span>
                            @elseif (isset($menu->count) && $menu->count > 0)
                                <span class="nav-badge">{{ $menu->count }}</span>
                            @endif
                        </a>
                    </div>
                @endforeach
            @endif
        @endforeach

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
