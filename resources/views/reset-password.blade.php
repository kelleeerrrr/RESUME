<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Reset Password - Resume App</title>
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
              url('{{ asset("images/bg.jpg") }}') no-repeat center center fixed;
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
  max-width: 400px;
  margin: 80px auto;
  display: flex;
  flex-direction: column;
  gap: 16px;
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
  margin-bottom:14px;
}

input:hover { transform: translateY(-1px); box-shadow:0 6px 16px rgba(0,0,0,0.08); }
input:focus-visible { outline:none; box-shadow:0 8px 20px rgba(0,0,0,0.10), 0 0 0 3px rgba(255,255,255,0.04); border-color: rgba(0,0,0,0.12); }

.password-wrapper {
  position: relative;
}

.password-wrapper i {
  position: absolute;
  right: 10px;
  top: 50%;
  transform: translateY(-50%);
  cursor: pointer;
  color: rgba(0,0,0,0.6);
}

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
  margin-top: 10px;
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
<div class="form-container" role="main" aria-labelledby="resetHeading">
  <h2 id="resetHeading"><i class="fas fa-key"></i> Reset Password</h2>

  {{-- Notifications --}}
  <div id="serverMessage" class="server-message {{ session('success') ? 'success' : 'error' }}" role="status" aria-live="polite" tabindex="-1">
      @if(session('success'))
          {{session('success') }}
      @elseif(session('error'))
          {{ session('error') }}
      @elseif($errors->any())
          {{ $errors->first() }}
      @endif
  </div>

  <form method="POST" action="{{ route('password.update') }}" novalidate>
      @csrf
      <input type="hidden" name="token" value="{{ $token }}">
      <input type="email" name="email" placeholder="Email" required
             oninvalid="this.setCustomValidity('All fields are required!')"
             oninput="this.setCustomValidity('')">
      
      <div class="password-wrapper">
          <input type="password" name="password" placeholder="New Password" required
                 oninvalid="this.setCustomValidity('All fields are required!')"
                 oninput="this.setCustomValidity('')">
          <i class="fas fa-eye toggle-password"></i>
      </div>

      <div class="password-wrapper">
          <input type="password" name="password_confirmation" placeholder="Confirm New Password" required
                 oninvalid="this.setCustomValidity('All fields are required!')"
                 oninput="this.setCustomValidity('')">
          <i class="fas fa-eye toggle-password"></i>
      </div>

      <button type="submit" class="login-btn">Reset Password</button>
  </form>

  <a href="{{ route('login') }}" class="link"><i class="fas fa-arrow-left"></i> Back to Login</a>
</div>

<script>
(function(){
    const serverMessage = document.getElementById('serverMessage');
    const inputs = document.querySelectorAll('input');
    const toggleButtons = document.querySelectorAll('.toggle-password');

    // Hide message on input
    inputs.forEach(input => {
        input.addEventListener('input', () => {
            serverMessage.style.display = 'none';
        });
    });

    // Show/Hide password toggle
    toggleButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const input = btn.previousElementSibling;
            if(input.type === 'password') input.type = 'text';
            else input.type = 'password';
            btn.classList.toggle('fa-eye-slash');
        });
    });

    // Basic front-end validation
    const form = document.querySelector('form');
    if(form){
        form.addEventListener('submit', function(e){
            let valid = true;
            for(let input of inputs){
                if(!input.value.trim()){
                    input.setCustomValidity('All fields are required!');
                    input.reportValidity();
                    valid = false;
                    break; // stop at first error
                } else input.setCustomValidity('');
            }
            if(!valid) e.preventDefault();
        });
    }
})();
</script>
</body>
</html>
