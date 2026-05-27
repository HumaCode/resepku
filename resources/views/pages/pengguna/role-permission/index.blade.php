<x-app-layout>
    @php
    if (!function_exists('hex2rgba')) {
        function hex2rgba($color, $opacity = false) {
            $default = 'rgb(0,0,0)';
            if(empty($color)) return $default; 
            if ($color[0] == '#' ) {
                $color = substr( $color, 1 );
            }
            if (strlen($color) == 6) {
                $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
            } elseif ( strlen( $color ) == 3 ) {
                $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
            } else {
                return $default;
            }
            $rgb =  array_map('hexdec', $hex);
            if($opacity){
                if(abs($opacity) > 1)
                    $opacity = 1.0;
                return 'rgba('.implode(",",$rgb).','.$opacity.')';
            } else {
                return 'rgb('.implode(",",$rgb).')';
            }
        }
    }
    @endphp

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
    <div class="role-grid" id="roleGrid" data-aos="fade-up" data-aos-delay="60">

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
          @foreach($roles as $role)
            @if($role->slug !== 'dev')
              <option value="{{ $role->slug }}">{{ $role->name === 'user' ? 'Member' : $role->name }}</option>
            @endif
          @endforeach
        </select>
      </div>

      <div class="matrix-scroll">
        <table class="perm-table" id="permTable">
          <thead>
            <tr>
              <th style="text-align:left">Izin / Permission</th>
              @foreach($roles as $role)
                @php
                  $roleColor = $role->color ?? '#64748b';
                  // Compute rgba ring (20% opacity) for CSS variable
                  preg_match('/^#?([a-f0-9]{2})([a-f0-9]{2})([a-f0-9]{2})$/i', ltrim($roleColor,'#'), $m);
                  $r = isset($m[1]) ? hexdec($m[1]) : 100;
                  $g = isset($m[2]) ? hexdec($m[2]) : 116;
                  $b = isset($m[3]) ? hexdec($m[3]) : 139;
                  $ringColor = "rgba($r,$g,$b,0.3)";
                @endphp
                <th data-col="{{ $role->slug }}" style="--cb-color:{{ $roleColor }};--cb-color-ring:{{ $ringColor }}">
                  <div style="display:flex;flex-direction:column;align-items:center;gap:.3rem">
                    <span class="role-th-pill" style="background:{{ hex2rgba($roleColor, 0.09) }};border-color:{{ hex2rgba($roleColor, 0.25) }};color:{{ $roleColor }}">
                      {{ $role->icon ?? '👤' }} {{ $role->slug === 'dev' ? 'Super Admin' : ($role->slug === 'user' ? 'Member' : $role->name) }}
                    </span>
                    <span style="font-size:.65rem;color:var(--muted);font-weight:400">{{ number_format($role->users_count, 0, ',', '.') }} user{{ $role->users_count != 1 ? 's' : '' }}</span>
                    
                    @if($role->slug !== 'dev')
                      <label class="select-all-wrap" style="--cb-color:{{ $roleColor }};--cb-color-ring:{{ $ringColor }}">
                        <input type="checkbox" class="select-all-column" data-role="{{ $role->slug }}" title="Pilih Semua / Bersihkan" style="--cb-color:{{ $roleColor }};--cb-color-ring:{{ $ringColor }}" />
                        <span class="select-all-label">Pilih Semua</span>
                      </label>
                    @endif
                  </div>
                </th>
              @endforeach
            </tr>
          </thead>
          <tbody>
            @php
            $groups = [
                '🍽️ Resep' => [
                    'color' => '#e85d26',
                    'permissions' => [
                        ['label' => 'Lihat semua resep', 'slug' => 'resep.view-all'],
                        ['label' => 'Tambah resep', 'slug' => 'resep.create'],
                        ['label' => 'Edit resep sendiri', 'slug' => 'resep.edit-own'],
                        ['label' => 'Edit semua resep', 'slug' => 'resep.edit-all'],
                        ['label' => 'Hapus resep', 'slug' => 'resep.delete'],
                        ['label' => 'Publish / Unpublish resep', 'slug' => 'resep.publish'],
                        ['label' => 'Tandai unggulan', 'slug' => 'resep.feature'],
                    ]
                ],
                '🔍 Moderasi' => [
                    'color' => '#3b82f6',
                    'permissions' => [
                        ['label' => 'Lihat antrian moderasi', 'slug' => 'moderasi.view'],
                        ['label' => 'Setujui resep', 'slug' => 'moderasi.approve'],
                        ['label' => 'Tolak resep', 'slug' => 'moderasi.reject'],
                        ['label' => 'Hapus komentar', 'slug' => 'komentar.delete'],
                    ]
                ],
                '👥 Pengguna' => [
                    'color' => '#22c55e',
                    'permissions' => [
                        ['label' => 'Lihat daftar pengguna', 'slug' => 'user.view'],
                        ['label' => 'Edit pengguna', 'slug' => 'user.edit'],
                        ['label' => 'Aktifkan / Nonaktifkan user', 'slug' => 'user.toggle-active'],
                        ['label' => 'Ubah role pengguna', 'slug' => 'user.assign-role'],
                        ['label' => 'Hapus pengguna', 'slug' => 'user.delete'],
                    ]
                ],
                '📦 Master Data' => [
                    'color' => '#f59e0b',
                    'permissions' => [
                        ['label' => 'Kelola kategori', 'slug' => 'kategori.manage'],
                        ['label' => 'Kelola tags', 'slug' => 'tags.manage'],
                        ['label' => 'Kelola bahan makanan', 'slug' => 'bahan.manage'],
                    ]
                ],
                '📊 Analitik' => [
                    'color' => '#a855f7',
                    'permissions' => [
                        ['label' => 'Lihat statistik', 'slug' => 'analitik.view'],
                        ['label' => 'Export laporan', 'slug' => 'analitik.export'],
                    ]
                ],
                '⚙️ Sistem' => [
                    'color' => '#64748b',
                    'permissions' => [
                        ['label' => 'Pengaturan umum', 'slug' => 'sistem.settings'],
                        ['label' => 'Kelola peran & akses', 'slug' => 'sistem.role-permission'],
                        ['label' => 'Akses log sistem', 'slug' => 'sistem.logs'],
                    ]
                ],
                '💬 Interaksi' => [
                    'color' => '#ec4899',
                    'permissions' => [
                        ['label' => 'Tulis komentar', 'slug' => 'komentar.create'],
                        ['label' => 'Beri rating resep', 'slug' => 'rating.create'],
                        ['label' => 'Simpan resep ke koleksi', 'slug' => 'koleksi.save'],
                    ]
                ]
            ];
            @endphp

            @foreach($groups as $groupName => $groupInfo)
              <tr class="group-row">
                <td colspan="{{ $roles->count() + 1 }}">
                  <span class="group-dot" style="background:{{ $groupInfo['color'] }}"></span>
                  {{ $groupName }}
                </td>
              </tr>
              @foreach($groupInfo['permissions'] as $perm)
                <tr class="perm-row">
                  <td>
                    <span class="perm-label">{{ $perm['label'] }}</span>
                    <span class="perm-slug">{{ $perm['slug'] }}</span>
                  </td>
                  @foreach($roles as $role)
                    @php
                      $roleColor = $role->color ?? '#64748b';
                      preg_match('/^#?([a-f0-9]{2})([a-f0-9]{2})([a-f0-9]{2})$/i', ltrim($roleColor,'#'), $m2);
                      $r2 = isset($m2[1]) ? hexdec($m2[1]) : 100;
                      $g2 = isset($m2[2]) ? hexdec($m2[2]) : 116;
                      $b2 = isset($m2[3]) ? hexdec($m2[3]) : 139;
                      $ringColor2 = "rgba($r2,$g2,$b2,0.3)";
                    @endphp
                    <td>
                      @if($role->slug === 'dev')
                        <i class="bi bi-lock-fill perm-lock"></i>
                      @else
                        <input type="checkbox" 
                               class="perm-toggle" 
                               data-role="{{ $role->slug }}"   
                               data-perm="{{ $perm['slug'] }}"
                               style="--cb-color:{{ $roleColor }};--cb-color-ring:{{ $ringColor2 }}"
                               {{ $role->permissions->contains('name', $perm['slug']) ? 'checked' : '' }} 
                               onchange="onPermChange()"/>
                      @endif
                    </td>
                  @endforeach
                </tr>
              @endforeach
            @endforeach
          </tbody>
        </table>
      </div><!-- /matrix-scroll -->
    </div><!-- /matrix-card -->

    <!-- TOAST -->
    <div class="toast-wrap" id="toastWrap"></div>

    <!-- ══ MODAL TAMBAH / EDIT ROLE ══ -->
    <div class="modal fade modal-custom" id="modalRole" tabindex="-1">
      <div class="modal-dialog modal-xl modal-dialog-centered">
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
            <div class="row g-4">
              <!-- Left Column: Details -->
              <div class="col-lg-6">
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

                <div class="mb-0">
                  <label class="form-label-m">Deskripsi</label>
                  <textarea class="form-input-m" rows="4" id="roleDescInput" placeholder="Jelaskan tugas dan tanggung jawab role ini..." style="resize:none"></textarea>
                </div>
              </div>

              <!-- Right Column: Visuals -->
              <div class="col-lg-6">
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
                  <div class="icon-selector-grid">
                    <span class="icon-opt sel" data-icon="👤" onclick="pickIcon(this)">👤</span>
                    <span class="icon-opt" data-icon="👑" onclick="pickIcon(this)">👑</span>
                    <span class="icon-opt" data-icon="🛡️" onclick="pickIcon(this)">🛡️</span>
                    <span class="icon-opt" data-icon="👥" onclick="pickIcon(this)">👥</span>
                    <span class="icon-opt" data-icon="✍️" onclick="pickIcon(this)">✍️</span>
                    <span class="icon-opt" data-icon="🔍" onclick="pickIcon(this)">🔍</span>
                    <span class="icon-opt" data-icon="📝" onclick="pickIcon(this)">📝</span>
                    <span class="icon-opt" data-icon="🎨" onclick="pickIcon(this)">🎨</span>
                    <span class="icon-opt" data-icon="📊" onclick="pickIcon(this)">📊</span>
                    <span class="icon-opt" data-icon="🏆" onclick="pickIcon(this)">🏆</span>
                    <span class="icon-opt" data-icon="⚙️" onclick="pickIcon(this)">⚙️</span>
                    <span class="icon-opt" data-icon="🔑" onclick="pickIcon(this)">🔑</span>
                    <span class="icon-opt" data-icon="🌐" onclick="pickIcon(this)">🌐</span>
                    <span class="icon-opt" data-icon="📅" onclick="pickIcon(this)">📅</span>
                    <span class="icon-opt" data-icon="✉️" onclick="pickIcon(this)">✉️</span>
                    <span class="icon-opt" data-icon="🔔" onclick="pickIcon(this)">🔔</span>
                    <span class="icon-opt" data-icon="💬" onclick="pickIcon(this)">💬</span>
                    <span class="icon-opt" data-icon="⭐" onclick="pickIcon(this)">⭐</span>
                    <span class="icon-opt" data-icon="🔥" onclick="pickIcon(this)">🔥</span>
                    <span class="icon-opt" data-icon="🍳" onclick="pickIcon(this)">🍳</span>
                    <span class="icon-opt" data-icon="🍕" onclick="pickIcon(this)">🍕</span>
                    <span class="icon-opt" data-icon="💡" onclick="pickIcon(this)">💡</span>
                    <span class="icon-opt" data-icon="🚀" onclick="pickIcon(this)">🚀</span>
                    <span class="icon-opt" data-icon="🔒" onclick="pickIcon(this)">🔒</span>
                  </div>
                </div>
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
