      <div class="matrix-scroll">
        <table class="perm-table" id="permTable">
          <thead>
            <tr>
              <th style="text-align:left">{{ __('pengguna/role_permission.matrix.header_permission') }}</th>
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
                    <span class="role-th-pill" style="background:rgba({{ $r }},{{ $g }},{{ $b }},0.09);border-color:rgba({{ $r }},{{ $g }},{{ $b }},0.25);color:{{ $roleColor }}">
                      {{ $role->icon ?? '👤' }} {{ $role->slug === 'dev' ? 'Super Admin' : ($role->slug === 'user' ? 'Member' : $role->name) }}
                    </span>
                    <span style="font-size:.65rem;color:var(--muted);font-weight:400">{{ number_format($role->users_count, 0, ',', '.') }} user{{ $role->users_count != 1 ? 's' : '' }}</span>
                    
                    @if($role->slug !== 'dev')
                      <label class="select-all-wrap" style="--cb-color:{{ $roleColor }};--cb-color-ring:{{ $ringColor }}">
                        <input type="checkbox" class="select-all-column" data-role="{{ $role->slug }}" title="{{ __('pengguna/role_permission.matrix.select_all') }}" style="--cb-color:{{ $roleColor }};--cb-color-ring:{{ $ringColor }}" />
                        <span class="select-all-label">{{ __('pengguna/role_permission.matrix.select_all') }}</span>
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
                    <td data-col="{{ $role->slug }}">
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
