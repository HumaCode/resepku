let currentPage = 1;
let currentSearch = '';
let currentStatus = 'all';
let currentCategory = 'all';
let loadedRecipes = [];
let currentSortCol = '';
let currentSortDir = 'asc';
let deleteTargetId = null;

// AJAX Setup for Laravel CSRF token
$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

// Debounce helper
function debounce(func, delay) {
  let timer;
  return function(...args) {
    clearTimeout(timer);
    timer = setTimeout(() => {
      func.apply(this, args);
    }, delay);
  };
}

// Debounced search function
const triggerSearch = debounce(function() {
  currentSearch = $('#resepSearch').val();
  loadRecipes(1);
}, 400);

// Global filterResep function (called from HTML oninput/onchange)
window.filterResep = function() {
  currentStatus = $('#resepStatusFilter').val();
  currentCategory = $('#resepKatFilter').val();
  triggerSearch();
};

// Global sortResep function (called from HTML table headers)
window.sortResep = function(col) {
  if (currentSortCol === col) {
    currentSortDir = currentSortDir === 'asc' ? 'desc' : 'asc';
  } else {
    currentSortCol = col;
    currentSortDir = 'desc'; // Default desc for numeric columns
  }

  // Update header sort icons
  $('#resepTable th.sortable').each(function() {
    $(this).find('i').removeClass('bi-chevron-up bi-chevron-down').addClass('bi-chevron-expand');
  });

  const header = $(`#resepTable th[onclick="sortResep('${col}')"]`);
  if (header.length) {
    header.find('i').removeClass('bi-chevron-expand')
      .addClass(currentSortDir === 'asc' ? 'bi-chevron-up' : 'bi-chevron-down');
  }

  if (loadedRecipes && loadedRecipes.length > 0) {
    loadedRecipes.sort((a, b) => {
      let va = a[col];
      let vb = b[col];

      if (col === 'judul') {
        va = a.title || '';
        vb = b.title || '';
      } else if (col === 'rating') {
        va = parseFloat(a.rating) || 0;
        vb = parseFloat(b.rating) || 0;
      } else if (col === 'views') {
        va = parseInt(a.views) || 0;
        vb = parseInt(b.views) || 0;
      }

      if (typeof va === 'string') {
        return currentSortDir === 'asc' ? va.localeCompare(vb) : vb.localeCompare(va);
      } else {
        return currentSortDir === 'asc' ? va - vb : vb - va;
      }
    });

    renderRecipes(loadedRecipes);
  }
};

// Global changePage function for pagination buttons
window.changePage = function(page) {
  loadRecipes(page);
};

// Load recipes via AJAX
function loadRecipes(page = 1) {
  currentPage = page;
  const $tbody = $('#resepTbody');

  $tbody.html(`
    <tr>
      <td colspan="8" class="text-center py-5">
        <div class="d-flex flex-column align-items-center gap-2">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
          <span class="text-muted" style="font-size: .82rem">Memuat data resep...</span>
        </div>
      </td>
    </tr>
  `);

  $.ajax({
    url: '/recipes',
    type: 'GET',
    dataType: 'json',
    data: {
      page: page,
      search: currentSearch,
      status: currentStatus,
      category: currentCategory
    },
    success: function(response) {
      loadedRecipes = response.data || [];
      renderRecipes(loadedRecipes);
      renderPagination(response.meta);
    },
    error: function(xhr) {
      console.error('Gagal memuat resep:', xhr);
      $tbody.html(`
        <tr>
          <td colspan="8" class="text-center py-5 text-danger">
            <i class="bi bi-exclamation-triangle-fill" style="font-size: 1.5rem"></i>
            <div class="mt-2" style="font-size: .85rem">Gagal memuat data resep. Silakan coba lagi.</div>
          </td>
        </tr>
      `);
    }
  });
}

// Render Table Rows
function renderRecipes(recipes) {
  const $tbody = $('#resepTbody');
  $tbody.empty();

  if (recipes.length === 0) {
    $tbody.html(`
      <tr>
        <td colspan="8" class="text-center py-5 text-muted">
          <i class="bi bi-journal-x" style="font-size: 2rem; opacity: .5"></i>
          <div class="mt-2" style="font-size: .85rem; font-weight: 500">Tidak ada resep yang ditemukan.</div>
        </td>
      </tr>
    `);
    return;
  }

  recipes.forEach(recipe => {
    // Initials
    const authorName = recipe.author ? recipe.author.name : 'Unknown';
    const initials = authorName.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();

    // Thumb / Cover
    let thumbHtml = '';
    if (recipe.cover_url) {
      thumbHtml = `<div class="resep-thumb"><img src="${recipe.cover_url}" alt="${recipe.title}"></div>`;
    } else {
      let emoji = '🍳';
      const catSlug = recipe.category && recipe.category.slug ? recipe.category.slug : '';
      if (catSlug.includes('utama')) emoji = '🍖';
      else if (catSlug.includes('cemilan')) emoji = '🥗';
      else if (catSlug.includes('minuman')) emoji = '🍹';
      else if (catSlug.includes('kue')) emoji = '🎂';
      else if (catSlug.includes('sarapan')) emoji = '🥣';
      thumbHtml = `<div class="resep-thumb">${emoji}</div>`;
    }

    const featuredHtml = recipe.is_featured === '1' 
      ? `<span class="badge-featured d-none d-lg-inline-block ms-1">⭐ Unggulan</span>` 
      : '';

    const catName = recipe.category ? recipe.category.name : 'Uncategorized';
    const waktu = parseInt(recipe.prep_time || 0) + parseInt(recipe.cook_time || 0);

    let viewsFormatted = recipe.views || 0;
    if (recipe.views >= 1000) {
      viewsFormatted = (recipe.views / 1000).toFixed(1) + 'k';
    }

    let badgeClass = 'badge-pending';
    let statusLabel = recipe.status;
    if (recipe.status === 'published') {
      badgeClass = 'badge-published';
      statusLabel = 'Published';
    } else if (recipe.status === 'pending') {
      badgeClass = 'badge-pending';
      statusLabel = 'Pending';
    } else if (recipe.status === 'draft') {
      badgeClass = 'badge-draft';
      statusLabel = 'Draft';
    } else if (recipe.status === 'rejected') {
      badgeClass = 'badge-rejected';
      statusLabel = 'Ditolak';
    }

    // Actions
    let actionsHtml = '';
    const viewUrl = `/recipes/${recipe.id}/detail`;
    const editUrl = `/recipes/${recipe.id}/edit`;

    if (recipe.status === 'pending') {
      actionsHtml = `
        <a href="${viewUrl}" class="tbl-action-btn view" title="Lihat Detail"><i class="bi bi-eye"></i></a>
        <button class="tbl-action-btn approve" title="Setujui" onclick="approveResep('${recipe.id}', this)"><i class="bi bi-check-lg"></i></button>
        <button class="tbl-action-btn del" onclick="openDeleteResep('${recipe.id}', '${recipe.title.replace(/'/g, "\\'")}')" title="Tolak / Hapus"><i class="bi bi-trash"></i></button>
      `;
    } else {
      actionsHtml = `
        <a href="${viewUrl}" class="tbl-action-btn view" title="Lihat Detail"><i class="bi bi-eye"></i></a>
        <a href="${editUrl}" class="tbl-action-btn edit" title="Edit"><i class="bi bi-pencil"></i></a>
        <button class="tbl-action-btn del" onclick="openDeleteResep('${recipe.id}', '${recipe.title.replace(/'/g, "\\'")}')" title="Hapus"><i class="bi bi-trash"></i></button>
      `;
    }

    const row = `
      <tr data-id="${recipe.id}">
        <td>${thumbHtml}</td>
        <td class="td-judul">
          <div class="resep-judul-text">${recipe.title}</div>
          <div class="resep-meta-text">
            <span>${catName}</span>
            <span class="resep-meta-dot"></span>
            <span>${waktu} menit</span>
            <span class="resep-meta-dot d-none d-md-inline-block"></span>
            <span class="d-none d-md-inline">${recipe.servings} porsi</span>
            <span class="resep-meta-dot d-none d-lg-inline-block"></span>
            ${featuredHtml}
          </div>
        </td>
        <td class="d-none d-lg-table-cell">
          <div class="resep-author">
            <div class="author-avatar">${initials}</div>
            <span class="author-name">${authorName}</span>
          </div>
        </td>
        <td class="d-none d-md-table-cell" style="text-align:center">
          <div class="resep-rating"><i class="bi bi-star-fill"></i> ${parseFloat(recipe.rating || 0).toFixed(1)}</div>
        </td>
        <td class="d-none d-md-table-cell resep-info-cell" style="text-align:center"><strong>${waktu}</strong> mnt</td>
        <td class="d-none d-lg-table-cell" style="text-align:center;font-size:.78rem;font-weight:600;color:var(--secondary)">${viewsFormatted}</td>
        <td><span class="resep-badge ${badgeClass}">${statusLabel}</span></td>
        <td>
          <div class="d-flex gap-1 justify-content-center">
            ${actionsHtml}
          </div>
        </td>
      </tr>
    `;
    $tbody.append(row);
  });
}

// Render Pagination Controls
function renderPagination(meta) {
  const $pagination = $('#resepPagination');
  const $info = $('#paginationInfo');
  const $btns = $('#paginationBtns');

  $btns.empty();

  if (!meta || meta.total === 0) {
    $pagination.hide();
    return;
  }

  $pagination.show();
  $info.html(`Menampilkan <strong>${meta.from || 0}–${meta.to || 0}</strong> dari <strong>${meta.total}</strong> resep`);

  // Prev
  const prevDisabled = meta.current_page === 1 ? 'disabled' : '';
  $btns.append(`<button class="pag-btn" ${prevDisabled} onclick="changePage(${meta.current_page - 1})"><i class="bi bi-chevron-left"></i></button>`);

  // Pages
  const lastPage = meta.last_page;
  const currentPage = meta.current_page;
  let startPage = Math.max(1, currentPage - 1);
  let endPage = Math.min(lastPage, currentPage + 1);

  if (startPage > 1) {
    $btns.append(`<button class="pag-btn" onclick="changePage(1)">1</button>`);
    if (startPage > 2) {
      $btns.append(`<span style="color:var(--muted);font-size:.85rem;padding:0 .25rem">…</span>`);
    }
  }

  for (let i = startPage; i <= endPage; i++) {
    const activeClass = i === currentPage ? 'active' : '';
    $btns.append(`<button class="pag-btn ${activeClass}" onclick="changePage(${i})">${i}</button>`);
  }

  if (endPage < lastPage) {
    if (endPage < lastPage - 1) {
      $btns.append(`<span style="color:var(--muted);font-size:.85rem;padding:0 .25rem">…</span>`);
    }
    $btns.append(`<button class="pag-btn" onclick="changePage(${lastPage})">${lastPage}</button>`);
  }

  // Next
  const nextDisabled = currentPage === lastPage ? 'disabled' : '';
  $btns.append(`<button class="pag-btn" ${nextDisabled} onclick="changePage(${currentPage + 1})"><i class="bi bi-chevron-right"></i></button>`);
}

// Approve pending recipe
window.approveResep = function(id, btn) {
  if (confirm('Apakah Anda yakin ingin menyetujui dan mempublikasikan resep ini?')) {
    const $btn = $(btn);
    $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');

    $.ajax({
      url: `/recipes/${id}/approve`,
      type: 'PATCH',
      success: function(response) {
        if (window.PA) {
          window.PA.toast({
            type: 'success',
            title: 'Berhasil',
            message: response.message || 'Resep berhasil disetujui dan dipublikasikan!',
            duration: 3000
          });
        } else {
          alert('Resep berhasil disetujui.');
        }
        loadRecipes(currentPage);
      },
      error: function(xhr) {
        console.error(xhr);
        alert('Gagal menyetujui resep.');
        loadRecipes(currentPage);
      }
    });
  }
};

// Modal delete handlers
window.openDeleteResep = function(id, judul) {
  deleteTargetId = id;
  const nameEl = document.getElementById('delResepName');
  if (nameEl) nameEl.textContent = '"' + judul + '"';

  const modalEl = document.getElementById('modalHapusResep');
  if (modalEl && window.bootstrap) {
    const modal = window.bootstrap.Modal.getOrCreateInstance(modalEl);
    modal.show();
  }
};

window.confirmHapusResep = function() {
  if (!deleteTargetId) return;

  $.ajax({
    url: `/recipes/${deleteTargetId}`,
    type: 'DELETE',
    success: function(response) {
      const modalEl = document.getElementById('modalHapusResep');
      if (modalEl && window.bootstrap) {
        const modal = window.bootstrap.Modal.getInstance(modalEl);
        if (modal) modal.hide();
      }

      if (window.PA) {
        window.PA.toast({
          type: 'success',
          title: 'Berhasil',
          message: response.message || 'Resep berhasil dihapus!',
          duration: 3000
        });
      } else {
        alert('Resep berhasil dihapus.');
      }
      loadRecipes(1);
    },
    error: function(xhr) {
      console.error(xhr);
      alert('Gagal menghapus resep.');
    }
  });
};

// Initial load
$(document).ready(function() {
  loadRecipes(1);
});
