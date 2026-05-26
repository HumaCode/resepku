<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Resepku') }}</title>

        <!-- Bootstrap 5 -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
        <!-- AOS -->
        <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet"/>
        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
        <!-- Bootstrap Icons -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"/>

        <!-- Styles with Vite -->
        @vite([
            'resources/css/backend/resepkita-alert.css',
            'resources/css/auth/login.css'
        ])
    </head>
    <body>
        <!-- Background Scene -->
        <div class="bg-scene">
            <div class="blob blob-1"></div>
            <div class="blob blob-2"></div>
            <div class="blob blob-3"></div>
            <div class="bg-grid"></div>
        </div>

        <!-- Floating food icons -->
        <div id="floatIcons"></div>

        <!-- Main Wrapper -->
        <div class="page-wrapper">
            <div class="container-fluid px-0">
                <div class="row g-0 main-row">
                    <!-- LEFT: Hero Panel (hidden on mobile) -->
                    <div class="col-lg-7 d-none d-lg-flex hero-panel" data-aos="fade-right" data-aos-duration="900">
                        <div>
                            <div class="hero-tag">
                                <i class="bi bi-stars"></i> Platform Resep #1
                            </div>
                            <h1 class="hero-heading">
                                Temukan &<br><span>Bagikan</span> Resep<br>Terbaikmu
                            </h1>
                            <p class="hero-desc">
                                Bergabung bersama ribuan chef rumahan dan food lover. Simpan, eksplor, dan ciptakan resep-resep menakjubkan setiap hari.
                            </p>

                            <div class="stat-cards" data-aos="fade-up" data-aos-delay="300">
                                <div class="stat-card">
                                    <div class="stat-num">12K+</div>
                                    <div class="stat-lbl">Resep Tersedia</div>
                                </div>
                                <div class="stat-card">
                                    <div class="stat-num">8K+</div>
                                    <div class="stat-lbl">Chef Aktif</div>
                                </div>
                                <div class="stat-card">
                                    <div class="stat-num">4.9★</div>
                                    <div class="stat-lbl">Rating Rata-rata</div>
                                </div>
                            </div>

                            <div class="emoji-strip" data-aos="fade-up" data-aos-delay="450">
                                <div class="emoji-pill">🍜 Mie</div>
                                <div class="emoji-pill">🍱 Bento</div>
                                <div class="emoji-pill">🥗 Salad</div>
                                <div class="emoji-pill">🍰 Dessert</div>
                                <div class="emoji-pill">🥤 Minuman</div>
                                <div class="emoji-pill">🍛 Nasi</div>
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT: Card Login -->
                    <div class="col-lg-5 card-col d-flex align-items-center justify-content-center" style="padding: 2rem 1.5rem; min-height:100vh;">
                        <div class="card-login" data-aos="fade-left" data-aos-duration="900">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- AOS JS -->
        <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
        <!-- Scripts with Vite -->
        @vite([
            'resources/js/backend/resepkita-alert.js',
            'resources/js/auth/login.js'
        ])
    </body>
</html>
