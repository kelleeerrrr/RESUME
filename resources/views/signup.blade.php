<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Sign Up - Community Resumes</title>

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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    :root { --pink-1: #ffe6eb; --pink-2: #ffd6f6; --accent-pink: #d63384; --container-pink: #ac4968ff; --radius: 14px; --line-grad: linear-gradient(90deg, rgba(255,115,165,1), rgba(255,200,220,1)); --transition: 0.22s ease; --error-color: #d21414; }

    *{box-sizing:border-box;margin:0;padding:0}
    html,body{ height:100%; }
    body{ font-family:"Poppins",sans-serif; background: linear-gradient(135deg, var(--pink-1), var(--pink-2)); color:#222; display:flex; flex-direction:column; min-height:100vh; }
    html.dark body { background: linear-gradient(135deg, #1b0f12, #2a1420); color:#eee; }

    header{ width:100%; max-width:1200px; margin-top:28px; padding:18px 22px; display:flex; justify-content:space-between; align-items:center; gap:12px; }
    header .left p{ color:var(--accent-pink); font-size:2.5rem; margin:0; font-weight:700; }

    .auth{ display:flex; gap:10px; align-items:center; }
    .auth a{ padding:10px 14px; border-radius:999px; font-weight:600; text-decoration:none; cursor:pointer; transition: transform var(--transition), box-shadow 0.2s, opacity 0.2s; color:#fff; }
    .auth a.welcome{ background: linear-gradient(90deg,#ff5a9b,#ff3d7a); }
    .auth a.login{ background: linear-gradient(90deg,#ff97b6,#ff6ea0); }
    .auth a:hover{ transform:translateY(-2px); box-shadow: 0 6px 18px rgba(0,0,0,0.15); opacity:0.9; }

    .theme-toggle{ display:inline-flex; align-items:center; gap:8px; padding:8px 10px; border-radius:999px; background: rgba(255,255,255,0.9); color: #6b2a4a; font-weight:600; cursor:pointer; border:none; box-shadow:0 6px 18px rgba(0,0,0,0.06); transition: transform 0.2s, box-shadow 0.2s; }
    .theme-toggle:hover{ transform: translateY(-2px); box-shadow: 0 6px 18px rgba(0,0,0,0.15); }
    html.dark .theme-toggle{ background: rgba(255,255,255,0.06); color: #f6e6ef; box-shadow: none; border:1px solid rgba(255,255,255,0.04); }

    main.hero{ margin-top:-25px; width:100%; padding:34px 20px 50px; display:flex; justify-content:center; align-items:flex-start; }
    .card{ width:96%; max-width:1200px; background:var(--container-pink); color:#fff; border-radius:var(--radius); padding:0 28px 28px; box-shadow:0 10px 40px rgba(0,0,0,0.15); display:flex; gap:28px; align-items:flex-start; overflow:visible; position:relative; }
    html.dark .card{ background: rgba(54, 18, 36, 0.92); }

    .card-topline{ position:absolute; top:0; left:0; right:0; height:8px; border-top-left-radius:var(--radius); border-top-right-radius:var(--radius); background: var(--line-grad); z-index:6; }
    html.dark .card-topline{ background: linear-gradient(90deg, rgba(200,80,140,0.9), rgba(130,60,120,0.85)); }

    .left-col{ margin: 10px; flex:1 1 640px; min-width:300px; max-width:760px; color: #fff; padding-top:20px; }
    .left-col h1{ font-size:2rem; margin-bottom:6px; color:#fff; line-height:1.02; }
    .left-col p{ margin-bottom:14px; color: rgba(255,255,255,0.96); font-size:1.05rem; }

    .inner{ background: #e03e7fff; color:#fff; border-radius:12px; padding:28px 20px; box-shadow: 0 8px 30px rgba(0,0,0,0.08); margin-bottom:18px; max-width:100%; display:flex; flex-direction:column; align-items:center; }
    html.dark .inner{ background: rgba(116, 54, 76, 0.95); color:#fff; box-shadow:none; border:1px solid rgba(192, 192, 192, 0.03); }

    form{ display:flex; flex-direction:column; width:100%; gap:16px; }
    input{ width:100%; padding:12px 44px 12px 12px; border-radius:8px; border:none; font-size:0.95rem; background:#fff; color:#333; transition: border 0.2s, outline 0.2s; }
    input:focus{ outline: 2px solid rgba(255,255,255,0.7); }
    html.dark input{ background: rgba(240, 240, 240, 0.95); color:#333; }

    .toggle-pass{ position:absolute; right:8px; top:50%; transform:translateY(-50%); background:transparent; border:none; cursor:pointer; font-size:1rem; color:#333; }
    html.dark .toggle-pass{ color:#333; }

    .signup-btn{ padding:12px; border-radius:8px; border:none; background: linear-gradient(90deg,#ff97b6,#ff6ea0); color:#fff; font-weight:600; cursor:pointer; transition: transform var(--transition), box-shadow 0.2s, opacity 0.2s; width:100%; text-align:center; }
    .signup-btn:hover{ transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,0.15); opacity:0.95; }

    .server-error{ display:none; padding:8px 10px; border-radius:8px; background: rgba(210,20,20,0.15); color: var(--error-color); font-weight:600; text-align:center; margin-bottom:10px; transition: opacity 0.2s; }
    .server-error.active{ display:block; }

    .right-col{ width:320px; display:flex; flex-direction:column; gap:12px; align-items:center; padding-top:18px; }
    .placeholder-card{ width:100%; max-width:320px; border-radius:12px; padding:14px; background: linear-gradient(180deg, rgba(255,255,255,0.06), rgba(255,255,255,0.02)); box-shadow:0 10px 30px rgba(0,0,0,0.12); display:flex; flex-direction:column; gap:12px; align-items:center; color:#fff; }
    html.dark .placeholder-card{ background: linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01)); box-shadow:none; border:1px solid rgba(255,255,255,0.03); }
    .placeholder-photo{ width:100%; height:270px; border-radius:10px; object-fit:cover; }
    .placeholder-title{ font-weight:700; color:#fff; text-align:center; font-size:0.95rem; }
  </style>
</head>
<body>

  <header>
    <div class="left">
      <p>Create Your Account!</p>
    </div>
    <div class="auth">
      <button id="themeToggle" class="theme-toggle" aria-pressed="false" title="Toggle dark mode">
        <span id="themeIcon">🌙</span>
        <span id="themeLabel">Dark</span>
      </button>
      <a href="{{ route('welcome') }}" class="welcome">Welcome</a>
      <a href="{{ route('login') }}" class="login">Login</a>
    </div>
  </header>

  <main class="hero">
    <div class="card">
      <div class="card-topline"></div>
      <div class="left-col">
        <h1>Join Our Community!</h1>
        <p>Sign up to get started and showcase your talents.</p>

        <div class="inner">
          <div id="serverError" class="server-error @if($errors->any()) active @endif">
            @if ($errors->any()) {{ $errors->first() }} @endif
          </div>

          <form id="signupForm" method="POST" action="{{ route('register.post') }}">
            @csrf

            <input id="name" name="name" type="text" placeholder="Full Name" required autocomplete="name">
            <input id="username" name="username" type="text" placeholder="Username" required autocomplete="username">
            <input id="email" name="email" type="email" placeholder="Email" required autocomplete="email">

            <div class="input-row" style="position:relative;">
              <input id="password" name="password" type="password" placeholder="Password" required autocomplete="new-password">
              <button type="button" class="toggle-pass" id="togglePassword"><i class="fa-solid fa-eye"></i></button>
            </div>

            <div class="input-row" style="position:relative;">
              <input id="password_confirmation" name="password_confirmation" type="password" placeholder="Confirm Password" required autocomplete="new-password">
              <button type="button" class="toggle-pass" id="togglePasswordConfirm"><i class="fa-solid fa-eye"></i></button>
            </div>

            <button type="submit" class="signup-btn">Create Account</button>
          </form>
        </div>
      </div>

      <div class="right-col">
        <div class="placeholder-card">
          <img src='{{ asset("images/community-photo.jpg") }}' alt="Community spotlight" class="placeholder-photo" onerror="this.onerror=null;this.src='{{ asset('default-spotlight.jpg') }}'">
          <div class="placeholder-title">Community Spotlight</div>
          <div style="font-size:0.9rem; color:rgba(255,255,255,0.95); text-align:center;">
            A curated view of member highlights — log in to learn more.
          </div>
        </div>
      </div>
    </div>
  </main>

  <script>
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
      const isDark = document.documentElement.classList.toggle('dark');
      localStorage.setItem('theme', isDark ? 'dark' : 'light');
      updateThemeUI();
    });
    updateThemeUI();

    function setupToggle(passId, toggleId){
      const toggle = document.getElementById(toggleId);
      const pass = document.getElementById(passId);
      toggle?.addEventListener('click', () => {
        const type = pass.type === 'password' ? 'text' : 'password';
        pass.type = type;
        toggle.querySelector('i').classList.toggle('fa-eye');
        toggle.querySelector('i').classList.toggle('fa-eye-slash');
      });
    }
    setupToggle('password', 'togglePassword');
    setupToggle('password_confirmation', 'togglePasswordConfirm');

    const fields = ['name','username','email','password','password_confirmation'].map(id=>document.getElementById(id));
    const serverError = document.getElementById('serverError');

    fields.forEach(input=>{
      input.addEventListener('invalid', (e)=>{
        if(input.id==='email' && input.validity.typeMismatch){
          input.setCustomValidity('Email must include @');
        } else {
          input.setCustomValidity('All fields are required!');
        }
      });
      input.addEventListener('input', ()=>{ input.setCustomValidity(''); if(serverError.classList.contains('active')){ serverError.classList.remove('active'); serverError.textContent=''; } });
    });

    const form = document.getElementById('signupForm');
    form?.addEventListener('submit', (e)=>{
      const pass = document.getElementById('password').value;
      const confirm = document.getElementById('password_confirmation').value;
      if(pass !== confirm){
        e.preventDefault();
        serverError.textContent = 'Passwords do not match.';
        serverError.classList.add('active');
      }
    });
  </script>
</body>
</html>
