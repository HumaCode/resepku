$(function() {
  let loadedTagsCache = [];
  let currentPage = 1;
  const perPage = 10;
  let activeColor = '#ef4444';
  let currentSortBy = 'name';
  let currentSortOrder = 'asc';
  let currentEditingTagId = null;

  // Initialize
  loadTags(1);

  // Bind Event Listeners
  let searchTimeout;
  $('#tagSearch').on('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
      loadTags(1);
    }, 300);
  });

  $('#tagStatusFilter').on('change', function() {
    loadTags(1);
  });

  $('#tagHotFilter').on('change', function() {
    loadTags(1);
  });

  $('#tagName').on('input', function() {
    autoSlug();
    updatePreview();
  });

  $('#tagSlug').on('input', function() {
    updatePreview();
  });

  // Bind function globally for HTML onclick event handlers
  window.pickColor = function(el) {
    $('.color-opt').removeClass('selected');
    $(el).addClass('selected');
    activeColor = $(el).data('c');
    updatePreview();
  };

  window.resetFilters = function() {
    $('#tagSearch').val('');
    $('#tagStatusFilter').val('all');
    $('#tagHotFilter').val('all');
    loadTags(1);
  };

  window.sortTable = function(col) {
    if (currentSortBy === col) {
      currentSortOrder = currentSortOrder === 'asc' ? 'desc' : 'asc';
    } else {
      currentSortBy = col;
      currentSortOrder = 'asc';
    }

    // Update table header icons
    $('.tag-table thead th i').removeClass('bi-chevron-up bi-chevron-down').addClass('bi-chevron-expand');
    const targetHeader = $(`.tag-table thead th.sortable[onclick="sortTable('${col}')"]`);
    if (currentSortOrder === 'asc') {
      targetHeader.find('i').removeClass('bi-chevron-expand').addClass('bi-chevron-up');
    } else {
      targetHeader.find('i').removeClass('bi-chevron-expand').addClass('bi-chevron-down');
    }

    loadTags(1);
  };

  window.openCreateModal = function() {
    currentEditingTagId = null;
    $('#modalTagTitle').text('Tambah Tag Baru');
    $('#tagName').val('');
    $('#tagSlug').val('');
    
    // Select first color by default
    $('.color-opt').removeClass('selected');
    const firstColor = $('.color-opt').first();
    firstColor.addClass('selected');
    activeColor = firstColor.data('c') || '#ef4444';

    $('#tagHot').prop('checked', false);
    $('#tagStatus').prop('checked', true);

    updatePreview();
    $('#modalTambahTag').modal('show');
  };

  window.openEditTag = function(id) {
    const tag = loadedTagsCache.find(t => t.id === id);
    if (!tag) return;

    currentEditingTagId = id;
    $('#modalTagTitle').text('Edit Tag — #' + tag.name);
    $('#tagName').val(tag.name);
    $('#tagSlug').val(tag.slug);
    
    // Select color
    $('.color-opt').removeClass('selected');
    let colorFound = false;
    $('.color-opt').each(function() {
      if ($(this).data('c') === tag.color) {
        $(this).addClass('selected');
        colorFound = true;
      }
    });
    if (!colorFound) {
      // Fallback
      $('.color-opt').first().addClass('selected');
      activeColor = $('.color-opt').first().data('c') || '#ef4444';
    } else {
      activeColor = tag.color;
    }

    $('#tagHot').prop('checked', tag.is_hot === '1');
    $('#tagStatus').prop('checked', tag.is_active === '1');

    updatePreview();
    $('#modalTambahTag').modal('show');
  };

  window.saveTag = function() {
    const name = $('#tagName').val().trim();
    const slug = $('#tagSlug').val().trim();
    const isHot = $('#tagHot').is(':checked') ? '1' : '0';
    const isActive = $('#tagStatus').is(':checked') ? '1' : '0';

    if (!name) {
      PA.toast({
        type: 'warning',
        title: 'Validasi',
        message: 'Nama tag wajib diisi.',
        duration: 4000,
        position: 'bottom-center'
      });
      return;
    }

    if (!slug) {
      PA.toast({
        type: 'warning',
        title: 'Validasi',
        message: 'Slug wajib diisi.',
        duration: 4000,
        position: 'bottom-center'
      });
      return;
    }

    const url = currentEditingTagId ? `/tags/${currentEditingTagId}` : '/tags';
    const method = currentEditingTagId ? 'PUT' : 'POST';

    const $btn = $('#btnSaveTag');
    const origHtml = $btn.html();
    $btn.html('<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...').prop('disabled', true);

    $.ajax({
      url: url,
      method: method,
      data: {
        name: name,
        slug: slug,
        color: activeColor,
        is_hot: isHot,
        is_active: isActive,
        _token: $('meta[name="csrf-token"]').attr('content')
      },
      dataType: 'json',
      success: function(response) {
        if (response.success) {
          $('#modalTambahTag').modal('hide');
          PA.toast({
            type: 'success',
            title: 'Berhasil',
            message: response.message,
            duration: 4000,
            position: 'bottom-center'
          });
          loadTags(currentEditingTagId ? currentPage : 1);
        }
      },
      error: function(xhr) {
        let msg = 'Gagal menyimpan data tag.';
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

    PA.loading({ title: 'Sedang Proses', message: 'Mengubah status tag...' });

    $.ajax({
      url: `/tags/${id}/toggle-active`,
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
          loadTags(currentPage);
        }
      },
      error: function() {
        PA.closeAll();
        PA.toast({
          type: 'danger',
          title: 'Gagal',
          message: 'Gagal mengubah status aktif tag.',
          duration: 5000,
          position: 'bottom-center'
        });
        $btn.prop('disabled', false);
      }
    });
  };

  window.openDeleteTag = function(id) {
    const tag = loadedTagsCache.find(t => t.id === id);
    if (!tag) return;

    currentEditingTagId = id;
    $('#delTagName').text('#' + tag.name);
    $('#modalHapusTag').modal('show');
  };

  window.confirmDelete = function() {
    if (!currentEditingTagId) return;

    const $btn = $('#btnConfirmDelete');
    const origHtml = $btn.html();
    $btn.html('<span class="spinner-border spinner-border-sm me-2"></span>Menghapus...').prop('disabled', true);

    $.ajax({
      url: `/tags/${currentEditingTagId}`,
      method: 'DELETE',
      data: {
        _token: $('meta[name="csrf-token"]').attr('content')
      },
      dataType: 'json',
      success: function(response) {
        if (response.success) {
          $('#modalHapusTag').modal('hide');
          PA.toast({
            type: 'success',
            title: 'Berhasil',
            message: response.message,
            duration: 4000,
            position: 'bottom-center'
          });
          loadTags(1);
        }
      },
      error: function() {
        PA.toast({
          type: 'danger',
          title: 'Gagal',
          message: 'Gagal menghapus tag.',
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
  function loadTags(page = currentPage) {
    currentPage = page;
    const search = $('#tagSearch').val();
    const status = $('#tagStatusFilter').val();
    const hot = $('#tagHotFilter').val();

    showTableSkeletons();

    $.ajax({
      url: '/tags/list',
      method: 'GET',
      data: {
        search: search,
        status: status,
        hot: hot,
        sort_by: currentSortBy,
        sort_order: currentSortOrder,
        page: currentPage,
        per_page: perPage
      },
      dataType: 'json',
      success: function(response) {
        if (response.success) {
          loadedTagsCache = response.data.data;
          
          updateStatistics(response.statistics);
          renderTable(loadedTagsCache);
          renderPagination(response.data.meta);
        }
      },
      error: function() {
        PA.toast({
          type: 'danger',
          title: 'Error',
          message: 'Gagal mengambil data tag.',
          duration: 5000,
          position: 'bottom-center'
        });
      }
    });
  }

  function showTableSkeletons() {
    const $body = $('#tagTbody');
    $body.empty();
    
    let skeletonHtml = '';
    for (let i = 0; i < 5; i++) {
      skeletonHtml += `
        <tr class="skeleton-tr">
          <td><div class="skeleton-icon-box table-style"></div></td>
          <td>
            <div class="skeleton-line table-title mb-1"></div>
            <div class="skeleton-line table-parent d-md-none"></div>
          </td>
          <td class="d-none d-md-table-cell"><div class="skeleton-line table-parent"></div></td>
          <td><div class="skeleton-line table-views"></div></td>
          <td><div class="skeleton-line table-status"></div></td>
          <td><div class="skeleton-line table-orders"></div></td>
          <td>
            <div class="skeleton-actions">
              <div class="skeleton-btn small"></div>
              <div class="skeleton-btn small circular"></div>
              <div class="skeleton-btn small circular"></div>
            </div>
          </td>
        </tr>
      `;
    }
    $body.append(skeletonHtml);
  }

  function updateStatistics(stats) {
    if (!stats) return;
    $('#stat-total').text(stats.total);
    $('#stat-active').text(stats.active);
    $('#stat-hot').text(stats.hot);
    $('#stat-new').text(stats.new);
  }

  function renderTable(tags) {
    const $body = $('#tagTbody');
    $body.empty();

    if (!tags || tags.length === 0) {
      $body.append(`
        <tr>
          <td colspan="7" class="text-center py-5 text-muted">
            <i class="bi bi-hash fs-1 d-block mb-2" style="opacity:.2"></i>
            Semua tag yang dicari tidak ditemukan.
          </td>
        </tr>
      `);
      return;
    }

    tags.forEach(tag => {
      const isInactive = tag.is_active === '0';
      const opacityStyle = isInactive ? ' style="opacity:.55"' : '';
      const dotColor = tag.color || '#ef4444';
      
      const statusBadge = tag.is_active === '1' 
        ? '<span class="tag-status-badge badge-active">Aktif</span>' 
        : '<span class="tag-status-badge badge-inactive">Nonaktif</span>';

      const hotBadge = tag.is_hot === '1' 
        ? '<span class="badge-hot">🔥 Hot</span>' 
        : '<span style="color:var(--muted);font-size:.8rem">—</span>';

      const toggleIcon = tag.is_active === '1' ? 'bi bi-toggle-on' : 'bi bi-toggle-off';
      const toggleTitle = tag.is_active === '1' ? 'Nonaktifkan' : 'Aktifkan';
      const toggleStyle = tag.is_active === '0' ? ' style="color:#d97706;border-color:rgba(245,158,11,.25)"' : '';

      const $tr = $(`
        <tr>
          <td><span class="tbl-color-dot" style="background:${dotColor}"></span></td>
          <td class="td-name">
            <div class="tbl-tag-name"${opacityStyle}><span>#${tag.name}</span></div>
            <div class="tbl-tag-slug d-md-none"${opacityStyle}>${tag.slug}</div>
          </td>
          <td class="d-none d-md-table-cell" style="font-size:.75rem;color:var(--muted);font-family:monospace${isInactive ? ';opacity:.55' : ''}">${tag.slug}</td>
          <td><span class="tbl-resep-num"${opacityStyle}>${tag.views}</span></td>
          <td>${statusBadge}</td>
          <td>${hotBadge}</td>
          <td>
            <div class="d-flex gap-1 justify-content-center">
              <button class="tbl-action-btn edit" onclick="openEditTag('${tag.id}')" title="Edit"><i class="bi bi-pencil"></i></button>
              <button class="tbl-action-btn toggle" onclick="toggleStatus(this, '${tag.id}')" title="${toggleTitle}"${toggleStyle}><i class="${toggleIcon}"></i></button>
              <button class="tbl-action-btn del" onclick="openDeleteTag('${tag.id}')" title="Hapus"><i class="bi bi-trash"></i></button>
            </div>
          </td>
        </tr>
      `);
      $body.append($tr);
    });
  }

  function renderPagination(meta) {
    const $info = $('#paginationInfo');
    const $buttons = $('#paginationButtons');

    $buttons.empty();

    if (!meta || meta.total === 0) {
      $('.tag-pagination').hide();
      return;
    }

    $('.tag-pagination').show();
    $info.html(`Menampilkan <strong>${meta.from || 0}–${meta.to || 0}</strong> dari <strong>${meta.total}</strong> tags`);

    // Prev button
    const prevDisabled = meta.current_page === 1 ? 'disabled' : '';
    const $prevBtn = $(`<button class="pag-btn" ${prevDisabled} title="Sebelumnya"><i class="bi bi-chevron-left"></i></button>`);
    if (meta.current_page > 1) {
      $prevBtn.on('click', () => loadTags(meta.current_page - 1));
    }
    $buttons.append($prevBtn);

    // Page numbers (smart ellipsis)
    const pages = getVisiblePages(meta.current_page, meta.last_page);
    pages.forEach(p => {
      if (p === '...') {
        $buttons.append($('<span class="pag-ellipsis">…</span>'));
      } else {
        const activeCls = p === meta.current_page ? 'active' : '';
        const $pageBtn = $(`<button class="pag-btn ${activeCls}">${p}</button>`);
        if (p !== meta.current_page) {
          $pageBtn.on('click', () => loadTags(p));
        }
        $buttons.append($pageBtn);
      }
    });

    // Next button
    const nextDisabled = meta.current_page === meta.last_page ? 'disabled' : '';
    const $nextBtn = $(`<button class="pag-btn" ${nextDisabled} title="Berikutnya"><i class="bi bi-chevron-right"></i></button>`);
    if (meta.current_page < meta.last_page) {
      $nextBtn.on('click', () => loadTags(meta.current_page + 1));
    }
    $buttons.append($nextBtn);
  }

  function autoSlug() {
    const name = $('#tagName').val();
    const slug = name.toLowerCase()
      .trim()
      .replace(/[^\w\s-]/g, '')
      .replace(/[\s_]+/g, '-')
      .replace(/^-+|-+$/g, '');
    $('#tagSlug').val(slug);
  }

  function updatePreview() {
    const slug = $('#tagSlug').val().trim() || 'nama-tag';
    $('#prevName').text('#' + slug);
    $('#prevDot').css('background', activeColor);
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
