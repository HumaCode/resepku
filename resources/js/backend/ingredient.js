$(function() {
  let loadedIngredientsCache = [];
  let currentPage = 1;
  const perPage = 12;
  let currentSortBy = 'name';
  let currentSortOrder = 'asc';
  let currentEditingId = null;
  let selectedEmoji = '🥦';
  let currentView = 'grid'; // Default view: grid

  // Emojis list for the picker
  const emojiList = [
    '🥦','🥕','🍅','🌶️','🧅','🧄','🥬','🌿','🫑','🥑','🍗','🥩','🐟','🦐','🦑','🥚',
    '🧀','🥛','🌾','🍜','🍚','🥔','🫘','🥥','🍋','🍎','🥭','🍓','🧂','🫙','🛢️','🫗'
  ];

  // Initialize
  initEmojiPicker();
  loadIngredients(1);

  // Bind Event Listeners
  let searchTimeout;
  $('#bahanSearch').on('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
      loadIngredients(1);
    }, 300);
  });

  $('#bahanKatFilter').on('change', function() {
    loadIngredients(1);
  });

  $('#bahanStatusFilter').on('change', function() {
    loadIngredients(1);
  });

  $('#bahanName').on('input', function() {
    autoSlug();
  });

  // Bind globally for HTML click event handlers
  window.setView = function(v) {
    currentView = v;
    const grid = $('#bahanGridView');
    const table = $('#bahanTableWrap');
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
    $('#bahanSearch').val('');
    $('#bahanKatFilter').val('all');
    $('#bahanStatusFilter').val('all');
    loadIngredients(1);
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

    loadIngredients(1);
  };

  window.openCreateModal = function() {
    currentEditingId = null;
    $('#modalBahanTitle').text('Tambah Bahan Makanan');
    $('#bahanName').val('');
    $('#bahanSlug').val('');
    $('#bahanKat').val('');
    $('#bahanSatuan').val('');
    $('#bahanDesc').val('');
    $('#bahanStatus').prop('checked', true);

    // Reset emoji picker
    selectedEmoji = '🥦';
    $('#emojiPreview').text(selectedEmoji);
    $('.emoji-pick-btn').removeClass('selected');
    $(`.emoji-pick-btn:contains('🥦')`).addClass('selected');

    $('#modalTambahBahan').modal('show');
  };

  window.openEditBahan = function(id) {
    const ingredient = loadedIngredientsCache.find(i => i.id === id);
    if (!ingredient) return;

    currentEditingId = id;
    $('#modalBahanTitle').text('Edit Bahan — ' + ingredient.name);
    $('#bahanName').val(ingredient.name);
    $('#bahanSlug').val(ingredient.slug);
    $('#bahanKat').val(ingredient.category);
    $('#bahanSatuan').val(ingredient.default_unit);
    $('#bahanDesc').val(ingredient.description || '');
    $('#bahanStatus').prop('checked', ingredient.is_active === '1');

    // Emoji picker
    selectedEmoji = ingredient.emoji || '🥦';
    $('#emojiPreview').text(selectedEmoji);
    $('.emoji-pick-btn').removeClass('selected');
    $('.emoji-pick-btn').each(function() {
      if ($(this).text() === selectedEmoji) {
        $(this).addClass('selected');
      }
    });

    $('#modalTambahBahan').modal('show');
  };

  window.saveBahan = function() {
    const name = $('#bahanName').val().trim();
    const slug = $('#bahanSlug').val().trim();
    const category = $('#bahanKat').val();
    const defaultUnit = $('#bahanSatuan').val().trim();
    const description = $('#bahanDesc').val().trim();
    const isActive = $('#bahanStatus').is(':checked') ? '1' : '0';

    if (!name) {
      PA.toast({ type: 'warning', title: 'Validasi', message: 'Nama bahan wajib diisi.', duration: 4000, position: 'bottom-center' });
      return;
    }
    if (!slug) {
      PA.toast({ type: 'warning', title: 'Validasi', message: 'Slug wajib diisi.', duration: 4000, position: 'bottom-center' });
      return;
    }
    if (!category) {
      PA.toast({ type: 'warning', title: 'Validasi', message: 'Kategori wajib dipilih.', duration: 4000, position: 'bottom-center' });
      return;
    }
    if (!defaultUnit) {
      PA.toast({ type: 'warning', title: 'Validasi', message: 'Satuan default wajib diisi.', duration: 4000, position: 'bottom-center' });
      return;
    }

    const url = currentEditingId ? `/ingredients/${currentEditingId}` : '/ingredients';
    const method = currentEditingId ? 'PUT' : 'POST';

    const $btn = $('#btnSaveBahan');
    const origHtml = $btn.html();
    $btn.html('<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...').prop('disabled', true);

    $.ajax({
      url: url,
      method: method,
      data: {
        emoji: selectedEmoji,
        name: name,
        slug: slug,
        category: category,
        default_unit: defaultUnit,
        description: description,
        is_active: isActive,
        _token: $('meta[name="csrf-token"]').attr('content')
      },
      dataType: 'json',
      success: function(response) {
        if (response.success) {
          $('#modalTambahBahan').modal('hide');
          PA.toast({
            type: 'success',
            title: 'Berhasil',
            message: response.message,
            duration: 4000,
            position: 'bottom-center'
          });
          loadIngredients(currentEditingId ? currentPage : 1);
        }
      },
      error: function(xhr) {
        let msg = 'Gagal menyimpan data bahan makanan.';
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

    PA.loading({ title: 'Sedang Proses', message: 'Mengubah status bahan...' });

    $.ajax({
      url: `/ingredients/${id}/toggle-active`,
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
          loadIngredients(currentPage);
        }
      },
      error: function() {
        PA.closeAll();
        PA.toast({
          type: 'danger',
          title: 'Gagal',
          message: 'Gagal mengubah status aktif bahan makanan.',
          duration: 5000,
          position: 'bottom-center'
        });
        $btn.prop('disabled', false);
      }
    });
  };

  window.openDeleteBahan = function(id) {
    const ingredient = loadedIngredientsCache.find(i => i.id === id);
    if (!ingredient) return;

    currentEditingId = id;
    $('#delBahanName').text(ingredient.name);
    $('#modalHapusBahan').modal('show');
  };

  window.confirmDelete = function() {
    if (!currentEditingId) return;

    const $btn = $('#btnConfirmDelete');
    const origHtml = $btn.html();
    $btn.html('<span class="spinner-border spinner-border-sm me-2"></span>Menghapus...').prop('disabled', true);

    $.ajax({
      url: `/ingredients/${currentEditingId}`,
      method: 'DELETE',
      data: {
        _token: $('meta[name="csrf-token"]').attr('content')
      },
      dataType: 'json',
      success: function(response) {
        if (response.success) {
          $('#modalHapusBahan').modal('hide');
          PA.toast({
            type: 'success',
            title: 'Berhasil',
            message: response.message,
            duration: 4000,
            position: 'bottom-center'
          });
          loadIngredients(1);
        }
      },
      error: function() {
        PA.toast({
          type: 'danger',
          title: 'Gagal',
          message: 'Gagal menghapus bahan makanan.',
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
  function loadIngredients(page = currentPage) {
    currentPage = page;
    const search = $('#bahanSearch').val();
    const category = $('#bahanKatFilter').val();
    const status = $('#bahanStatusFilter').val();

    showSkeletons();

    $.ajax({
      url: '/ingredients/list',
      method: 'GET',
      data: {
        search: search,
        category: category,
        status: status,
        sort_by: currentSortBy,
        sort_order: currentSortOrder,
        page: currentPage,
        per_page: perPage
      },
      dataType: 'json',
      success: function(response) {
        if (response.success) {
          loadedIngredientsCache = response.data.data;
          
          updateStatistics(response.statistics);
          renderGrid(loadedIngredientsCache);
          renderTable(loadedIngredientsCache);
          renderPagination(response.data.meta);
        }
      },
      error: function() {
        PA.toast({
          type: 'danger',
          title: 'Error',
          message: 'Gagal mengambil data bahan makanan.',
          duration: 5000,
          position: 'bottom-center'
        });
      }
    });
  }

  function showSkeletons() {
    // Show Skeletons in Grid
    const $grid = $('#bahanGrid');
    $grid.empty();
    let gridSkeletonHtml = '';
    for (let i = 0; i < 8; i++) {
      gridSkeletonHtml += `
        <div class="bahan-card skeleton-card">
          <div class="skeleton-line card-stripe"></div>
          <div class="skeleton-icon-box card-emoji"></div>
          <div class="skeleton-line card-title"></div>
          <div class="skeleton-line card-unit"></div>
          <div class="bahan-card-footer">
            <div class="skeleton-line card-badge"></div>
            <div class="bahan-card-actions">
              <div class="skeleton-btn small"></div>
              <div class="skeleton-btn small"></div>
            </div>
          </div>
        </div>
      `;
    }
    $grid.append(gridSkeletonHtml);

    // Show Skeletons in Table
    const $tbody = $('#bahanTbody');
    $tbody.empty();
    let tableSkeletonHtml = '';
    for (let i = 0; i < 6; i++) {
      tableSkeletonHtml += `
        <tr class="skeleton-tr">
          <td><div class="skeleton-icon-box table-style"></div></td>
          <td>
            <div class="skeleton-line table-title mb-1"></div>
            <div class="skeleton-line table-parent d-md-none"></div>
          </td>
          <td class="d-none d-md-table-cell"><div class="skeleton-line table-parent"></div></td>
          <td><div class="skeleton-line table-parent"></div></td>
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
    $('.pill-cat strong').text(stats.categories);
    $('.pill-warn strong').text(stats.inactive);
  }

  function renderGrid(ingredients) {
    const $grid = $('#bahanGrid');
    $grid.empty();

    if (!ingredients || ingredients.length === 0) {
      $grid.html('<div class="col-12 text-center py-5 text-muted w-100" style="grid-column: 1 / -1;"><i class="bi bi-basket fs-1 d-block mb-2" style="opacity:.2"></i>Bahan makanan tidak ditemukan.</div>');
      return;
    }

    ingredients.forEach(i => {
      const isInactive = i.is_active === '0';
      const opacityStyle = isInactive ? ' style="opacity:.55"' : '';
      const stripeBg = getCategoryGradient(i.category);
      
      const $card = $(`
        <div class="bahan-card" data-id="${i.id}">
          <div class="bahan-card-stripe" style="background:${stripeBg}"></div>
          <span class="bahan-emoji"${opacityStyle}>${i.emoji || '🥦'}</span>
          <div class="bahan-name"${opacityStyle}>${i.name}</div>
          <div class="bahan-unit"${opacityStyle}><i class="bi bi-rulers"></i> Satuan: ${i.default_unit}</div>
          <div class="bahan-card-footer">
            <span class="bahan-cat-chip" style="${getCategoryChipStyle(i.category)}">${i.category_label}</span>
            <div class="bahan-card-actions">
              <button class="bahan-action-btn edit" onclick="openEditBahan('${i.id}')" title="Edit"><i class="bi bi-pencil"></i></button>
              <button class="bahan-action-btn del" onclick="openDeleteBahan('${i.id}')" title="Hapus"><i class="bi bi-trash"></i></button>
            </div>
          </div>
        </div>
      `);
      $grid.append($card);
    });
  }

  function renderTable(ingredients) {
    const $tbody = $('#bahanTbody');
    $tbody.empty();

    if (!ingredients || ingredients.length === 0) {
      $tbody.append('<tr><td colspan="6" class="text-center py-5 text-muted"><i class="bi bi-basket fs-1 d-block mb-2" style="opacity:.2"></i>Bahan makanan tidak ditemukan.</td></tr>');
      return;
    }

    ingredients.forEach(i => {
      const isInactive = i.is_active === '0';
      const opacityStyle = isInactive ? ' style="opacity:.55"' : '';
      
      const statusBadge = i.is_active === '1' 
        ? '<span class="bahan-status-badge badge-active">Aktif</span>' 
        : '<span class="bahan-status-badge badge-inactive">Nonaktif</span>';

      const toggleIcon = i.is_active === '1' ? 'bi bi-toggle-on' : 'bi bi-toggle-off';
      const toggleTitle = i.is_active === '1' ? 'Nonaktifkan' : 'Aktifkan';
      const toggleStyle = i.is_active === '0' ? ' style="color:#d97706"' : '';

      const $tr = $(`
        <tr>
          <td style="font-size:1.4rem;text-align:center">${i.emoji || '🥦'}</td>
          <td class="td-name">
            <div class="tbl-name-text"${opacityStyle}>${i.name}</div>
            <div class="tbl-sub-text d-md-none"${opacityStyle}>${i.slug}</div>
          </td>
          <td class="d-none d-md-table-cell" style="font-size:.75rem;color:var(--muted);font-family:monospace${isInactive ? ';opacity:.55' : ''}">${i.slug}</td>
          <td><span class="bahan-cat-chip" style="${getCategoryChipStyle(i.category)}">${i.category_label}</span></td>
          <td>${statusBadge}</td>
          <td>
            <div class="d-flex gap-1">
              <button class="tb-icon-btn" style="width:28px;height:28px;border-radius:7px;font-size:.8rem" onclick="openEditBahan('${i.id}')" title="Edit"><i class="bi bi-pencil"></i></button>
              <button class="tb-icon-btn toggle" style="width:28px;height:28px;border-radius:7px;font-size:.8rem;color:#16a34a" onclick="toggleStatus(this, '${i.id}')" title="${toggleTitle}"${toggleStyle}><i class="${toggleIcon}"></i></button>
              <button class="tb-icon-btn" style="width:28px;height:28px;border-radius:7px;font-size:.8rem;color:var(--danger)" onclick="openDeleteBahan('${i.id}')" title="Hapus"><i class="bi bi-trash"></i></button>
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
    $info.html(`Menampilkan <strong>${meta.from || 0}–${meta.to || 0}</strong> dari <strong>${meta.total}</strong> bahan`);

    // Prev
    const prevDisabled = meta.current_page === 1 ? 'disabled' : '';
    const $prevBtn = $(`<button class="pag-btn" ${prevDisabled} title="Sebelumnya"><i class="bi bi-chevron-left"></i></button>`);
    if (meta.current_page > 1) {
      $prevBtn.on('click', () => loadIngredients(meta.current_page - 1));
    }
    $buttons.append($prevBtn);

    // Pages
    for (let i = 1; i <= meta.last_page; i++) {
      const activeCls = i === meta.current_page ? 'active' : '';
      const $pageBtn = $(`<button class="pag-btn ${activeCls}">${i}</button>`);
      if (i !== meta.current_page) {
        $pageBtn.on('click', () => loadIngredients(i));
      }
      $buttons.append($pageBtn);
    }

    // Next
    const nextDisabled = meta.current_page === meta.last_page ? 'disabled' : '';
    const $nextBtn = $(`<button class="pag-btn" ${nextDisabled} title="Berikutnya"><i class="bi bi-chevron-right"></i></button>`);
    if (meta.current_page < meta.last_page) {
      $nextBtn.on('click', () => loadIngredients(meta.current_page + 1));
    }
    $buttons.append($nextBtn);
  }

  function initEmojiPicker() {
    const $grid = $('#emojiGrid');
    $grid.empty();
    
    emojiList.forEach(em => {
      const $btn = $('<button>', {
        type: 'button',
        class: 'emoji-pick-btn' + (em === selectedEmoji ? ' selected' : ''),
        text: em
      });

      $btn.on('click', function() {
        $('.emoji-pick-btn').removeClass('selected');
        $(this).addClass('selected');
        selectedEmoji = em;
        $('#emojiPreview').text(em);
      });

      $grid.append($btn);
    });
  }

  function autoSlug() {
    const name = $('#bahanName').val();
    const slug = name.toLowerCase()
      .trim()
      .replace(/[^\w\s-]/g, '')
      .replace(/[\s_]+/g, '-')
      .replace(/^-+|-+$/g, '');
    $('#bahanSlug').val(slug);
  }

  function getCategoryGradient(cat) {
    const grads = {
      sayuran: 'linear-gradient(90deg, #16a34a, #22c55e)',
      daging: 'linear-gradient(90deg, #dc2626, #ef4444)',
      bumbu: 'linear-gradient(90deg, #e85d26, #f97316)',
      karbohidrat: 'linear-gradient(90deg, #ca8a04, #eab308)',
      seafood: 'linear-gradient(90deg, #0284c7, #0ea5e9)',
      susu: 'linear-gradient(90deg, #7c3aed, #8b5cf6)',
      buah: 'linear-gradient(90deg, #db2777, #ec4899)',
      lainnya: 'linear-gradient(90deg, #4b5563, #6b7280)'
    };
    return grads[cat] || 'linear-gradient(90deg, #4b5563, #6b7280)';
  }

  function getCategoryChipStyle(cat) {
    const styles = {
      sayuran: 'background:rgba(34,197,94,.08); border-color:rgba(34,197,94,.18); color:#16a34a;',
      daging: 'background:rgba(239,68,68,.08); border-color:rgba(239,68,68,.18); color:#dc2626;',
      bumbu: 'background:rgba(232,93,38,.08); border-color:rgba(232,93,38,.18); color:#e85d26;',
      karbohidrat: 'background:rgba(234,179,8,.08); border-color:rgba(234,179,8,.18); color:#ca8a04;',
      seafood: 'background:rgba(14,165,233,.08); border-color:rgba(14,165,233,.18); color:#0284c7;',
      susu: 'background:rgba(139,92,246,.08); border-color:rgba(139,92,246,.18); color:#7c3aed;',
      buah: 'background:rgba(236,72,153,.08); border-color:rgba(236,72,153,.18); color:#db2777;',
      lainnya: 'background:rgba(107,114,128,.08); border-color:rgba(107,114,128,.18); color:#4b5563;'
    };
    return styles[cat] || 'background:rgba(107,114,128,.08); border-color:rgba(107,114,128,.18); color:#4b5563;';
  }
});
