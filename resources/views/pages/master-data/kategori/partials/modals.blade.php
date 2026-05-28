<!-- ══════════════════════════════════════
     MODAL TAMBAH / EDIT KATEGORI
     ══════════════════════════════════════ -->
<div class="modal fade modal-cat" id="modalTambahKategori" tabindex="-1" data-bs-backdrop="static">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <div class="modal-title">
          <i class="bi bi-plus-lg"></i>
          <span id="modalCatTitle">{{ __('master-data/category.modal.title_add') }}</span>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">

          <!-- Kiri: Icon + Nama + Slug + Deskripsi -->
          <div class="col-12 col-md-7">
            <!-- Icon picker -->
            <x-form-label :required="true">{{ __('master-data/category.modal.label_icon') }}</x-form-label>
            <div class="d-flex align-items-center gap-3 mb-3">
              <div class="icon-preview-box" id="iconPreview" onclick="$('#iconPickerWrap').toggleClass('d-none')">🍛</div>
              <div style="flex:1">
                <div style="font-size:.8rem;color:var(--muted)">{{ __('master-data/category.modal.hint_icon') }}</div>
              </div>
            </div>
            <div id="iconPickerWrap" class="d-none mb-3">
              <div class="icon-picker-grid" id="iconPickerGrid"></div>
            </div>

            <!-- Nama -->
            <x-form-label :required="true">{{ __('master-data/category.modal.label_name') }}</x-form-label>
            <x-form-input id="catName" :placeholder="__('master-data/category.modal.placeholder_name')" class="mb-1" />
            <div class="form-hint-m mb-3">{{ __('master-data/category.modal.hint_name') }}</div>

            <!-- Slug -->
            <x-form-label :required="true">{{ __('master-data/category.modal.label_slug') }}</x-form-label>
            <x-form-input id="catSlug" :placeholder="__('master-data/category.modal.placeholder_slug')" readonly class="mb-1" />
            <div class="form-hint-m mb-3">{{ __('master-data/category.modal.hint_slug') }}</div>

            <!-- Deskripsi -->
            <x-form-label>{{ __('master-data/category.modal.label_desc') }}</x-form-label>
            <x-form-textarea id="catDesc" :placeholder="__('master-data/category.modal.placeholder_desc')" rows="3" style="resize:none" />
          </div>

          <!-- Kanan: Parent + Sort + Status + Gambar -->
          <div class="col-12 col-md-5">
            <!-- Parent kategori -->
            <x-form-label>{{ __('master-data/category.modal.label_parent') }}</x-form-label>
            <x-form-select class="modal-select mb-3" id="catParentId">
              <option value="">{{ __('master-data/category.modal.option_no_parent') }}</option>
              @foreach($parentCategories as $parent)
                <option value="{{ $parent->id }}">{{ $parent->icon ?? '📁' }} {{ $parent->name }}</option>
              @endforeach
            </x-form-select>

            <!-- Sort order -->
            <x-form-label>{{ __('master-data/category.modal.label_order') }}</x-form-label>
            <div class="sort-input-wrap mb-3">
              <button type="button" class="sort-btn" onclick="changeSortOrder(-1)"><i class="bi bi-dash"></i></button>
              <x-form-input type="number" class="sort-input" id="catSortOrder" value="1" min="0" />
              <button type="button" class="sort-btn" onclick="changeSortOrder(1)"><i class="bi bi-plus"></i></button>
            </div>

            <!-- Status -->
            <x-form-label>{{ __('master-data/category.modal.label_status') }}</x-form-label>
            <div class="modal-toggle-wrap">
              <div>
                <div class="modal-toggle-label">{{ __('master-data/category.modal.label_toggle_status') }}</div>
                <div class="modal-toggle-sub">{{ __('master-data/category.modal.hint_toggle_status') }}</div>
              </div>
              <div class="form-check form-switch mb-0">
                <input class="form-check-input" type="checkbox" id="catStatusToggle" checked/>
              </div>
            </div>

            <!-- Image upload -->
            <x-form-label class="mt-3">{{ __('master-data/category.modal.label_image') }}</x-form-label>
            <div class="image-upload-wrapper" id="imageUploadArea">
              <!-- Placeholder State (shows when no image is loaded) -->
              <div class="upload-placeholder" id="uploadPlaceholder" onclick="document.getElementById('catImageInput').click()">
                <i class="bi bi-cloud-upload"></i>
                <div class="upload-text">{{ __('master-data/category.modal.hint_image') }}<br><span>{{ __('master-data/category.modal.hint_image_size') }}</span></div>
              </div>
              
              <!-- Preview State (shows when image is selected/existing) -->
              <div class="upload-preview-container d-none" id="uploadPreviewContainer">
                <img src="" class="uploaded-image-preview" id="uploadedImagePreview" alt="Preview"/>
                <button type="button" class="btn-remove-image-overlay" onclick="removeSelectedImage(event)"><i class="bi bi-x"></i></button>
              </div>
              
              <input type="file" id="catImageInput" accept="image/*" style="display:none"/>
            </div>
          </div>

        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">{{ __('master-data/category.modal.btn_cancel') }}</button>
        <button type="button" class="btn-modal-save" onclick="saveCategory()">
          <i class="bi bi-check-lg"></i> {{ __('master-data/category.modal.btn_save') }}
        </button>
      </div>
    </div>
  </div>
</div>
