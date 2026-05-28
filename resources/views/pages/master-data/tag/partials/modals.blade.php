<!-- ══════════════════════════════════════
     MODAL TAMBAH / EDIT TAG
     ══════════════════════════════════════ -->
<div class="modal fade modal-cat" id="modalTambahTag" tabindex="-1" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <div class="modal-title">
          <i class="bi bi-hash"></i>
          <span id="modalTagTitle">{{ __('master-data/tag.modal.title_add') }}</span>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-12">
            <!-- Nama Tag -->
            <x-form-label :required="true">{{ __('master-data/tag.modal.label_name') }}</x-form-label>
            <x-form-input id="tagName" :placeholder="__('master-data/tag.modal.placeholder_name')" class="mb-1" />
            <div class="form-hint-m mb-3">{{ __('master-data/tag.modal.hint_name') }}</div>

            <!-- Slug Tag -->
            <x-form-label :required="true">{{ __('master-data/tag.modal.label_slug') }}</x-form-label>
            <x-form-input id="tagSlug" :placeholder="__('master-data/tag.modal.placeholder_slug')" readonly class="mb-1" />
            <div class="form-hint-m mb-3">{{ __('master-data/tag.modal.hint_slug') }}</div>

            <!-- Pilih Warna -->
            <x-form-label :required="true">{{ __('master-data/tag.modal.label_color') }}</x-form-label>
            <div class="color-palette mb-3">
              <div class="color-opt selected" style="background:#ef4444" data-c="#ef4444" onclick="pickColor(this)"></div>
              <div class="color-opt" style="background:#f59e0b" data-c="#f59e0b" onclick="pickColor(this)"></div>
              <div class="color-opt" style="background:#e85d26" data-c="#e85d26" onclick="pickColor(this)"></div>
              <div class="color-opt" style="background:#ec4899" data-c="#ec4899" onclick="pickColor(this)"></div>
              <div class="color-opt" style="background:#10b981" data-c="#10b981" onclick="pickColor(this)"></div>
              <div class="color-opt" style="background:#22c55e" data-c="#22c55e" onclick="pickColor(this)"></div>
              <div class="color-opt" style="background:#dc2626" data-c="#dc2626" onclick="pickColor(this)"></div>
              <div class="color-opt" style="background:#7c3aed" data-c="#7c3aed" onclick="pickColor(this)"></div>
              <div class="color-opt" style="background:#f472b6" data-c="#f472b6" onclick="pickColor(this)"></div>
              <div class="color-opt" style="background:#14b8a6" data-c="#14b8a6" onclick="pickColor(this)"></div>
              <div class="color-opt" style="background:#0ea5e9" data-c="#0ea5e9" onclick="pickColor(this)"></div>
              <div class="color-opt" style="background:#06b6d4" data-c="#06b6d4" onclick="pickColor(this)"></div>
              <div class="color-opt" style="background:#94a3b8" data-c="#94a3b8" onclick="pickColor(this)"></div>
            </div>

            <!-- Preview Chip -->
            <x-form-label>{{ __('master-data/tag.modal.label_preview') }}</x-form-label>
            <div class="tag-preview-wrap mb-3">
              <div class="tag-preview-chip">
                <span class="tag-preview-dot" id="prevDot"></span>
                <span class="tag-preview-name" id="prevName">#</span>
              </div>
              <div class="tag-preview-hint">{{ __('master-data/tag.modal.hint_preview') }}</div>
            </div>

            <!-- Opsi Tambahan -->
            <x-form-label>{{ __('master-data/tag.modal.label_additional') }}</x-form-label>
            
            <!-- Hot Toggle -->
            <div class="modal-toggle-wrap mb-2">
              <div>
                <div class="modal-toggle-label">{{ __('master-data/tag.modal.label_toggle_hot') }}</div>
                <div class="modal-toggle-sub">{{ __('master-data/tag.modal.hint_toggle_hot') }}</div>
              </div>
              <div class="form-check form-switch mb-0">
                <input class="form-check-input" type="checkbox" id="tagHot" />
              </div>
            </div>

            <!-- Status Toggle -->
            <div class="modal-toggle-wrap">
              <div>
                <div class="modal-toggle-label">{{ __('master-data/tag.modal.label_toggle_status') }}</div>
                <div class="modal-toggle-sub">{{ __('master-data/tag.modal.hint_toggle_status') }}</div>
              </div>
              <div class="form-check form-switch mb-0">
                <input class="form-check-input" type="checkbox" id="tagStatus" checked />
              </div>
            </div>

          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">{{ __('master-data/tag.modal.btn_cancel') }}</button>
        <button type="button" class="btn-modal-save" id="btnSaveTag" onclick="saveTag()">
          <i class="bi bi-save"></i> {{ __('master-data/tag.modal.btn_save') }}
        </button>
      </div>
    </div>
  </div>
</div>

<!-- ══════════════════════════════════════
     MODAL HAPUS TAG (CONFIRMATION)
     ══════════════════════════════════════ -->
<div class="modal fade modal-delete" id="modalHapusTag" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
    <div class="modal-content text-center p-4">
      <div class="delete-icon-wrap">
        <i class="bi bi-exclamation-triangle-fill"></i>
      </div>
      <h5 class="fw-bold mb-2">Hapus Tag</h5>
      <p class="text-muted mb-4 fs-7">Apakah Anda yakin ingin menghapus tag <span id="delTagName" class="fw-bold text-danger"></span>? Tindakan ini tidak dapat dibatalkan.</p>
      <div class="d-flex gap-2 justify-content-center">
        <button type="button" class="btn-modal-cancel w-100" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-danger w-100 py-2" id="btnConfirmDelete" onclick="confirmDelete()" style="border-radius: 12px; font-weight: 600; font-family: 'Poppins', sans-serif;">Hapus</button>
      </div>
    </div>
  </div>
</div>
