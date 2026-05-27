<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>419 — Sesi Kedaluwarsa — {{ config('app.name', 'ResepKita') }}</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"/>
  <style>
  :root {
    --primary:        #e85d26;
    --primary-light:  #ff7b47;
    --primary-pale:   rgba(232,93,38,.08);
    --primary-border: rgba(232,93,38,.22);
    --secondary:      #2d1b0e;
    --bg:             #faf7f4;
    --surface:        #ffffff;
    --text:           #1a0e05;
    --muted:          #8a7060;
    --border:         #ede5dc;
    --danger:         #ef4444;
    --success:        #22c55e;
    --warning:        #f59e0b;
    --info:           #3b82f6;
    --purple:         #7c3aed;
    --radius:         16px;
    --shadow-sm:      0 2px 8px rgba(0,0,0,.06);
    --shadow-md:      0 6px 28px rgba(0,0,0,.1);
    --shadow-lg:      0 16px 56px rgba(0,0,0,.14);
    --transition:     .3s cubic-bezier(.4,0,.2,1);
  }

  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  html, body { height: 100%; }
  body {
    font-family: 'DM Sans', sans-serif;
    background: var(--bg);
    color: var(--text);
    overflow-x: hidden;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  ::-webkit-scrollbar { width: 5px; }
  ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 3px; }

  /* ── Floating bg emojis ── */
  .bg-float {
    position: fixed; pointer-events: none; z-index: 0; opacity: 0;
    animation: bgFloat linear infinite;
    user-select: none;
  }
  @keyframes bgFloat {
    0%   { opacity: 0; transform: translateY(105vh) rotate(0deg); }
    5%   { opacity: .045; }
    95%  { opacity: .045; }
    100% { opacity: 0; transform: translateY(-8vh) rotate(200deg); }
  }

  /* ── Blob decorations ── */
  .blob {
    position: fixed; border-radius: 50%;
    pointer-events: none; z-index: 0;
    filter: blur(72px);
  }

  /* ── Main card ── */
  .error-wrap {
    position: relative; z-index: 2;
    width: 100%; max-width: 520px;
    margin: 2rem 1rem;
    animation: cardIn .6s cubic-bezier(.34,1.56,.64,1) both;
  }
  @keyframes cardIn {
    from { opacity: 0; transform: translateY(32px) scale(.96); }
    to   { opacity: 1; transform: none; }
  }

  /* Brand link atas */
  .brand-link {
    display: flex; align-items: center; justify-content: center; gap: .6rem;
    text-decoration: none; margin-bottom: 1.75rem;
  }
  .brand-icon-sm {
    width: 38px; height: 38px; border-radius: 11px;
    background: linear-gradient(135deg, var(--primary-light), var(--primary));
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; flex-shrink: 0;
    box-shadow: 0 4px 14px rgba(232,93,38,.4);
    animation: brandPulse 3s ease-in-out infinite;
  }
  @keyframes brandPulse {
    0%,100% { box-shadow: 0 4px 14px rgba(232,93,38,.4); }
    50%      { box-shadow: 0 6px 22px rgba(232,93,38,.65); }
  }
  .brand-name-sm {
    font-family: 'Playfair Display', serif;
    font-size: 1.3rem; font-weight: 900;
    color: var(--secondary); letter-spacing: -.3px;
  }
  .brand-name-sm span { color: var(--primary); }

  /* Card utama */
  .error-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 24px;
    box-shadow: var(--shadow-lg);
    padding: 2.75rem 2.5rem 2.25rem;
    text-align: center;
    position: relative;
    overflow: hidden;
  }
  /* Stripe warna di atas card */
  .error-card::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0;
    height: 4px;
  }

  /* Nomor error besar */
  .error-code {
    font-family: 'Playfair Display', serif;
    font-size: clamp(5.5rem, 20vw, 9.5rem);
    font-weight: 900;
    line-height: 1;
    letter-spacing: -.04em;
    margin-bottom: .1rem;
    background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary) 55%, #c44a18 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    animation: codeFloat 4s ease-in-out infinite;
    display: block;
  }
  @keyframes codeFloat {
    0%,100% { transform: translateY(0); }
    50%      { transform: translateY(-7px); }
  }

  /* Emoji */
  .error-emoji {
    font-size: clamp(2.2rem, 7vw, 3.2rem);
    display: block;
    margin-bottom: .65rem;
    animation: emojiWiggle 5s ease-in-out infinite;
  }
  @keyframes emojiWiggle {
    0%,100%  { transform: rotate(0deg) scale(1); }
    20%      { transform: rotate(-6deg) scale(1.08); }
    40%      { transform: rotate(6deg) scale(1.08); }
    60%      { transform: rotate(-3deg) scale(1.04); }
    80%      { transform: rotate(3deg) scale(1.04); }
  }

  /* Status pill */
  .error-pill {
    display: inline-flex; align-items: center; gap: .38rem;
    padding: .26rem .9rem; border-radius: 20px;
    font-size: .71rem; font-weight: 700;
    font-family: monospace; letter-spacing: .04em;
    margin-bottom: 1rem;
  }
  .error-pill i { font-size: .78rem; }

  /* Judul & deskripsi */
  .error-title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(1.25rem, 4vw, 1.7rem);
    font-weight: 900; color: var(--secondary);
    margin-bottom: .7rem; line-height: 1.3;
  }
  .error-desc {
    font-size: .88rem; color: var(--muted);
    line-height: 1.85; margin-bottom: 1.75rem;
  }
  .error-desc strong { color: var(--secondary); }

  /* Divider */
  .error-divider { height: 1px; background: var(--border); margin: 1.5rem 0; }

  /* Action buttons */
  .error-actions {
    display: flex; gap: .6rem;
    justify-content: center; flex-wrap: wrap;
    margin-bottom: 1.5rem;
  }
  .btn-primary-e {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: .7rem 1.5rem; border: none; border-radius: 12px;
    background: linear-gradient(135deg, var(--primary-light), var(--primary));
    color: #fff; font-family: 'DM Sans', sans-serif;
    font-size: .88rem; font-weight: 600; cursor: pointer;
    text-decoration: none;
    box-shadow: 0 4px 16px rgba(232,93,38,.35);
    transition: transform .2s, box-shadow .2s;
  }
  .btn-primary-e:hover { transform: translateY(-2px); box-shadow: 0 6px 24px rgba(232,93,38,.45); color: #fff; }
  .btn-outline-e {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: .68rem 1.25rem;
    border: 1.5px solid var(--border); border-radius: 12px;
    background: var(--surface); color: var(--secondary);
    font-family: 'DM Sans', sans-serif; font-size: .88rem;
    font-weight: 600; cursor: pointer; text-decoration: none;
    transition: border-color .2s, color .2s, background .2s;
  }
  .btn-outline-e:hover { border-color: var(--primary-border); color: var(--primary); background: var(--primary-pale); }

  /* Tips box */
  .error-tips {
    background: var(--bg); border: 1.5px solid var(--border);
    border-radius: 14px; padding: 1rem 1.2rem; text-align: left;
  }
  .error-tips-ttl {
    font-size: .72rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: .6px; color: var(--muted);
    margin-bottom: .6rem; display: flex; align-items: center; gap: .35rem;
  }
  .error-tips-ttl i { font-size: .85rem; }
  .tip-item {
    display: flex; align-items: flex-start; gap: .45rem;
    font-size: .8rem; color: var(--muted); line-height: 1.6;
    margin-bottom: .38rem;
  }
  .tip-item:last-child { margin-bottom: 0; }
  .tip-item i { font-size: .78rem; flex-shrink: 0; margin-top: .18rem; color: var(--primary); }
  .tip-link { color: var(--primary); font-weight: 600; text-decoration: none; }
  .tip-link:hover { text-decoration: underline; }

  /* Countdown */
  .countdown-val {
    font-family: 'Playfair Display', serif;
    font-size: 2.2rem; font-weight: 900;
    color: var(--primary); line-height: 1;
    margin: .5rem 0 .2rem;
    transition: color .3s;
  }
  .countdown-val.urgent { color: var(--danger); }
  .countdown-lbl { font-size: .7rem; color: var(--muted); text-transform: uppercase; letter-spacing: .5px; margin-bottom: 1.25rem; }

  /* Progress bar 503 */
  .prog-track { height: 6px; background: var(--border); border-radius: 3px; overflow: hidden; margin: .7rem 0 .4rem; }
  .prog-fill   { height: 100%; border-radius: 3px; background: linear-gradient(90deg, var(--primary-light), var(--primary)); animation: progSlide 3s ease-in-out infinite alternate; }
  @keyframes progSlide { 0% { width: 28%; } 100% { width: 82%; } }

  /* Status chips 503 */
  .status-chips { display: flex; gap: .45rem; justify-content: center; flex-wrap: wrap; margin: .9rem 0 1.5rem; }
  .s-chip { display: inline-flex; align-items: center; gap: .3rem; padding: .22rem .72rem; border-radius: 20px; font-size: .71rem; font-weight: 600; }
  .chip-ok   { background: rgba(34,197,94,.09); border: 1.5px solid rgba(34,197,94,.25); color: #16a34a; }
  .chip-busy { background: rgba(245,158,11,.09); border: 1.5px solid rgba(245,158,11,.25); color: #d97706; }

  /* Login hint 403 */
  .login-hint {
    display: flex; align-items: center; gap: .75rem;
    background: var(--bg); border: 1.5px solid var(--border);
    border-radius: 14px; padding: .85rem 1.1rem;
    margin-bottom: 1.5rem; text-align: left;
  }
  .lh-icon {
    width: 38px; height: 38px; border-radius: 10px; flex-shrink: 0;
    background: linear-gradient(135deg, var(--primary-light), var(--primary));
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 1rem;
  }
  .lh-text { font-size: .8rem; color: var(--muted); line-height: 1.55; }
  .lh-text strong { color: var(--secondary); }

  /* Error ID 500 */
  .err-id-box {
    display: inline-flex; align-items: center; gap: .5rem;
    background: var(--bg); border: 1.5px solid var(--border);
    border-radius: 10px; padding: .5rem 1rem;
    font-family: monospace; font-size: .78rem;
    color: var(--muted); margin-bottom: 1.5rem;
    cursor: pointer; transition: border-color .2s, color .2s;
  }
  .err-id-box:hover { border-color: var(--primary-border); color: var(--primary); }
  .err-id-box i { font-size: .85rem; }

  /* Back to home footer note */
  .back-note {
    margin-top: 1.5rem; font-size: .78rem; color: var(--muted);
    display: flex; align-items: center; justify-content: center; gap: .35rem;
  }
  .back-note a { color: var(--primary); font-weight: 600; text-decoration: none; }
  .back-note a:hover { text-decoration: underline; }

  /* Responsive */
  @media (max-width: 575px) {
    .error-card { padding: 2rem 1.25rem 1.75rem; border-radius: 20px; }
    .error-actions { flex-direction: column; align-items: stretch; }
    .btn-primary-e, .btn-outline-e { justify-content: center; }
  }

  /* ── COMPACT OVERRIDES ── */
  html { overflow-y: auto; }
  body {
    align-items: flex-start;
    padding: 1.25rem 1rem;
    min-height: 100vh;
  }
  .error-wrap {
    max-width: 460px;
    margin: 0 auto;
  }
  .brand-link { margin-bottom: 1rem; }
  .brand-icon-sm { width: 32px; height: 32px; font-size: .95rem; }
  .brand-name-sm { font-size: 1.1rem; }
  .error-card { padding: 1.6rem 1.75rem 1.4rem; border-radius: 20px; }
  .error-emoji {
    font-size: clamp(1.6rem, 5vw, 2.2rem);
    margin-bottom: .4rem;
  }
  .error-code {
    font-size: clamp(4rem, 16vw, 6.5rem);
    margin-bottom: .05rem;
  }
  .error-pill { margin-bottom: .65rem; padding: .2rem .75rem; font-size: .68rem; }
  .error-title { font-size: clamp(1.1rem, 3.5vw, 1.4rem); margin-bottom: .45rem; }
  .error-desc  { font-size: .83rem; line-height: 1.7; margin-bottom: 1.1rem; }
  .error-actions { gap: .45rem; margin-bottom: 1rem; }
  .btn-primary-e { padding: .58rem 1.15rem; font-size: .84rem; border-radius: 10px; }
  .btn-outline-e { padding: .56rem 1rem;   font-size: .84rem; border-radius: 10px; }
  .error-tips { padding: .75rem 1rem; border-radius: 12px; }
  .error-tips-ttl { font-size: .68rem; margin-bottom: .45rem; }
  .tip-item { font-size: .77rem; margin-bottom: .28rem; line-height: 1.5; }
  .back-note { margin-top: 1rem; font-size: .74rem; }
  /* 419 countdown */
  .countdown-val { font-size: 1.8rem; }
  .countdown-lbl { font-size: .67rem; margin-bottom: .9rem; }
  /* 503 */
  .prog-track { margin: .5rem 0 .3rem; }
  .status-chips { margin: .6rem 0 1rem; gap: .4rem; }
  .s-chip { font-size: .68rem; padding: .18rem .6rem; }
  /* 500 err-id */
  .err-id-box { font-size: .75rem; padding: .4rem .85rem; margin-bottom: 1.1rem; }
  /* 403 login hint */
  .login-hint { padding: .7rem .9rem; margin-bottom: 1.1rem; }
  .lh-icon { width: 32px; height: 32px; font-size: .9rem; }
  .lh-text { font-size: .76rem; }

  </style>
</head>
<body>

  <!-- Blobs -->
  <div class="blob" style="width:380px;height:380px;background:rgba(124,58,237,.08);top:-100px;right:-100px"></div>
  <div class="blob" style="width:270px;height:270px;background:rgba(232,93,38,.06);bottom:-70px;left:-70px"></div>

  <div class="error-wrap">

    <!-- Brand -->
    <a href="{{ auth()->check() ? route('dashboard') : url('/') }}" class="brand-link">
      <div class="brand-icon-sm">🔥</div>
      <span class="brand-name-sm">Resep<span>Kita</span></span>
    </a>

    <!-- Card -->
    <div class="error-card" style="--stripe:linear-gradient(90deg,#a78bfa,#7c3aed)">
      <style>.error-card::before{background:linear-gradient(90deg,#a78bfa,#7c3aed)}</style>

      <span class="error-emoji">⏳</span>
      <span class="error-code">419</span>
      <span class="error-pill" style="background:rgba(124,58,237,.09);border:1.5px solid rgba(124,58,237,.28);color:#6d28d9"><i class="bi bi-clock-history"></i> Session Expired — 419</span>
      <h1 class="error-title">Sesi Kamu Sudah Kedaluwarsa</h1>
      <p class="error-desc">{{ $exception->getMessage() ?: 'Token keamanan halaman ini sudah tidak valid. Ini biasanya terjadi setelah kamu tidak aktif terlalu lama atau membuka tab baru.' }}</p>

      
      <div style="margin-bottom:1.5rem">
        <div class="countdown-val" id="cd419">05:00</div>
        <div class="countdown-lbl">Auto-redirect ke login</div>
      </div>
      <div class="error-actions">
        <button class="btn-primary-e" onclick="reloadNow()"><i class="bi bi-arrow-clockwise"></i> Refresh Halaman</button>
        <a href="{{ route('login') }}" class="btn-outline-e"><i class="bi bi-box-arrow-in-right"></i> Login Ulang</a>
      </div>
      <div class="error-tips">
        <div class="error-tips-ttl"><i class="bi bi-shield-exclamation"></i> Tentang Error 419</div>
        <div class="tip-item"><i class="bi bi-check2"></i> CSRF token sudah expired karena terlalu lama tidak aktif</div>
        <div class="tip-item"><i class="bi bi-check2"></i> Cukup <strong>refresh halaman</strong> — biasanya langsung teratasi</div>
        <div class="tip-item"><i class="bi bi-check2"></i> Jika masih bermasalah, hapus cache browser lalu login kembali</div>
        <div class="tip-item"><i class="bi bi-check2"></i> Hindari membuka banyak tab form secara bersamaan</div>
      </div>

    </div>

    <!-- Back note -->
    <div class="back-note">
      <i class="bi bi-house"></i>
      Kembali ke <a href="{{ auth()->check() ? route('dashboard') : url('/') }}">Dashboard</a>
      &nbsp;·&nbsp;
      <i class="bi bi-headset"></i>
      <a href="#">Hubungi Support</a>
    </div>

  </div>

  <script>
    let secs = 300;
    const el = document.getElementById('cd419');
    const t  = setInterval(() => {
      secs--;
      if (secs <= 0) { clearInterval(t); window.location.href = '{{ route("login") }}'; return; }
      const m = String(Math.floor(secs/60)).padStart(2,'0');
      const s = String(secs%60).padStart(2,'0');
      el.textContent = m + ':' + s;
      el.classList.toggle('urgent', secs <= 60);
    }, 1000);
    function reloadNow() { clearInterval(t); window.location.reload(); }
  </script>

  <script>
    /* Floating bg emojis */
    const FOOD = ['🍕','🍜','🌶️','🥗','🍰','🥤','🍛','🍣','🧁','🍩','🍓','🥑','🌿','🫕','🍲','🔥','🧄','🫚','🥥','🍱'];
    FOOD.forEach(e => {
      const el = document.createElement('div');
      el.className = 'bg-float'; el.textContent = e;
      const dur  = 14 + Math.random() * 16;
      const del  = Math.random() * 16;
      el.style.cssText = `left:${Math.random()*100}vw;font-size:${.9+Math.random()*1.3}rem;animation-duration:${dur}s;animation-delay:-${del}s;`;
      document.body.appendChild(el);
    });
  </script>

</body>
</html>
