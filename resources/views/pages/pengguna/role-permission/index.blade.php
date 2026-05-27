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
    <div class="matrix-card" id="matrixCard" data-aos="fade-up" data-aos-delay="100">
      <!-- Loading Overlay -->
      <div class="matrix-card-overlay">
        <div class="matrix-spinner"></div>
        <div class="matrix-loading-text">Menyinkronkan Matriks...</div>
      </div>

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

      <div id="matrixTableWrapper">
        @include('pages.pengguna.role-permission.partials.matrix-table')
      </div>
    </div><!-- /matrix-card -->

    <!-- TOAST -->
    <div class="toast-wrap" id="toastWrap"></div>

    <!-- Include modals partial -->
    @include('pages.pengguna.role-permission.partials.modals')
</x-app-layout>
