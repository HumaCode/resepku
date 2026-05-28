<!-- ══════════════════════════════════════
     MODAL TAMBAH / EDIT PERMISSION
     ══════════════════════════════════════ -->
<div class="modal fade modal-cat" id="modalTambahPerm" tabindex="-1" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width: 480px;">
    <div class="modal-content">
      <div class="modal-header">
        <div class="modal-title">
          <i class="bi bi-key"></i>
          <span id="modalPermTitle">{{ __('pengguna/permission.modal.title_add') }}</span>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-12">
            <!-- Nama Permission -->
            <x-form-label :required="true">{{ __('pengguna/permission.modal.label_name') }}</x-form-label>
            <x-form-input id="permName" :placeholder="__('pengguna/permission.modal.placeholder_name')" class="mb-1" />
            <div class="form-hint-m mb-3">{{ __('pengguna/permission.modal.hint_name') }}</div>

            <!-- Guard Name -->
            <x-form-label :required="true">{{ __('pengguna/permission.modal.label_guard') }}</x-form-label>
            <x-form-input id="permGuard" value="web" :placeholder="__('pengguna/permission.modal.placeholder_guard')" class="mb-3" />

            <!-- Status Toggle -->
            <x-form-label>{{ __('pengguna/permission.modal.label_additional') }}</x-form-label>
            <div class="modal-toggle-wrap">
              <div>
                <div class="modal-toggle-label">{{ __('pengguna/permission.modal.label_toggle_status') }}</div>
                <div class="modal-toggle-sub">{{ __('pengguna/permission.modal.hint_toggle_status') }}</div>
              </div>
              <div class="form-check form-switch mb-0">
                <input class="form-check-input" type="checkbox" id="permStatus" checked />
              </div>
            </div>

          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">{{ __('pengguna/permission.modal.btn_cancel') }}</button>
        <button type="button" class="btn-modal-save" id="btnSavePerm" onclick="savePerm()">
          <i class="bi bi-save"></i> {{ __('pengguna/permission.modal.btn_save') }}
        </button>
      </div>
    </div>
  </div>
</div>

<!-- ══════════════════════════════════════
     MODAL HAPUS PERMISSION (CONFIRMATION)
     ══════════════════════════════════════ -->
<div class="modal fade modal-delete" id="modalHapusPerm" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
    <div class="modal-content text-center p-4">
      <div class="delete-icon-wrap">
        <i class="bi bi-exclamation-triangle-fill"></i>
      </div>
      <h5 class="fw-bold mb-2">{{ __('pengguna/permission.delete.title') }}</h5>
      <p class="text-muted mb-4 fs-7">Apakah Anda yakin ingin menghapus permission <span id="delPermName" class="fw-bold text-danger"></span>? Tindakan ini tidak dapat dibatalkan.</p>
      <div class="d-flex gap-2 justify-content-center">
        <button type="button" class="btn-modal-cancel w-100" data-bs-dismiss="modal">{{ __('pengguna/permission.delete.btn_cancel') }}</button>
        <button type="button" class="btn btn-danger w-100 py-2" id="btnConfirmDelete" onclick="confirmDelete()" style="border-radius: 12px; font-weight: 600; font-family: 'Poppins', sans-serif;">{{ __('pengguna/permission.delete.btn_delete') }}</button>
      </div>
    </div>
  </div>
</div>
