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
const togglePw = document.getElementById('togglePw');
const pwInput  = document.getElementById('password');
const eyeIcon  = document.getElementById('eyeIcon');
if (togglePw && pwInput && eyeIcon) {
  togglePw.addEventListener('click', () => {
    const show = pwInput.type === 'password';
    pwInput.type = show ? 'text' : 'password';
    eyeIcon.className = show ? 'bi bi-eye-slash' : 'bi bi-eye';
  });
}

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
          if (response.success && response.redirect) {
            window.location.href = response.redirect;
          } else {
            window.location.reload();
          }
        },
        error: function(xhr) {
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
          } else {
            // General error
            const generalMsg = xhr.responseJSON?.message || 'Terjadi kesalahan sistem, silakan coba lagi.';
            $('#error-username').text(generalMsg);
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
