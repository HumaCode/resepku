$(function() {
  // Cache of loaded categories for editing
  let loadedCategoriesCache = [];
  let currentView = 'grid'; // 'grid' or 'table'
  let selectedIcon = '🍛';
  let currentPage = 1;
  const perPage = 6;

  // List of emojis for the icon picker (extended culinary selection)
  const iconList = [
    // Makanan Utama (Main Dishes)
    '🍛','🍜','🍝','🍲','🥘','🍣','🍱','🍤','🍕','🍔',
    '🍟','🌭','🥪','🌮','🌯','🥙','🥗','🍳','🥩','🍗',
    '🍖','🍢','🥟','🍘','🍙','🍚','🍠','🫕','🥣','🦀',
    '🦞','🦐','🦑','🦪','🧆',

    // Roti & Cemilan (Bakery & Snacks)
    '🥐','🍞','🥖','🥨','🥯','🥞','🧇','🧀','🍿','🫓',

    // Kue & Manisan (Desserts & Sweets)
    '🍰','🧁','🎂','🥧','🍩','🍪','🍫','🍬','🍭','🍮',
    '🍯','🍦','🍧','🍨',

    // Minuman (Drinks)
    '🥤','🧋','🧃','🥛','☕','🍵','🫖','🧉','🍶','🍺',
    '🍻','🍷','🥂','🥃','🍹','🍸','🍾','🧊','🫙',

    // Bahan Makanan (Ingredients)
    '🥚','🧂','🧈','🥫','🌶️','🫑','🧅','🧄','🥔','🥕',
    '🌽','🥦','🥬','🥒','🍄','🥜','🌰','🫒',

    // Buah-buahan (Fruits)
    '🍎','🍏','🍐','🍊','🍋','🍌','🍉','🍇','🍓','🫐',
    '🍒','🍑','🥭','🍍','🥥','🥝','🍅','🍆','🥑',

    // Memasak (Cooking)
    '👨‍🍳','👩‍🍳','🧑‍🍳','🔪','🥢','🍴','🥄','🍽️','🏺','🔥'
  ];

  // Initialize AOS
  if (typeof AOS !== 'undefined') {
    AOS.init({ once: true, easing: 'ease-out-cubic', offset: 40 });
  }

  // --- Initialize UI Elements ---
  initIconPicker();
  loadCategories(1);

  // --- Bind Event Listeners ---
  let searchTimeout;
  $('#catSearch').on('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
      loadCategories(1);
    }, 300);
  });

  $('#catFilter').on('change', function() {
    loadCategories(1);
  });

  $('#catParentFilter').on('change', function() {
    loadCategories(1);
  });

  $('#catName').on('input', autoSlug);

  // --- Functions Declarations ---

  function setView(view) {
    currentView = view;
    const $grid = $('#catGrid');
    const $tableWrap = $('#catTableWrap');
    const $btnGrid = $('#btnGrid');
    const $btnTable = $('#btnTable');

    if (view === 'grid') {
      $grid.show();
      $tableWrap.removeClass('show');
      $btnGrid.addClass('active');
      $btnTable.removeClass('active');
    } else {
      $grid.hide();
      $tableWrap.addClass('show');
      $btnTable.addClass('active');
      $btnGrid.removeClass('active');
    }
  }

  // Image Upload Preview and Remove
  $('#catImageInput').on('change', function() {
    const file = this.files[0];
    if (file) {
      if (file.size > 2 * 1024 * 1024) {
        PA.toast({
          type: 'danger',
          title: 'Error',
          message: 'Ukuran gambar maksimal adalah 2MB.',
          duration: 4000,
          position: 'bottom-center'
        });
        this.value = '';
        return;
      }
      const reader = new FileReader();
      reader.onload = function(e) {
        $('#uploadedImagePreview').attr('src', e.target.result);
        $('#uploadPlaceholder').addClass('d-none');
        $('#uploadPreviewContainer').removeClass('d-none');
        $('#imageUploadArea').data('removed', '0');
      };
      reader.readAsDataURL(file);
    }
  });

  function removeSelectedImage(e) {
    if (e) {
      e.stopPropagation();
    }
    $('#catImageInput').val('');
    $('#uploadPlaceholder').removeClass('d-none');
    $('#uploadPreviewContainer').addClass('d-none');
    $('#uploadedImagePreview').attr('src', '');
    $('#imageUploadArea').data('removed', '1');
  }

  function changeSortOrder(delta) {
    const $inp = $('#catSortOrder');
    const newVal = Math.max(0, parseInt($inp.val() || 0) + delta);
    $inp.val(newVal);
  }

  function autoSlug() {
    const name = $('#catName').val();
    const slug = name.toLowerCase()
      .trim()
      .replace(/[^\w\s-]/g, '')
      .replace(/[\s_]+/g, '-')
      .replace(/^-+|-+$/g, '');
    $('#catSlug').val(slug);
  }

  function initIconPicker() {
    const $grid = $('#iconPickerGrid');
    $grid.empty();

    iconList.forEach(ico => {
      const $btn = $('<button>', {
        type: 'button',
        class: `icon-pick-btn${ico === selectedIcon ? ' selected' : ''}`,
        text: ico,
        click: function() {
          selectedIcon = ico;
          $('#iconPreview').text(ico);
          $('.icon-pick-btn').removeClass('selected');
          $(this).addClass('selected');
          $('#iconPickerWrap').addClass('d-none');
        }
      });
      $grid.append($btn);
    });
  }

  function getGradientForCategory(slug) {
    const gradients = [
      'linear-gradient(90deg, #ff9e6c, #e85d26)', // Orange
      'linear-gradient(90deg, #60a5fa, #3b82f6)', // Blue
      'linear-gradient(90deg, #f9a8d4, #ec4899)', // Pink
      'linear-gradient(90deg, #fcd34d, #f59e0b)', // Yellow
      'linear-gradient(90deg, #34d399, #10b981)', // Green
      'linear-gradient(90deg, #a78bfa, #7c3aed)', // Purple
      'linear-gradient(90deg, #fb923c, #ea580c)', // Red-Orange
      'linear-gradient(90deg, #94a3b8, #64748b)'  // Gray
    ];
    let hash = 0;
    for (let i = 0; i < slug.length; i++) {
      hash = slug.charCodeAt(i) + ((hash << 5) - hash);
    }
    const idx = Math.abs(hash) % gradients.length;
    return gradients[idx];
  }

  function showGridSkeletons() {
    const $grid = $('#catGrid');
    const $empty = $('#gridEmpty');
    $empty.hide();
    
    // Clear existing cards
    $grid.find('.cat-card').remove();
    
    let skeletonHtml = '';
    for (let i = 0; i < perPage; i++) {
      skeletonHtml += `
        <div class="cat-card skeleton-card">
          <div class="skeleton-stripe"></div>
          <div class="skeleton-header">
            <div class="skeleton-icon-box"></div>
            <div class="skeleton-title-lines">
              <div class="skeleton-line skeleton-title"></div>
              <div class="skeleton-line skeleton-subtitle"></div>
            </div>
          </div>
          <div class="skeleton-body">
            <div class="skeleton-line skeleton-desc"></div>
            <div class="skeleton-line skeleton-desc second"></div>
            <div class="skeleton-meta">
              <div class="skeleton-meta-item"></div>
              <div class="skeleton-meta-item"></div>
            </div>
          </div>
          <div class="skeleton-footer">
            <div class="skeleton-btn"></div>
            <div class="skeleton-btn circular"></div>
            <div class="skeleton-btn circular"></div>
          </div>
        </div>
      `;
    }
    $grid.append(skeletonHtml);
  }

  function showTableSkeletons() {
    const $body = $('#catTableBody');
    const $empty = $('#tableEmpty');
    $empty.hide();
    
    $body.empty();
    
    let skeletonHtml = '';
    for (let i = 0; i < 5; i++) {
      skeletonHtml += `
        <tr class="skeleton-tr">
          <td><div class="skeleton-icon-box table-style"></div></td>
          <td><div class="skeleton-line table-title"></div></td>
          <td><div class="skeleton-line table-parent"></div></td>
          <td><div class="skeleton-line table-views"></div></td>
          <td><div class="skeleton-line table-status"></div></td>
          <td><div class="skeleton-line table-orders"></div></td>
          <td>
            <div class="skeleton-actions">
              <div class="skeleton-btn small"></div>
              <div class="skeleton-btn small circular"></div>
            </div>
          </td>
        </tr>
      `;
    }
    $body.append(skeletonHtml);
  }

  function loadCategories(page = currentPage) {
    currentPage = page;
    const search = $('#catSearch').val();
    const status = $('#catFilter').val();
    const type = $('#catParentFilter').val();

    // Show skeletons on both views during load
    showGridSkeletons();
    showTableSkeletons();

    $.ajax({
      url: '/categories/list',
      method: 'GET',
      data: {
        search: search,
        status: status,
        type: type,
        page: currentPage,
        per_page: perPage
      },
      dataType: 'json',
      success: function(response) {
        if (response.success) {
          loadedCategoriesCache = response.data.data;
          
          updateStatistics(response.statistics);
          updateParentDropdowns(response.parents);
          
          renderGrid(loadedCategoriesCache);
          renderTable(loadedCategoriesCache);
          renderPagination(response.data.meta);
        }
      },
      error: function() {
        PA.toast({
          type: 'danger',
          title: 'Error',
          message: 'Gagal mengambil data kategori.',
          duration: 5000,
          position: 'bottom-center'
        });
      }
    });
  }

  function updateStatistics(stats) {
    if (!stats) return;
    $('#stat-total').text(stats.total);
    $('#stat-active').text(stats.active);
    $('#stat-inactive').text(stats.inactive);
    $('#stat-sub').text(stats.sub);
  }

  function updateParentDropdowns(parents) {
    if (!parents) return;
    const $select = $('#catParentId');
    const currentValue = $select.val();
    $select.html('<option value="">— Tidak ada (Kategori Induk) —</option>');

    parents.forEach(p => {
      $select.append(`<option value="${p.id}">${p.icon || '📁'} ${p.name}</option>`);
    });
    $select.val(currentValue);
  }

  function renderPagination(meta) {
    const $pagination = $('#catPagination');
    const $info = $('#paginationInfo');
    const $buttons = $('#paginationButtons');

    $buttons.empty();

    if (!meta || meta.total === 0) {
      $pagination.hide();
      return;
    }

    $pagination.show();
    $info.html(`Menampilkan <strong>${meta.from || 0}–${meta.to || 0}</strong> dari <strong>${meta.total}</strong> kategori`);

    // Prev button
    const prevDisabled = meta.current_page === 1 ? 'disabled' : '';
    const $prevBtn = $(`<button class="pag-btn" ${prevDisabled} title="Sebelumnya"><i class="bi bi-chevron-left"></i></button>`);
    if (meta.current_page > 1) {
      $prevBtn.on('click', () => loadCategories(meta.current_page - 1));
    }
    $buttons.append($prevBtn);

    // Page numbers
    for (let i = 1; i <= meta.last_page; i++) {
      const activeCls = i === meta.current_page ? 'active' : '';
      const $pageBtn = $(`<button class="pag-btn ${activeCls}">${i}</button>`);
      if (i !== meta.current_page) {
        $pageBtn.on('click', () => loadCategories(i));
      }
      $buttons.append($pageBtn);
    }

    // Next button
    const nextDisabled = meta.current_page === meta.last_page ? 'disabled' : '';
    const $nextBtn = $(`<button class="pag-btn" ${nextDisabled} title="Berikutnya"><i class="bi bi-chevron-right"></i></button>`);
    if (meta.current_page < meta.last_page) {
      $nextBtn.on('click', () => loadCategories(meta.current_page + 1));
    }
    $buttons.append($nextBtn);
  }

  function renderGrid(data) {
    const $grid = $('#catGrid');
    const $empty = $('#gridEmpty');

    $grid.find('.cat-card').remove();

    if (data.length === 0) {
      $empty.show();
      return;
    }
    $empty.hide();

    data.forEach(c => {
      const isParent = c.parent_id === null;
      const stripeBg = getGradientForCategory(c.slug);
      
      const badgeCls = c.is_active === '1' ? 'badge-active' : 'badge-inactive';
      const badgeText = c.is_active === '1' ? 'Aktif' : 'Nonaktif';

      const toggleBtn = c.is_active === '1' 
        ? `<button class="btn-cat-action btn-toggle-off" title="Nonaktifkan" onclick="toggleActive('${c.id}')"><i class="bi bi-toggle-on"></i></button>`
        : `<button class="btn-cat-action btn-toggle-on" title="Aktifkan" onclick="toggleActive('${c.id}')"><i class="bi bi-toggle-off"></i></button>`;

      const parentChip = !isParent && c.parent
        ? `<div class="cat-parent-chip"><i class="bi bi-arrow-return-right"></i> ${c.parent.name}</div>`
        : '';

      const viewText = c.views >= 1000 ? (c.views / 1000).toFixed(1) + 'K' : c.views;

      const mediaUrl = c.image_url ? c.image_url : null;
      const displayIcon = mediaUrl 
        ? `<img src="${mediaUrl}" style="width: 52px; height: 52px; border-radius: 14px; object-fit: cover;" alt="${c.name}"/>`
        : c.icon || '🍛';

      const cardHtml = `
        <div class="cat-card" data-id="${c.id}" data-aos="fade-up">
          <div class="cat-card-stripe" style="background: ${stripeBg}"></div>
          <div class="cat-order-badge">#${c.orders}</div>
          <div class="cat-card-header">
            <div class="cat-card-icon-wrap">
              <div class="cat-icon-box" style="background: linear-gradient(135deg, rgba(232,93,38,0.1), rgba(232,93,38,0.02))">
                ${displayIcon}
              </div>
              <div>
                <h5 class="cat-card-name">${c.name}</h5>
                <span class="cat-card-slug">${c.slug}</span>
              </div>
            </div>
            <span class="cat-status-badge ${badgeCls}">${badgeText}</span>
          </div>
          <div class="cat-card-body">
            ${parentChip}
            <p class="cat-card-desc">${c.description || 'Tidak ada deskripsi.'}</p>
            <div class="cat-card-meta">
              <div class="cat-meta-item"><i class="bi bi-eye"></i> <strong>${viewText}</strong> views</div>
              ${isParent ? `<div class="cat-meta-item"><i class="bi bi-diagram-2"></i> <strong>${c.children_count}</strong> sub</div>` : ''}
            </div>
          </div>
          <div class="cat-card-footer">
            <button class="btn-cat-action btn-edit" onclick="openEditModal('${c.id}')">
              <i class="bi bi-pencil-square"></i> Edit
            </button>
            ${toggleBtn}
            <button class="btn-cat-action btn-delete" onclick="deleteCategory('${c.id}', '${c.name}')" title="Hapus">
              <i class="bi bi-trash"></i>
            </button>
          </div>
        </div>
      `;
      $grid.append(cardHtml);
    });
  }

  function renderTable(data) {
    const $tbody = $('#catTableBody');
    const $empty = $('#tableEmpty');

    $tbody.empty();

    if (data.length === 0) {
      $empty.show();
      return;
    }
    $empty.hide();

    data.forEach(c => {
      const badgeCls = c.is_active === '1' ? 'badge-active' : 'badge-inactive';
      const badgeText = c.is_active === '1' ? 'Aktif' : 'Nonaktif';

      const toggleBtn = c.is_active === '1'
        ? `<button class="tb-icon-btn" style="width:28px;height:28px;border-radius:7px;font-size:.8rem;color:#d97706" title="Nonaktifkan" onclick="toggleActive('${c.id}')"><i class="bi bi-toggle-on"></i></button>`
        : `<button class="tb-icon-btn" style="width:28px;height:28px;border-radius:7px;font-size:.8rem;color:#16a34a" title="Aktifkan" onclick="toggleActive('${c.id}')"><i class="bi bi-toggle-off"></i></button>`;

      const parentText = c.parent ? `${c.parent.name}` : '<span class="text-muted">—</span>';
      
      const mediaUrl = c.image_url ? c.image_url : null;
      const displayIcon = mediaUrl 
        ? `<img src="${mediaUrl}" style="width: 32px; height: 32px; border-radius: 8px; object-fit: cover;" alt="${c.name}"/>`
        : c.icon || '🍛';

      const rowHtml = `
        <tr>
          <td>
            <div class="tbl-icon-cell" style="background: var(--primary-pale)">
              ${displayIcon}
            </div>
          </td>
          <td class="td-name">
            <div class="tbl-name-text">${c.name}</div>
            <div class="tbl-slug-text">${c.slug}</div>
          </td>
          <td>${parentText}</td>
          <td style="text-align:center">${c.views.toLocaleString()}</td>
          <td><span class="cat-status-badge ${badgeCls}">${badgeText}</span></td>
          <td style="text-align:center"><strong>${c.orders}</strong></td>
          <td>
            <div class="d-flex gap-1">
              <button class="tb-icon-btn" style="width:28px;height:28px;border-radius:7px;font-size:.8rem;color:var(--primary)" title="Edit" onclick="openEditModal('${c.id}')"><i class="bi bi-pencil"></i></button>
              ${toggleBtn}
              <button class="tb-icon-btn" style="width:28px;height:28px;border-radius:7px;font-size:.8rem;color:var(--danger)" title="Hapus" onclick="deleteCategory('${c.id}', '${c.name}')"><i class="bi bi-trash"></i></button>
            </div>
          </td>
        </tr>
      `;
      $tbody.append(rowHtml);
    });
  }

  function openCreateModal() {
    $('#modalCatTitle').text('Tambah Kategori Baru');
    $('#modalTambahKategori').removeAttr('data-edit-id');

    // Reset fields
    $('#catName').val('');
    $('#catSlug').val('');
    $('#catDesc').val('');
    $('#catParentId').val('');
    $('#catSortOrder').val('1');
    $('#catStatusToggle').prop('checked', true);
    $('#catImageInput').val('');
    $('#uploadedImagePreview').attr('src', '');
    $('#uploadPlaceholder').removeClass('d-none');
    $('#uploadPreviewContainer').addClass('d-none');
    $('#imageUploadArea').data('removed', '0');

    // Reset selected icon
    selectedIcon = '🍛';
    $('#iconPreview').text(selectedIcon);
    $('.icon-pick-btn').removeClass('selected');
    $(`.icon-pick-btn:contains('🍛')`).first().addClass('selected');
    $('#iconPickerWrap').addClass('d-none');

    // Reset input borders
    $('.modal-input, .modal-select, .modal-textarea').css('border-color', '');

    // Show modal
    new bootstrap.Modal(document.getElementById('modalTambahKategori')).show();
  }

  function openEditModal(id) {
    const c = loadedCategoriesCache.find(item => item.id === id);
    if (!c) return;

    $('#modalCatTitle').text('Edit Kategori');
    $('#modalTambahKategori').attr('data-edit-id', id);

    // Reset borders
    $('.modal-input, .modal-select, .modal-textarea').css('border-color', '');

    // Populate fields
    $('#catName').val(c.name);
    $('#catSlug').val(c.slug);
    $('#catDesc').val(c.description || '');
    $('#catParentId').val(c.parent_id || '');
    $('#catSortOrder').val(c.orders);
    $('#catStatusToggle').prop('checked', c.is_active === '1');
    $('#catImageInput').val('');

    // Icon handling
    selectedIcon = c.icon || '🍛';
    $('#iconPreview').text(selectedIcon);
    $('.icon-pick-btn').removeClass('selected');
    $(`.icon-pick-btn:contains('${selectedIcon}')`).first().addClass('selected');
    $('#iconPickerWrap').addClass('d-none');

    // Image preview handling
    if (c.image_url) {
      $('#uploadedImagePreview').attr('src', c.image_url);
      $('#uploadPlaceholder').addClass('d-none');
      $('#uploadPreviewContainer').removeClass('d-none');
    } else {
      $('#uploadedImagePreview').attr('src', '');
      $('#uploadPlaceholder').removeClass('d-none');
      $('#uploadPreviewContainer').addClass('d-none');
    }
    $('#imageUploadArea').data('removed', '0');

    // Show modal
    new bootstrap.Modal(document.getElementById('modalTambahKategori')).show();
  }

  function saveCategory() {
    const id = $('#modalTambahKategori').attr('data-edit-id');
    const isEdit = !!id;

    // Validate client side
    const name = $('#catName').val().trim();
    const slug = $('#catSlug').val().trim();
    if (!name || !slug) {
      PA.toast({
        type: 'danger',
        title: 'Validasi Gagal',
        message: 'Nama Kategori dan Slug wajib diisi.',
        duration: 4000,
        position: 'bottom-center'
      });
      if (!name) $('#catName').css('border-color', 'var(--danger)');
      if (!slug) $('#catSlug').css('border-color', 'var(--danger)');
      return;
    }

    const $btn = $('.btn-modal-save');
    const originalText = $btn.html();
    $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...');

    // Prepare Form Data (to support file upload)
    const formData = new FormData();
    formData.append('name', name);
    formData.append('slug', slug);
    formData.append('icon', selectedIcon);
    formData.append('description', $('#catDesc').val().trim());
    formData.append('parent_id', $('#catParentId').val() || '');
    formData.append('orders', $('#catSortOrder').val() || '0');
    formData.append('is_active', $('#catStatusToggle').is(':checked') ? '1' : '0');
    
    const imageRemoved = $('#imageUploadArea').data('removed') === '1' ? '1' : '0';
    formData.append('remove_image', imageRemoved);

    const imageFile = $('#catImageInput')[0].files[0];
    if (imageFile) {
      formData.append('image', imageFile);
    }

    let url = '/categories';
    if (isEdit) {
      url = `/categories/${id}`;
      formData.append('_method', 'PUT');
    }

    PA.loading({ title: 'Sedang Proses', message: isEdit ? 'Memperbarui kategori...' : 'Menyimpan kategori baru...' });

    $.ajax({
      url: url,
      method: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function(response) {
        PA.closeAll();
        $btn.prop('disabled', false).html(originalText);

        const modalEl = document.getElementById('modalTambahKategori');
        const modalInstance = bootstrap.Modal.getInstance(modalEl);
        if (modalInstance) {
          modalInstance.hide();
        }

        PA.toast({
          type: 'success',
          title: 'Sukses',
          message: response.message || 'Kategori berhasil disimpan.',
          duration: 4000,
          position: 'bottom-center'
        });

        loadCategories(isEdit ? currentPage : 1);
      },
      error: function(xhr) {
        PA.closeAll();
        $btn.prop('disabled', false).html(originalText);

        let errorMsg = 'Terjadi kesalahan, silakan coba lagi.';
        if (xhr.status === 422 && xhr.responseJSON?.errors) {
          const errors = xhr.responseJSON.errors;
          const firstKey = Object.keys(errors)[0];
          errorMsg = errors[firstKey][0];

          if (errors.name) $('#catName').css('border-color', 'var(--danger)');
          if (errors.slug) $('#catSlug').css('border-color', 'var(--danger)');
        } else if (xhr.responseJSON?.message) {
          errorMsg = xhr.responseJSON.message;
        }

        PA.toast({
          type: 'danger',
          title: 'Gagal Menyimpan',
          message: errorMsg,
          duration: 5000,
          position: 'bottom-center'
        });
      }
    });
  }

  function toggleActive(id) {
    PA.loading({ title: 'Sedang Proses', message: 'Mengubah status kategori...' });

    $.ajax({
      url: `/categories/${id}/toggle-active`,
      method: 'PATCH',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function(response) {
        PA.closeAll();
        PA.toast({
          type: 'success',
          title: 'Sukses',
          message: response.message || 'Status kategori berhasil diubah.',
          duration: 4000,
          position: 'bottom-center'
        });
        loadCategories();
      },
      error: function(xhr) {
        PA.closeAll();
        let errorMsg = 'Gagal mengubah status kategori.';
        if (xhr.responseJSON?.message) {
          errorMsg = xhr.responseJSON.message;
        }
        PA.toast({
          type: 'danger',
          title: 'Error',
          message: errorMsg,
          duration: 5000,
          position: 'bottom-center'
        });
      }
    });
  }

  function deleteCategory(id, name) {
    PA.dialog({
      type: 'warning',
      title: 'Hapus Kategori?',
      message: `Apakah Anda yakin ingin menghapus kategori "${name}"? Semua sub-kategori di bawahnya akan kehilangan induknya. Tindakan ini tidak dapat dibatalkan.`,
      confirm: { text: '<i class="bi bi-trash-fill me-1"></i> Ya, Hapus', cls: 'btn-danger' },
      cancel: '<i class="bi bi-x-circle me-1"></i> Batal'
    }).then((confirmed) => {
      if (confirmed) {
        PA.loading({ title: 'Sedang Proses', message: 'Menghapus kategori...' });

        $.ajax({
          url: `/categories/${id}`,
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function(response) {
            PA.closeAll();
            PA.toast({
              type: 'success',
              title: 'Sukses',
              message: response.message || 'Kategori berhasil dihapus.',
              duration: 4000,
              position: 'bottom-center'
            });
            if (loadedCategoriesCache.length === 1 && currentPage > 1) {
              loadCategories(currentPage - 1);
            } else {
              loadCategories();
            }
          },
          error: function(xhr) {
            PA.closeAll();
            let errorMsg = 'Gagal menghapus kategori.';
            if (xhr.responseJSON?.message) {
              errorMsg = xhr.responseJSON.message;
            }
            PA.toast({
              type: 'danger',
              title: 'Error',
              message: errorMsg,
              duration: 5000,
              position: 'bottom-center'
            });
          }
        });
      }
    });
  }

  function resetFilters() {
    $('#catSearch').val('');
    $('#catFilter').val('all');
    $('#catParentFilter').val('all');
    loadCategories(1);
  }

  // --- Bind Functions to Window for HTML event handler access ---
  window.setView = setView;
  window.removeSelectedImage = removeSelectedImage;
  window.changeSortOrder = changeSortOrder;
  window.loadCategories = loadCategories;
  window.openCreateModal = openCreateModal;
  window.openEditModal = openEditModal;
  window.saveCategory = saveCategory;
  window.toggleActive = toggleActive;
  window.deleteCategory = deleteCategory;
  window.resetFilters = resetFilters;
});
