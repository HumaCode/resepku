document.addEventListener('DOMContentLoaded', () => {
  /* ════════════════════════════════════════════
     AJAX ROLE DATA LOADING
     ════════════════════════════════════════════ */
  function getRoleConfig(slug) {
    switch (slug) {
      case 'dev':
        return { title: 'Super Admin', class: 'role-super', icon: '👑' };
      case 'admin':
        return { title: 'Admin', class: 'role-admin', icon: '🛡️' };
      case 'user':
        return { title: 'Member', class: 'role-member', icon: '👤' };
      default:
        return { title: slug.charAt(0).toUpperCase() + slug.slice(1), class: 'role-member', icon: '👤' };
    }
  }

  function loadRoles() {
    const $grid = $('#roleGrid');
    if (!$grid.length) return;
    const $addCard = $grid.find('.role-add-card').detach();
    $grid.empty();
    $grid.append('<div class="loading-state text-center w-100 py-5"><span class="spinner-border spinner-border-sm text-primary"></span> Memuat data peran...</div>');

    $.ajax({
      url: '/roles-permissions-management/roles',
      method: 'GET',
      dataType: 'json',
      success: function(response) {
        $grid.find('.loading-state').remove();
        if (response.success && response.data) {
          response.data.forEach(role => {
            const config = getRoleConfig(role.slug);
            const isDev = role.slug === 'dev';
            const userCountStr = Number(role.users_count).toLocaleString('id-ID');
            const permCountStr = isDev ? 'Semua Akses' : `${role.permissions_count} izin`;

            let actionsHtml = `<button class="rc-btn edit" title="Edit Role" onclick="event.stopPropagation();openEditRole('${role.name}')"><i class="bi bi-pencil"></i></button>`;
            if (!isDev) {
              actionsHtml += `<button class="rc-btn del" title="Hapus Role" onclick="event.stopPropagation();openDeleteRole('${role.name}')"><i class="bi bi-trash"></i></button>`;
            }

            const cardHtml = `
              <div class="role-card ${config.class}" onclick="highlightRole('${role.slug}', event)">
                <div class="role-card-top">
                  <div class="role-icon">${config.icon}</div>
                  <div class="role-card-actions">
                    ${actionsHtml}
                  </div>
                </div>
                <div class="role-name">${config.title}</div>
                <div class="role-desc">${role.description || ''}</div>
                <div class="role-meta">
                  <span class="role-user-count"><i class="bi bi-people-fill"></i> ${userCountStr} pengguna</span>
                  <span class="role-perm-count">${permCountStr}</span>
                </div>
              </div>
            `;
            $grid.append(cardHtml);
          });
        }
        $grid.append($addCard);
      },
      error: function(xhr, status, error) {
        $grid.find('.loading-state').remove();
        window.showToast('Gagal memuat data peran: ' + error, 'danger');
        $grid.append($addCard);
      }
    });
  }

  loadRoles();
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

  window.pickIcon = function(el) {
    document.querySelectorAll('.icon-opt').forEach(o => o.classList.remove('sel'));
    el.classList.add('sel');
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
