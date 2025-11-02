<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

  {{-- Pre-paint theme init --}}
  <script>
    (function(){
      try {
        var params = (function(){ try { return new URLSearchParams(location.search); } catch(e){ return null; } })();
        var qTheme = params ? params.get('theme') : null;

        function readCookie(name){
          try { var m = document.cookie.match(new RegExp('(?:^|; )' + name + '=([^;]*)')); return m ? decodeURIComponent(m[1]) : null; } catch(e){ return null; }
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

  <title>Login - Resume App</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

  <style>
    :root{
      --white: #ffffff;
      --black: #111111;
      --black-hover: #333333;
      --pink-bg: rgba(206,133,150,0.9);
      --input-bg: rgba(255,255,255,0.96);
      --transition: 0.22s cubic-bezier(.2,.9,.3,1);
      --error: #d21414;
    }

    html, body { margin:0; padding:0; height:100%; box-sizing:border-box; font-family:'Poppins', Arial, sans-serif; }
    *, *::before, *::after { box-sizing: inherit; }

    body {
      background: url('{{ asset("images/bg.jpg") }}') no-repeat center center fixed;
      background-size: cover;
      color: var(--white);
      transition: background .32s, color .32s;
      overflow-y: auto;
    }

    html.dark body,
    body.dark {
      background: linear-gradient(to bottom right, #1a1a1a, #2a2a2a),
                  url('{{ asset("images/resumebg.jpg") }}') no-repeat center center fixed;
      background-blend-mode: multiply;
      color: var(--white);
    }

    .form-container {
      background: var(--pink-bg);
      backdrop-filter: blur(6px);
      padding: 26px;
      border-radius: 12px;
      box-shadow: 0 0 18px rgba(0,0,0,0.45);
      width: 86%;
      max-width: 450px;
      margin: 110px auto;
      display: flex;
      flex-direction: column;
      gap: 10px;
      color: var(--white);
    }

    .form-container h2 { margin:0; font-size:1.6rem; text-align:center; padding-bottom:4px; display:flex; gap:10px; align-items:center; justify-content:center; }
    form { display:flex; flex-direction:column; gap:8px; width:100%; }

    input {
      width:100%;
      padding:10px 44px 10px 12px;
      border-radius:6px;
      border:1px solid rgba(0,0,0,0.06);
      background: var(--input-bg);
      color:#111;
      font-size:0.95rem;
      box-sizing:border-box;
      transition: box-shadow var(--transition), transform var(--transition), border-color var(--transition);
    }

    input:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(0,0,0,0.08); }
    input:focus-visible {
      outline: none;
      box-shadow: 0 8px 20px rgba(0,0,0,0.10), 0 0 0 3px rgba(255,255,255,0.04);
      border-color: rgba(0,0,0,0.12);
    }

    .input-row { position: relative; width:100%; }

    .toggle-pass {
      position:absolute;
      right:8px;
      top:50%;
      transform: translateY(-50%);
      background: transparent;
      border: none;
      padding:6px;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      border-radius:6px;
      cursor:pointer;
      color: rgba(17,17,17,0.85);
      transition: background var(--transition), transform var(--transition);
      font-size:1rem;
      z-index: 3;
    }
    .toggle-pass:hover { background: rgba(0,0,0,0.04); transform: translateY(-50%) scale(1.03); }
    .toggle-pass:focus-visible { outline:none; box-shadow: 0 6px 18px rgba(0,0,0,0.12); }

    .login-btn {
      padding:10px 12px;
      border-radius:6px;
      border:none;
      background: rgba(0,0,0,0.75);
      color: var(--white);
      font-weight:600;
      cursor:pointer;
      transition: transform var(--transition), background var(--transition);
    }
    .login-btn:hover { transform: translateY(-2px); background: rgba(0,0,0,0.86); }

    .link {
      text-decoration:none;
      display:block;
      text-align:center;
      font-size:0.92rem;
      padding:6px 4px;
      position:relative;
      transition: color .18s, transform .18s;
      line-height:1.1;
      color: var(--white);
    }
    .link::after {
      content: "";
      position:absolute;
      left:50%;
      transform: translateX(-50%);
      bottom:3px;
      width:0;
      height:2px;
      background: currentColor;
      border-radius:2px;
      transition: width .18s ease;
    }
    .link:hover::after, .link:focus-visible::after { width:55%; }

    /* Specific link colors */
    .forgot-pass { color: var(--white); }
    .forgot-pass:hover { color: #ffe6ec; transform: translateY(-2px); }

    .signup-link { color: var(--white); }
    .signup-link:hover { color: #ffe6ec; transform: translateY(-2px); }

    .back-welcome { color: var(--black); font-weight:500; }
    .back-welcome:hover { color: var(--black); transform: none; }

    .server-error {
      display:none;
      padding:8px 10px;
      border-radius:8px;
      background: rgba(210,20,20,0.08);
      color: var(--error);
      font-weight:600;
      font-size:0.92rem;
      text-align:center;
    }
    .server-error.active { display:block; }

    @media (max-width:880px) {
      .form-container { width: calc(100% - 32px); margin: 28px 16px; padding: 18px; }
    }

    @media (prefers-reduced-motion: reduce) {
      * { transition: none !important; animation: none !important; transform: none !important; }
    }
  </style>
</head>
<body>
  {{-- Top bar include --}}
  @include('public_topbar')

  <div class="form-container" role="main" aria-labelledby="loginHeading">
    <h2 id="loginHeading"><i class="fas fa-sign-in-alt" aria-hidden="true"></i> Login</h2>

    <div id="serverError" class="server-error" role="status" aria-live="polite" tabindex="-1">
      @if ($errors->any())
        {{ $errors->first() }}
      @endif
    </div>

    <form id="loginForm" method="POST" action="{{ route('login.post') }}" novalidate>
      @csrf

      <input id="login" name="login" type="text" placeholder="Username or Email" autocomplete="username" required aria-required="true" aria-label="Username or Email">

      <div class="input-row">
        <input id="password" name="password" type="password" placeholder="Password" autocomplete="current-password" required aria-required="true" aria-label="Password">
        <button type="button" class="toggle-pass" id="togglePassword" aria-label="Show password" aria-pressed="false" tabindex="0">
          <i class="fa-solid fa-eye" aria-hidden="true"></i>
        </button>
      </div>

      <button type="submit" class="login-btn" aria-label="Login">Login</button>
    </form>

    <a href="{{ url('/forgot-password') }}" class="link forgot-pass" aria-label="Forgot your password?">
      <i class="fas fa-key" aria-hidden="true"></i> Forgot your password?
    </a>
    <a href="{{ route('register') }}" class="link signup-link" aria-label="Don't have an account? Sign up">
      <i class="fas fa-user-plus" aria-hidden="true"></i> Don't have an account? Sign Up
    </a>
    <a href="{{ route('welcome') }}" class="link back-welcome" aria-label="Back to Welcome">
      <i class="fas fa-home" aria-hidden="true"></i> Back to Welcome
    </a>
  </div>

  <script>
  (function(){
    // Password toggle
    (function passwordToggle(){
      var toggle = document.getElementById('togglePassword');
      var pass = document.getElementById('password');
      if(!toggle || !pass) return;

      toggle.addEventListener('click', function(){
        var show = pass.type === 'password';
        pass.type = show ? 'text' : 'password';
        this.setAttribute('aria-pressed', String(show));
        this.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
        var icon = this.querySelector('i');
        if(icon){
          icon.classList.toggle('fa-eye');
          icon.classList.toggle('fa-eye-slash');
          icon.classList.add('fa-solid');
        }
      });

      toggle.addEventListener('keydown', function(e){
        if(e.key === 'Enter' || e.key === ' ') { e.preventDefault(); this.click(); }
      });
    })();

    // Form validation
    (function formValidation(){
      var form = document.getElementById('loginForm');
      var loginInput = document.getElementById('login');
      var passwordInput = document.getElementById('password');
      var serverErrorBox = document.getElementById('serverError');

      [loginInput, passwordInput].forEach(function(f){
        if(!f) return;
        f.addEventListener('input', function(){
          if(serverErrorBox && serverErrorBox.classList.contains('active')){
            serverErrorBox.classList.remove('active');
            serverErrorBox.textContent = '';
          }
          f.setCustomValidity('');
        });
      });

      function setCustomEmptyMessage(){
        if(!loginInput.value.trim()) loginInput.setCustomValidity('All fields are required!');
        else if(!passwordInput.value.trim()) passwordInput.setCustomValidity('All fields are required!');
      }

      if(form){
        form.addEventListener('submit', function(e){
          setCustomEmptyMessage();
          if(!form.checkValidity()){
            e.preventDefault();
            form.reportValidity();
            if(!loginInput.value.trim()) loginInput.focus();
            else if(!passwordInput.value.trim()) passwordInput.focus();
            return false;
          }
          return true;
        });
      }

      // server errors from Laravel
      try{
        var serverMsg = @json($errors->first() ?? null);
        if(serverMsg && serverErrorBox){
          serverErrorBox.textContent = serverMsg;
          serverErrorBox.classList.add('active');
        }
      } catch(e){}
    })();

    // Themed links
    (function setupThemedLinks(){
      var themed = document.querySelectorAll('.themed-link');
      if(!themed || themed.length === 0) return;

      function currentTheme(){
        try { return localStorage.getItem('theme'); } catch(e){}
        return document.documentElement.classList.contains('dark') || document.body.classList.contains('dark') ? 'dark' : 'light';
      }

      function writeThemeCookie(value){
        try { document.cookie = 'theme=' + encodeURIComponent(value) + ';path=/;max-age=' + 60*60*24*365 + ';SameSite=Lax'; } catch(e){}
      }

      themed.forEach(function(a){
        a.addEventListener('click', function(e){
          if(e.metaKey || e.ctrlKey || e.button === 1) return;
          e.preventDefault();
          var theme = currentTheme() || 'light';
          writeThemeCookie(theme);
          try {
            var href = a.getAttribute('href') || window.location.href;
            var sep = href.indexOf('?') === -1 ? '?' : '&';
            window.location.href = href + sep + 'theme=' + encodeURIComponent(theme);
          } catch(err){ window.location.href = a.getAttribute('href') || '/'; }
        });
      });
    })();
  })();
  </script>
</body>
</html>
