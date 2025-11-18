<div class="public-topbar" aria-hidden="false">
  <header role="banner">
    <img src="{{ asset('images/logo.png') }}" alt="Logo" />
    <button id="themeToggle" aria-label="Toggle dark mode" type="button">🌙</button>
  </header>

  <div class="back-btn-row">
    <a href="{{ route('welcome') }}" id="backBtn" class="back-btn" role="link" aria-label="Back to welcome">Back →</a>
  </div>
</div>

<style>
  :root {
    --pink: #ff1f84;
    --btn-radius: 6px;
    --topbar-height: 56px;
  }

  .public-topbar {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 9999;
    display: flex;
    flex-direction: column;
    align-items: stretch;
    -webkit-font-smoothing:antialiased;
    -moz-osx-font-smoothing:grayscale;
  }

  header {
    width: 100%;
    background: var(--pink);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 24px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.12);
  }
  header img { height: 44px; display: block; }

  #themeToggle {
    background: transparent;
    border: 0;
    color: #fff;
    font-size: 1.2rem;
    cursor: pointer;
    padding: 6px;
    transition: transform .15s ease;
    z-index: 10001;
  }
  #themeToggle:hover { transform: scale(1.15); }

  .back-btn-row {
    display: flex;
    justify-content: flex-end;
    padding: 6px 24px;
    background: transparent;
  }
  .back-btn {
    display: inline-block;
    padding: 8px 14px;
    border-radius: var(--btn-radius);
    background: #f10078ff;
    color: white;
    text-decoration: none;
    font-weight: 500;
    transition: .3s;
    border: none;
    cursor: pointer;
    box-shadow: 0 3px 10px rgba(177,110,207,0.3);
  }
  .back-btn:hover { background: #f73898ff; }

  @media (max-width: 880px) {
    .back-btn-row { padding: 6px 12px; justify-content: center; }
    header { padding: 10px 12px; }
    .public-topbar { z-index: 99999; }
  }
</style>

<script>
(function(){
  var welcomeFallback = {!! json_encode(route('welcome')) !!};

  function readCookie(name){
    try { var m = document.cookie.match(new RegExp('(?:^|; )' + name + '=([^;]*)')); return m ? decodeURIComponent(m[1]) : null; }
    catch(e){ return null; }
  }
  function writeCookie(name, value, days){
    try { var maxAge = (days||365)*24*60*60; document.cookie = encodeURIComponent(name)+'='+encodeURIComponent(value)+';path=/;max-age='+maxAge+';SameSite=Lax'; } catch(e){}
  }
  function writeLocal(key, value){ try { localStorage.setItem(key,value); } catch(e){} }
  function readLocal(key){ try { return localStorage.getItem(key); } catch(e){ return null; } }

  function getCurrentTheme(){
    try {
      var ls = readLocal('theme'); if(ls) return ls;
      var ck = readCookie('theme'); if(ck) return ck;
      if(document.documentElement.classList.contains('dark')||document.body.classList.contains('dark')) return 'dark';
      if(window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) return 'dark';
    } catch(e){}
    return 'light';
  }

  function applyTheme(t){
    if(t==='dark'){ document.documentElement.classList.add('dark'); document.body.classList.add('dark'); }
    else { document.documentElement.classList.remove('dark'); document.body.classList.remove('dark'); }
    var toggle = document.getElementById('themeToggle');
    if(toggle){ toggle.textContent = (t==='dark')?'🌞':'🌙'; toggle.setAttribute('aria-pressed', t==='dark'?'true':'false'); }
  }

  function persistTheme(t){ writeLocal('theme', t); writeCookie('theme', t, 365); }

  function initTopbar(){
    var themeToggle = document.getElementById('themeToggle');
    var backBtn = document.getElementById('backBtn');

    var current = getCurrentTheme();
    applyTheme(current);

    if(themeToggle){
      themeToggle.addEventListener('click', function(){
        var now = getCurrentTheme();
        var next = (now==='dark')?'light':'dark';
        persistTheme(next);
        applyTheme(next);
      });
    }

    if(backBtn){
      backBtn.addEventListener('click', function(e){
        if(e.metaKey||e.ctrlKey||e.button===1) return;
        e.preventDefault();
        
        persistTheme(getCurrentTheme()||'light');

        var href = backBtn.getAttribute('href') || welcomeFallback;
        window.location.href = href;
      });
    }
  }

  if(document.readyState==='loading'){ document.addEventListener('DOMContentLoaded', initTopbar); }
  else { initTopbar(); }
})();
</script>
