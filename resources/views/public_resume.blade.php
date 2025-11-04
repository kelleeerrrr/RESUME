<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Public Resume | Irish Rivera</title>

  {{-- Pre-paint theme init --}}
  <script>
    (function(){
      try {
        const params = new URLSearchParams(location.search);
        const qTheme = params.get('theme');
        const cookieTheme = document.cookie.replace(/(?:(?:^|.*;\s*)theme\s*\=\s*([^;]*).*$)|^.*$/, "$1") || null;
        let lsTheme = null; try { lsTheme = localStorage.getItem('theme'); } catch(e){}
        const prefers = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        const theme = qTheme || cookieTheme || lsTheme || prefers;
        if(theme==='dark') document.documentElement.classList.add('dark');
      } catch(e){}
    })();
  </script>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

  <style>
    :root{
      --pink: rgba(255,31,132,0.95);
      --pink-soft: #d6417a;
      --btn-radius: 6px;
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
    html.dark body,
    body.dark {
      background: linear-gradient(to bottom right, #0f1720, #1b2430), url('{{ asset("images/resumebg.jpg") }}') no-repeat center center fixed;
      background-blend-mode: multiply;
      color: #e9eef6;
    }

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

    .welcome-msg {
      text-align:center;
      padding-top: calc(var(--topbar-height) + 30px);
      padding-bottom: 20px;
    }
    .welcome-msg h1 { font-size:1.6rem; margin-bottom:6px; color:var(--pink-soft); }
    .welcome-msg p { font-size: 0.98rem; color: #111; transition: color 0.3s ease; }
    html.dark .welcome-msg p,
    body.dark .welcome-msg p { color: #fff; }

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

    /* Certificate modal */
    #certificateModal {
      display:none;
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,0.78);
      z-index: 9999;
      justify-content: center;
      align-items: center;
      padding: 20px;
    }
    #certificateModal .modal-content {
      width: 90%;
      max-width: 950px;
      background: #fff;
      border-radius: 12px;
      padding: 12px;
      position: relative;
      display:flex;
      flex-direction:column;
      gap: 8px;
      overflow: hidden;
    }
    #certificateModal .modal-body {
      flex:1;
      display:flex;
      align-items:center;
      justify-content:center;
      min-height: 320px;
    }
    #certificateModal img {
      width: 100%;
      height: auto;
      max-height: 80vh;
      object-fit: contain;
      display:block;
    }
    #certificateModal iframe {
      width:100%;
      height:80vh;
      border: none;
    }
    #certificateModal .close-top {
      position: absolute;
      top: 10px;
      right: 10px;
      z-index: 10;
      border-radius: 8px;
      padding: 8px 12px;
      background: #d6417aff;
      color: white;
      border: none;
      cursor:pointer;
    }

    /* small helper: hide printable-only bits */
    .no-print { display: inline; }
    @media print { .no-print { display:none !important; } }

    @media (max-width:880px) {
      .container { flex-direction:column; width:calc(100% - 30px); margin:20px 15px; padding-top: 20px; }
      .left,.right { width:100%; }
      .action-row { flex-direction: column; align-items: center; }
      .back-btn { position: static; margin-top:10px; }
      header { padding:10px 12px; }
    }

    @media print {
      html, body { background: #fff !important; color: #000 !important; height: auto !important; margin: 0 !important; padding: 0 !important; }
      header, .action-row, .welcome-msg { display: none !important; }
      .container { display: flex !important; flex-direction: row !important; flex-wrap: nowrap !important; width: calc(100% - 20mm) !important; max-width: none !important; margin: 0 auto !important; box-shadow: none !important; border-radius: 0 !important; overflow: visible !important; }
      .left { width: 35% !important; background: #f7d6e0 !important; color: #000 !important; }
      .right { width: 65% !important; background: #fff !important; color: #000 !important; }
      .left h2, .right h2 { background: #d6417aff !important; color: white !important; }
      .container, .container * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
      .no-print { display: none !important; }
      /* ensure certificate modal content doesn't show text in print */
      #certificateModal, #certificateModal * { display: none !important; }
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
  <a href="{{ route('welcome') }}" id="backBtn" class="back-btn themed-link">Back →</a>
</div>

@php
    use App\Models\Resume;
    use App\Models\AwardFile;

    // Prefer controller-provided $resume if present; otherwise fetch latest
    $resume = $resume ?? Resume::with('awardFiles')->latest()->first();
    if (!$resume) {
        // fallback object so the blade doesn't break
        $resume = (object)[
            'profile_photo' => null,
            'fullname' => '',
            'dob' => '',
            'pob' => '',
            'civil_status' => '',
            'specialization' => '',
            'organization' => ['name' => [], 'position' => [], 'year' => []],
            'education' => [],
            'skills' => ['key' => [], 'value' => []],
            'programming' => ['key' => [], 'value' => []],
            'projects' => [],
            'interests' => [],
            'awardFiles' => collect([]),
            'address' => '',
            'email' => '',
            'phone' => '',
        ];
    } else {
        // ensure awardFiles is loaded and is a collection
        if (!isset($resume->awardFiles)) {
            $resume->load('awardFiles');
        }
    }

    // Normalize potential JSON fields (same logic as edit blade/controller)
    $fields = ['organization','education','skills','programming','projects','interests'];
    foreach($fields as $f){
        if(isset($resume->$f)){
            $decoded = is_string($resume->$f) ? json_decode($resume->$f, true) : $resume->$f;
            $resume->$f = is_array($decoded) ? $decoded : [];
        } else {
            $resume->$f = [];
        }
    }

    // unify awards collection
    $awards = $resume->awardFiles ?? collect();

    // Helper: return true if array/collection contains at least one non-empty (trimmed) value
    if (!function_exists('hasValues')) {
        function hasValues($arr) {
            if (!$arr) return false;
            // convert collections to arrays
            if (is_object($arr) && method_exists($arr, 'toArray')) $arr = $arr->toArray();
            $flat = [];
            $g = function($x) use (&$g, &$flat) {
                if (is_array($x)) {
                    foreach($x as $v) $g($v);
                } else {
                    $flat[] = $x;
                }
            };
            $g($arr);
            foreach($flat as $v) {
                if (is_string($v) && trim($v) !== '') return true;
                if (!is_string($v) && !empty($v)) return true;
            }
            return false;
        }
    }
@endphp

<div class="container" id="resumeContainer">
  <div class="left">
    <img src="{{ $resume->profile_photo ? asset('storage/' . $resume->profile_photo) . '?t=' . time() : asset('profile.jpg') }}" alt="Profile Picture">

    @if(hasValues([$resume->fullname, $resume->dob, $resume->pob, $resume->civil_status, $resume->specialization]))
      <h2>Personal Information</h2>
      @if(!empty($resume->fullname)) <p><strong>Full Name:</strong> {{ $resume->fullname }}</p> @endif
      @if(!empty($resume->dob)) <p><strong>Date of Birth:</strong> {{ $resume->dob }}</p> @endif
      @if(!empty($resume->pob)) <p><strong>Place of Birth:</strong> {{ $resume->pob }}</p> @endif
      @if(!empty($resume->civil_status)) <p><strong>Civil Status:</strong> {{ $resume->civil_status }}</p> @endif
      @if(!empty($resume->specialization)) <p><strong>Field of Specialization:</strong> {{ $resume->specialization }}</p> @endif
    @endif

    @if(hasValues($resume->organization['name'] ?? []))
      <h2>Organization</h2>
      @foreach($resume->organization['name'] as $i => $name)
        @if(trim((string)$name) !== '')
          <p>
            {{ $name }}
            @if(!empty($resume->organization['position'][$i])) - {{ $resume->organization['position'][$i] }} @endif
            @if(!empty($resume->organization['year'][$i])) ({{ $resume->organization['year'][$i] }}) @endif
          </p>
        @endif
      @endforeach
    @endif
  </div>

  <div class="right">
    @if(!empty($resume->fullname)) <h1>{{ strtoupper($resume->fullname) }}</h1> @endif
    @if(hasValues([$resume->address, $resume->email, $resume->phone]))
      <p>
        @if(!empty($resume->address)) {{ $resume->address }}<br>@endif
        @if(!empty($resume->email)) {{ $resume->email }}<br>@endif
        @if(!empty($resume->phone)) {{ $resume->phone }}@endif
      </p>
    @endif

    {{-- Education: render only if any level has value --}}
    @php
      $eduHas = false;
      foreach(['Elementary','Secondary','Tertiary'] as $lvl) {
          if(!empty($resume->education[$lvl]['school'] ?? '') || !empty($resume->education[$lvl]['year'] ?? '')) { $eduHas = true; break; }
      }
    @endphp
    @if($eduHas)
      <h2>Educational Background</h2>
      @foreach(['Elementary','Secondary','Tertiary'] as $level)
        @php
          $school = $resume->education[$level]['school'] ?? '';
          $year = $resume->education[$level]['year'] ?? '';
        @endphp
        @if(trim((string)$school) !== '' || trim((string)$year) !== '')
          <p><strong>{{ $level }}:</strong> {{ $school ?: '' }}{{ $school && $year ? ' ('.$year.')' : ($year && !$school ? ' ('.$year.')' : '') }}</p>
        @endif
      @endforeach
    @endif

    {{-- Skills --}}
    @if(hasValues($resume->skills['key'] ?? []))
      <h2>Skills</h2>
      <ul>
        @foreach($resume->skills['key'] as $i => $skill)
          @if(trim((string)$skill) !== '')
            <li><strong>{{ $skill }}:</strong> {{ $resume->skills['value'][$i] ?? '' }}</li>
          @endif
        @endforeach
      </ul>
    @endif

    {{-- Programming --}}
    @if(hasValues($resume->programming['key'] ?? []))
      <h2>Programming Languages</h2>
      <ul>
        @foreach($resume->programming['key'] as $i => $lang)
          @if(trim((string)$lang) !== '')
            <li><strong>{{ $lang }}:</strong> {{ $resume->programming['value'][$i] ?? '' }}</li>
          @endif
        @endforeach
      </ul>
    @endif

    {{-- Interests --}}
    @if(hasValues($resume->interests ?? []))
      <h2>Field of Interest</h2>
      <ul>
        @foreach($resume->interests as $interest)
          @if(trim((string)$interest) !== '') <li>{{ $interest }}</li> @endif
        @endforeach
      </ul>
    @endif

    {{-- Projects --}}
    @if(hasValues($resume->projects ?? []))
      <h2>Projects</h2>
      <ul>
        @foreach($resume->projects as $project)
          @if(trim((string)$project) !== '') <li>{{ $project }}</li> @endif
        @endforeach
      </ul>
    @endif

    {{-- Awards --}}
    @if($awards->isNotEmpty())
      @php
        // check if there is at least one award_name non-empty
        $awardHas = false;
        foreach($awards as $a) {
            $n = $a->award_name ?? ($a->name ?? '');
            if(trim((string)$n) !== '') { $awardHas = true; break; }
        }
      @endphp

      @if($awardHas)
        <h2>Awards and Recognitions</h2>
        <ul>
          @foreach($awards as $award)
            @php
              $awardName = $award->award_name ?? $award->name ?? '';
              // possible file property names: file_path, certificate, file, etc.
              $filePath = $award->file_path ?? $award->certificate ?? $award->file ?? null;
            @endphp

            @if(trim((string)$awardName) !== '')
              <li>
                {{ $awardName }}
                @if(!empty($filePath))
                  {{-- on-screen we show icon + text; the text is hidden in print via .no-print --}}
                  <span class="no-print"> - 
                    <a href="javascript:void(0)" onclick="openCertificateModal('{{ asset('storage/' . ltrim($filePath, '/')) }}')" aria-label="Open certificate ({{ htmlspecialchars($awardName, ENT_QUOTES) }})">
                       <span class="no-print">View Certificate </span>
                    </a>
                  </span>
                @endif
              </li>
            @endif
          @endforeach
        </ul>
      @endif
    @endif

  </div>
</div>

{{-- Certificate Modal --}}
<div id="certificateModal" style="display:none;" role="dialog" aria-modal="true" aria-hidden="true">
  <div class="modal-content">
    <button class="close-top" aria-label="Close" onclick="closeCertificateModal()">Close</button>
    <div class="modal-body" id="certificateModalBody">
      <!-- preview will be injected here (iframe for pdf, img for images) -->
    </div>
  </div>
</div>

<script>
(function(){
  const themeToggle = document.getElementById('themeToggle');
  const root = document.documentElement;
  const body = document.body;
  const welcomeMsg = document.getElementById('welcomeMsg');

  function currentTheme() {
    try {
      let t = localStorage.getItem('theme');
      if(!t) t = document.cookie.replace(/(?:(?:^|.*;\s*)theme\s*\=\s*([^;]*).*$)|^.*$/, "$1") || 'light';
      return t;
    } catch(e){ return 'light'; }
  }
  function persistTheme(t){
    try { localStorage.setItem('theme', t); } catch(e){}
    document.cookie = "theme="+t+";path=/;max-age=" + 60*60*24*365;
  }

  function applyTheme(t){
    if(t==='dark'){ root.classList.add('dark'); body.classList.add('dark'); themeToggle.textContent='🌞'; }
    else{ root.classList.remove('dark'); body.classList.remove('dark'); themeToggle.textContent='🌙'; }
  }

  applyTheme(currentTheme());

  if(themeToggle){
    themeToggle.addEventListener('click', ()=>{ let newTheme = root.classList.contains('dark') ? 'light' : 'dark'; applyTheme(newTheme); persistTheme(newTheme); });
  }

  const downloadBtn = document.getElementById('downloadCvBtn');
  if(downloadBtn){
    downloadBtn.addEventListener('click', (e)=>{
      e.preventDefault();
      if(welcomeMsg) welcomeMsg.style.display='none';
      window.print();
      setTimeout(()=>{ if(welcomeMsg) welcomeMsg.style.display=''; }, 500);
    });
  }
})();

/* Certificate modal logic:
   - openCertificateModal(url) : url should be a public URL (asset('storage/...'))
   - if url ends with .pdf => iframe
   - else => img (object-fit: contain)
*/
function openCertificateModal(url) {
  if(!url) return;
  const modal = document.getElementById('certificateModal');
  const body = document.getElementById('certificateModalBody');
  // cleanup
  body.innerHTML = '';

  const lower = url.split('?')[0].toLowerCase();
  const isPdf = lower.endsWith('.pdf');

  if(isPdf) {
    const iframe = document.createElement('iframe');
    iframe.src = url;
    iframe.setAttribute('title','Certificate PDF');
    body.appendChild(iframe);
  } else {
    const img = document.createElement('img');
    img.src = url;
    img.alt = 'Certificate image';
    img.style.maxHeight = '80vh';
    img.style.objectFit = 'contain';
    img.style.width = '100%';
    body.appendChild(img);
  }

  modal.style.display = 'flex';
  modal.setAttribute('aria-hidden','false');
}

function closeCertificateModal(){
  const modal = document.getElementById('certificateModal');
  const body = document.getElementById('certificateModalBody');
  modal.style.display = 'none';
  modal.setAttribute('aria-hidden','true');
  // cleanup
  body.innerHTML = '';
}

// close when clicking outside content
window.addEventListener('click', function(e){
  const modal = document.getElementById('certificateModal');
  const content = modal && modal.querySelector('.modal-content');
  if(modal && content && e.target === modal) {
    closeCertificateModal();
  }
});

// escape key
document.addEventListener('keydown', function(e){
  if(e.key === 'Escape') {
    const modal = document.getElementById('certificateModal');
    if(modal && modal.style.display === 'flex') closeCertificateModal();
  }
});
</script>
</body>
</html>
