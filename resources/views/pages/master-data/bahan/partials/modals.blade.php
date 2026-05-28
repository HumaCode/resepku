<!-- ══════════════════════════════════════
     MODAL TAMBAH / EDIT BAHAN MAKANAN
     ══════════════════════════════════════ -->
<div class="modal fade modal-cat" id="modalTambahBahan" tabindex="-1" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width: 500px;">
    <div class="modal-content">
      <div class="modal-header">
        <div class="modal-title">
          <i class="bi bi-basket"></i>
          <span id="modalBahanTitle">{{ __('master-data/ingredient.modal.title_add') }}</span>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-12">
            <!-- Emoji Picker -->
            <x-form-label :required="true">{{ __('master-data/ingredient.modal.label_emoji') }}</x-form-label>
            <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:.5rem">
              <div id="emojiPreview" style="width:52px;height:52px;border-radius:14px;background:var(--primary-pale);border:2px dashed var(--primary-border);display:flex;align-items:center;justify-content:center;font-size:1.8rem;cursor:pointer;flex-shrink:0">🥦</div>
              <div style="flex:1">
                <div class="emoji-picker-grid" id="emojiGrid">
                  <!-- Javascript will populate -->
                </div>
              </div>
            </div>
            <div class="form-hint-m mb-3">{{ __('master-data/ingredient.modal.hint_emoji') }}</div>

            <!-- Nama Bahan -->
            <x-form-label :required="true">{{ __('master-data/ingredient.modal.label_name') }}</x-form-label>
            <x-form-input id="bahanName" :placeholder="__('master-data/ingredient.modal.placeholder_name')" class="mb-1" />
            <div class="form-hint-m mb-3">{{ __('master-data/ingredient.modal.hint_name') }}</div>

            <!-- Slug -->
            <x-form-label :required="true">{{ __('master-data/ingredient.modal.label_slug') }}</x-form-label>
            <x-form-input id="bahanSlug" :placeholder="__('master-data/ingredient.modal.placeholder_slug')" readonly class="mb-3" />

            <!-- Kategori -->
            <x-form-label :required="true">{{ __('master-data/ingredient.modal.label_category') }}</x-form-label>
            <select class="form-input-m mb-3" id="bahanKat"
              style="background-image:url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 16 16'%3E%3Cpath fill='%238a7060' d='M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E&quot;);background-repeat:no-repeat;background-position:right 12px center;padding-right:2.5rem;">
              <option value="">{{ __('master-data/ingredient.modal.placeholder_category') }}</option>
              <option value="sayuran">🥦 Sayuran</option>
              <option value="daging">🥩 Daging</option>
              <option value="bumbu">🌶️ Bumbu & Rempah</option>
              <option value="karbohidrat">🌾 Karbohidrat</option>
              <option value="seafood">🦐 Seafood</option>
              <option value="susu">🥛 Susu & Telur</option>
              <option value="buah">🍎 Buah</option>
              <option value="lainnya">📦 Lainnya</option>
            </select>

            <!-- Satuan -->
            <x-form-label :required="true">{{ __('master-data/ingredient.modal.label_unit') }}</x-form-label>
            <x-form-input id="bahanSatuan" :placeholder="__('master-data/ingredient.modal.placeholder_unit')" class="mb-1" />
            <div class="form-hint-m mb-3">{{ __('master-data/ingredient.modal.hint_unit') }}</div>

            <!-- Deskripsi -->
            <x-form-label>{{ __('master-data/ingredient.modal.label_description') }}</x-form-label>
            <textarea class="form-input-m mb-3" id="bahanDesc" placeholder="{{ __('master-data/ingredient.modal.placeholder_description') }}" style="resize: none; min-height: 72px; line-height: 1.55;"></textarea>

            <!-- Status Toggle -->
            <x-form-label>{{ __('master-data/ingredient.modal.label_additional') }}</x-form-label>
            <div class="modal-toggle-wrap">
              <div>
                <div class="modal-toggle-label">{{ __('master-data/ingredient.modal.label_toggle_status') }}</div>
                <div class="modal-toggle-sub">{{ __('master-data/ingredient.modal.hint_toggle_status') }}</div>
              </div>
              <div class="form-check form-switch mb-0">
                <input class="form-check-input" type="checkbox" id="bahanStatus" checked />
              </div>
            </div>

          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">{{ __('master-data/ingredient.modal.btn_cancel') }}</button>
        <button type="button" class="btn-modal-save" id="btnSaveBahan" onclick="saveBahan()">
          <i class="bi bi-save"></i> {{ __('master-data/ingredient.modal.btn_save') }}
        </button>
      </div>
    </div>
  </div>
</div>

<!-- ══════════════════════════════════════
     MODAL HAPUS BAHAN (CONFIRMATION)
     ══════════════════════════════════════ -->
<div class="modal fade modal-delete" id="modalHapusBahan" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
    <div class="modal-content text-center p-4">
      <div class="delete-icon-wrap">
        <i class="bi bi-exclamation-triangle-fill"></i>
      </div>
      <h5 class="fw-bold mb-2">Hapus Bahan Makanan</h5>
      <p class="text-muted mb-4 fs-7">Apakah Anda yakin ingin menghapus bahan makanan <span id="delBahanName" class="fw-bold text-danger"></span>? Tindakan ini tidak dapat dibatalkan.</p>
      <div class="d-flex gap-2 justify-content-center">
        <button type="button" class="btn-modal-cancel w-100" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-danger w-100 py-2" id="btnConfirmDelete" onclick="confirmDelete()" style="border-radius: 12px; font-weight: 600; font-family: 'Poppins', sans-serif;">Hapus</button>
      </div>
    </div>
  </div>
</div>
