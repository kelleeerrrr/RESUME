<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Public Resume | Irish Rivera</title>

  {{-- Pre-paint theme init: read ?theme=, cookie, localStorage or prefers-color-scheme --}}
  <script>
    (function(){
      try {
        var params = (function(){ try { return new URLSearchParams(location.search); } catch(e){ return null; } })();
        var qTheme = params ? params.get('theme') : null;

        function readCookie(name){
          try {
            var m = document.cookie.match(new RegExp('(?:^|; )' + name + '=([^;]*)'));
            return m ? decodeURIComponent(m[1]) : null;
          } catch(e){ return null; }
        }

        var cookieTheme = readCookie('theme');
        var lsTheme = null;
        try { lsTheme = localStorage.getItem('theme'); } catch(e){}
        var prefers = (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) ? 'dark' : 'light';

        var theme = qTheme || cookieTheme || lsTheme || prefers;
        if (theme === 'dark') document.documentElement.classList.add('dark');
        else document.documentElement.classList.remove('dark');
      } catch(e){}
    })();
  </script>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <style>
    :root{
      --pink: rgba(255,31,132,0.95);
      --pink-soft: #d6417a;
      --btn-radius: 6px;
      --resume-btn-color: var(--pink-soft);
      --topbar-height: 56px;
    }

    html { box-sizing: border-box; }
    *, *:before, *:after { box-sizing: inherit; }
    body {
      margin: 5px;
      font-family: 'Poppins', sans-serif;
      background: url('{{ asset("images/resumebg.jpg") }}') no-repeat center center fixed;
      background-size: cover;
      color: #333;
      transition: background 0.4s, color 0.4s;
    }
    /* prefer html.dark but keep body.dark compatibility */
    html.dark body,
    body.dark {
      background: linear-gradient(to bottom right, #0f1720, #1b2430), url('{{ asset("images/resumebg.jpg") }}') no-repeat center center fixed;
      background-blend-mode: multiply;
      color: #e9eef6;
    }

    /* Header */
    header{
      width:100%;
      background: var(--pink);
      color:#fff;
      display:flex;
      align-items:center;
      justify-content:space-between;
      padding:12px 24px;
      position:fixed;
      top:0; left:0;
      z-index:50;
      box-shadow:0 3px 10px rgba(0,0,0,0.12);
    }
    header img { height:44px; display:block; }
    #themeToggle {
      background:transparent;
      border:0;
      color:#fff;
      font-size:1.2rem;
      cursor:pointer;
      padding:6px;
      transition:transform .15s ease;
    }
    #themeToggle:hover{ transform: scale(1.15); }

    /* Welcome section */
    .welcome-msg {
      text-align:center;
      padding-top: calc(var(--topbar-height) + 30px);
      padding-bottom: 20px;
    }
    .welcome-msg h1 { font-size:1.6rem; margin-bottom:6px; color:var(--pink-soft); }
    .welcome-msg p {
      font-size: 0.98rem;
      color: #111;
      transition: color 0.3s ease;
    }
    html.dark .welcome-msg p,
    body.dark .welcome-msg p {
      color: #fff;
    }

    /* Action row (Download + Back button) */
    .action-row {
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      max-width: 1000px;
      margin: 0 auto 15px auto;
    }
    .download-cv-btn {
      display:inline-block;
      padding:16px 30px;
      border-radius:6px;
      background:#2ecc71;
      color:white;
      text-decoration:none;
      font-weight:500;
      transition:.3s;
      border:none;
      cursor:pointer;
    }
    .download-cv-btn:hover { background:#29b765; }

    .back-btn {
      position: absolute;
      right: 0;
      display:inline-block;
      padding:10px 15px;
      border-radius:6px;
      background: #f10078ff;
      color:white;
      text-decoration:none;
      font-weight:500;
      transition:.3s;
      border:none;
      cursor:pointer;
      box-shadow:0 5px 15px rgba(177,110,207,0.3);
    }
    .back-btn:hover { background:#f73898ff; }

    /* Resume container */
    .container {
      display:flex;
      max-width:1000px;
      margin: 20px auto 70px auto;
      background: rgba(255,255,255,0.95);
      box-shadow:0 0 10px rgba(0,0,0,0.2);
      border-radius:8px;
      overflow:hidden;
      color:#333;
      position:relative;
      transition:.3s;
      padding-top: 12px;
    }

    .left { background:#ce8596ff; color:white; width:35%; padding:20px; transition:.3s; }
    .left img { width:150px; height:150px; border-radius:50%; display:block; margin:0 auto 20px; border:3px solid white; object-fit:cover; }
    .left h2 { border-bottom:2px solid white; padding-bottom:5px; margin-bottom:10px; }
    .left p, .left ul li { margin:5px 0; }

    .right { width:65%; padding:20px; background:#fff; transition:.3s; position:relative; }
    .right h1 { color:var(--pink-soft); transition:.3s; }
    .right h2 { background:#d6799c; color:white; padding:5px 10px; border-radius:5px; margin-top:15px; transition:.3s; }
    ul { list-style:none; padding-left:15px; }
    ul li { margin:8px 0; }

    @media (max-width:880px) {
      .container { flex-direction:column; width:calc(100% - 30px); margin:20px 15px; padding-top: 20px; }
      .left,.right { width:100%; }
      .action-row { flex-direction: column; align-items: center; }
      .back-btn { position: static; margin-top:10px; }
      header { padding:10px 12px; }
    }

    /* PRINT STYLES */
    @media print {
      html, body { background: #fff !important; color: #000 !important; height: auto !important; margin: 0 !important; padding: 0 !important; }
      header, .action-row, .welcome-msg { display: none !important; }
      .container { display: flex !important; flex-direction: row !important; flex-wrap: nowrap !important; width: calc(100% - 20mm) !important; max-width: none !important; margin: 0 auto !important; box-shadow: none !important; border-radius: 0 !important; overflow: visible !important; }
      .left { width: 35% !important; background: #f7d6e0 !important; color: #000 !important; }
      .right { width: 65% !important; background: #fff !important; color: #000 !important; }
      .left h2, .right h2 { background: #d6417aff !important; color: white !important; }
      .container, .container * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
    }
  </style>
</head>
<body>

  <header role="banner">
    <img src="{{ asset('images/logo.png') }}" alt="Logo" />
    <button id="themeToggle" aria-label="Toggle dark mode" type="button">🌙</button>
  </header>

  <div class="welcome-msg" id="welcomeMsg">
    <h1>Welcome to My Resume</h1>
    <p>Explore my personal background, skills, and experience.</p>
  </div>

  <div class="action-row">
    <button class="download-cv-btn" id="downloadCvBtn">📄 Download CV</button>
    <!-- back-btn is a themed link so theme persists on navigation -->
    <a href="{{ route('welcome') }}" id="backBtn" class="back-btn themed-link">Back →</a>
  </div>

  @php
    $resume = \App\Models\Resume::latest()->first();
    $fields = ['organization','education','skills','programming','projects','interests','awards'];
    foreach($fields as $f){
      if(isset($resume->$f) && is_string($resume->$f)) { $resume->$f = json_decode($resume->$f, true); }
      elseif(!isset($resume->$f)){ $resume->$f = []; }
    }
  @endphp

  <div class="container" id="resumeContainer">
    <div class="left">
      <img src="{{ asset('profile.jpg') }}" alt="Profile Picture">
      @if(!empty($resume->fullname) || !empty($resume->dob) || !empty($resume->pob) || !empty($resume->civil_status) || !empty($resume->specialization))
        <h2>Personal Information</h2>
        @if(!empty($resume->fullname)) <p><strong>Full Name:</strong> {{ $resume->fullname }}</p> @endif
        @if(!empty($resume->dob)) <p><strong>Date of Birth:</strong> {{ $resume->dob }}</p> @endif
        @if(!empty($resume->pob)) <p><strong>Place of Birth:</strong> {{ $resume->pob }}</p> @endif
        @if(!empty($resume->civil_status)) <p><strong>Civil Status:</strong> {{ $resume->civil_status }}</p> @endif
        @if(!empty($resume->specialization)) <p><strong>Field of Specialization:</strong> {{ $resume->specialization }}</p> @endif
      @endif

      @if(!empty($resume->organization['name']))
        <h2>Organization</h2>
        @foreach($resume->organization['name'] as $i => $name)
          @if($name)
            <p>{{ $name }} @if(!empty($resume->organization['position'][$i])) - {{ $resume->organization['position'][$i] }} @endif @if(!empty($resume->organization['year'][$i])) ({{ $resume->organization['year'][$i] }}) @endif</p>
          @endif
        @endforeach
      @endif
    </div>

    <div class="right">
      @if(!empty($resume->fullname)) <h1>{{ strtoupper($resume->fullname) }}</h1> @endif
      <p>
        @if(!empty($resume->address)) {{ $resume->address }}<br>@endif
        @if(!empty($resume->email)) {{ $resume->email }}<br>@endif
        @if(!empty($resume->phone)) {{ $resume->phone }}@endif
      </p>

      @php
        $eduLevels = ['Elementary','Secondary','Tertiary'];
        $education = $resume->education ?? [];
      @endphp

      @if(!empty($education))
        <h2>Educational Background</h2>
        @foreach($eduLevels as $level)
          @php
            $school = $education[$level]['school'] ?? '';
            $year = $education[$level]['year'] ?? '';
          @endphp
          <p><strong>{{ $level }}:</strong> {{ $school ? $school : 'N/A' }}{{ $year && $school ? ' ('.$year.')' : '' }}</p>
        @endforeach
      @endif

      @if(!empty($resume->skills['key']))
        <h2>Skills</h2>
        <ul>
          @foreach($resume->skills['key'] as $i => $skill)
            @php $desc = $resume->skills['value'][$i] ?? ''; @endphp
            @if($skill) <li><strong>{{ $skill }}:</strong> {{ $desc }}</li> @endif
          @endforeach
        </ul>
      @endif

      @if(!empty($resume->programming['key']))
        <h2>Programming Languages</h2>
        <ul>
          @foreach($resume->programming['key'] as $i => $lang)
            @php $desc = $resume->programming['value'][$i] ?? ''; @endphp
            @if($lang) <li><strong>{{ $lang }}:</strong> {{ $desc }}</li> @endif
          @endforeach
        </ul>
      @endif

      @if(!empty($resume->interests))
        <h2>Field of Interest</h2>
        <ul>@foreach($resume->interests as $interest) @if($interest) <li>{{ $interest }}</li> @endif @endforeach</ul>
      @endif

      @if(!empty($resume->projects))
        <h2>Projects</h2>
        <ul>@foreach($resume->projects as $project) @if($project) <li>{{ $project }}</li> @endif @endforeach</ul>
      @endif

      @if(!empty($resume->awards))
        <h2>Awards and Recognitions</h2>
        <ul>@foreach($resume->awards as $award) @if($award) <li>{{ $award }}</li> @endif @endforeach</ul>
      @endif
    </div>
  </div>

  <script>
    (function(){
      // helpers: cookie read/write
      function readCookie(name){
        try {
          var m = document.cookie.match(new RegExp('(?:^|; )' + name + '=([^;]*)'));
          return m ? decodeURIComponent(m[1]) : null;
        } catch(e){ return null; }
      }
      function writeThemeCookie(value){
        try {
          var maxAge = 60 * 60 * 24 * 365;
          document.cookie = 'theme=' + encodeURIComponent(value) + ';path=/;max-age=' + maxAge + ';SameSite=Lax';
        } catch(e){}
      }

      // get current theme: prefer localStorage -> cookie -> html/body class -> default light
      function currentTheme(){
        try {
          var s = null;
          try { s = localStorage.getItem('theme'); } catch(e){}
          if (s) return s;
          var c = readCookie('theme');
          if (c) return c;
          if (document.documentElement.classList.contains('dark') || document.body.classList.contains('dark')) return 'dark';
        } catch(e){}
        return 'light';
      }

      // persist theme to storage + cookie
      function persistTheme(t){
        try { localStorage.setItem('theme', t); } catch(e){}
        writeThemeCookie(t);
      }

      var themeToggle = document.getElementById('themeToggle');
      var root = document.documentElement; // use <html> for theme class
      var welcomeMsg = document.getElementById('welcomeMsg');

      // Sync toggle icon on load & ensure classes reflect stored theme
      (function syncToggleIcon(){
        try {
          var saved = currentTheme();
          if (themeToggle) {
            themeToggle.textContent = saved === 'dark' ? '🌞' : '🌙';
            themeToggle.setAttribute('aria-pressed', saved === 'dark' ? 'true' : 'false');
          }
          if (saved === 'dark') { root.classList.add('dark'); document.body.classList.add('dark'); }
          else { root.classList.remove('dark'); document.body.classList.remove('dark'); }
        } catch(e){}
      })();

      // Toggle behavior
      if (themeToggle) {
        themeToggle.addEventListener('click', function(){
          try {
            var isDark = root.classList.toggle('dark');
            if (isDark) document.body.classList.add('dark'); else document.body.classList.remove('dark');
            var newTheme = isDark ? 'dark' : 'light';
            persistTheme(newTheme);
            themeToggle.textContent = isDark ? '🌞' : '🌙';
            themeToggle.setAttribute('aria-pressed', isDark ? 'true' : 'false');
          } catch(e){}
        });
      }

      // Themed links: append ?theme=... and set cookie before navigation
      // Preserve theme on navigation without adding ?theme= to the URL
      (function setupThemedLinks(){
        var links = document.querySelectorAll('.themed-link');
        if (!links || links.length === 0) return;

        links.forEach(function(a){
          a.addEventListener('click', function(e){
      // Allow open in new tab behavior
          if (e.metaKey || e.ctrlKey || e.button === 1) return;
      // Just store the theme before navigation
          var t = currentTheme() || 'light';
          persistTheme(t);
      // Go directly to href without adding ?theme=
      // Browser will open the link normally
        });
      });
    })();


      // Back button — navigate to welcome with current theme query param (preserves dark/light)
      var backBtn = document.getElementById('backBtn');
      if (backBtn) {
        backBtn.addEventListener('click', function(e){
          if (e.metaKey || e.ctrlKey || e.button === 1) return;
          var theme = currentTheme() || 'light';
          persistTheme(theme);
          // just navigate normally (no ?theme=)
          window.location.href = backBtn.getAttribute('href') || '{{ route("welcome") }}';
        });
      }


      // Print / download: temporarily remove dark class so print colors come out correctly
      var downloadBtn = document.getElementById('downloadCvBtn');
      function handleBeforePrint() {
        if (welcomeMsg) welcomeMsg.style.display = 'none';
        if (root.classList.contains('dark')) { document._wasDark = true; root.classList.remove('dark'); document.body.classList.remove('dark'); }
      }
      function handleAfterPrint() {
        if (welcomeMsg) welcomeMsg.style.display = '';
        if (document._wasDark) { root.classList.add('dark'); document.body.classList.add('dark'); document._wasDark = false; }
      }
      window.addEventListener('beforeprint', handleBeforePrint);
      window.addEventListener('afterprint', handleAfterPrint);

      if (downloadBtn) {
        downloadBtn.addEventListener('click', function(e){
          e.preventDefault();
          handleBeforePrint();
          setTimeout(function(){ window.print(); setTimeout(handleAfterPrint, 500); }, 50);
        });
      }

      // Absorb query param theme if present into storage/cookie (keeps consistency when arriving)
      (function absorbQueryTheme(){
        try {
          var params = new URLSearchParams(location.search);
          var q = params.get('theme');
          if (q === 'dark' || q === 'light') {
            persistTheme(q);
            if (q === 'dark') { root.classList.add('dark'); document.body.classList.add('dark'); }
            else { root.classList.remove('dark'); document.body.classList.remove('dark'); }
            themeToggle && (themeToggle.textContent = q === 'dark' ? '🌞' : '🌙');
          }
        } catch(e){}
      })();

    })();
  </script>
</body>
</html>
