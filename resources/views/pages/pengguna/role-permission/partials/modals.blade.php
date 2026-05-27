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
              <x-form-label :required="true">Nama Role</x-form-label>
              <x-form-input id="roleNameInput" placeholder="cth: Editor, Reviewer..." />
              <div class="form-hint-m">Gunakan nama yang jelas dan mudah dipahami</div>
            </div>

            <div class="mb-3">
              <x-form-label>Slug (kode sistem)</x-form-label>
              <x-form-input id="roleSlugInput" placeholder="editor" style="font-family:monospace" />
              <div class="form-hint-m">Lowercase, tanpa spasi. Diisi otomatis dari nama.</div>
            </div>

            <div class="mb-0">
              <x-form-label>Deskripsi</x-form-label>
              <x-form-textarea id="roleDescInput" placeholder="Jelaskan tugas dan tanggung jawab role ini..." rows="4" style="resize:none" />
            </div>
          </div>

          <!-- Right Column: Visuals -->
          <div class="col-lg-6">
            <div class="mb-3">
              <x-form-label>Warna / Tema</x-form-label>
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
              <x-form-label>Ikon</x-form-label>
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
        <x-modal-button variant="cancel" data-bs-dismiss="modal">Batal</x-modal-button>
        <x-modal-button variant="primary" onclick="saveRole()"><i class="bi bi-floppy"></i> Simpan Role</x-modal-button>
      </div>
    </div>
  </div>
</div>

