// ── AOS Init ──
AOS.init({ once: true, easing: 'ease-out-cubic' });

// ── Floating icons ──
const icons = ['🍕','🍜','🍱','🥗','🍰','🥤','🍛','🍣','🧆','🫕','🍲','🥘','🧁','🍩','🍓','🥑'];
const container = document.getElementById('floatIcons');
if (container) {
  icons.forEach((emoji, i) => {
    const el = document.createElement('div');
    el.className = 'float-icon';
    el.textContent = emoji;
    el.style.cssText = `
      left: ${Math.random() * 100}vw;
      font-size: ${1.2 + Math.random() * 1.5}rem;
      animation-duration: ${8 + Math.random() * 12}s;
      animation-delay: ${Math.random() * 10}s;
    `;
    container.appendChild(el);
  });
}

// ── Toggle password ──
$(document).ready(function() {
  $(document).on('click', '.toggle-pw', function() {
    const $btn = $(this);
    const $input = $btn.siblings('input');
    const $icon = $btn.find('i');
    
    const show = $input.attr('type') === 'password';
    $input.attr('type', show ? 'text' : 'password');
    $icon.toggleClass('bi-eye-slash bi-eye');
  });
});

// ── Form submit (jQuery AJAX) ──
$(document).ready(function() {
  const $loginForm = $('#loginForm');
  const $loginBtn = $('#loginBtn');

  if ($loginForm.length && $loginBtn.length) {
    $loginForm.on('submit', function(e) {
      e.preventDefault();

      // Clear previous error messages
      $('.error-feedback').text('');

      // Show spinner and disable button
      $loginBtn.addClass('loading').prop('disabled', true);
      const $btnText = $loginBtn.find('.btn-text');
      const originalText = $btnText.text();
      $btnText.text('Memproses...');

      // Open fullscreen loader
      const loader = PA.loading({
        title: 'Memproses Masuk...',
        message: 'Sedang memvalidasi akun Anda.',
        dots: false
      });

      // Get form data and action url
      const formData = $loginForm.serialize();
      const actionUrl = $loginForm.attr('action');

      $.ajax({
        url: actionUrl,
        type: 'POST',
        data: formData,
        dataType: 'json',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json'
        },
        success: function(response) {
          loader.update('Autentikasi berhasil! Mengalihkan...');

          PA.toast({
            type: 'success',
            title: 'Masuk Berhasil',
            message: 'Selamat datang kembali di ResepKita!',
            duration: 3000,
            position: 'top-right'
          });

          setTimeout(() => {
            loader.close();
            if (response.success && response.redirect) {
              window.location.href = response.redirect;
            } else {
              window.location.reload();
            }
          }, 1000);
        },
        error: function(xhr) {
          // Close loader
          loader.close();

          // Restore button state
          $loginBtn.removeClass('loading').prop('disabled', false);
          $btnText.text(originalText);

          if (xhr.status === 422) {
            // Validation errors
            const errors = xhr.responseJSON.errors;
            if (errors) {
              Object.keys(errors).forEach(function(key) {
                const errorMsg = errors[key][0];
                $(`#error-${key}`).text(errorMsg);
              });
            }

            PA.toast({
              type: 'warning',
              title: 'Gagal Masuk',
              message: 'Periksa kembali data yang dimasukkan.',
              duration: 4000,
              position: 'top-right'
            });
          } else {
            // General error
            const generalMsg = xhr.responseJSON?.message || 'Terjadi kesalahan sistem, silakan coba lagi.';
            $(`#error-username`).text(generalMsg);

            PA.toast({
              type: 'danger',
              title: 'Kesalahan Sistem',
              message: generalMsg,
              duration: 5000,
              position: 'top-right'
            });
          }
        }
      });
    });
  }

  // ── Registration Form submit (jQuery AJAX) ──
  const $registerForm = $('#registerForm');
  const $registerBtn = $('#registerBtn');

  if ($registerForm.length && $registerBtn.length) {
    $registerForm.on('submit', function(e) {
      e.preventDefault();

      // Clear previous error messages
      $('.error-feedback').text('');

      // Show spinner and disable button
      $registerBtn.addClass('loading').prop('disabled', true);
      const $btnText = $registerBtn.find('.btn-text');
      const originalText = $btnText.text();
      $btnText.text('Mendaftar...');

      // Open fullscreen loader
      const loader = PA.loading({
        title: 'Memproses Pendaftaran...',
        message: 'Sedang membuat akun baru Anda.',
        dots: false
      });

      // Get form data and action url
      const formData = $registerForm.serialize();
      const actionUrl = $registerForm.attr('action');

      $.ajax({
        url: actionUrl,
        type: 'POST',
        data: formData,
        dataType: 'json',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json'
        },
        success: function(response) {
          loader.update('Pendaftaran berhasil! Mengalihkan...');

          PA.toast({
            type: 'success',
            title: 'Pendaftaran Berhasil',
            message: 'Selamat bergabung di ResepKita!',
            duration: 3000,
            position: 'top-right'
          });

          setTimeout(() => {
            loader.close();
            if (response.success && response.redirect) {
              window.location.href = response.redirect;
            } else {
              window.location.reload();
            }
          }, 1000);
        },
        error: function(xhr) {
          // Close loader
          loader.close();

          // Restore button state
          $registerBtn.removeClass('loading').prop('disabled', false);
          $btnText.text(originalText);

          if (xhr.status === 422) {
            // Validation errors
            const errors = xhr.responseJSON.errors;
            if (errors) {
              Object.keys(errors).forEach(function(key) {
                const errorMsg = errors[key][0];
                $(`#error-${key}`).text(errorMsg);
              });
            }

            PA.toast({
              type: 'warning',
              title: 'Pendaftaran Gagal',
              message: 'Periksa kembali data pendaftaran Anda.',
              duration: 4000,
              position: 'top-right'
            });
          } else {
            // General error
            const generalMsg = xhr.responseJSON?.message || 'Terjadi kesalahan sistem, silakan coba lagi.';
            $(`#error-name`).text(generalMsg);

            PA.toast({
              type: 'danger',
              title: 'Kesalahan Sistem',
              message: generalMsg,
              duration: 5000,
              position: 'top-right'
            });
          }
        }
      });
    });
  }
});

// ── Input focus styling helper ──
document.querySelectorAll('.input-wrap input').forEach(input => {
  input.addEventListener('focus', () => {
    const icon = input.closest('.input-wrap').querySelector('.icon-left');
    if (icon) icon.style.color = 'var(--primary)';
  });
  input.addEventListener('blur',  () => {
    const icon = input.closest('.input-wrap').querySelector('.icon-left');
    if (icon) icon.style.color = '';
  });
});
