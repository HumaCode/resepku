<x-app-layout>
    @section('title', 'Dashboard')
    @section('page-title', 'Dashboard')

    <!-- Breadcrumb -->
    <div class="breadcrumb-bar" data-aos="fade-down" data-aos-duration="600">
        <div class="bc-left">
            <div class="bc-title">
                <i class="bi bi-grid-1x2"></i>
                Dashboard
            </div>
            <div class="bc-desc">Selamat datang kembali! Berikut ringkasan aktivitas hari ini.</div>
        </div>
        <div class="bc-right">
            <i class="bi bi-house-fill text-warning"></i>
            <a href="#">Home</a>
            <i class="bi bi-chevron-right bc-sep"></i>
            <span style="color:var(--primary); font-weight:600">Dashboard</span>
        </div>
    </div>

    <!-- Welcome Banner -->
    <div class="welcome-banner" data-aos="fade-up" data-aos-duration="600" data-aos-delay="100">
        <div class="d-flex align-items-start justify-content-between flex-wrap gap-3">
            <div>
                <div class="wb-emoji">👋</div>
                <div class="wb-title mt-1">Halo, {{ auth()->user()->name ?? 'Ahmad' }}! Selamat Datang</div>
                <div class="wb-sub">Ada 4 resep menunggu moderasi dan 7 komentar baru hari ini.</div>
                <a href="#" class="wb-btn"><i class="bi bi-arrow-right-circle"></i> Lihat Moderasi</a>
            </div>
            <div style="font-size:5rem; opacity:.25; line-height:1; animation: iconBob 2.5s ease-in-out infinite;">🍳</div>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3" data-aos="fade-up" data-aos-delay="50">
            <div class="stat-card c-orange">
                <div class="stat-icon"><i class="bi bi-journal-richtext"></i></div>
                <div class="stat-num">0</div>
                <div class="stat-label">Total Resep</div>
                <div class="stat-change up"><i class="bi bi-arrow-up-short"></i> +12% bulan ini</div>
                <div class="stat-bg-icon">🍜</div>
            </div>
        </div>
        <div class="col-6 col-xl-3" data-aos="fade-up" data-aos-delay="100">
            <div class="stat-card c-green">
                <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
                <div class="stat-num">0</div>
                <div class="stat-label">Total Pengguna</div>
                <div class="stat-change up"><i class="bi bi-arrow-up-short"></i> +8% bulan ini</div>
                <div class="stat-bg-icon">👤</div>
            </div>
        </div>
        <div class="col-6 col-xl-3" data-aos="fade-up" data-aos-delay="150">
            <div class="stat-card c-blue">
                <div class="stat-icon"><i class="bi bi-eye-fill"></i></div>
                <div class="stat-num">0</div>
                <div class="stat-label">Total Views</div>
                <div class="stat-change up"><i class="bi bi-arrow-up-short"></i> +23% bulan ini</div>
                <div class="stat-bg-icon">👁️</div>
            </div>
        </div>
        <div class="col-6 col-xl-3" data-aos="fade-up" data-aos-delay="200">
            <div class="stat-card c-yellow">
                <div class="stat-icon"><i class="bi bi-star-fill"></i></div>
                <div class="stat-num">0</div>
                <div class="stat-label">Rating Rata-rata</div>
                <div class="stat-change down"><i class="bi bi-arrow-down-short"></i> -0.03 minggu ini</div>
                <div class="stat-bg-icon">⭐</div>
            </div>
        </div>
    </div>

    <!-- Quick bar -->
    <div class="quick-bar mb-4" data-aos="fade-up" data-aos-delay="100">
        <div class="qb-item">
            <div class="qb-num">4</div>
            <div class="qb-label">Pending Moderasi</div>
        </div>
        <div class="qb-divider"></div>
        <div class="qb-item">
            <div class="qb-num">7</div>
            <div class="qb-label">Komentar Baru</div>
        </div>
        <div class="qb-divider"></div>
        <div class="qb-item">
            <div class="qb-num">2</div>
            <div class="qb-label">Laporan User</div>
        </div>
        <div class="qb-divider"></div>
        <div class="qb-item">
            <div class="qb-num">15</div>
            <div class="qb-label">Resep Baru Hari Ini</div>
        </div>
        <div class="qb-divider"></div>
        <div class="qb-item">
            <div class="qb-num">312</div>
            <div class="qb-label">Pengunjung Aktif</div>
        </div>
    </div>

    <!-- Recipe cards + Activity -->
    <div class="row g-3 mb-4">
        <!-- Recent recipes -->
        <div class="col-lg-8" data-aos="fade-up" data-aos-delay="100">
            <div class="section-heading">
                <div class="section-title">Resep Terbaru</div>
                <a href="#" class="btn-see-all">Lihat Semua →</a>
            </div>
            <div class="row g-3">
                <div class="col-sm-6 col-md-4">
                    <div class="recipe-card">
                        <div class="recipe-thumb" style="background:linear-gradient(135deg,#fff3e0,#ffe0b2)">
                            🍛
                            <span class="recipe-badge badge-published">Published</span>
                        </div>
                        <div class="recipe-body">
                            <div class="recipe-name">Rendang Padang Asli</div>
                            <div class="recipe-meta">
                                <i class="bi bi-clock"></i> 120 mnt
                                <span class="recipe-dot">•</span>
                                <i class="bi bi-star-fill text-warning"></i> 4.9
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <div class="recipe-card">
                        <div class="recipe-thumb" style="background:linear-gradient(135deg,#e8f5e9,#c8e6c9)">
                            🥗
                            <span class="recipe-badge badge-published">Published</span>
                        </div>
                        <div class="recipe-body">
                            <div class="recipe-name">Gado-gado Jakarta</div>
                            <div class="recipe-meta">
                                <i class="bi bi-clock"></i> 45 mnt
                                <span class="recipe-dot">•</span>
                                <i class="bi bi-star-fill text-warning"></i> 4.7
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <div class="recipe-card">
                        <div class="recipe-thumb" style="background:linear-gradient(135deg,#fce4ec,#f8bbd9)">
                            🍰
                            <span class="recipe-badge badge-pending">Pending</span>
                        </div>
                        <div class="recipe-body">
                            <div class="recipe-name">Lapis Legit Spesial</div>
                            <div class="recipe-meta">
                                <i class="bi bi-clock"></i> 90 mnt
                                <span class="recipe-dot">•</span>
                                <i class="bi bi-star-fill text-warning"></i> 4.8
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <div class="recipe-card">
                        <div class="recipe-thumb" style="background:linear-gradient(135deg,#e3f2fd,#bbdefb)">
                            🥤
                            <span class="recipe-badge badge-published">Published</span>
                        </div>
                        <div class="recipe-body">
                            <div class="recipe-name">Es Teh Cincau Segar</div>
                            <div class="recipe-meta">
                                <i class="bi bi-clock"></i> 15 mnt
                                <span class="recipe-dot">•</span>
                                <i class="bi bi-star-fill text-warning"></i> 4.6
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <div class="recipe-card">
                        <div class="recipe-thumb" style="background:linear-gradient(135deg,#fff8e1,#ffecb3)">
                            🍜
                            <span class="recipe-badge badge-pending">Pending</span>
                        </div>
                        <div class="recipe-body">
                            <div class="recipe-name">Mie Ayam Bakso Solo</div>
                            <div class="recipe-meta">
                                <i class="bi bi-clock"></i> 60 mnt
                                <span class="recipe-dot">•</span>
                                <i class="bi bi-star-fill text-warning"></i> 4.8
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <div class="recipe-card">
                        <div class="recipe-thumb" style="background:linear-gradient(135deg,#f3e5f5,#e1bee7)">
                            🧁
                            <span class="recipe-badge badge-draft">Draft</span>
                        </div>
                        <div class="recipe-body">
                            <div class="recipe-name">Cupcake Pandan Keju</div>
                            <div class="recipe-meta">
                                <i class="bi bi-clock"></i> 50 mnt
                                <span class="recipe-dot">•</span>
                                <i class="bi bi-star-fill text-warning"></i> —
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity Feed -->
        <div class="col-lg-4" data-aos="fade-up" data-aos-delay="150">
            <div class="section-heading">
                <div class="section-title">Aktivitas Terbaru</div>
            </div>
            <div class="activity-card">
                <div class="activity-item">
                    <div class="act-icon" style="background:#fff0e8;color:var(--primary)"><i class="bi bi-person-plus"></i></div>
                    <div class="act-body">
                        <div class="act-text"><strong>Siti Nurhaliza</strong> mendaftar sebagai pengguna baru</div>
                        <div class="act-time"><i class="bi bi-clock me-1"></i>5 menit lalu</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="act-icon" style="background:#d1fae5;color:#16a34a"><i class="bi bi-journal-plus"></i></div>
                    <div class="act-body">
                        <div class="act-text"><strong>Budi Santoso</strong> mengirim resep <em>Sate Ayam Madura</em></div>
                        <div class="act-time"><i class="bi bi-clock me-1"></i>18 menit lalu</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="act-icon" style="background:#dbeafe;color:#2563eb"><i class="bi bi-star-fill"></i></div>
                    <div class="act-body">
                        <div class="act-text"><strong>Rina Agustina</strong> memberi rating 5⭐ pada <em>Rendang Padang</em></div>
                        <div class="act-time"><i class="bi bi-clock me-1"></i>34 menit lalu</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="act-icon" style="background:#fef9c3;color:#d97706"><i class="bi bi-chat-dots"></i></div>
                    <div class="act-body">
                        <div class="act-text"><strong>Dian Permata</strong> berkomentar pada resep <em>Gado-gado</em></div>
                        <div class="act-time"><i class="bi bi-clock me-1"></i>1 jam lalu</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="act-icon" style="background:#fee2e2;color:#dc2626"><i class="bi bi-flag-fill"></i></div>
                    <div class="act-body">
                        <div class="act-text"><strong>Sistem</strong> mendeteksi laporan baru pada komentar</div>
                        <div class="act-time"><i class="bi bi-clock me-1"></i>2 jam lalu</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="act-icon" style="background:#f3e5f5;color:#7c3aed"><i class="bi bi-bookmark-check"></i></div>
                    <div class="act-body">
                        <div class="act-text"><strong>Resep Lapis Legit</strong> ditambahkan ke 12 koleksi</div>
                        <div class="act-time"><i class="bi bi-clock me-1"></i>3 jam lalu</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="mb-4" data-aos="fade-up" data-aos-delay="100">
        <div class="section-heading">
            <div class="section-title">Pengguna Terdaftar</div>
            <a href="#" class="btn-see-all">Lihat Semua →</a>
        </div>
        <div class="table-card">
            <table class="table">
                <colgroup>
                    <col class="col-no"/>
                    <col class="col-user"/>
                    <col class="col-email d-none d-md-table-column"/>
                    <col class="col-resep d-none d-sm-table-column"/>
                    <col class="col-status d-none d-sm-table-column"/>
                    <col class="col-join d-none d-lg-table-column"/>
                    <col class="col-aksi"/>
                </colgroup>
                <thead>
                    <tr>
                        <th class="col-no">#</th>
                        <th class="col-user">Pengguna</th>
                        <th class="col-email d-none d-md-table-cell">Email</th>
                        <th class="col-resep d-none d-sm-table-cell" style="text-align:center">Resep</th>
                        <th class="col-status d-none d-sm-table-cell">Status</th>
                        <th class="col-join d-none d-lg-table-cell">Bergabung</th>
                        <th class="col-aksi">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="col-no"><span style="color:var(--muted);font-size:.75rem">01</span></td>
                        <td class="col-user">
                            <div class="d-flex align-items-center gap-2">
                                <div style="width:32px;height:32px;border-radius:9px;background:linear-gradient(135deg,#ffd5bc,#e85d26);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.8rem;flex-shrink:0">B</div>
                                <div style="min-width:0">
                                    <div class="user-cell-name">Budi Santoso</div>
                                    <div class="user-cell-uname">@chef_budi</div>
                                </div>
                            </div>
                        </td>
                        <td class="col-email d-none d-md-table-cell" style="color:var(--muted);font-size:.8rem">budi@email.com</td>
                        <td class="col-resep d-none d-sm-table-cell" style="text-align:center;font-weight:600">24</td>
                        <td class="col-status d-none d-sm-table-cell"><span class="recipe-badge badge-published">Aktif</span></td>
                        <td class="col-join d-none d-lg-table-cell" style="color:var(--muted);font-size:.78rem">12 Jan 2025</td>
                        <td class="col-aksi">
                            <div class="d-flex gap-1">
                                <button class="tb-icon-btn" style="width:28px;height:28px;border-radius:7px;font-size:.8rem" title="Edit"><i class="bi bi-pencil"></i></button>
                                <button class="tb-icon-btn" style="width:28px;height:28px;border-radius:7px;font-size:.8rem;color:var(--danger)" title="Hapus"><i class="bi bi-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="col-no"><span style="color:var(--muted);font-size:.75rem">02</span></td>
                        <td class="col-user">
                            <div class="d-flex align-items-center gap-2">
                                <div style="width:32px;height:32px;border-radius:9px;background:linear-gradient(135deg,#c8e6c9,#388e3c);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.8rem;flex-shrink:0">S</div>
                                <div style="min-width:0">
                                    <div class="user-cell-name">Siti Nurhaliza</div>
                                    <div class="user-cell-uname">@siti_masak</div>
                                </div>
                            </div>
                        </td>
                        <td class="col-email d-none d-md-table-cell" style="color:var(--muted);font-size:.8rem">siti@email.com</td>
                        <td class="col-resep d-none d-sm-table-cell" style="text-align:center;font-weight:600">8</td>
                        <td class="col-status d-none d-sm-table-cell"><span class="recipe-badge badge-published">Aktif</span></td>
                        <td class="col-join d-none d-lg-table-cell" style="color:var(--muted);font-size:.78rem">3 Feb 2025</td>
                        <td class="col-aksi">
                            <div class="d-flex gap-1">
                                <button class="tb-icon-btn" style="width:28px;height:28px;border-radius:7px;font-size:.8rem" title="Edit"><i class="bi bi-pencil"></i></button>
                                <button class="tb-icon-btn" style="width:28px;height:28px;border-radius:7px;font-size:.8rem;color:var(--danger)" title="Hapus"><i class="bi bi-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="col-no"><span style="color:var(--muted);font-size:.75rem">03</span></td>
                        <td class="col-user">
                            <div class="d-flex align-items-center gap-2">
                                <div style="width:32px;height:32px;border-radius:9px;background:linear-gradient(135deg,#bbdefb,#1976d2);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.8rem;flex-shrink:0">R</div>
                                <div style="min-width:0">
                                    <div class="user-cell-name">Rina Agustina</div>
                                    <div class="user-cell-uname">@rina_foodie</div>
                                </div>
                            </div>
                        </td>
                        <td class="col-email d-none d-md-table-cell" style="color:var(--muted);font-size:.8rem">rina@email.com</td>
                        <td class="col-resep d-none d-sm-table-cell" style="text-align:center;font-weight:600">3</td>
                        <td class="col-status d-none d-sm-table-cell"><span class="recipe-badge badge-pending">Pending</span></td>
                        <td class="col-join d-none d-lg-table-cell" style="color:var(--muted);font-size:.78rem">20 Feb 2025</td>
                        <td class="col-aksi">
                            <div class="d-flex gap-1">
                                <button class="tb-icon-btn" style="width:28px;height:28px;border-radius:7px;font-size:.8rem" title="Edit"><i class="bi bi-pencil"></i></button>
                                <button class="tb-icon-btn" style="width:28px;height:28px;border-radius:7px;font-size:.8rem;color:var(--danger)" title="Hapus"><i class="bi bi-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="col-no"><span style="color:var(--muted);font-size:.75rem">04</span></td>
                        <td class="col-user">
                            <div class="d-flex align-items-center gap-2">
                                <div style="width:32px;height:32px;border-radius:9px;background:linear-gradient(135deg,#e1bee7,#7b1fa2);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.8rem;flex-shrink:0">D</div>
                                <div style="min-width:0">
                                    <div class="user-cell-name">Dian Permata</div>
                                    <div class="user-cell-uname">@dian_chef</div>
                                </div>
                            </div>
                        </td>
                        <td class="col-email d-none d-md-table-cell" style="color:var(--muted);font-size:.8rem">dian@email.com</td>
                        <td class="col-resep d-none d-sm-table-cell" style="text-align:center;font-weight:600">17</td>
                        <td class="col-status d-none d-sm-table-cell"><span class="recipe-badge badge-published">Aktif</span></td>
                        <td class="col-join d-none d-lg-table-cell" style="color:var(--muted);font-size:.78rem">5 Mar 2025</td>
                        <td class="col-aksi">
                            <div class="d-flex gap-1">
                                <button class="tb-icon-btn" style="width:28px;height:28px;border-radius:7px;font-size:.8rem" title="Edit"><i class="bi bi-pencil"></i></button>
                                <button class="tb-icon-btn" style="width:28px;height:28px;border-radius:7px;font-size:.8rem;color:var(--danger)" title="Hapus"><i class="bi bi-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
