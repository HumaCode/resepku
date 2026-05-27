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
    <x-breadcrumb-bar 
        title="Peran & Akses"
        icon="bi-person-badge"
        desc="Kelola peran pengguna dan atur hak akses per modul secara granular."
        :items="[
            'Home' => route('dashboard'),
            'Peran & Akses' => null
        ]"
    />

    <!-- ══ ROLE CARDS ══ -->
    <div class="role-grid" id="roleGrid" data-aos="fade-up" data-aos-delay="60">

      <!-- Skeleton loader (rendered initially by server and replaced by JS) -->
      <div class="role-card skeleton-card">
        <div class="role-card-top">
          <div class="skeleton-icon-circle skeleton-shimmer"></div>
          <div class="role-card-actions">
            <div class="skeleton-action-btn skeleton-shimmer"></div>
          </div>
        </div>
        <div class="skeleton-text skeleton-title skeleton-shimmer"></div>
        <div class="skeleton-text skeleton-desc-line-1 skeleton-shimmer"></div>
        <div class="skeleton-text skeleton-desc-line-2 skeleton-shimmer"></div>
        <div class="role-meta" style="margin-top: auto; display: flex; justify-content: space-between; align-items: center; width: 100%">
          <div class="skeleton-text skeleton-meta-1 skeleton-shimmer"></div>
          <div class="skeleton-text skeleton-meta-2 skeleton-shimmer"></div>
        </div>
      </div>
      
      <div class="role-card skeleton-card">
        <div class="role-card-top">
          <div class="skeleton-icon-circle skeleton-shimmer"></div>
          <div class="role-card-actions">
            <div class="skeleton-action-btn skeleton-shimmer"></div>
          </div>
        </div>
        <div class="skeleton-text skeleton-title skeleton-shimmer"></div>
        <div class="skeleton-text skeleton-desc-line-1 skeleton-shimmer"></div>
        <div class="skeleton-text skeleton-desc-line-2 skeleton-shimmer"></div>
        <div class="role-meta" style="margin-top: auto; display: flex; justify-content: space-between; align-items: center; width: 100%">
          <div class="skeleton-text skeleton-meta-1 skeleton-shimmer"></div>
          <div class="skeleton-text skeleton-meta-2 skeleton-shimmer"></div>
        </div>
      </div>

      <div class="role-card skeleton-card">
        <div class="role-card-top">
          <div class="skeleton-icon-circle skeleton-shimmer"></div>
          <div class="role-card-actions">
            <div class="skeleton-action-btn skeleton-shimmer"></div>
          </div>
        </div>
        <div class="skeleton-text skeleton-title skeleton-shimmer"></div>
        <div class="skeleton-text skeleton-desc-line-1 skeleton-shimmer"></div>
        <div class="skeleton-text skeleton-desc-line-2 skeleton-shimmer"></div>
        <div class="role-meta" style="margin-top: auto; display: flex; justify-content: space-between; align-items: center; width: 100%">
          <div class="skeleton-text skeleton-meta-1 skeleton-shimmer"></div>
          <div class="skeleton-text skeleton-meta-2 skeleton-shimmer"></div>
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

    <!-- Include modals partial -->
    @include('pages.pengguna.role-permission.partials.modals')
</x-app-layout>
