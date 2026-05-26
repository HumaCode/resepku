document.addEventListener('DOMContentLoaded', () => {
  AOS.init({ once: true, easing: 'ease-out-cubic', offset: 40 });

  /* ── BG Floats ── */
  const bgEmojis = ['🍕','🍜','🍱','🥗','🍰','🥤','🍛','🍣','🧆','🫕','🍲','🧁','🍩','🍓','🥑','🌶️','🧅','🫚'];
  const bgWrap = document.getElementById('bgFloats');
  if (bgWrap) {
    bgEmojis.forEach(e => {
      const el = document.createElement('div');
      el.className = 'bg-float';
      el.textContent = e;
      el.style.cssText = `left:${Math.random()*100}vw;font-size:${1.2+Math.random()*1.6}rem;animation-duration:${14+Math.random()*16}s;animation-delay:${Math.random()*12}s;`;
      bgWrap.appendChild(el);
    });
  }

  /* ── Sidebar toggle ── */
  let sidebarCollapsed = false;
  window.toggleSidebar = function() {
    const sb = document.getElementById('sidebar');
    const mw = document.getElementById('mainWrapper');
    const btn = document.getElementById('toggleBtn');
    const isMobile = window.innerWidth <= 991;
    if (isMobile) {
      if (sb) sb.classList.toggle('mobile-open');
      const overlay = document.getElementById('sidebarOverlay');
      if (overlay) overlay.classList.toggle('show');
    } else {
      sidebarCollapsed = !sidebarCollapsed;
      if (sb) sb.classList.toggle('collapsed', sidebarCollapsed);
      if (mw) mw.classList.toggle('expanded', sidebarCollapsed);
      if (btn) btn.classList.toggle('rotated', sidebarCollapsed);
    }
  }

  window.closeMobileSidebar = function() {
    const sb = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    if (sb) sb.classList.remove('mobile-open');
    if (overlay) overlay.classList.remove('show');
  }

  /* ── User dropdown ── */
  window.toggleUserMenu = function() {
    const menu = document.getElementById('userMenu');
    const dd   = document.getElementById('userDropdown');
    if (menu) menu.classList.toggle('open');
    if (dd) dd.classList.toggle('show');
  }

  document.addEventListener('click', e => {
    const menu = document.getElementById('userMenu');
    const dd   = document.getElementById('userDropdown');
    if (menu && !e.target.closest('.user-menu')) {
      menu.classList.remove('open');
      if (dd) dd.classList.remove('show');
    }
  });

  /* ── Logout modal ── */
  window.showLogoutModal = function() {
    const dd   = document.getElementById('userDropdown');
    const menu = document.getElementById('userMenu');
    if (dd) dd.classList.remove('show');
    if (menu) menu.classList.remove('open');
    
    const logoutModalEl = document.getElementById('logoutModal');
    if (logoutModalEl && window.bootstrap) {
      const modal = window.bootstrap.Modal.getOrCreateInstance(logoutModalEl);
      modal.show();
    }
  }

  window.doLogout = function() {
    const btn = document.querySelector('.btn-logout-confirm');
    if (btn) {
      btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Keluar...';
      btn.disabled = true;
    }
    const form = document.getElementById('logout-form');
    if (form) {
      form.submit();
    } else {
      window.location.href = '/logout';
    }
  }

  /* ── FAB ── */
  const fab = document.getElementById('fab');
  if (fab) {
    window.addEventListener('scroll', () => {
      fab.classList.toggle('visible', window.scrollY > 250);
    });
    window.scrollToTop = function() {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }
  }

  /* ── Nav active ── */
  document.querySelectorAll('.nav-link-custom').forEach(link => {
    link.addEventListener('click', function(e) {
      if (this.getAttribute('href') === '#') e.preventDefault();
      document.querySelectorAll('.nav-link-custom').forEach(l => l.classList.remove('active'));
      this.classList.add('active');
    });
  });

  /* ── Sidebar tooltips for collapsed ── */
  const sidebar = document.getElementById('sidebar');
  document.querySelectorAll('.nav-link-custom').forEach(link => {
    link.addEventListener('mouseenter', function() {
      if (!sidebar || !sidebar.classList.contains('collapsed')) return;
      const tip = document.createElement('div');
      tip.className = 'sidebar-tip';
      tip.textContent = this.dataset.tooltip || '';
      tip.style.cssText = `position:fixed;background:var(--secondary);color:#fff;padding:.35rem .85rem;border-radius:8px;font-size:.78rem;font-weight:600;z-index:9999;pointer-events:none;white-space:nowrap;box-shadow:0 4px 14px rgba(0,0,0,.25);transition:opacity .2s;`;
      const rect = this.getBoundingClientRect();
      tip.style.left = (rect.right + 10) + 'px';
      tip.style.top  = (rect.top + rect.height/2) + 'px';
      tip.style.transform = 'translateY(-50%)';
      document.body.appendChild(tip);
      this._tip = tip;
    });
    link.addEventListener('mouseleave', function() {
      if (this._tip) { this._tip.remove(); this._tip = null; }
    });
  });

  /* ── Counter animation ── */
  function animateCount(el, target) {
    let start = 0; const dur = 1400;
    const isFloat = target % 1 !== 0;
    const step = timestamp => {
      if (!start) start = timestamp;
      const prog = Math.min((timestamp - start) / dur, 1);
      const ease = 1 - Math.pow(1 - prog, 3);
      const val = target * ease;
      if (target >= 1000) el.textContent = (val/1000).toFixed(1) + 'K';
      else if (isFloat) el.textContent = val.toFixed(2);
      else el.textContent = Math.floor(val).toLocaleString();
      if (prog < 1) requestAnimationFrame(step);
      else el.textContent = isFloat ? target.toFixed(2) : (target >= 1000 ? (target/1000).toFixed(1)+'K' : target.toLocaleString());
    };
    requestAnimationFrame(step);
  }

  const firstStat = document.querySelector('.stat-card');
  if (firstStat) {
    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const nums = [1248, 8372, 94500, 4.87];
          document.querySelectorAll('.stat-num').forEach((el, i) => animateCount(el, nums[i]));
          observer.disconnect();
        }
      });
    });
    observer.observe(firstStat);
  }
});
