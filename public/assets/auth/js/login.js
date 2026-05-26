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

// ── Form submit (Laravel-compatible loading state) ──
const loginForm = document.getElementById('loginForm');
const loginBtn = document.getElementById('loginBtn');
if (loginForm && loginBtn) {
  loginForm.addEventListener('submit', function() {
    if (loginForm.checkValidity()) {
      loginBtn.classList.add('loading');
      const btnText = loginBtn.querySelector('.btn-text');
      if (btnText) {
        btnText.textContent = 'Memproses...';
      }
    }
  });
}

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
