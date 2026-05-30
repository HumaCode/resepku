$(document).ready(function () {
  // Set up AJAX header for CSRF Token
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  const recipeId = $('#recipeDetailContainer').data('id');

  // 1. Toggle status (Publish / Unpublish)
  window.toggleStatus = function (btn) {
    const $btn = $(btn);
    const originalHtml = $btn.html();
    $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Processing...');

    $.ajax({
      url: `/recipes/${recipeId}/toggle-status`,
      type: 'PATCH',
      success: function (response) {
        if (response.success) {
          // Update Status Badge(s)
          const $badges = $('.ab-status-badge');
          if (response.status === 'published') {
            $badges.removeClass('draft pending').addClass('published').html('<i class="bi bi-check-circle-fill"></i> Published');
            $btn.removeClass('success').addClass('warning').html('<i class="bi bi-eye-slash"></i> Unpublish');
            $('.aside-btn.unpublish').html('<i class="bi bi-eye-slash"></i> Unpublish');
          } else {
            $badges.removeClass('published pending').addClass('draft').html('<i class="bi bi-hourglass-split"></i> Draft');
            $btn.removeClass('warning').addClass('success').html('<i class="bi bi-check-circle-fill"></i> Publish');
            $('.aside-btn.unpublish').html('<i class="bi bi-check-circle-fill"></i> Publish');
          }

          if (window.PA) {
            window.PA.toast({
              type: 'success',
              title: 'Berhasil',
              message: response.message,
              duration: 3000
            });
          }
        }
      },
      error: function (xhr) {
        console.error(xhr);
        $btn.html(originalHtml).prop('disabled', false);
        if (window.PA) {
          window.PA.toast({
            type: 'error',
            title: 'Gagal',
            message: 'Gagal mengubah status resep.',
            duration: 3000
          });
        } else {
          alert('Gagal mengubah status resep.');
        }
      },
      complete: function () {
        $btn.prop('disabled', false);
      }
    });
  };

  // 2. Toggle Featured
  window.toggleFeatured = function (btn) {
    const $btn = $(btn);
    const originalHtml = $btn.html();
    $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Processing...');

    $.ajax({
      url: `/recipes/${recipeId}/toggle-featured`,
      type: 'PATCH',
      success: function (response) {
        if (response.success) {
          const $featuredBadge = $('.ab-featured-badge');
          if (response.is_featured === '1') {
            $featuredBadge.removeClass('d-none').addClass('d-flex');
            $btn.html('<i class="bi bi-star-fill"></i> Lepas dari Unggulan');
          } else {
            $featuredBadge.removeClass('d-flex').addClass('d-none');
            $btn.html('<i class="bi bi-star"></i> Tandai Unggulan');
          }

          if (window.PA) {
            window.PA.toast({
              type: 'success',
              title: 'Berhasil',
              message: response.message,
              duration: 3000
            });
          }
        }
      },
      error: function (xhr) {
        console.error(xhr);
        $btn.html(originalHtml).prop('disabled', false);
        if (window.PA) {
          window.PA.toast({
            type: 'error',
            title: 'Gagal',
            message: 'Gagal mengubah status unggulan.',
            duration: 3000
          });
        } else {
          alert('Gagal mengubah status unggulan.');
        }
      },
      complete: function () {
        $btn.prop('disabled', false);
      }
    });
  };

  // 3. Duplicate Recipe
  window.duplicateResep = function (btn) {
    const $btn = $(btn);
    const originalHtml = $btn.html();
    $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Menduplikat...');

    $.ajax({
      url: `/recipes/${recipeId}/duplicate`,
      type: 'POST',
      success: function (response) {
        if (response.success) {
          if (window.PA) {
            window.PA.toast({
              type: 'success',
              title: 'Berhasil',
              message: response.message,
              duration: 3000
            });
          }
          setTimeout(() => {
            window.location.href = response.redirect;
          }, 1000);
        }
      },
      error: function (xhr) {
        console.error(xhr);
        $btn.html(originalHtml).prop('disabled', false);
        if (window.PA) {
          window.PA.toast({
            type: 'error',
            title: 'Gagal',
            message: 'Gagal menduplikat resep.',
            duration: 3000
          });
        } else {
          alert('Gagal menduplikat resep.');
        }
      }
    });
  };

  // 4. Confirm Delete
  window.confirmHapus = function (btn) {
    const $btn = $(btn);
    const originalHtml = $btn.html();
    $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Menghapus...');

    $.ajax({
      url: `/recipes/${recipeId}`,
      type: 'DELETE',
      success: function (response) {
        const modalEl = document.getElementById('modalHapus');
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
        }
        setTimeout(() => {
          window.location.href = '/recipes';
        }, 1000);
      },
      error: function (xhr) {
        console.error(xhr);
        $btn.html(originalHtml).prop('disabled', false);
        if (window.PA) {
          window.PA.toast({
            type: 'error',
            title: 'Gagal',
            message: 'Gagal menghapus resep.',
            duration: 3000
          });
        } else {
          alert('Gagal menghapus resep.');
        }
      }
    });
  };

  // 5. Toggle Ingredient Checkbox
  window.toggleIngredientCheck = function (card) {
    $(card).toggleClass('checked');
  };
});
