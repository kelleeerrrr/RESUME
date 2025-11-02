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

<title>Forgot Password - Resume App</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<style>
:root{
  --white:#fff;
  --black:#111;
  --pink-bg: rgba(206,133,150,0.9);
  --input-bg: rgba(255,255,255,0.96);
  --transition: 0.22s cubic-bezier(.2,.9,.3,1);
  --green: #008332;
  --error: #d21414;
}

html, body { margin:0; padding:0; height:100%; font-family:'Poppins', Arial, sans-serif; box-sizing:border-box; }
*, *::before, *::after { box-sizing:inherit; }

body {
  background: url('{{ asset("images/bg.jpg") }}') no-repeat center center fixed;
  background-size: cover;
  color: var(--white);
  overflow-y:auto;
}

html.dark body, body.dark {
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
  gap: 20px;
}

.form-container h2 {
  margin:0;
  font-size:1.6rem;
  text-align:center;
  padding-bottom:4px;
  display:flex;
  gap:10px;
  align-items:center;
  justify-content:center;
}

input {
  width:100%;
  padding:10px 12px;
  border-radius:6px;
  border:1px solid rgba(0,0,0,0.06);
  background: var(--input-bg);
  color:#111;
  font-size:0.95rem;
  transition: box-shadow var(--transition), transform var(--transition), border-color var(--transition);
  margin-bottom:16px; /* space between input and button */
}

input:hover { transform: translateY(-1px); box-shadow:0 6px 16px rgba(0,0,0,0.08); }
input:focus-visible { outline:none; box-shadow:0 8px 20px rgba(0,0,0,0.10), 0 0 0 3px rgba(255,255,255,0.04); border-color: rgba(0,0,0,0.12); }

button.login-btn {
  padding:10px 24px;
  border-radius:6px;
  border:none;
  background: rgba(0,0,0,0.75);
  color: var(--white);
  font-weight:600;
  cursor:pointer;
  align-self: center; /* center button */
  transition: transform var(--transition), background var(--transition);
}
button.login-btn:hover { transform: translateY(-2px); background: rgba(0,0,0,0.86); }

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
  margin-top: 12px;
}
.link i { margin-right: 6px; }
.link:hover { color: #ffe6ec; transform: translateY(-2px); }
.link::after {
  content:"";
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

.server-message {
  display:none;
  padding:8px 10px;
  border-radius:8px;
  font-weight:600;
  font-size:0.92rem;
  text-align:center;
}
.server-message.success { display:block; background: rgba(0,131,50,0.08); color: var(--green);}
.server-message.error { display:block; background: rgba(210,20,20,0.08); color: var(--error);}
</style>
</head>
<body>
  {{-- Top bar include --}}
  @include('public_topbar')

  <div class="form-container" role="main" aria-labelledby="forgotHeading">
    <h2 id="forgotHeading"><i class="fas fa-key"></i> Forgot Password</h2>

    {{-- Notification box --}}
    <div id="serverMessage" class="server-message" role="status" aria-live="polite" tabindex="-1"></div>

    <form id="forgotForm" method="POST" action="{{ route('password.email') }}" novalidate>
      @csrf
      <input type="email" name="email" placeholder="Enter your email" required
             oninvalid="this.setCustomValidity('All fields are required!')"
             oninput="this.setCustomValidity('')">
      <button type="submit" class="login-btn">Send Reset Link</button>
    </form>

    <a href="{{ route('login') }}" class="link"><i class="fas fa-arrow-left"></i> Back to Login</a>
  </div>

  <script>
  (function(){
    var serverMsgBox = document.getElementById('serverMessage');

    function showMessage(message,type){
      if(!serverMsgBox) return;
      serverMsgBox.textContent = message;
      serverMsgBox.className = 'server-message ' + type;
      serverMsgBox.focus();
    }

    // Laravel session messages
    @if(session('status'))
      showMessage("{{ session('status') }}", "success");
    @elseif(session('already_sent'))
      showMessage("{{ session('already_sent') }}", "success");
    @elseif(session('error'))
      showMessage("{{ session('error') }}", "error");
    @endif

    // Form validation
    var form = document.getElementById('forgotForm');
    if(form){
      form.addEventListener('submit', function(e){
        var emailInput = form.querySelector('input[name="email"]');
        if(!emailInput.value.trim()){
          emailInput.setCustomValidity('All fields are required!');
          form.reportValidity();
          e.preventDefault();
        } else {
          emailInput.setCustomValidity('');
        }
      });
    }
  })();
  </script>
</body>
</html>
