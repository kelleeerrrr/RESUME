<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Community Resumes</title>

  {{-- Theme init (keeps dark preference via localStorage) --}}
  <script>
    (function () {
      try {
        var lsTheme = localStorage.getItem('theme');
        var prefers = (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) ? 'dark' : 'light';
        var theme = lsTheme || prefers;
        if (theme === 'dark') document.documentElement.classList.add('dark');
        else document.documentElement.classList.remove('dark');
      } catch (e) {}
    })();
  </script>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

  <style>
    :root {
      --pink-1: #ffe6eb;
      --pink-2: #ffd6f6;
      --accent-pink: #d63384;
      --container-pink: rgba(206,133,150,0.95);
      --btn-green: #13d363;
      --radius: 14px;
      --line-grad: linear-gradient(90deg, rgba(255,115,165,1), rgba(255,200,220,1));
    }

    *{box-sizing:border-box;margin:0;padding:0}
    html,body{ height:100%; }
    body{
      font-family:"Poppins",sans-serif;
      background: linear-gradient(135deg, var(--pink-1), var(--pink-2));
      color:#222;
      display:flex;
      flex-direction:column;
      align-items:center;
      min-height:100vh;
      -webkit-font-smoothing:antialiased;
      -moz-osx-font-smoothing:grayscale;
    }

    html.dark body {
      background: linear-gradient(135deg, #1b0f12, #2a1420);
      color:#eee;
    }

    header{
      width:100%;
      max-width:1200px;
      margin-top:28px;
      padding:18px 22px;
      display:flex;
      justify-content:space-between;
      align-items:center;
      gap:12px;
    }
    header .left { display:flex; flex-direction:column; gap:6px; }
    header h1{ color:var(--accent-pink); font-size:2.5rem; margin:0; font-weight:700; }
    header p{ color: #6b2a4a; font-size:1.05rem; margin:0; opacity:0.95; }

    .auth {
      display:flex;
      gap:10px;
      align-items:center;
    }
    .auth a {
      padding:10px 14px;
      border-radius:999px;
      background: linear-gradient(135deg,#ff85a2,#ffb8d2);
      color:#fff; text-decoration:none; font-weight:600;
      box-shadow:0 6px 18px rgba(255,105,180,0.12);
    }
    .auth a.login { background: linear-gradient(90deg,#ff5a9b,#ff3d7a); }
    .auth a.signup { background: linear-gradient(90deg,#ff97b6,#ff6ea0); }

    .theme-toggle {
      display:inline-flex;
      align-items:center;
      gap:8px;
      padding:8px 10px;
      border-radius:999px;
      background: rgba(255,255,255,0.9);
      color: #6b2a4a;
      font-weight:600;
      cursor:pointer;
      border:none;
      box-shadow:0 6px 18px rgba(0,0,0,0.06);
    }
    html.dark .theme-toggle {
      background: rgba(255,255,255,0.06);
      color: #f6e6ef;
      box-shadow: none;
      border:1px solid rgba(255,255,255,0.04);
    }

    main.hero{
      margin-top:-25px;
      width:100%;
      padding:34px 20px 50px;
      display:flex;
      justify-content:center;
      align-items:flex-start;
    }

    .card {
      width:96%;
      max-width:1200px;
      background:var(--container-pink);
      color:#fff;
      border-radius:var(--radius);
      padding:0 28px 28px;
      box-shadow:0 10px 40px rgba(0,0,0,0.15);
      display:flex;
      gap:28px;
      align-items:flex-start;
      overflow:visible;
      position:relative;
    }
    html.dark .card { background: rgba(24, 7, 16, 0.92); }

    .card-topline {
      position:absolute;
      top:0;
      left:0;
      right:0;
      height:8px;
      border-top-left-radius:var(--radius);
      border-top-right-radius:var(--radius);
      background: var(--line-grad);
      z-index:6;
    }
    html.dark .card-topline {
      background: linear-gradient(90deg, rgba(200,80,140,0.9), rgba(130,60,120,0.85));
    }

    .left-col {
      margin: 10px;
      flex:1 1 640px;
      min-width:300px;
      max-width:760px;
      color: #fff;
      padding-top:20px;
    }
    .left-col h1 { font-size:2rem; margin-bottom:6px; color:#fff; line-height:1.02; }
    .left-col p { margin-bottom:14px; color: rgba(255,255,255,0.96); font-size:1.05rem; }

    .inner {
      background: #fff;
      color: #333;
      border-radius:12px;
      padding:18px;
      box-shadow: 0 8px 30px rgba(0,0,0,0.08);
      margin-bottom:18px;
      max-width:820px;
    }
    html.dark .inner {
      background: rgba(255,255,255,0.04);
      color: #eee;
      box-shadow: none;
      border:1px solid rgba(255,255,255,0.03);
    }

    .controls {
      display:flex;
      gap:12px;
      align-items:center;
      justify-content:flex-start;
      flex-wrap:wrap;
      margin-bottom:8px;
    }

    .search {
      padding:12px 14px;
      border-radius:12px;
      border:1px solid rgba(16,24,40,0.06);
      min-width:280px;
      box-shadow: inset 0 -2px 8px rgba(0,0,0,0.03);
      font-size:0.98rem;
    }
    html.dark .search {
      background: rgba(0,0,0,0.4);
      color:#fff;
      border:1px solid rgba(255,255,255,0.04);
      box-shadow:none;
    }

    .sort-select {
      padding:10px 12px;
      border-radius:10px;
      border:1px solid rgba(16,24,40,0.06);
      min-width:220px;
      font-size:0.95rem;
      background:#fff;
    }
    html.dark .sort-select {
      background: rgba(0,0,0,0.35);
      color:#fff;
      border:1px solid rgba(255,255,255,0.04);
    }

    .results { margin-left:auto; color:#6b2a4a; font-weight:700; }

    .users-grid {
      display:grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap:18px;
      margin-top:14px;
    }

    .user-card {
      background:rgba(219, 149, 184, 0.73);
      border-radius:12px;
      padding:14px;
      text-align:center;
      text-decoration:none;
      color:inherit;
      box-shadow:0 8px 20px rgba(0,0,0,0.06);
      border:1px solid rgba(255,193,203,0.28);
      transition: transform .22s ease, box-shadow .22s ease;
      position:relative;
      overflow:hidden;
      display:flex;
      flex-direction:column;
      align-items:center;
      gap:8px;
      min-height:150px;
    }
    .user-card:hover { transform:translateY(-8px); box-shadow:0 18px 42px rgba(255,105,180,0.18); }
    html.dark .user-card {
      background: rgba(255,255,255,0.03);
      border:1px solid rgba(255,255,255,0.03);
      box-shadow:none;
    }

    .user-photo {
      width:96px;
      height:96px;
      border-radius:50%;
      object-fit:cover;
      border:3px solid rgba(255,255,255,0.9);
      box-shadow:0 10px 22px rgba(0,0,0,0.08);
      background:#f3f3f3;
    }
    html.dark .user-photo { border-color: rgba(255,255,255,0.12); }

    .user-card h3 { color:var(--accent-pink); font-size:1.05rem; margin:6px 0 2px; font-weight:700; }
    .user-card .course { color:#666; font-size:0.92rem; margin:0; min-height:28px; }

    .user-card::after {
      content: "View Resume";
      position:absolute;
      left:8px; right:8px; bottom:-40px;
      background: linear-gradient(90deg,#ffb3c6,#ff9ec7);
      color:#fff; height:36px; line-height:36px; font-weight:700; text-align:center;
      border-radius:8px;
      transition: bottom .18s ease;
      box-shadow:0 8px 20px rgba(255,105,180,0.12);
    }
    .user-card:hover::after { bottom:8px; }

    .see-all-card {
      display:flex;
      flex-direction:column;
      align-items:center;
      justify-content:center;
      gap:8px;
      min-height:150px;
      border-radius:12px;
      padding:14px;
      cursor:pointer;
      text-decoration:none;
      color:inherit;
      border: 2px dashed rgba(255,255,255,0.28);
      background: linear-gradient(180deg, rgba(255,255,255,0.04), rgba(255,255,255,0.02));
    }
    html.dark .see-all-card {
      border-color: rgba(255,255,255,0.06);
      background: rgba(255,255,255,0.02);
    }
    .see-all-card .btn {
      padding:10px 14px;
      border-radius:999px;
      background: linear-gradient(90deg,#ff5a9b,#ff3d7a);
      color:#fff;
      font-weight:700;
      text-decoration:none;
    }

    .right-col {
      width:320px;
      display:flex;
      flex-direction:column;
      gap:12px;
      align-items:center;
      padding-top:18px;
    }

    .placeholder-card {
      width:100%;
      max-width:320px;
      border-radius:12px;
      padding:14px;
      background: linear-gradient(180deg, rgba(255,255,255,0.06), rgba(255,255,255,0.02));
      box-shadow:0 10px 30px rgba(0,0,0,0.12);
      display:flex;
      flex-direction:column;
      gap:12px;
      align-items:center;
      color:#fff;
    }
    html.dark .placeholder-card {
      background: linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));
      box-shadow:none;
      border:1px solid rgba(255,255,255,0.03);
    }

    .placeholder-photo {
      width:100%;
      height:270px;
      border-radius:10px;
      overflow:hidden;
      background: linear-gradient(135deg, rgba(255,255,255,0.06), rgba(255,255,255,0.02));
      display:block;
      object-fit:cover;
    }

    .placeholder-title {
      font-weight:700;
      color:#fff;
      text-align:center;
      font-size:0.95rem;
    }

    @media (max-width:1100px){
      .users-grid { grid-template-columns: repeat(auto-fit, minmax(180px,1fr)); }
    }
    @media (max-width:980px){
      .card { flex-direction:column; align-items:stretch; }
      .right-col{ width:100%; order:3; align-items:center; }
      .left-col{ order:1; }
    }
  </style>
</head>
<body>
  <header>
    <div class="left">
      <h1>Discover talent in our community</h1>
    </div>

    <div class="auth">
      <button id="themeToggle" class="theme-toggle" aria-pressed="false" title="Toggle dark mode">
        <span id="themeIcon">🌙</span>
        <span id="themeLabel">Dark</span>
      </button>

      <a href="{{ route('login') }}" class="login">Login</a>
      <a href="{{ route('register') }}" class="signup">Sign Up</a>
    </div>
  </header>

  <main class="hero" role="main" aria-labelledby="main-title">
    <div class="card" role="region" aria-label="Community resume browser">
      <div class="card-topline" aria-hidden="true"></div>

      <div class="left-col">
        <h1 id="main-title">Community Resumes</h1>
        <p>Browse public resumes shared by our members.</p>

        <div class="inner" aria-label="Search and sort controls">
          <div class="controls" role="group" aria-label="Search and sort">
            <input id="searchInput" class="search" type="search" placeholder="Search name or interest (e.g. 'Web', 'AI')"
                   aria-label="Search resumes by name or interest">
            <select id="sortSelect" class="sort-select" aria-label="Sort resumes">
              <option value="az">A to Z (First Name)</option>
              <option value="za">Z to A (First Name)</option>
              <option value="newest">Newest to Oldest</option>
              <option value="oldest">Oldest to Newest</option>
            </select>
            <div class="results" id="resultsCount" aria-live="polite">0 result(s)</div>
          </div>

          <div class="users-grid" id="usersGrid" style="margin-top:14px;">
            @forelse($users as $user)
              @php
                $r = $user->resume ?? null;
                $photo = $r && !empty($r->profile_photo) ? asset('storage/'.$r->profile_photo) : asset('images/default-avatar.png');
                $course = $r->course ?? $r->specialization ?? ($r && is_string($r->interests) ? $r->interests : '');
                $created = $user->created_at ?? now();
              @endphp

              <a href="{{ route('resume.public.show', $user->id) }}"
                 class="user-card"
                 data-name="{{ strtolower($user->name ?? '') }}"
                 data-interests="{{ strtolower(is_string($r && $r->interests ? $r->interests : '')) }}"
                 data-date="{{ $created }}"
                 title="Open {{ $user->name ?? 'User' }}'s resume"
              >
                <img src="{{ $photo }}" alt="{{ $user->name ?? 'User' }}" class="user-photo" loading="lazy">
                <h3>{{ $user->name ?? 'Anonymous' }}</h3>
                <p class="course">{{ \Illuminate\Support\Str::limit($course ?: '—', 48) }}</p>
              </a>
            @empty
              <p style="color:#666">No public resumes yet — invite members to sign up and create their resume.</p>
            @endforelse
          </div>
        </div>
      </div>

      <div class="right-col" aria-hidden="true">
        <div class="placeholder-card" role="img" aria-label="Decorative photo">
          <img src='{{ asset("images/community-photo.jpg") }}' alt="Community spotlight" class="placeholder-photo" onerror="this.onerror=null;this.src='{{ asset('default-spotlight.jpg') }}'">
          <div class="placeholder-title">Community Spotlight</div>
          <div style="font-size:0.9rem; color:rgba(255,255,255,0.95); text-align:center;">
            A curated view of member highlights — click a resume to learn more.
          </div>
        </div>
      </div>
    </div>
  </main>

  <script>
    (function(){
      const MAX_VISIBLE = 5;
      const searchInput = document.getElementById('searchInput');
      const sortSelect = document.getElementById('sortSelect');
      const usersGrid = document.getElementById('usersGrid');
      const resultsCountEl = document.getElementById('resultsCount');

      const themeToggle = document.getElementById('themeToggle');
      const themeIcon = document.getElementById('themeIcon');
      const themeLabel = document.getElementById('themeLabel');

      function updateThemeUI() {
        const isDark = document.documentElement.classList.contains('dark');
        themeToggle.setAttribute('aria-pressed', String(isDark));
        themeIcon.textContent = isDark ? '☀️' : '🌙';
        themeLabel.textContent = isDark ? 'Light' : 'Dark';
      }

      themeToggle.addEventListener('click', () => {
        document.documentElement.classList.toggle('dark');
        localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
        updateThemeUI();
      });

      updateThemeUI();

      function getCardsArray() {
        return Array.from(usersGrid.querySelectorAll('.user-card'));
      }

      function createSeeAllCard(hiddenCount){
        removeSeeAllCard();
        const el = document.createElement('div');
        el.className = 'see-all-card';
        el.setAttribute('role','button');
        el.setAttribute('tabindex','0');
        el.dataset.state = 'seeall';
        el.innerHTML = `<div><strong>+${hiddenCount} more</strong></div><div class="btn">See all</div>`;
        el.addEventListener('click', toggleSeeAll);
        el.addEventListener('keypress', (e) => { if(e.key==='Enter'||e.key===' ') toggleSeeAll(); });
        usersGrid.appendChild(el);
      }

      function removeSeeAllCard(){
        const existing = usersGrid.querySelector('.see-all-card');
        if(existing) existing.remove();
      }

      function toggleSeeAll(){
        const el = usersGrid.querySelector('.see-all-card');
        const cards = getCardsArray();

        if(el.dataset.state === 'seeall'){
          cards.forEach(c => c.style.display = '');
          el.dataset.state = 'showless';
          el.innerHTML = `<div><strong>Show Less</strong></div><div class="btn">Show Less</div>`;
        } else {
          cards.forEach((c, idx) => c.style.display = (idx < MAX_VISIBLE) ? '' : 'none');
          const hiddenCount = cards.length - MAX_VISIBLE;
          el.dataset.state = 'seeall';
          el.innerHTML = `<div><strong>+${hiddenCount} more</strong></div><div class="btn">See all</div>`;
        }
      }

      function applyFilters(){
        const q = searchInput.value.toLowerCase().trim();
        const cards = getCardsArray();
        let visibleCount = 0;

        cards.forEach(c => {
          const name = c.dataset.name || '';
          const interests = c.dataset.interests || '';
          const match = name.includes(q) || interests.includes(q);
          c.style.display = match ? '' : 'none';
          if(match) visibleCount++;
        });

        resultsCountEl.textContent = `${visibleCount} result(s)`;

        removeSeeAllCard();
        if(visibleCount > MAX_VISIBLE){
          createSeeAllCard(visibleCount - MAX_VISIBLE);
          cards.forEach((c, idx) => { if(idx >= MAX_VISIBLE) c.style.display = 'none'; });
        }
      }

      searchInput.addEventListener('input', applyFilters);
      sortSelect.addEventListener('change', () => {
        const val = sortSelect.value;
        const cards = getCardsArray();
        cards.sort((a,b)=>{
          const nameA = a.dataset.name||'', nameB = b.dataset.name||'';
          const dateA = new Date(a.dataset.date), dateB = new Date(b.dataset.date);
          if(val==='az') return nameA.localeCompare(nameB);
          if(val==='za') return nameB.localeCompare(nameA);
          if(val==='newest') return dateB - dateA;
          if(val==='oldest') return dateA - dateB;
          return 0;
        }).forEach(c=>usersGrid.appendChild(c));
        applyFilters();
      });

      applyFilters(); // initial
    })();
  </script>
</body>
</html>
