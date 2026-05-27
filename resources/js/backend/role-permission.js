document.addEventListener('DOMContentLoaded', () => {
  /* ════════════════════════════════════════════
     ROLE CARDS
  ════════════════════════════════════════════ */
  window.highlightRole = function(role, event) {
    document.querySelectorAll('.role-card').forEach(c => c.classList.remove('active'));
    event.currentTarget.classList.add('active');
    /* Scroll ke matriks */
    const matrix = document.querySelector('.matrix-card');
    if (matrix) {
      matrix.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  }

  /* ════════════════════════════════════════════
     MODAL ROLE — TAMBAH / EDIT
  ════════════════════════════════════════════ */
  window.openAddRole = function() {
    document.getElementById('modalRoleTitle').textContent = 'Tambah Role Baru';
    document.getElementById('modalRoleSub').textContent   = 'Buat peran baru dengan izin custom';
    document.getElementById('modalRoleIcon').innerHTML    = '<i class="bi bi-plus-lg"></i>';
    document.getElementById('roleNameInput').value = '';
    document.getElementById('roleSlugInput').value = '';
    document.getElementById('roleDescInput').value = '';
    const modalEl = document.getElementById('modalRole');
    if (modalEl && window.bootstrap) {
      new window.bootstrap.Modal(modalEl).show();
    }
  }

  window.openEditRole = function(name) {
    document.getElementById('modalRoleTitle').textContent = 'Edit Role';
    document.getElementById('modalRoleSub').textContent   = `Mengubah konfigurasi role "${name}"`;
    document.getElementById('modalRoleIcon').innerHTML    = '<i class="bi bi-pencil"></i>';
    document.getElementById('roleNameInput').value = name;
    document.getElementById('roleSlugInput').value = name.toLowerCase().replace(/\s+/g,'-');
    document.getElementById('roleDescInput').value = '';
    const modalEl = document.getElementById('modalRole');
    if (modalEl && window.bootstrap) {
      new window.bootstrap.Modal(modalEl).show();
    }
  }

  /* Auto slug dari nama */
  const nameInput = document.getElementById('roleNameInput');
  if (nameInput) {
    nameInput.addEventListener('input', function() {
      const slugInput = document.getElementById('roleSlugInput');
      if (slugInput) {
        slugInput.value = this.value.toLowerCase()
          .replace(/\s+/g,'-')
          .replace(/[^a-z0-9-]/g,'');
      }
    });
  }

  /* Color picker */
  window.pickColor = function(el) {
    document.querySelectorAll('.color-swatch').forEach(s => s.classList.remove('sel'));
    el.classList.add('sel');
  }

  /* Icon picker */
  window.pickIcon = function(el) {
    document.querySelectorAll('.icon-opt').forEach(o => {
      o.style.borderColor = 'var(--border)';
      o.style.background  = 'transparent';
    });
    el.style.borderColor = 'var(--primary-border)';
    el.style.background  = 'var(--primary-pale)';
  }

  window.saveRole = function() {
    const nameInputEl = document.getElementById('roleNameInput');
    if (!nameInputEl) return;
    const name = nameInputEl.value.trim();
    if (!name) {
      nameInputEl.style.borderColor = 'var(--danger)';
      nameInputEl.focus();
      return;
    }
    const modalEl = document.getElementById('modalRole');
    if (modalEl && window.bootstrap) {
      const modalInstance = window.bootstrap.Modal.getInstance(modalEl);
      if (modalInstance) modalInstance.hide();
    }
    window.showToast('Role "' + name + '" berhasil disimpan', 'success');
  }

  /* ════════════════════════════════════════════
     MODAL HAPUS ROLE
  ════════════════════════════════════════════ */
  let _delRoleName = '';
  window.openDeleteRole = function(name) {
    _delRoleName = name;
    document.getElementById('delRoleName').textContent = '"' + name + '"';
    const modalEl = document.getElementById('modalDelRole');
    if (modalEl && window.bootstrap) {
      new window.bootstrap.Modal(modalEl).show();
    }
  }
  window.confirmDeleteRole = function() {
    const modalEl = document.getElementById('modalDelRole');
    if (modalEl && window.bootstrap) {
      const modalInstance = window.bootstrap.Modal.getInstance(modalEl);
      if (modalInstance) modalInstance.hide();
    }
    window.showToast('Role "' + _delRoleName + '" berhasil dihapus', 'danger');
  }

  /* ════════════════════════════════════════════
     PERMISSION MATRIX
  ════════════════════════════════════════════ */
  let changeCount = 0;
  const origState = {};

  /* Snapshot initial state */
  document.querySelectorAll('.perm-toggle').forEach((cb, i) => {
    origState[i] = cb.checked;
  });

  window.onPermChange = function() {
    let diff = 0;
    document.querySelectorAll('.perm-toggle').forEach((cb, i) => {
      if (cb.checked !== origState[i]) diff++;
    });
    changeCount = diff;
    const badge = document.getElementById('changeCount');
    if (badge) {
      badge.innerHTML = `<i class="bi bi-pencil-fill"></i> ${diff} perubahan`;
      badge.style.display = diff > 0 ? '' : 'none';
    }
  }

  window.savePermissions = function() {
    const btn = document.querySelector('.btn-save');
    if (!btn) return;
    const orig = btn.innerHTML;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" style="width:14px;height:14px"></span> Menyimpan...';
    btn.disabled  = true;

    setTimeout(() => {
      /* Commit state */
      document.querySelectorAll('.perm-toggle').forEach((cb, i) => {
        origState[i] = cb.checked;
      });
      changeCount = 0;
      const badge = document.getElementById('changeCount');
      if (badge) {
        badge.innerHTML = '<i class="bi bi-pencil-fill"></i> 0 perubahan';
        badge.style.display = 'none';
      }
      btn.innerHTML = orig;
      btn.disabled  = false;
      window.showToast('Perubahan izin berhasil disimpan!', 'success');
    }, 1400);
  }

  window.resetPermissions = function() {
    document.querySelectorAll('.perm-toggle').forEach((cb, i) => {
      cb.checked = origState[i];
    });
    changeCount = 0;
    const badge = document.getElementById('changeCount');
    if (badge) {
      badge.innerHTML = '<i class="bi bi-pencil-fill"></i> 0 perubahan';
      badge.style.display = 'none';
    }
    window.showToast('Permission direset ke kondisi terakhir', 'info');
  }

  /* ════════════════════════════════════════════
     TOAST
  ════════════════════════════════════════════ */
  window.showToast = function(msg, type = 'info') {
    const wrap = document.getElementById('toastWrap');
    if (!wrap) return;
    const t    = document.createElement('div');
    const icon = { success:'bi-check2-circle', danger:'bi-x-circle', info:'bi-info-circle' }[type] || 'bi-info-circle';
    t.className = `toast-item toast-${type}`;
    t.innerHTML = `<i class="bi ${icon}"></i> ${msg}`;
    wrap.appendChild(t);
    setTimeout(() => {
      t.classList.add('removing');
      setTimeout(() => t.remove(), 320);
    }, 3000);
  }

  /* Hide change badge awal */
  const changeBadgeEl = document.getElementById('changeCount');
  if (changeBadgeEl) {
    changeBadgeEl.style.display = 'none';
  }
});
