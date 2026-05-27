<x-app-layout>
    @section('title', 'Peran & Akses')
    @section('page-title', 'Peran & Akses')

    @push('styles')
        @vite(['resources/css/backend/role-permission.css'])
    @endpush

    @push('scripts')
        @vite(['resources/js/backend/role-permission.js'])
    @endpush

    <!-- Breadcrumb -->
    <div class="breadcrumb-bar" data-aos="fade-down" data-aos-duration="600">
      <div class="bc-left">
        <div class="bc-title">
          <div class="bc-icon"><i class="bi bi-person-badge"></i></div>
          Peran &amp; Akses
        </div>
        <div class="bc-desc">Kelola peran pengguna dan atur hak akses per modul secara granular.</div>
      </div>
      <div class="bc-right">
        <i class="bi bi-house-fill" style="color:var(--warning)"></i>
        <a href="{{ route('dashboard') }}">Home</a>
        <i class="bi bi-chevron-right" style="color:var(--border)"></i>
        <span style="color:var(--primary);font-weight:600">Peran &amp; Akses</span>
      </div>
    </div>

    <!-- ══ ROLE CARDS ══ -->
    <div class="role-grid" data-aos="fade-up" data-aos-delay="60">

      <!-- Super Admin -->
      <div class="role-card role-super" onclick="highlightRole('super', event)">
        <div class="role-card-top">
          <div class="role-icon">👑</div>
          <div class="role-card-actions">
            <button class="rc-btn edit" title="Edit Role" onclick="event.stopPropagation();openEditRole('Super Admin')"><i class="bi bi-pencil"></i></button>
          </div>
        </div>
        <div class="role-name">Super Admin</div>
        <div class="role-desc">Akses penuh ke seluruh sistem. Tidak dapat dihapus atau dibatasi.</div>
        <div class="role-meta">
          <span class="role-user-count"><i class="bi bi-people-fill"></i> 1 pengguna</span>
          <span class="role-perm-count">Semua Akses</span>
        </div>
      </div>

      <!-- Admin -->
      <div class="role-card role-admin" onclick="highlightRole('admin', event)">
        <div class="role-card-top">
          <div class="role-icon">🛡️</div>
          <div class="role-card-actions">
            <button class="rc-btn edit" title="Edit Role" onclick="event.stopPropagation();openEditRole('Admin')"><i class="bi bi-pencil"></i></button>
            <button class="rc-btn del"  title="Hapus Role" onclick="event.stopPropagation();openDeleteRole('Admin')"><i class="bi bi-trash"></i></button>
          </div>
        </div>
        <div class="role-name">Admin</div>
        <div class="role-desc">Mengelola konten, pengguna, dan pengaturan platform secara keseluruhan.</div>
        <div class="role-meta">
          <span class="role-user-count"><i class="bi bi-people-fill"></i> 3 pengguna</span>
          <span class="role-perm-count">38 izin</span>
        </div>
      </div>

      <!-- Moderator -->
      <div class="role-card role-mod" onclick="highlightRole('mod', event)">
        <div class="role-card-top">
          <div class="role-icon">🔍</div>
          <div class="role-card-actions">
            <button class="rc-btn edit" title="Edit Role" onclick="event.stopPropagation();openEditRole('Moderator')"><i class="bi bi-pencil"></i></button>
            <button class="rc-btn del"  title="Hapus Role" onclick="event.stopPropagation();openDeleteRole('Moderator')"><i class="bi bi-trash"></i></button>
          </div>
        </div>
        <div class="role-name">Moderator</div>
        <div class="role-desc">Moderasi resep dan komentar yang masuk dari pengguna platform.</div>
        <div class="role-meta">
          <span class="role-user-count"><i class="bi bi-people-fill"></i> 7 pengguna</span>
          <span class="role-perm-count">22 izin</span>
        </div>
      </div>

      <!-- Penulis -->
      <div class="role-card role-author" onclick="highlightRole('author', event)">
        <div class="role-card-top">
          <div class="role-icon">✍️</div>
          <div class="role-card-actions">
            <button class="rc-btn edit" title="Edit Role" onclick="event.stopPropagation();openEditRole('Penulis')"><i class="bi bi-pencil"></i></button>
            <button class="rc-btn del"  title="Hapus Role" onclick="event.stopPropagation();openDeleteRole('Penulis')"><i class="bi bi-trash"></i></button>
          </div>
        </div>
        <div class="role-name">Penulis</div>
        <div class="role-desc">Membuat dan mengelola resep milik sendiri, tidak bisa edit resep orang lain.</div>
        <div class="role-meta">
          <span class="role-user-count"><i class="bi bi-people-fill"></i> 54 pengguna</span>
          <span class="role-perm-count">12 izin</span>
        </div>
      </div>

      <!-- Member -->
      <div class="role-card role-member" onclick="highlightRole('member', event)">
        <div class="role-card-top">
          <div class="role-icon">👤</div>
          <div class="role-card-actions">
            <button class="rc-btn edit" title="Edit Role" onclick="event.stopPropagation();openEditRole('Member')"><i class="bi bi-pencil"></i></button>
            <button class="rc-btn del"  title="Hapus Role" onclick="event.stopPropagation();openDeleteRole('Member')"><i class="bi bi-trash"></i></button>
          </div>
        </div>
        <div class="role-name">Member</div>
        <div class="role-desc">Pengguna terdaftar biasa. Dapat menyimpan, memberi rating, dan berkomentar.</div>
        <div class="role-meta">
          <span class="role-user-count"><i class="bi bi-people-fill"></i> 1.247 pengguna</span>
          <span class="role-perm-count">5 izin</span>
        </div>
      </div>

      <!-- Tambah Role -->
      <div class="role-add-card" onclick="openAddRole()">
        <i class="bi bi-plus-circle-dotted"></i>
        <span>Tambah Role Baru</span>
      </div>

    </div><!-- /role-grid -->

    <!-- ══ SAVE BAR ══ -->
    <div class="save-bar" id="saveBar" data-aos="fade-up" data-aos-delay="80">
      <div class="save-bar-info">
        <i class="bi bi-info-circle"></i>
        Perubahan permission belum disimpan &nbsp;
        <span class="change-badge" id="changeCount"><i class="bi bi-pencil-fill"></i> 0 perubahan</span>
      </div>
      <button class="btn-reset" onclick="resetPermissions()"><i class="bi bi-arrow-counterclockwise"></i> Reset</button>
      <button class="btn-save"  onclick="savePermissions()"><i class="bi bi-floppy-fill"></i> Simpan Perubahan</button>
    </div>

    <!-- ══ PERMISSION MATRIX ══ -->
    <div class="matrix-card" data-aos="fade-up" data-aos-delay="100">
      <div class="matrix-header">
        <div class="matrix-title-wrap">
          <div class="matrix-icon"><i class="bi bi-table"></i></div>
          <div>
            <div class="matrix-title">Matriks Izin</div>
            <div class="matrix-sub">Centang untuk memberi akses, kosongkan untuk mencabut</div>
          </div>
        </div>
        <!-- Select role filter (mobile) -->
        <select class="d-md-none" id="roleFilter" onchange="filterByRole(this.value)"
          style="padding:.5rem .8rem;border:1.5px solid var(--border);border-radius:10px;font-family:'DM Sans',sans-serif;font-size:.83rem;background:var(--bg);color:var(--text);outline:none">
          <option value="all">Tampilkan semua</option>
          <option value="admin">Admin</option>
          <option value="mod">Moderator</option>
          <option value="author">Penulis</option>
          <option value="member">Member</option>
        </select>
      </div>

      <div class="matrix-scroll">
        <table class="perm-table" id="permTable">
          <thead>
            <tr>
              <th style="text-align:left">Izin / Permission</th>
              <!-- Super Admin -->
              <th>
                <div style="display:flex;flex-direction:column;align-items:center;gap:.3rem">
                  <span class="role-th-pill" style="background:rgba(245,158,11,.1);border-color:rgba(245,158,11,.28);color:#b45309">
                    👑 Super Admin
                  </span>
                  <span style="font-size:.65rem;color:var(--muted);font-weight:400">1 user</span>
                </div>
              </th>
              <!-- Admin -->
              <th data-col="admin">
                <div style="display:flex;flex-direction:column;align-items:center;gap:.3rem">
                  <span class="role-th-pill" style="background:var(--primary-pale);border-color:var(--primary-border);color:var(--primary)">
                    🛡️ Admin
                  </span>
                  <span style="font-size:.65rem;color:var(--muted);font-weight:400">3 users</span>
                </div>
              </th>
              <!-- Moderator -->
              <th data-col="mod">
                <div style="display:flex;flex-direction:column;align-items:center;gap:.3rem">
                  <span class="role-th-pill" style="background:rgba(59,130,246,.09);border-color:rgba(59,130,246,.25);color:#1d4ed8">
                    🔍 Moderator
                  </span>
                  <span style="font-size:.65rem;color:var(--muted);font-weight:400">7 users</span>
                </div>
              </th>
              <!-- Penulis -->
              <th data-col="author">
                <div style="display:flex;flex-direction:column;align-items:center;gap:.3rem">
                  <span class="role-th-pill" style="background:rgba(34,197,94,.09);border-color:rgba(34,197,94,.25);color:#15803d">
                    ✍️ Penulis
                  </span>
                  <span style="font-size:.65rem;color:var(--muted);font-weight:400">54 users</span>
                </div>
              </th>
              <!-- Member -->
              <th data-col="member">
                <div style="display:flex;flex-direction:column;align-items:center;gap:.3rem">
                  <span class="role-th-pill" style="background:rgba(100,116,139,.09);border-color:rgba(100,116,139,.2);color:#475569">
                    👤 Member
                  </span>
                  <span style="font-size:.65rem;color:var(--muted);font-weight:400">1.247 users</span>
                </div>
              </th>
            </tr>
          </thead>
          <tbody>

            <!-- ── RESEP ── -->
            <tr class="group-row">
              <td colspan="6">
                <span class="group-dot" style="background:#e85d26"></span>
                🍽️ Resep
              </td>
            </tr>
            <tr class="perm-row">
              <td><span class="perm-label">Lihat semua resep</span><span class="perm-slug">resep.view-all</span></td>
              <td><i class="bi bi-lock-fill perm-lock"></i></td>
              <td><input type="checkbox" class="perm-toggle" data-role="admin"   data-perm="resep.view-all"   checked onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="mod"     data-perm="resep.view-all"   checked onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="author"  data-perm="resep.view-all"   checked onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="member"  data-perm="resep.view-all"   checked onchange="onPermChange()"/></td>
            </tr>
            <tr class="perm-row">
              <td><span class="perm-label">Tambah resep</span><span class="perm-slug">resep.create</span></td>
              <td><i class="bi bi-lock-fill perm-lock"></i></td>
              <td><input type="checkbox" class="perm-toggle" data-role="admin"   data-perm="resep.create"     checked onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="mod"     data-perm="resep.create"            onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="author"  data-perm="resep.create"     checked onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="member"  data-perm="resep.create"            onchange="onPermChange()"/></td>
            </tr>
            <tr class="perm-row">
              <td><span class="perm-label">Edit resep sendiri</span><span class="perm-slug">resep.edit-own</span></td>
              <td><i class="bi bi-lock-fill perm-lock"></i></td>
              <td><input type="checkbox" class="perm-toggle" data-role="admin"   data-perm="resep.edit-own"   checked onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="mod"     data-perm="resep.edit-own"          onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="author"  data-perm="resep.edit-own"   checked onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="member"  data-perm="resep.edit-own"          onchange="onPermChange()"/></td>
            </tr>
            <tr class="perm-row">
              <td><span class="perm-label">Edit semua resep</span><span class="perm-slug">resep.edit-all</span></td>
              <td><i class="bi bi-lock-fill perm-lock"></i></td>
              <td><input type="checkbox" class="perm-toggle" data-role="admin"   data-perm="resep.edit-all"   checked onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="mod"     data-perm="resep.edit-all"          onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="author"          data-perm="resep.edit-all"          onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="member"  data-perm="resep.edit-all"          onchange="onPermChange()"/></td>
            </tr>
            <tr class="perm-row">
              <td><span class="perm-label">Hapus resep</span><span class="perm-slug">resep.delete</span></td>
              <td><i class="bi bi-lock-fill perm-lock"></i></td>
              <td><input type="checkbox" class="perm-toggle" data-role="admin"   data-perm="resep.delete"     checked onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="mod"     data-perm="resep.delete"            onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="author"  data-perm="resep.delete"            onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="member"  data-perm="resep.delete"            onchange="onPermChange()"/></td>
            </tr>
            <tr class="perm-row">
              <td><span class="perm-label">Publish / Unpublish resep</span><span class="perm-slug">resep.publish</span></td>
              <td><i class="bi bi-lock-fill perm-lock"></i></td>
              <td><input type="checkbox" class="perm-toggle" data-role="admin"   data-perm="resep.publish"    checked onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="mod"     data-perm="resep.publish"    checked onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="author"  data-perm="resep.publish"           onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="member"  data-perm="resep.publish"           onchange="onPermChange()"/></td>
            </tr>
            <tr class="perm-row">
              <td><span class="perm-label">Tandai unggulan</span><span class="perm-slug">resep.feature</span></td>
              <td><i class="bi bi-lock-fill perm-lock"></i></td>
              <td><input type="checkbox" class="perm-toggle" data-role="admin"   data-perm="resep.feature"    checked onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="mod"     data-perm="resep.feature"           onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="author"  data-perm="resep.feature"           onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="member"  data-perm="resep.feature"           onchange="onPermChange()"/></td>
            </tr>

            <!-- ── MODERASI ── -->
            <tr class="group-row">
              <td colspan="6">
                <span class="group-dot" style="background:#3b82f6"></span>
                🔍 Moderasi
              </td>
            </tr>
            <tr class="perm-row">
              <td><span class="perm-label">Lihat antrian moderasi</span><span class="perm-slug">moderasi.view</span></td>
              <td><i class="bi bi-lock-fill perm-lock"></i></td>
              <td><input type="checkbox" class="perm-toggle" data-role="admin"   checked onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="mod"     checked onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="author"         onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="member"         onchange="onPermChange()"/></td>
            </tr>
            <tr class="perm-row">
              <td><span class="perm-label">Setujui resep</span><span class="perm-slug">moderasi.approve</span></td>
              <td><i class="bi bi-lock-fill perm-lock"></i></td>
              <td><input type="checkbox" class="perm-toggle" data-role="admin"   checked onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="mod"     checked onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="author"         onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="member"         onchange="onPermChange()"/></td>
            </tr>
            <tr class="perm-row">
              <td><span class="perm-label">Tolak resep</span><span class="perm-slug">moderasi.reject</span></td>
              <td><i class="bi bi-lock-fill perm-lock"></i></td>
              <td><input type="checkbox" class="perm-toggle" data-role="admin"   checked onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="mod"     checked onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="author"         onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="member"         onchange="onPermChange()"/></td>
            </tr>
            <tr class="perm-row">
              <td><span class="perm-label">Hapus komentar</span><span class="perm-slug">komentar.delete</span></td>
              <td><i class="bi bi-lock-fill perm-lock"></i></td>
              <td><input type="checkbox" class="perm-toggle" data-role="admin"   checked onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="mod"     checked onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="author"         onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="member"         onchange="onPermChange()"/></td>
            </tr>

            <!-- ── PENGGUNA ── -->
            <tr class="group-row">
              <td colspan="6">
                <span class="group-dot" style="background:#22c55e"></span>
                👥 Pengguna
              </td>
            </tr>
            <tr class="perm-row">
              <td><span class="perm-label">Lihat daftar pengguna</span><span class="perm-slug">user.view</span></td>
              <td><i class="bi bi-lock-fill perm-lock"></i></td>
              <td><input type="checkbox" class="perm-toggle" data-role="admin"   checked onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="mod"     checked onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="author"         onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="member"         onchange="onPermChange()"/></td>
            </tr>
            <tr class="perm-row">
              <td><span class="perm-label">Edit pengguna</span><span class="perm-slug">user.edit</span></td>
              <td><i class="bi bi-lock-fill perm-lock"></i></td>
              <td><input type="checkbox" class="perm-toggle" data-role="admin"   checked onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="mod"            onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="author"         onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="member"         onchange="onPermChange()"/></td>
            </tr>
            <tr class="perm-row">
              <td><span class="perm-label">Aktifkan / Nonaktifkan user</span><span class="perm-slug">user.toggle-active</span></td>
              <td><i class="bi bi-lock-fill perm-lock"></i></td>
              <td><input type="checkbox" class="perm-toggle" data-role="admin"   checked onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="mod"            onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="author"         onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="member"         onchange="onPermChange()"/></td>
            </tr>
            <tr class="perm-row">
              <td><span class="perm-label">Ubah role pengguna</span><span class="perm-slug">user.assign-role</span></td>
              <td><i class="bi bi-lock-fill perm-lock"></i></td>
              <td><input type="checkbox" class="perm-toggle" data-role="admin"   checked onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="mod"            onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="author"         onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="member"         onchange="onPermChange()"/></td>
            </tr>
            <tr class="perm-row">
              <td><span class="perm-label">Hapus pengguna</span><span class="perm-slug">user.delete</span></td>
              <td><i class="bi bi-lock-fill perm-lock"></i></td>
              <td><input type="checkbox" class="perm-toggle" data-role="admin"   checked onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="mod"            onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="author"         onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="member"         onchange="onPermChange()"/></td>
            </tr>

            <!-- ── MASTER DATA ── -->
            <tr class="group-row">
              <td colspan="6">
                <span class="group-dot" style="background:#f59e0b"></span>
                📦 Master Data
              </td>
            </tr>
            <tr class="perm-row">
              <td><span class="perm-label">Kelola kategori</span><span class="perm-slug">kategori.manage</span></td>
              <td><i class="bi bi-lock-fill perm-lock"></i></td>
              <td><input type="checkbox" class="perm-toggle" data-role="admin"   checked onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="mod"            onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="author"         onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="member"         onchange="onPermChange()"/></td>
            </tr>
            <tr class="perm-row">
              <td><span class="perm-label">Kelola tags</span><span class="perm-slug">tags.manage</span></td>
              <td><i class="bi bi-lock-fill perm-lock"></i></td>
              <td><input type="checkbox" class="perm-toggle" data-role="admin"   checked onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="mod"     checked onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="author"         onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="member"         onchange="onPermChange()"/></td>
            </tr>
            <tr class="perm-row">
              <td><span class="perm-label">Kelola bahan makanan</span><span class="perm-slug">bahan.manage</span></td>
              <td><i class="bi bi-lock-fill perm-lock"></i></td>
              <td><input type="checkbox" class="perm-toggle" data-role="admin"   checked onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="mod"            onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="author"         onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="member"         onchange="onPermChange()"/></td>
            </tr>

            <!-- ── ANALITIK ── -->
            <tr class="group-row">
              <td colspan="6">
                <span class="group-dot" style="background:#a855f7"></span>
                📊 Analitik
              </td>
            </tr>
            <tr class="perm-row">
              <td><span class="perm-label">Lihat statistik</span><span class="perm-slug">analitik.view</span></td>
              <td><i class="bi bi-lock-fill perm-lock"></i></td>
              <td><input type="checkbox" class="perm-toggle" data-role="admin"   checked onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="mod"     checked onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="author"  checked onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="member"         onchange="onPermChange()"/></td>
            </tr>
            <tr class="perm-row">
              <td><span class="perm-label">Export laporan</span><span class="perm-slug">analitik.export</span></td>
              <td><i class="bi bi-lock-fill perm-lock"></i></td>
              <td><input type="checkbox" class="perm-toggle" data-role="admin"   checked onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="mod"            onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="author"         onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="member"         onchange="onPermChange()"/></td>
            </tr>

            <!-- ── SISTEM ── -->
            <tr class="group-row">
              <td colspan="6">
                <span class="group-dot" style="background:#64748b"></span>
                ⚙️ Sistem
              </td>
            </tr>
            <tr class="perm-row">
              <td><span class="perm-label">Pengaturan umum</span><span class="perm-slug">sistem.settings</span></td>
              <td><i class="bi bi-lock-fill perm-lock"></i></td>
              <td><input type="checkbox" class="perm-toggle" data-role="admin"   checked onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="mod"            onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="author"         onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="member"         onchange="onPermChange()"/></td>
            </tr>
            <tr class="perm-row">
              <td><span class="perm-label">Kelola peran &amp; akses</span><span class="perm-slug">sistem.role-permission</span></td>
              <td><i class="bi bi-lock-fill perm-lock"></i></td>
              <td><input type="checkbox" class="perm-toggle" data-role="admin"   checked onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="mod"            onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="author"         onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="member"         onchange="onPermChange()"/></td>
            </tr>
            <tr class="perm-row">
              <td><span class="perm-label">Akses log sistem</span><span class="perm-slug">sistem.logs</span></td>
              <td><i class="bi bi-lock-fill perm-lock"></i></td>
              <td><input type="checkbox" class="perm-toggle" data-role="admin"   checked onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="mod"            onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="author"         onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="member"         onchange="onPermChange()"/></td>
            </tr>

            <!-- ── INTERAKSI ── -->
            <tr class="group-row">
              <td colspan="6">
                <span class="group-dot" style="background:#ec4899"></span>
                💬 Interaksi
              </td>
            </tr>
            <tr class="perm-row">
              <td><span class="perm-label">Tulis komentar</span><span class="perm-slug">komentar.create</span></td>
              <td><i class="bi bi-lock-fill perm-lock"></i></td>
              <td><input type="checkbox" class="perm-toggle" data-role="admin"   checked onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="mod"     checked onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="author"  checked onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="member"  checked onchange="onPermChange()"/></td>
            </tr>
            <tr class="perm-row">
              <td><span class="perm-label">Beri rating resep</span><span class="perm-slug">rating.create</span></td>
              <td><i class="bi bi-lock-fill perm-lock"></i></td>
              <td><input type="checkbox" class="perm-toggle" data-role="admin"   checked onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="mod"     checked onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="author"  checked onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="member"  checked onchange="onPermChange()"/></td>
            </tr>
            <tr class="perm-row">
              <td><span class="perm-label">Simpan resep ke koleksi</span><span class="perm-slug">koleksi.save</span></td>
              <td><i class="bi bi-lock-fill perm-lock"></i></td>
              <td><input type="checkbox" class="perm-toggle" data-role="admin"   checked onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="mod"     checked onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="author"  checked onchange="onPermChange()"/></td>
              <td><input type="checkbox" class="perm-toggle" data-role="member"  checked onchange="onPermChange()"/></td>
            </tr>

          </tbody>
        </table>
      </div><!-- /matrix-scroll -->
    </div><!-- /matrix-card -->

    <!-- TOAST -->
    <div class="toast-wrap" id="toastWrap"></div>

    <!-- ══ MODAL TAMBAH / EDIT ROLE ══ -->
    <div class="modal fade modal-custom" id="modalRole" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered" style="max-width:480px">
        <div class="modal-content">
          <div class="modal-header-custom">
            <div class="modal-header-icon" id="modalRoleIcon"><i class="bi bi-person-badge"></i></div>
            <div>
              <div class="modal-header-title" id="modalRoleTitle">Tambah Role Baru</div>
              <div class="modal-header-sub" id="modalRoleSub">Buat peran baru dengan izin custom</div>
            </div>
            <button class="modal-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
          </div>
          <div class="modal-body-custom">

            <div class="mb-3">
              <label class="form-label-m">Nama Role <span class="req">*</span></label>
              <input type="text" class="form-input-m" id="roleNameInput" placeholder="cth: Editor, Reviewer..."/>
              <div class="form-hint-m">Gunakan nama yang jelas dan mudah dipahami</div>
            </div>

            <div class="mb-3">
              <label class="form-label-m">Slug (kode sistem)</label>
              <input type="text" class="form-input-m" id="roleSlugInput" placeholder="editor" style="font-family:monospace"/>
              <div class="form-hint-m">Lowercase, tanpa spasi. Diisi otomatis dari nama.</div>
            </div>

            <div class="mb-3">
              <label class="form-label-m">Deskripsi</label>
              <textarea class="form-input-m" rows="2" id="roleDescInput" placeholder="Jelaskan tugas dan tanggung jawab role ini..." style="resize:none"></textarea>
            </div>

            <div class="mb-3">
              <label class="form-label-m">Warna / Tema</label>
              <div class="color-picker-wrap">
                <div class="color-swatch sel" data-color="#e85d26" style="background:#e85d26" onclick="pickColor(this)"></div>
                <div class="color-swatch" data-color="#f59e0b" style="background:#f59e0b" onclick="pickColor(this)"></div>
                <div class="color-swatch" data-color="#22c55e" style="background:#22c55e" onclick="pickColor(this)"></div>
                <div class="color-swatch" data-color="#3b82f6" style="background:#3b82f6" onclick="pickColor(this)"></div>
                <div class="color-swatch" data-color="#a855f7" style="background:#a855f7" onclick="pickColor(this)"></div>
                <div class="color-swatch" data-color="#ec4899" style="background:#ec4899" onclick="pickColor(this)"></div>
                <div class="color-swatch" data-color="#64748b" style="background:#64748b" onclick="pickColor(this)"></div>
                <div class="color-swatch" data-color="#0d9488" style="background:#0d9488" onclick="pickColor(this)"></div>
              </div>
            </div>

            <div class="mb-0">
              <label class="form-label-m">Ikon</label>
              <div style="display:flex;gap:.45rem;flex-wrap:wrap">
                <span class="icon-opt sel" data-icon="👤" onclick="pickIcon(this)" style="padding:.3rem .55rem;border-radius:8px;border:1.5px solid var(--primary-border);background:var(--primary-pale);cursor:pointer;font-size:1.1rem;transition:all .2s">👤</span>
                <span class="icon-opt" data-icon="🛡️" onclick="pickIcon(this)" style="padding:.3rem .55rem;border-radius:8px;border:1.5px solid var(--border);background:transparent;cursor:pointer;font-size:1.1rem;transition:all .2s">🛡️</span>
                <span class="icon-opt" data-icon="✍️" onclick="pickIcon(this)" style="padding:.3rem .55rem;border-radius:8px;border:1.5px solid var(--border);background:transparent;cursor:pointer;font-size:1.1rem;transition:all .2s">✍️</span>
                <span class="icon-opt" data-icon="🔍" onclick="pickIcon(this)" style="padding:.3rem .55rem;border-radius:8px;border:1.5px solid var(--border);background:transparent;cursor:pointer;font-size:1.1rem;transition:all .2s">🔍</span>
                <span class="icon-opt" data-icon="📝" onclick="pickIcon(this)" style="padding:.3rem .55rem;border-radius:8px;border:1.5px solid var(--border);background:transparent;cursor:pointer;font-size:1.1rem;transition:all .2s">📝</span>
                <span class="icon-opt" data-icon="🎨" onclick="pickIcon(this)" style="padding:.3rem .55rem;border-radius:8px;border:1.5px solid var(--border);background:transparent;cursor:pointer;font-size:1.1rem;transition:all .2s">🎨</span>
                <span class="icon-opt" data-icon="📊" onclick="pickIcon(this)" style="padding:.3rem .55rem;border-radius:8px;border:1.5px solid var(--border);background:transparent;cursor:pointer;font-size:1.1rem;transition:all .2s">📊</span>
                <span class="icon-opt" data-icon="🏆" onclick="pickIcon(this)" style="padding:.3rem .55rem;border-radius:8px;border:1.5px solid var(--border);background:transparent;cursor:pointer;font-size:1.1rem;transition:all .2s">🏆</span>
              </div>
            </div>

          </div>
          <div class="modal-footer-custom">
            <button class="btn-modal-cancel" data-bs-dismiss="modal">Batal</button>
            <button class="btn-modal-primary" onclick="saveRole()"><i class="bi bi-floppy"></i> Simpan Role</button>
          </div>
        </div>
      </div>
    </div>

    <!-- ══ MODAL HAPUS ROLE ══ -->
    <div class="modal fade modal-custom" id="modalDelRole" tabindex="-1">
      <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-body-custom text-center py-4">
            <div class="del-icon-wrap"><i class="bi bi-person-x"></i></div>
            <h5 style="font-family:'Playfair Display',serif;font-weight:900;color:var(--secondary);margin-bottom:.3rem">Hapus Role?</h5>
            <p style="font-size:.82rem;color:var(--muted);line-height:1.6;margin-bottom:1.25rem">
              Role <strong id="delRoleName" style="color:var(--secondary)"></strong> akan dihapus.<br>
              <span style="color:var(--danger);font-size:.76rem">⚠ Pengguna dengan role ini akan direset ke Member.</span>
            </p>
            <div class="d-flex gap-2 justify-content-center">
              <button class="btn-modal-cancel" data-bs-dismiss="modal">Batal</button>
              <button class="btn-del-ok" onclick="confirmDeleteRole()"><i class="bi bi-trash"></i> Hapus</button>
            </div>
          </div>
        </div>
      </div>
    </div>
</x-app-layout>
