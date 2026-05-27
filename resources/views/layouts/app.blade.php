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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"/>

    <!-- Vite Assets -->
    @vite(['resources/css/backend/global.css', 'resources/js/backend/global.js'])
    @stack('styles')
</head>
<body>

    <!-- ── BACKGROUND FLOATS ── -->
    <div id="bgFloats"></div>

    <!-- ── SIDEBAR OVERLAY (mobile) ── -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeMobileSidebar()"></div>

    <!-- ══════════════════ SIDEBAR ══════════════════ -->
    @include('layouts.partials.sidebar')

    <!-- ══════════════════ MAIN ══════════════════ -->
    <div class="main-wrapper" id="mainWrapper">

        <!-- ── TOPBAR ── -->
        @include('layouts.partials.topbar')

        <!-- ── CONTENT ── -->
        <main class="content-area" id="mainContent">
            {{ $slot }}
        </main>

        <!-- ── FOOTER ── -->
        @include('layouts.partials.footer')

    </div>

    <!-- ── FAB ── -->
    <button class="fab" id="fab" onclick="scrollToTop()" title="Kembali ke atas">
        <i class="bi bi-arrow-up"></i>
    </button>

    <!-- ══ MODAL LOGOUT ══ -->
    @include('layouts.partials.logout-modal')

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    @stack('scripts')
</body>
</html>
