document.addEventListener('DOMContentLoaded', () => {
  let loadedRolesCache = [];

  /* ════════════════════════════════════════════
     AJAX ROLE DATA LOADING
     ════════════════════════════════════════════ */
  function getRoleConfig(role) {
    if (role.slug === 'dev') {
      return { title: 'Super Admin', class: 'role-super', icon: '👑' };
    }
    if (role.slug === 'admin') {
      return { title: 'Admin', class: 'role-admin', icon: '🛡️' };
    }
    if (role.slug === 'user') {
      return { title: 'Member', class: 'role-member', icon: '👤' };
    }

    // Default & Fallback options for other custom roles
    const colorClasses = {
      '#f59e0b': 'role-super',
      '#e85d26': 'role-admin',
      '#3b82f6': 'role-mod',
      '#22c55e': 'role-author',
      '#64748b': 'role-member',
      '#a855f7': 'role-purple',
      '#ec4899': 'role-pink',
      '#0d9488': 'role-teal'
    };

    return {
      title: role.name,
      class: colorClasses[role.color] || 'role-member',
      icon: role.icon || '👤'
    };
  }

  function loadRoles() {
    const $grid = $('#roleGrid');
    if (!$grid.length) return;
    const $addCard = $grid.find('.role-add-card').detach();
    $grid.empty();
    
    const skeletonHtml = `
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
    `;

    for (let i = 0; i < 3; i++) {
      $grid.append(skeletonHtml);
    }

    $.ajax({
      url: '/roles-permissions-management/roles',
      method: 'GET',
      dataType: 'json',
      success: function(response) {
        $grid.find('.skeleton-card').remove();
        if (response.success && response.data) {
          loadedRolesCache = response.data;
          response.data.forEach(role => {
            const config = getRoleConfig(role);
            const isDev = role.slug === 'dev';
            const userCountStr = Number(role.users_count).toLocaleString('id-ID');
            const permCountStr = isDev ? 'Semua Akses' : `${role.permissions_count} izin`;

            let actionsHtml = `<button class="rc-btn edit" title="Edit Role" onclick="event.stopPropagation();openEditRole('${role.id}')"><i class="bi bi-pencil"></i></button>`;
            if (!isDev) {
              actionsHtml += `<button class="rc-btn del" title="Hapus Role" onclick="event.stopPropagation();openDeleteRole('${role.id}')"><i class="bi bi-trash"></i></button>`;
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
        $grid.find('.skeleton-card').remove();
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
    $('#modalRole').removeAttr('data-edit-id');
    document.getElementById('modalRoleTitle').textContent = 'Tambah Role Baru';
    document.getElementById('modalRoleSub').textContent   = 'Buat peran baru dengan izin custom';
    document.getElementById('modalRoleIcon').innerHTML    = '<i class="bi bi-plus-lg"></i>';
    document.getElementById('roleNameInput').value = '';
    document.getElementById('roleSlugInput').value = '';
    document.getElementById('roleDescInput').value = '';

    // Enable fields in case they were disabled from system role editing
    $('#roleNameInput').prop('disabled', false);
    $('#roleSlugInput').prop('disabled', false);

    // Reset picker selections to defaults
    document.querySelectorAll('.color-swatch').forEach((s, idx) => {
      s.classList.remove('sel');
      if (idx === 0) s.classList.add('sel');
    });
    document.querySelectorAll('.icon-opt').forEach((o, idx) => {
      o.classList.remove('sel');
      if (idx === 0) o.classList.add('sel');
    });

    const modalEl = document.getElementById('modalRole');
    if (modalEl && window.bootstrap) {
      new window.bootstrap.Modal(modalEl).show();
    }
  }

  window.openEditRole = function(id) {
    const role = loadedRolesCache.find(r => r.id === id);
    if (!role) return;

    $('#modalRole').attr('data-edit-id', id);

    document.getElementById('modalRoleTitle').textContent = 'Edit Role';
    document.getElementById('modalRoleSub').textContent   = `Mengubah konfigurasi role "${role.name}"`;
    document.getElementById('modalRoleIcon').innerHTML    = '<i class="bi bi-pencil"></i>';
    
    document.getElementById('roleNameInput').value = role.name;
    document.getElementById('roleSlugInput').value = role.slug;
    document.getElementById('roleDescInput').value = role.description || '';
    
    // Disable name/slug editing if it is a system role (dev, admin, user)
    const isSystem = ['dev', 'admin', 'user'].includes(role.slug);
    $('#roleNameInput').prop('disabled', isSystem);
    $('#roleSlugInput').prop('disabled', isSystem);

    // Select color swatch
    document.querySelectorAll('.color-swatch').forEach(s => {
      s.classList.remove('sel');
      if (s.getAttribute('data-color') === role.color) {
        s.classList.add('sel');
      }
    });

    // Select icon option
    document.querySelectorAll('.icon-opt').forEach(o => {
      o.classList.remove('sel');
      if (o.getAttribute('data-icon') === role.icon) {
        o.classList.add('sel');
      }
    });

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
    const $nameInput = $('#roleNameInput');
    const $slugInput = $('#roleSlugInput');
    const $descInput = $('#roleDescInput');
    
    // Ignore input values from disabled fields for system roles when building payload,
    // but validate if enabled.
    const name = $nameInput.val().trim();
    const slug = $slugInput.val().trim();
    const description = $descInput.val().trim();
    const color = $('.color-swatch.sel').attr('data-color') || '';
    const icon = $('.icon-opt.sel').attr('data-icon') || '';
    
    // Reset validation styles
    $nameInput.css('border-color', '');
    $slugInput.css('border-color', '');
    
    let isValid = true;
    if (!$nameInput.prop('disabled') && !name) {
      $nameInput.css('border-color', 'var(--danger)');
      $nameInput.focus();
      isValid = false;
    }
    if (!$slugInput.prop('disabled') && !slug) {
      $slugInput.css('border-color', 'var(--danger)');
      if (isValid) {
        $slugInput.focus();
      }
      isValid = false;
    }
    
    if (!isValid) return;
    
    // Get Save button and Cancel button
    const $saveBtn = $('.btn-modal-primary');
    const $cancelBtn = $('.btn-modal-cancel');
    const originalSaveHtml = $saveBtn.html();
    
    // Set loading state: disable buttons & show spinner
    $saveBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sedang proses...');
    $cancelBtn.prop('disabled', true);
    
    const editId = $('#modalRole').attr('data-edit-id');
    const isEdit = !!editId;
    const ajaxUrl = isEdit 
      ? `/roles-permissions-management/roles/${editId}` 
      : '/roles-permissions-management/roles';
    const ajaxMethod = isEdit ? 'PUT' : 'POST';
    
    $.ajax({
      url: ajaxUrl,
      method: ajaxMethod,
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      dataType: 'json',
      data: {
        name: name,
        slug: slug,
        description: description,
        color: color,
        icon: icon
      },
      success: function(response) {
        // Reset buttons
        $saveBtn.prop('disabled', false).html(originalSaveHtml);
        $cancelBtn.prop('disabled', false);
        
        // Hide Modal
        const modalEl = document.getElementById('modalRole');
        if (modalEl && window.bootstrap) {
          const modalInstance = window.bootstrap.Modal.getInstance(modalEl) || new window.bootstrap.Modal(modalEl);
          modalInstance.hide();
        }
        
        // Clear inputs
        $nameInput.val('');
        $slugInput.val('');
        $descInput.val('');
        $('#modalRole').removeAttr('data-edit-id');
        
        // Success Toast
        PA.toast({
          type: 'success',
          title: 'Sukses',
          message: response.message || (isEdit ? 'Role berhasil diperbarui' : 'Role baru berhasil disimpan'),
          duration: 4000,
          position: 'bottom-center'
        });
        
        // Reload role cards
        loadRoles();
      },
      error: function(xhr) {
        // Reset buttons
        $saveBtn.prop('disabled', false).html(originalSaveHtml);
        $cancelBtn.prop('disabled', false);
        
        // Handle validation errors or general error message
        let errorMsg = 'Terjadi kesalahan sistem, silakan coba lagi.';
        if (xhr.status === 422 && xhr.responseJSON?.errors) {
          const errors = xhr.responseJSON.errors;
          const firstErrorKey = Object.keys(errors)[0];
          errorMsg = errors[firstErrorKey][0];
          
          // Highlight fields
          if (errors.name) $nameInput.css('border-color', 'var(--danger)');
          if (errors.slug) $slugInput.css('border-color', 'var(--danger)');
        } else if (xhr.responseJSON?.message) {
          errorMsg = xhr.responseJSON.message;
        }
        
        // Error Toast
        PA.toast({
          type: 'danger',
          title: 'Kesalahan Sistem',
          message: errorMsg,
          duration: 5000,
          position: 'bottom-center'
        });
      }
    });
  }

  /* ════════════════════════════════════════════
     MODAL HAPUS ROLE (PA.dialog)
  ════════════════════════════════════════════ */
  window.openDeleteRole = function(id) {
    const role = loadedRolesCache.find(r => r.id === id);
    if (!role) return;

    PA.dialog({
      type: 'warning',
      title: 'Hapus Peran?',
      message: `Apakah Anda yakin ingin menghapus peran "${role.name}"? Tindakan ini tidak dapat dibatalkan.`,
      confirm: { text: '<i class="bi bi-trash-fill me-1"></i> Ya, Hapus', cls: 'pa-btn-sidebar' },
      cancel: '<i class="bi bi-x-circle me-1"></i> Batal'
    }).then((result) => {
      if (result) {
        // Show loading state
        PA.loading({ title: 'Sedang Proses', message: 'Menghapus peran...' });

        $.ajax({
          url: `/roles-permissions-management/roles/${id}`,
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          dataType: 'json',
          success: function(response) {
            PA.closeAll();
            PA.toast({
              type: 'success',
              title: 'Sukses',
              message: response.message || 'Peran berhasil dihapus',
              duration: 4000,
              position: 'bottom-center'
            });
            loadRoles();
          },
          error: function(xhr) {
            PA.closeAll();
            let errorMsg = 'Terjadi kesalahan sistem, silakan coba lagi.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
              errorMsg = xhr.responseJSON.message;
            }
            PA.toast({
              type: 'danger',
              title: 'Kesalahan Sistem',
              message: errorMsg,
              duration: 5000,
              position: 'bottom-center'
            });
          }
        });
      }
    });
  }

  /* ════════════════════════════════════════════
     PERMISSION MATRIX
  ════════════════════════════════════════════ */
  let changeCount = 0;
  const origState = {};

  /* Helper to sync select-all column headers */
  function updateSelectAllHeaders() {
    document.querySelectorAll('.select-all-column').forEach(sa => {
      const role = sa.getAttribute('data-role');
      const toggles = document.querySelectorAll(`.perm-toggle[data-role="${role}"]`);
      const checked = document.querySelectorAll(`.perm-toggle[data-role="${role}"]:checked`);
      sa.checked = (toggles.length > 0 && toggles.length === checked.length);
    });
  }

  /* Snapshot initial state */
  document.querySelectorAll('.perm-toggle').forEach((cb, i) => {
    origState[i] = cb.checked;
  });

  updateSelectAllHeaders();

  /* Re-trigger CSS animation on a checkbox (needed for subsequent toggles) */
  function triggerCheckAnim(cb) {
    if (!cb.checked) return;
    cb.style.animation = 'none';
    // Force reflow
    void cb.offsetWidth;
    cb.style.animation = '';
  }

  /* Listen to header select-all clicks */
  $(document).on('change', '.select-all-column', function() {
    const role = $(this).attr('data-role');
    const checked = $(this).is(':checked');
    const toggles = document.querySelectorAll(`.perm-toggle[data-role="${role}"]`);
    
    toggles.forEach((cb) => {
      const wasChecked = cb.checked;
      cb.checked = checked;
      if (checked && !wasChecked) {
        triggerCheckAnim(cb);
      }
    });

    if (window.onPermChange) {
      window.onPermChange();
    }
  });

  /* Re-trigger pulse animation on individual perm-toggle change */
  $(document).on('change', '.perm-toggle', function() {
    triggerCheckAnim(this);
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
    updateSelectAllHeaders();
  }

  window.savePermissions = function() {
    const btn = document.querySelector('.btn-save');
    if (!btn) return;
    const orig = btn.innerHTML;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" style="width:14px;height:14px"></span> Menyimpan...';
    btn.disabled  = true;

    const matrix = {};
    document.querySelectorAll('.perm-toggle').forEach(cb => {
      const role = cb.getAttribute('data-role');
      const perm = cb.getAttribute('data-perm');
      if (role && perm) {
        if (!matrix[role]) {
          matrix[role] = [];
        }
        if (cb.checked) {
          matrix[role].push(perm);
        }
      }
    });

    $.ajax({
      url: '/roles-permissions-management/permissions',
      method: 'POST',
      contentType: 'application/json',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      data: JSON.stringify({
        matrix: matrix
      }),
      dataType: 'json',
      success: function(response) {
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
        updateSelectAllHeaders();
        window.showToast(response.message || 'Perubahan izin berhasil disimpan!', 'success');
      },
      error: function(xhr) {
        btn.innerHTML = orig;
        btn.disabled  = false;
        let errorMsg = 'Terjadi kesalahan sistem, silakan coba lagi.';
        if (xhr.responseJSON && xhr.responseJSON.message) {
          errorMsg = xhr.responseJSON.message;
        }
        window.showToast(errorMsg, 'danger');
      }
    });
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
    updateSelectAllHeaders();
    window.showToast('Permission direset ke kondisi terakhir', 'info');
  }

  /* ════════════════════════════════════════════
     TOAST
  ════════════════════════════════════════════ */
  window.showToast = function(msg, type = 'info') {
    const titles = {
      success: 'Sukses',
      danger: 'Error',
      info: 'Informasi',
      warning: 'Peringatan'
    };
    PA.toast({
      type: type,
      title: titles[type] || 'Notifikasi',
      message: msg,
      duration: 4000,
      position: 'bottom-center'
    });
  }

  /* Hide change badge awal */
  const changeBadgeEl = document.getElementById('changeCount');
  if (changeBadgeEl) {
    changeBadgeEl.style.display = 'none';
  }
});
