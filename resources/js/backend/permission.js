$(function() {
  let loadedPermissionsCache = [];
  let currentPage = 1;
  const perPage = 12;
  let currentSortBy = 'name';
  let currentSortOrder = 'asc';
  let currentEditingId = null;
  let currentView = 'grid'; // Default view: grid

  // Initialize
  loadPermissions(1);

  // Bind Event Listeners
  let searchTimeout;
  $('#permSearch').on('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
      loadPermissions(1);
    }, 300);
  });

  $('#permStatusFilter').on('change', function() {
    loadPermissions(1);
  });

  // Bind globally for HTML click event handlers
  window.setView = function(v) {
    currentView = v;
    const grid = $('#permGridView');
    const table = $('#permTableWrap');
    const btnG = $('#btnGrid');
    const btnT = $('#btnTable');

    if (v === 'grid') {
      grid.show();
      table.removeClass('show');
      btnG.addClass('active');
      btnT.removeClass('active');
    } else {
      grid.hide();
      table.addClass('show');
      btnT.addClass('active');
      btnG.removeClass('active');
    }
  };

  window.resetFilters = function() {
    $('#permSearch').val('');
    $('#permStatusFilter').val('all');
    loadPermissions(1);
  };

  window.sortTable = function(col) {
    if (currentSortBy === col) {
      currentSortOrder = currentSortOrder === 'asc' ? 'desc' : 'asc';
    } else {
      currentSortBy = col;
      currentSortOrder = 'asc';
    }

    // Update table header icons
    $('.cat-table thead th i').removeClass('bi-chevron-up bi-chevron-down').addClass('bi-chevron-expand');
    const targetHeader = $(`.cat-table thead th.sortable[onclick="sortTable('${col}')"]`);
    if (currentSortOrder === 'asc') {
      targetHeader.find('i').removeClass('bi-chevron-expand').addClass('bi-chevron-up');
    } else {
      targetHeader.find('i').removeClass('bi-chevron-expand').addClass('bi-chevron-down');
    }

    loadPermissions(1);
  };

  window.openCreateModal = function() {
    currentEditingId = null;
    $('#modalPermTitle').text('Tambah Permission Baru');
    $('#permName').val('');
    $('#permGuard').val('web');
    $('#permStatus').prop('checked', true);
    $('#modalTambahPerm').modal('show');
  };

  window.openEditPerm = function(id) {
    const permission = loadedPermissionsCache.find(p => p.id === id);
    if (!permission) return;

    currentEditingId = id;
    $('#modalPermTitle').text('Edit Permission — ' + permission.name);
    $('#permName').val(permission.name);
    $('#permGuard').val(permission.guard_name);
    $('#permStatus').prop('checked', permission.is_active === '1');

    $('#modalTambahPerm').modal('show');
  };

  window.savePerm = function() {
    const name = $('#permName').val().trim();
    const guard = $('#permGuard').val().trim();
    const isActive = $('#permStatus').is(':checked') ? '1' : '0';

    if (!name) {
      PA.toast({ type: 'warning', title: 'Validasi', message: 'Nama permission wajib diisi.', duration: 4000, position: 'bottom-center' });
      return;
    }

    const url = currentEditingId ? `/permissions/${currentEditingId}` : '/permissions';
    const method = currentEditingId ? 'PUT' : 'POST';

    const $btn = $('#btnSavePerm');
    const origHtml = $btn.html();
    $btn.html('<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...').prop('disabled', true);

    $.ajax({
      url: url,
      method: method,
      data: {
        name: name,
        guard_name: guard,
        is_active: isActive,
        _token: $('meta[name="csrf-token"]').attr('content')
      },
      dataType: 'json',
      success: function(response) {
        if (response.success) {
          $('#modalTambahPerm').modal('hide');
          PA.toast({
            type: 'success',
            title: 'Berhasil',
            message: response.message,
            duration: 4000,
            position: 'bottom-center'
          });
          loadPermissions(currentEditingId ? currentPage : 1);
        }
      },
      error: function(xhr) {
        let msg = 'Gagal menyimpan data permission.';
        if (xhr.responseJSON && xhr.responseJSON.errors) {
          msg = Object.values(xhr.responseJSON.errors)[0][0];
        }
        PA.toast({
          type: 'danger',
          title: 'Gagal',
          message: msg,
          duration: 5000,
          position: 'bottom-center'
        });
      },
      complete: function() {
        $btn.html(origHtml).prop('disabled', false);
      }
    });
  };

  window.toggleStatus = function(btn, id) {
    const $btn = $(btn);
    $btn.prop('disabled', true);

    PA.loading({ title: 'Sedang Proses', message: 'Mengubah status permission...' });

    $.ajax({
      url: `/permissions/${id}/toggle-active`,
      method: 'PATCH',
      data: {
        _token: $('meta[name="csrf-token"]').attr('content')
      },
      dataType: 'json',
      success: function(response) {
        PA.closeAll();
        if (response.success) {
          PA.toast({
            type: 'success',
            title: 'Berhasil',
            message: response.message,
            duration: 4000,
            position: 'bottom-center'
          });
          loadPermissions(currentPage);
        }
      },
      error: function() {
        PA.closeAll();
        PA.toast({
          type: 'danger',
          title: 'Gagal',
          message: 'Gagal mengubah status aktif permission.',
          duration: 5000,
          position: 'bottom-center'
        });
        $btn.prop('disabled', false);
      }
    });
  };

  window.openDeletePerm = function(id) {
    const permission = loadedPermissionsCache.find(p => p.id === id);
    if (!permission) return;

    currentEditingId = id;
    $('#delPermName').text(permission.name);
    $('#modalHapusPerm').modal('show');
  };

  window.confirmDelete = function() {
    if (!currentEditingId) return;

    const $btn = $('#btnConfirmDelete');
    const origHtml = $btn.html();
    $btn.html('<span class="spinner-border spinner-border-sm me-2"></span>Menghapus...').prop('disabled', true);

    $.ajax({
      url: `/permissions/${currentEditingId}`,
      method: 'DELETE',
      data: {
        _token: $('meta[name="csrf-token"]').attr('content')
      },
      dataType: 'json',
      success: function(response) {
        if (response.success) {
          $('#modalHapusPerm').modal('hide');
          PA.toast({
            type: 'success',
            title: 'Berhasil',
            message: response.message,
            duration: 4000,
            position: 'bottom-center'
          });
          loadPermissions(1);
        }
      },
      error: function() {
        PA.toast({
          type: 'danger',
          title: 'Gagal',
          message: 'Gagal menghapus permission.',
          duration: 5000,
          position: 'bottom-center'
        });
      },
      complete: function() {
        $btn.html(origHtml).prop('disabled', false);
      }
    });
  };

  // Internal Functions
  function loadPermissions(page = currentPage) {
    currentPage = page;
    const search = $('#permSearch').val();
    const status = $('#permStatusFilter').val();

    showSkeletons();

    $.ajax({
      url: '/permissions/list',
      method: 'GET',
      data: {
        search: search,
        status: status,
        sort_by: currentSortBy,
        sort_order: currentSortOrder,
        page: currentPage,
        per_page: perPage
      },
      dataType: 'json',
      success: function(response) {
        if (response.success) {
          loadedPermissionsCache = response.data.data;
          
          updateStatistics(response.statistics);
          renderGrid(loadedPermissionsCache);
          renderTable(loadedPermissionsCache);
          renderPagination(response.data.meta);
        }
      },
      error: function() {
        PA.toast({
          type: 'danger',
          title: 'Error',
          message: 'Gagal mengambil data permission.',
          duration: 5000,
          position: 'bottom-center'
        });
      }
    });
  }

  function showSkeletons() {
    // Grid skeleton loader
    const $grid = $('#permGrid');
    $grid.empty();
    let gridSkeletonHtml = '';
    for (let i = 0; i < 8; i++) {
      gridSkeletonHtml += `
        <div class="perm-card skeleton-card">
          <div class="skeleton-line card-stripe"></div>
          <div class="skeleton-icon-box card-emoji" style="border-radius:10px"></div>
          <div class="skeleton-line card-title"></div>
          <div class="perm-card-footer mt-2">
            <div class="skeleton-line card-badge"></div>
            <div class="perm-card-actions">
              <div class="skeleton-btn small"></div>
              <div class="skeleton-btn small"></div>
            </div>
          </div>
        </div>
      `;
    }
    $grid.append(gridSkeletonHtml);

    // Table skeleton loader
    const $tbody = $('#permTbody');
    $tbody.empty();
    let tableSkeletonHtml = '';
    for (let i = 0; i < 6; i++) {
      tableSkeletonHtml += `
        <tr class="skeleton-tr">
          <td><div class="skeleton-line table-title"></div></td>
          <td><div class="skeleton-line table-status" style="width:50px"></div></td>
          <td><div class="skeleton-line table-status"></div></td>
          <td>
            <div class="skeleton-actions">
              <div class="skeleton-btn small"></div>
              <div class="skeleton-btn small"></div>
            </div>
          </td>
        </tr>
      `;
    }
    $tbody.append(tableSkeletonHtml);
  }

  function updateStatistics(stats) {
    if (!stats) return;
    $('.pill-all strong').text(stats.total);
    $('.pill-active strong').text(stats.active);
    $('.pill-inactive strong').text(stats.inactive);
    $('.pill-guards strong').text(stats.guards);
  }

  function renderGrid(permissions) {
    const $grid = $('#permGrid');
    $grid.empty();

    if (!permissions || permissions.length === 0) {
      $grid.html('<div class="col-12 text-center py-5 text-muted w-100" style="grid-column: 1 / -1;"><i class="bi bi-key fs-1 d-block mb-2" style="opacity:.2"></i>Permission tidak ditemukan.</div>');
      return;
    }

    permissions.forEach(p => {
      const isInactive = p.is_active === '0';
      const opacityStyle = isInactive ? ' style="opacity:.55"' : '';
      
      const $card = $(`
        <div class="perm-card" data-id="${p.id}">
          <div class="perm-card-stripe"></div>
          <div class="perm-icon-box"${opacityStyle}><i class="bi bi-key"></i></div>
          <div class="perm-name"${opacityStyle}>${p.name}</div>
          <div class="perm-card-footer">
            <span class="perm-guard-chip">${p.guard_name}</span>
            <div class="perm-card-actions">
              <button class="perm-action-btn edit" onclick="openEditPerm('${p.id}')" title="Edit"><i class="bi bi-pencil"></i></button>
              <button class="perm-action-btn del" onclick="openDeletePerm('${p.id}')" title="Hapus"><i class="bi bi-trash"></i></button>
            </div>
          </div>
        </div>
      `);
      $grid.append($card);
    });
  }

  function renderTable(permissions) {
    const $tbody = $('#permTbody');
    $tbody.empty();

    if (!permissions || permissions.length === 0) {
      $tbody.append('<tr><td colspan="4" class="text-center py-5 text-muted"><i class="bi bi-key fs-1 d-block mb-2" style="opacity:.2"></i>Permission tidak ditemukan.</td></tr>');
      return;
    }

    permissions.forEach(p => {
      const isInactive = p.is_active === '0';
      const opacityStyle = isInactive ? ' style="opacity:.55"' : '';
      
      const statusBadge = p.is_active === '1' 
        ? '<span class="perm-status-badge badge-active">Aktif</span>' 
        : '<span class="perm-status-badge badge-inactive">Nonaktif</span>';

      const toggleIcon = p.is_active === '1' ? 'bi bi-toggle-on' : 'bi bi-toggle-off';
      const toggleTitle = p.is_active === '1' ? 'Nonaktifkan' : 'Aktifkan';
      const toggleStyle = p.is_active === '0' ? ' style="color:#d97706"' : '';

      const $tr = $(`
        <tr>
          <td><span class="fw-bold text-secondary"${opacityStyle}>${p.name}</span></td>
          <td><span class="badge bg-light text-dark">${p.guard_name}</span></td>
          <td>${statusBadge}</td>
          <td>
            <div class="d-flex gap-1">
              <button class="tb-icon-btn" style="width:28px;height:28px;border-radius:7px;font-size:.8rem" onclick="openEditPerm('${p.id}')" title="Edit"><i class="bi bi-pencil"></i></button>
              <button class="tb-icon-btn toggle" style="width:28px;height:28px;border-radius:7px;font-size:.8rem;color:#16a34a" onclick="toggleStatus(this, '${p.id}')" title="${toggleTitle}"${toggleStyle}><i class="${toggleIcon}"></i></button>
              <button class="tb-icon-btn" style="width:28px;height:28px;border-radius:7px;font-size:.8rem;color:var(--danger)" onclick="openDeletePerm('${p.id}')" title="Hapus"><i class="bi bi-trash"></i></button>
            </div>
          </td>
        </tr>
      `);
      $tbody.append($tr);
    });
  }

  function renderPagination(meta) {
    const $info = $('#paginationInfo');
    const $buttons = $('#paginationButtons');
    $buttons.empty();

    if (!meta || meta.total === 0) {
      $('.cat-pagination').hide();
      return;
    }

    $('.cat-pagination').show();
    $info.html(`Menampilkan <strong>${meta.from || 0}–${meta.to || 0}</strong> dari <strong>${meta.total}</strong> permission`);

    // Prev
    const prevDisabled = meta.current_page === 1 ? 'disabled' : '';
    const $prevBtn = $(`<button class="pag-btn" ${prevDisabled} title="Sebelumnya"><i class="bi bi-chevron-left"></i></button>`);
    if (meta.current_page > 1) {
      $prevBtn.on('click', () => loadPermissions(meta.current_page - 1));
    }
    $buttons.append($prevBtn);

    // Pages (smart ellipsis)
    const pages = getVisiblePages(meta.current_page, meta.last_page);
    pages.forEach(p => {
      if (p === '...') {
        $buttons.append($('<span class="pag-ellipsis">…</span>'));
      } else {
        const activeCls = p === meta.current_page ? 'active' : '';
        const $pageBtn = $(`<button class="pag-btn ${activeCls}">${p}</button>`);
        if (p !== meta.current_page) {
          $pageBtn.on('click', () => loadPermissions(p));
        }
        $buttons.append($pageBtn);
      }
    });

    // Next
    const nextDisabled = meta.current_page === meta.last_page ? 'disabled' : '';
    const $nextBtn = $(`<button class="pag-btn" ${nextDisabled} title="Berikutnya"><i class="bi bi-chevron-right"></i></button>`);
    if (meta.current_page < meta.last_page) {
      $nextBtn.on('click', () => loadPermissions(meta.current_page + 1));
    }
    $buttons.append($nextBtn);
  }

  /**
   * Smart pagination: returns an array of page numbers and '...' ellipsis markers.
   */
  function getVisiblePages(current, last) {
    const delta = 2;
    const pages = [];
    const rangeStart = Math.max(2, current - delta);
    const rangeEnd = Math.min(last - 1, current + delta);

    pages.push(1);
    if (rangeStart > 2) pages.push('...');
    for (let i = rangeStart; i <= rangeEnd; i++) pages.push(i);
    if (rangeEnd < last - 1) pages.push('...');
    if (last > 1) pages.push(last);

    return pages;
  }
});
