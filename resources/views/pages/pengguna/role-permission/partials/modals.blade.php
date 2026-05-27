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
