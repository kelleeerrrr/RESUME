<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Welcome | Irish Rivera</title>

  {{-- Theme initialization: from cookie, localStorage or prefers-color-scheme --}}
  <script>
    (function(){
      try {
        function readCookie(name){
          var m = document.cookie.match(new RegExp('(?:^|; )' + name + '=([^;]*)'));
          return m ? decodeURIComponent(m[1]) : null;
        }

        var cookieTheme = readCookie('theme');
        var lsTheme = localStorage.getItem('theme');
        var prefers = (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) ? 'dark' : 'light';
        var theme = cookieTheme || lsTheme || prefers;

        if (theme === 'dark') document.documentElement.classList.add('dark');
        else document.documentElement.classList.remove('dark');
      } catch(e){}
    })();
  </script>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <style>
    :root{
      --accent:#d6417a;--pink:rgba(255,31,132,0.95);
      --container-pink:rgba(206,133,150,0.95);
      --resume-btn:#13d363ff;--radius:14px;--white:#fff;
    }
    *{box-sizing:border-box;margin:0;padding:0}
    body{
      font-family:"Poppins",sans-serif;
      background:url('{{ asset("images/resumebg.jpg") }}') no-repeat center center fixed;
      background-size:cover;min-height:100vh;color:#222;
      display:flex;flex-direction:column;justify-content:flex-start;
    }
    html.dark body{
      background:linear-gradient(to bottom right,#0f1720,#1b2430),
        url('{{ asset("images/resumebg.jpg") }}') no-repeat center center fixed;
      background-blend-mode:multiply;color:#eaeef6;
    }
    header{
      width:100%;background:var(--pink);color:#fff;
      display:flex;align-items:center;justify-content:space-between;
      padding:12px 24px;position:fixed;top:0;left:0;z-index:50;
      box-shadow:0 3px 10px rgba(0,0,0,0.12);
    }
    header img{height:44px;display:block;}
    #themeToggle{background:transparent;border:0;color:#fff;
      font-size:1.2rem;cursor:pointer;padding:6px;
      transition:transform .15s ease;z-index:100;}
    #themeToggle:hover{transform:scale(1.15);}
    main.hero{width:100%;padding:110px 20px 60px;display:flex;justify-content:center;align-items:center;}
    .container{
      width:96%;max-width:1200px;background:var(--container-pink);
      color:var(--white);border-radius:var(--radius);padding:36px 40px;
      box-shadow:0 10px 40px rgba(0,0,0,0.25);backdrop-filter:blur(6px);
      transition:background .25s ease;overflow:visible;
    }
    html.dark .container{background:rgba(36,18,28,0.92);}
    .hero-main{display:flex;align-items:center;gap:36px;justify-content:space-between;flex-wrap:wrap;}
    .left-col{flex:1 1 560px;min-width:280px;max-width:760px;}
    .left-col h1{font-size:2.6rem;line-height:1.02;margin-bottom:10px;color:var(--white);font-weight:700;}
    .left-col p{font-size:1.05rem;line-height:1.6;margin:0 0 .25rem;color:var(--white);}
    .highlight{color:rgba(170,3,72,0.95);font-weight:700;}
    html.dark .highlight{color:var(--pink);}
    .cta{margin-top:18px;display:flex;gap:12px;flex-wrap:wrap}
    .btn{display:inline-block;padding:12px 20px;border-radius:10px;
      text-decoration:none;font-weight:600;color:#fff;
      transition:transform .12s,box-shadow .12s,opacity .12s;
      box-shadow:0 6px 18px rgba(0,0,0,0.18);}
    .btn:hover{transform:translateY(-5px);opacity:.98;box-shadow:0 12px 26px rgba(0,0,0,0.26);}
    .btn-resume{background:var(--resume-btn);color:#033;font-weight:800}
    .btn-login{background:rgba(255,31,132,0.95);}
    .btn-signup{background:#ff7f7f;}
    .language-section{margin-top:18px;display:flex;gap:10px;flex-wrap:wrap;align-items:center}
    .language-badge{background:rgba(255,255,255,0.18);color:var(--white);padding:8px 14px;border-radius:999px;font-weight:700;font-size:.95rem;cursor:default;}
    .contact{margin-top:18px;font-size:.98rem;color:rgba(255,255,255,0.95);}
    .contact a{color:#fff;text-decoration:underline;font-weight:700;margin-left:6px;}
    html.dark .contact a{color:var(--pink);}
    .right-col{flex:0 0 320px;display:flex;align-items:center;justify-content:flex-end;min-width:220px;}
    .right-col img{width:280px;height:280px;border-radius:50%;object-fit:cover;
      box-shadow:0 18px 40px rgba(0,0,0,0.32);transition:.25s;}
    .right-col img:hover{transform:translateY(-6px) scale(1.03);box-shadow:0 26px 60px rgba(0,0,0,0.42);}
    .image-quote{margin-top:14px;text-align:center;color:rgba(255,255,255,0.95);font-style:italic;font-weight:600;}
    @media(max-width:720px){.hero-main{flex-direction:column-reverse;align-items:center}.left-col{text-align:center}}
  </style>
</head>
<body>
  <header>
    <img src="{{ asset('images/logo.png') }}" alt="Logo" />
    <button id="themeToggle" aria-label="Toggle dark mode" type="button">🌙</button>
  </header>

  <main class="hero">
    <div class="container">
      <div class="hero-main">
        <div class="left-col">
          <h1>Hi, I’m Irish Rivera 👋</h1>
          <p>
            Bachelor of Science in Computer Science<br>
            <strong>Batangas State University TNEU – Alangilan Campus</strong><br>
            Aspiring <span class="highlight">Cloud Engineer</span> &amp;
            <span class="highlight">Cybersecurity Enthusiast</span>.
          </p>

          <div class="cta">
            <a href="{{ route('resume.public') }}" class="btn btn-resume">View My Resume</a>
          </div>

          <div class="language-section">
            <span class="language-badge">HTML</span>
            <span class="language-badge">CSS</span>
            <span class="language-badge">JavaScript</span>
            <span class="language-badge">PHP</span>
            <span class="language-badge">Laravel</span>
          </div>

          <div class="contact">
            You can contact me here:
            <a href="mailto:23-00679@g.batstate-u.edu.ph">23-00679@g.batstate-u.edu.ph</a>
          </div>

          <div style="margin-top:20px;display:flex;gap:12px;flex-wrap:wrap;">
            <a href="{{ url('/login') }}" class="btn btn-login">Login</a>
            <a href="{{ url('/signup') }}" class="btn btn-signup">Sign Up</a>
          </div>
        </div>

        <div class="right-col">
          <div>
            <img src="{{ asset('keller.jpg') }}" alt="Profile picture of Irish Rivera">
            <div class="image-quote">“Designing with logic, coding with purpose.”</div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <script>
  (function(){
    function readCookie(name){
      var m=document.cookie.match(new RegExp('(?:^|; )'+name+'=([^;]*)'));
      return m?decodeURIComponent(m[1]):null;
    }
    function writeThemeCookie(value){
      var maxAge=60*60*24*365;
      document.cookie='theme='+encodeURIComponent(value)+';path=/;max-age='+maxAge+';SameSite=Lax';
    }

    var toggle=document.getElementById('themeToggle');
    var root=document.documentElement;

    function syncToggleIcon(){
      var saved=localStorage.getItem('theme')||readCookie('theme');
      if(!saved)saved=root.classList.contains('dark')?'dark':'light';
      toggle.textContent=saved==='dark'?'🌞':'🌙';
    }
    syncToggleIcon();

    toggle.addEventListener('click',function(){
      var isDark=root.classList.toggle('dark');
      document.body.classList.toggle('dark',isDark);
      localStorage.setItem('theme',isDark?'dark':'light');
      writeThemeCookie(isDark?'dark':'light');
      toggle.textContent=isDark?'🌞':'🌙';
    });
  })();
  </script>
</body>
</html>
