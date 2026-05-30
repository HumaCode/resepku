<x-app-layout>
    @section('title', 'Resep')
    @section('page-title', 'Resep')

    @push('styles')
        @vite(['resources/css/backend/resep.css'])
    @endpush

    @push('scripts')
        @vite(['resources/js/backend/resep.js'])
    @endpush

    <!-- Breadcrumb -->
    <x-breadcrumb-bar 
        title="Daftar Resep"
        icon="bi-journal-richtext"
        desc="Kelola semua resep yang diunggah oleh pengguna platform ResepKita."
        :items="[
            'Home' => route('dashboard'),
            'Konten' => '#',
            'Resep' => null
        ]"
    />

    <!-- Stat Pills -->
    <div class="resep-stat-pills" data-aos="fade-up" data-aos-delay="50">
      <div class="stat-pill pill-all">
        <i class="bi bi-journal-richtext"></i>
        <span>Total: <strong>{{ $stats['total'] }}</strong> Resep</span>
      </div>
      <div class="stat-pill pill-published">
        <i class="bi bi-check-circle-fill"></i>
        <span>Published: <strong>{{ $stats['published'] }}</strong></span>
      </div>
      <div class="stat-pill pill-pending">
        <i class="bi bi-hourglass-split"></i>
        <span>Pending: <strong>{{ $stats['pending'] }}</strong></span>
      </div>
      <div class="stat-pill pill-draft">
        <i class="bi bi-file-earmark"></i>
        <span>Draft: <strong>{{ $stats['draft'] }}</strong></span>
      </div>
      <div class="stat-pill pill-rejected">
        <i class="bi bi-x-circle-fill"></i>
        <span>Ditolak: <strong>{{ $stats['rejected'] }}</strong></span>
      </div>
    </div>

    <!-- Toolbar -->
    <div class="resep-toolbar" data-aos="fade-up" data-aos-delay="100">
      <!-- Search -->
      <div class="resep-search-wrap">
        <i class="bi bi-search"></i>
        <input type="text" class="resep-search" id="resepSearch" placeholder="Cari judul resep atau nama penulis..." oninput="filterResep()"/>
      </div>

      <!-- Filter Status -->
      <select class="resep-filter" id="resepStatusFilter" onchange="filterResep()">
        <option value="all">Semua Status</option>
        <option value="published">Published</option>
        <option value="pending">Pending</option>
        <option value="draft">Draft</option>
        <option value="rejected">Ditolak</option>
      </select>

      <!-- Filter Kategori -->
      <select class="resep-filter d-none d-md-block" id="resepKatFilter" onchange="filterResep()">
        <option value="all">Semua Kategori</option>
        @foreach($categories as $category)
          <option value="{{ $category->slug }}">{{ $category->name }}</option>
        @endforeach
      </select>

      <!-- Tombol Tambah — arahkan ke halaman create, bukan modal -->
      <a href="{{ route('recipes.create') }}" class="btn-tambah-resep ms-auto">
        <i class="bi bi-plus-lg"></i>
        <span class="d-none d-sm-inline">Tambah Resep</span>
        <span class="d-inline d-sm-none">Tambah</span>
      </a>
    </div>

    <!-- ── TABLE RESEP ── -->
    <div class="resep-table-wrap" data-aos="fade-up" data-aos-delay="150">
      <div class="resep-table-responsive">
        <table class="resep-table" id="resepTable">
          <thead>
            <tr>
              <th style="width:54px"></th><!-- thumbnail -->
              <th class="sortable" onclick="sortResep('judul')">Judul Resep <i class="bi bi-chevron-expand"></i></th>
              <th class="d-none d-lg-table-cell" style="width:130px">Penulis</th>
              <th class="sortable d-none d-md-table-cell" style="width:95px;text-align:center" onclick="sortResep('rating')">Rating <i class="bi bi-chevron-expand"></i></th>
              <th class="d-none d-md-table-cell" style="width:90px;text-align:center">Waktu</th>
              <th class="sortable d-none d-lg-table-cell" style="width:85px;text-align:center" onclick="sortResep('views')">Views <i class="bi bi-chevron-expand"></i></th>
              <th style="width:105px">Status</th>
              <th style="width:110px;text-align:center">Aksi</th>
            </tr>
          </thead>
          <tbody id="resepTbody">
            <tr>
              <td colspan="8" class="text-center py-5">
                <div class="d-flex flex-column align-items-center gap-2">
                  <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                  </div>
                  <span class="text-muted" style="font-size: .82rem">Memuat data resep...</span>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Pagination -->
    <div class="resep-pagination" id="resepPagination" style="display: none;">
      <div class="pag-info" id="paginationInfo">Menampilkan <strong>0–0</strong> dari <strong>0</strong> resep</div>
      <div class="pag-btns" id="paginationBtns">
        <!-- Buttons rendered dynamically -->
      </div>
    </div>

    <!-- MODAL HAPUS RESEP -->
    <div class="modal fade modal-delete" id="modalHapusResep" tabindex="-1">
      <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content" style="border-radius:24px;border:none;box-shadow:0 20px 60px rgba(0,0,0,.18)">
          <div class="modal-body text-center p-4">
            <div class="delete-icon-wrap"><i class="bi bi-trash3"></i></div>
            <h5 style="font-family:'Playfair Display',serif;font-weight:900;color:var(--secondary);margin-bottom:.35rem">Hapus Resep?</h5>
            <p style="font-size:.83rem;color:var(--muted);line-height:1.6;margin-bottom:1.4rem">
              Kamu yakin ingin menghapus resep<br>
              <strong id="delResepName" style="color:var(--secondary)"></strong>?<br>
              <span style="color:var(--danger);font-size:.77rem">⚠ Tindakan ini tidak dapat dibatalkan.</span>
            </p>
            <div class="d-flex gap-2 justify-content-center">
              <button class="btn-modal-cancel" data-bs-dismiss="modal">Batal</button>
              <button onclick="confirmHapusResep()" style="display:inline-flex;align-items:center;gap:.35rem;padding:.68rem 1.5rem;border:none;border-radius:12px;background:linear-gradient(135deg,#f87171,#ef4444);color:#fff;font-family:'DM Sans',sans-serif;font-weight:600;font-size:.88rem;cursor:pointer;box-shadow:0 4px 14px rgba(239,68,68,.3)">
                <i class="bi bi-trash3"></i> Hapus
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
</x-app-layout>
