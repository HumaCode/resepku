document.addEventListener('DOMContentLoaded', () => {
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
