<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

  <title>Sign Up - Resume App</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

  <style>
    :root{
      --white: #ffffff;
      --black: #111111;
      --pink-bg: rgba(206,133,150,0.9);
      --input-bg: rgba(255,255,255,0.96);
      --transition: 0.22s cubic-bezier(.2,.9,.3,1);
      --error: #d21414;
    }

    html, body { margin:0px; padding:0; height:100%; box-sizing:border-box; font-family:'Poppins', Arial, sans-serif; }
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
      max-width: 500px;
      margin: 85px auto;
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

    .signup-btn {
      padding:10px 12px;
      border-radius:6px;
      border:none;
      background: rgba(0,0,0,0.75);
      color: var(--white);
      font-weight:600;
      cursor:pointer;
      transition: transform var(--transition), background var(--transition);
    }
    .signup-btn:hover { transform: translateY(-2px); background: rgba(0,0,0,0.86); }

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

    .login-link { color: var(--white); }
    .login-link:hover { color: #ffe6ec; transform: translateY(-2px); }

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

  <div class="form-container" role="main" aria-labelledby="signupHeading">
    <h2 id="signupHeading"><i class="fas fa-user-plus" aria-hidden="true"></i> Sign Up</h2>

    <div id="serverError" class="server-error" role="status" aria-live="polite" tabindex="-1">
      @if ($errors->any())
        {{ $errors->first() }}
      @endif
    </div>

    <form id="signupForm" method="POST" action="{{ route('register.post') }}" novalidate>
      @csrf

      <input id="name" name="name" type="text" placeholder="Full Name" autocomplete="name" required aria-required="true" aria-label="Full name">
      <input id="username" name="username" type="text" placeholder="Username" autocomplete="username" required aria-required="true" aria-label="Username">
      <input id="email" name="email" type="email" placeholder="Email" autocomplete="email" required aria-required="true" aria-label="Email">

      <div class="input-row" style="margin-top:0;">
        <input id="password" name="password" type="password" placeholder="Password" autocomplete="new-password" required aria-required="true" aria-label="Password">
        <button type="button" class="toggle-pass" id="togglePassword" aria-label="Show password" aria-pressed="false" tabindex="0">
          <i class="fa-solid fa-eye" aria-hidden="true"></i>
        </button>
      </div>

      <div class="input-row" style="margin-top:0;">
        <input id="password_confirmation" name="password_confirmation" type="password" placeholder="Confirm Password" autocomplete="new-password" required aria-required="true" aria-label="Confirm password">
        <button type="button" class="toggle-pass" id="togglePasswordConfirm" aria-label="Show confirm password" aria-pressed="false" tabindex="0">
          <i class="fa-solid fa-eye" aria-hidden="true"></i>
        </button>
      </div>

      <button type="submit" class="signup-btn" aria-label="Create account">Create Account</button>
    </form>

    <a href="{{ route('login') }}" class="link login-link" aria-label="Already have an account? Login"><i class="fas fa-sign-in-alt" aria-hidden="true"></i> Already have an account? Login</a>
    <a href="{{ route('welcome') }}" class="link back-welcome" aria-label="Back to Welcome"><i class="fas fa-home" aria-hidden="true"></i> Back to Welcome</a>
  </div>

  <script>
  (function(){
    // ---------- Sync visual state of themeToggle ----------
    (function syncThemeToggleVisual() {
      var btns = document.querySelectorAll('#themeToggle');
      if (!btns || btns.length === 0) return;
      var isDark = (function(){
        try { return localStorage.getItem('theme') === 'dark'; }
        catch(e) { return document.documentElement.classList.contains('dark') || document.body.classList.contains('dark'); }
      })();
      btns.forEach(function(b){
        b.textContent = isDark ? '🌞' : '🌙';
        b.setAttribute('aria-pressed', isDark ? 'true' : 'false');
        b.setAttribute('type','button');
      });
    })();

    // Password toggle
    (function passwordToggle(){
      var toggle = document.getElementById('togglePassword');
      var pass = document.getElementById('password');
      if (!toggle || !pass) return;

      toggle.addEventListener('click', function(){
        var show = pass.type === 'password';
        pass.type = show ? 'text' : 'password';
        this.setAttribute('aria-pressed', String(show));
        this.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
        var icon = this.querySelector('i');
        if (icon) {
          icon.classList.toggle('fa-eye');
          icon.classList.toggle('fa-eye-slash');
          icon.classList.add('fa-solid');
        }
      });

      toggle.addEventListener('keydown', function(e){
        if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); this.click(); }
      });
    })();

    // Password toggle confirm
    (function passwordToggleConfirm(){
      var toggle = document.getElementById('togglePasswordConfirm');
      var pass = document.getElementById('password_confirmation');
      if (!toggle || !pass) return;

      toggle.addEventListener('click', function(){
        var show = pass.type === 'password';
        pass.type = show ? 'text' : 'password';
        this.setAttribute('aria-pressed', String(show));
        this.setAttribute('aria-label', show ? 'Hide confirm password' : 'Show confirm password');
        var icon = this.querySelector('i');
        if (icon) {
          icon.classList.toggle('fa-eye');
          icon.classList.toggle('fa-eye-slash');
          icon.classList.add('fa-solid');
        }
      });

      toggle.addEventListener('keydown', function(e){
        if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); this.click(); }
      });
    })();

    // Form validation & UX helpers
    (function formValidation(){
      var form = document.getElementById('signupForm');
      var fields = [
        document.getElementById('name'),
        document.getElementById('username'),
        document.getElementById('email'),
        document.getElementById('password'),
        document.getElementById('password_confirmation')
      ];
      var serverErrorBox = document.getElementById('serverError');

      fields.forEach(function(f){
        if (!f) return;
        f.addEventListener('input', function(){
          if (serverErrorBox && serverErrorBox.classList.contains('active')) {
            serverErrorBox.classList.remove('active');
            serverErrorBox.textContent = '';
          }
          f.setCustomValidity('');
        });
      });

      function setCustomEmptyMessageFirstOnly() {
        var firstEmpty = fields.find(function(f){ return f && !f.value.trim(); });
        fields.forEach(function(f){ if(f) f.setCustomValidity(''); });
        if (firstEmpty) firstEmpty.setCustomValidity('All fields are required!');
      }

      if (form) {
        form.addEventListener('submit', function(e){
          setCustomEmptyMessageFirstOnly();

          if (!form.checkValidity()) {
            e.preventDefault();
            form.reportValidity();
            var firstEmpty = fields.find(function(f){ return f && !f.value.trim(); });
            if (firstEmpty) firstEmpty.focus();
            return false;
          }

          var p = document.getElementById('password').value;
          var pc = document.getElementById('password_confirmation').value;
          if (p !== pc) {
            e.preventDefault();
            if (serverErrorBox) {
              serverErrorBox.textContent = 'Passwords do not match.';
              serverErrorBox.classList.add('active');
              serverErrorBox.focus && serverErrorBox.focus();
            } else {
              alert('Passwords do not match.');
            }
            return false;
          }

          return true;
        });
      }

      try {
        var serverMsg = @json($errors->first() ?? null);
        if (serverMsg && serverErrorBox) {
          serverErrorBox.textContent = serverMsg;
          serverErrorBox.classList.add('active');
        }
      } catch(e){}
    })();

  })();
  </script>
</body>
</html>
