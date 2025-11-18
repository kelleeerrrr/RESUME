{{-- resources/views/home.blade.php --}}
@php
  use Illuminate\Support\Facades\Route;
  use App\Models\Resume;
  use Illuminate\Support\Carbon;
  use Illuminate\Support\Facades\Storage;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Home — Community Resumes</title>

  {{-- Fonts --}}
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

  {{-- CropperJS --}}
  <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">

  <style>
    :root{
      --pink-1:#ffe6eb; --pink-2:#ffd6f6; --accent-pink:#d63384; --container-pink:rgba(206,133,150,0.95);
      --radius:14px; --card-width:1200px;
    }
    *{box-sizing:border-box;margin:0;padding:0}
    html,body{height:100%}
    body{ font-family:"Poppins",sans-serif; background:linear-gradient(135deg,var(--pink-1),var(--pink-2)); color:#222; display:flex; flex-direction:column; align-items:center; min-height:100vh; }
    html.dark body { background: linear-gradient(135deg,#171016,#2a1420); color:#eee; }

    header{ width:100%; max-width:var(--card-width); margin-top:28px; padding:18px 22px; display:flex; justify-content:space-between; align-items:center; gap:12px; }
    header .left { display:flex; flex-direction:column; gap:6px; }
    header h1{ color:var(--accent-pink); font-size:1.7rem; margin:0; font-weight:800; letter-spacing:0.6px; }
    header p{ color:#6b2a4a; font-size:1rem; margin:0; opacity:0.95; }
    html.dark header p { color: #f0dfe7; }

    .auth { display:flex; gap:10px; align-items:center; }
    .btn { padding:10px 14px; border-radius:999px; text-decoration:none; font-weight:600; border:none; cursor:pointer; background: linear-gradient(135deg,#ff85a2,#ffb8d2); color:#fff; box-shadow:0 8px 24px rgba(0,0,0,0.08); }
    .btn.secondary { background:transparent; color:#fff; border:1px solid rgba(255,255,255,0.14); box-shadow:none; }
    .btn.logout { background: linear-gradient(90deg,#ff5a9b,#ff3d7a); }

    .theme-toggle { display:inline-flex; align-items:center; gap:8px; padding:8px 10px; border-radius:999px; background: rgba(255,255,255,0.9); color:#6b2a4a; font-weight:600; cursor:pointer; border:none; }
    html.dark .theme-toggle { background: rgba(255,255,255,0.06); color:#f6e6ef; border:1px solid rgba(255,255,255,0.04); }

    main.container{ width:100%; max-width:var(--card-width); padding:36px 22px 80px; display:flex; gap:28px; align-items:flex-start; flex-wrap:wrap; }
    .card { flex:1 1 640px; background:var(--container-pink); color:#fff; border-radius:var(--radius); padding:28px; box-shadow:0 12px 48px rgba(0,0,0,0.15); display:flex; gap:24px; align-items:flex-start; flex-wrap:wrap; }
    html.dark .card { background: rgba(58, 29, 45, 0.92); }

    .left-col { flex:1 1 520px; min-width:280px; }
    .welcome { font-size:1.85rem; font-weight:800; margin-bottom:6px; line-height:1.02; color:#fff; text-transform:uppercase; }
    .subtitle { color:rgba(255,255,255,0.96); font-size:1.05rem; margin-bottom:18px; }

    .resume-link-container {
      display:flex; align-items:center; gap:8px; margin-bottom:18px;
    }
    .resume-link-container input {
      flex:1; padding:10px 12px; border-radius:8px; border:none; font-size:0.95rem; background:#fff; color:#333; transition:0.2s;
    }
    html.dark .resume-link-container input { background: rgba(255,255,255,0.06); color:#eee; border:1px solid rgba(255,255,255,0.15); }
    .resume-link-container button {
      padding:10px 14px; border:none; border-radius:8px; background:#d63384; color:#fff; font-weight:600; cursor:pointer; transition:0.2s;
    }
    .resume-link-container button:hover { background:#b72b70; }

    .actions { display:flex; gap:12px; align-items:center; margin-bottom:16px; flex-wrap:wrap; }
    .actions .btn { padding:12px 18px; font-size:0.98rem; border-radius:12px; }

    .preview { background:#fff; color:#333; border-radius:12px; padding:16px; box-shadow:0 8px 30px rgba(0,0,0,0.08); display:flex; gap:14px; align-items:center; cursor:pointer; transition:0.2s; }
    html.dark .preview { background: rgba(255,255,255,0.04); color:#eee; border:1px solid rgba(255,255,255,0.03); }
    .preview .photo-wrap { position:relative; width:96px; height:96px; border-radius:50%; overflow:hidden; flex-shrink:0; border:2px solid transparent; transition:0.2s; display:flex; align-items:center; justify-content:center; font-size:1.8rem; color:#d63384; }
    .preview.active .photo-wrap { border:2px solid #d63384; }
    .preview .photo { width:100%; height:100%; object-fit:cover; display:block; }
    .preview .meta { display:flex; flex-direction:column; gap:4px; }
    .preview .meta h3 { margin:0; color:var(--accent-pink); font-size:1.05rem; font-weight:700; }
    .preview .meta p { margin:0; color:#000; font-size:0.95rem; max-width:560px; }
    html.dark .preview .meta p { color: #ddd; }

    .right-col { width:360px; display:flex; flex-direction:column; gap:12px; align-items:center; }
    .spotlight-photo-wrap { width:280px; height:280px; border-radius:50%; overflow:hidden; display:flex; align-items:center; justify-content:center; background:linear-gradient(135deg,#ffd6f6,#ffe6eb); position:relative; cursor:pointer; transition: transform 0.2s; }
    .spotlight-photo-wrap:hover { transform:scale(1.03); }

    .spotlight-photo { width:100%; height:100%; object-fit:cover; display:block; border-radius:50%; transition: opacity 0.3s; }

    .spotlight-overlay {
      position:absolute; left:0; right:0; bottom:0; padding:12px 0;
      display:flex; justify-content:center; align-items:center;
      background:linear-gradient(135deg,#ff85a2,#ffb8d2); color:#fff; font-weight:700; font-size:1.2rem;
      opacity:0; transform: translateY(100%); border-radius:0 0 50% 50%; transition:0.3s;
      pointer-events:none;
    }
    .spotlight-photo-wrap:hover .spotlight-overlay { opacity:1; transform: translateY(0); pointer-events:auto; }

    .spotlight-plus { font-size:3rem; color:#fff; display:block; }
    .upload-text { font-size:1rem; color:#fff; margin-top:8px; }

    /* Modal */
    .modal-backdrop { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6); align-items:center; justify-content:center; z-index:1000; padding:20px; }
    .modal { background:rgba(220, 153, 164, 1); color: #000; border-radius:12px; max-width:600px; width:100%; padding:16px; display:flex; flex-direction:column; gap:10px; }
    .crop-container { width:100%; height:400px; position:relative; background:#333; display:flex; align-items:center; justify-content:center; overflow:hidden; border-radius:50%; }
    .crop-container img { border-radius:50%; max-width:100%; }
    .modal-footer { display:flex; justify-content:flex-end; gap:10px; }
    .btn { padding:8px 14px; border:none; border-radius:6px; background:#d63384; color:#fff; cursor:pointer; transition: background 0.2s; }
    .btn:hover { background:#b72b70; }
    .btn.secondary { background:#555; }
    .btn.secondary:hover { background:#333; }

    @media(max-width:480px){
      .spotlight-photo-wrap { width:200px; height:200px; }
      .crop-container { height:300px; }
    }
  </style>
</head>
<body>
  {{-- HEADER --}}
  <header>
    <div class="left">
      <h1>{{ strtoupper($userName ?? 'FRIEND') }} </h1>
    </div>
    <div class="auth">
      @php
        $userId = session('user_id');
        $userName = session('user_name') ?? null;
      @endphp
      @if($userId)
        <button id="themeToggle" class="theme-toggle" aria-pressed="false" title="Toggle dark mode"><span id="themeIcon">🌙</span><span id="themeLabel">Dark</span></button>
        <form id="logoutForm" method="POST" action="{{ route('logout') }}" style="display:inline;margin-left:8px;">
          @csrf
          <button type="submit" class="btn logout">Logout</button>
        </form>
      @else
        <a href="{{ route('login') }}" class="btn">Login</a>
        <a href="{{ route('register') }}" class="btn">Sign Up</a>
      @endif
    </div>
  </header>

  {{-- MAIN --}}
  <main class="container">
    <div class="card">
      <div class="left-col">
        <div class="welcome">Dashboard</div>
        <div class="subtitle">Here you can create or edit your resume, and manage what others see.</div>

        {{-- Public Resume Link --}}
        @php
          $resume = ($userId) ? Resume::where('user_id', $userId)->with('awardFiles')->first() : null;
          $resumeLink = $resume ? url('/public-resume/'.$userId) : '';
        @endphp
        <div class="resume-link-container">
          <input type="text" id="publicResumeLink" value="{{ $resumeLink }}" readonly {{ $resumeLink ? '' : 'placeholder= -' }}>
          <button type="button" id="copyResumeBtn" {{ $resumeLink ? '' : 'disabled' }}>Copy</button>
        </div>

        {{-- Actions --}}
        <div class="actions">
          @if($userId)
            @if($resume)
              <a href="{{ route('resume.edit', $resume->id) }}" class="btn">Edit Resume</a>
              <a href="{{ url('/public-resume/'.$userId) }}" class="btn secondary">View Public Resume</a>
              <button id="deleteResumeBtn" class="btn secondary">Delete Resume</button>
            @else
              <a href="{{ route('resume.create') }}" class="btn">Create Resume</a>
            @endif
          @endif
        </div>

        {{-- Preview --}}
        <div class="preview" id="resumePreview">
          <div class="photo-wrap" id="previewPhotoWrap">
            @if($resume && $resume->profile_photo)
              <img src="{{ asset('storage/'.$resume->profile_photo) }}" alt="{{ $userName ?? 'User' }}" class="photo">
            @else
              <img src="{{ asset('images/default-avatar.jpg') }}" alt="Default Profile" class="photo">
            @endif
          </div>
          <div class="meta">
            <h3>{{ $userName ?? 'Your name' }}</h3>
            <p class="specialization">{{ $resume->specialization ?? '—' }}</p>
            @if($resume && $resume->updated_at)
              <p class="last-updated">Last updated: {{ Carbon::parse($resume->updated_at)->diffForHumans() }}</p>
            @endif
          </div>
        </div>
      </div>

      {{-- Spotlight photo right --}}
      <div class="right-col">
        @php
          $pending = session('pending_spotlight') ?? null;
          $spotSrc = null;
          if ($pending && Storage::disk('public')->exists($pending)) {
              $spotSrc = asset('storage/'.$pending);
          } elseif ($resume && $resume->spotlight_photo) {
              $spotSrc = asset('storage/'.$resume->spotlight_photo);
          }
        @endphp
        <div class="spotlight-photo-wrap" id="spotlightWrap" tabindex="0" aria-label="Spotlight photo upload">
          @if($spotSrc)
            <img src="{{ $spotSrc }}" alt="Spotlight photo" class="spotlight-photo" id="currentSpotlight">
            <div class="spotlight-overlay">Change Photo</div>
          @else
            <span class="spotlight-plus">+</span>
            <div class="upload-text">Upload a photo</div>
          @endif
        </div>
      </div>
    </div>
  </main>

  {{-- Delete confirmation modal --}}
  <div class="modal-backdrop" id="deleteModal" style="display:none;">
    <div class="modal">
      <p>Are you sure you want to delete your resume?</p>
      <div class="modal-footer">
        <form id="deleteForm" method="POST" action="{{ $resume ? route('resume.destroy', $resume->id) : '#' }}">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn">Delete</button>
        </form>
        <button class="btn secondary" id="cancelDelete">Cancel</button>
      </div>
    </div>
  </div>

  {{-- Hidden file input and modal --}}
  <input type="file" id="photoInput" accept="image/*" style="display:none">
  <div class="modal-backdrop" id="cropModalBackdrop">
    <div class="modal">
      <div class="crop-container">
        <img id="cropImage" style="display:block;">
      </div>
      <div class="modal-footer">
        <button class="btn secondary" id="cancelCrop">Cancel</button>
        <button class="btn" id="applyCrop">Apply</button>
      </div>
    </div>
  </div>

  <form id="photoUploadForm" method="POST" action="{{ route('resume.photo.update') }}" style="display:none;">
    @csrf
    <input type="hidden" name="photo_type" value="spotlight">
    <input type="hidden" name="photo_data" id="photoDataInput" value="">
  </form>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
  <script>
    // Dark mode localStorage
    (function(){
      const themeToggle = document.getElementById('themeToggle');
      const themeIcon = document.getElementById('themeIcon');
      const themeLabel = document.getElementById('themeLabel');
      if(localStorage.getItem('theme')==='dark') document.documentElement.classList.add('dark');
      function updateThemeUI(){ const isDark = document.documentElement.classList.contains('dark'); themeToggle.setAttribute('aria-pressed', isDark); themeIcon.textContent=isDark?'☀️':'🌙'; themeLabel.textContent=isDark?'Light':'Dark'; }
      themeToggle.addEventListener('click', ()=>{ document.documentElement.classList.toggle('dark'); localStorage.setItem('theme', document.documentElement.classList.contains('dark')?'dark':'light'); updateThemeUI(); });
      updateThemeUI();
    })();

    // Copy resume link
    const copyBtn = document.getElementById('copyResumeBtn');
    const resumeLinkInput = document.getElementById('publicResumeLink');
    if(copyBtn) copyBtn.addEventListener('click', ()=>{ if(!resumeLinkInput.value) return; resumeLinkInput.select(); resumeLinkInput.setSelectionRange(0,99999); navigator.clipboard.writeText(resumeLinkInput.value).then(()=>{ const orig=copyBtn.textContent; copyBtn.textContent='Copied'; setTimeout(()=>copyBtn.textContent=orig,3000); }); });

    // Delete confirmation
    const deleteBtn = document.getElementById('deleteResumeBtn');
    const deleteModal = document.getElementById('deleteModal');
    const cancelDelete = document.getElementById('cancelDelete');
    if(deleteBtn){
      deleteBtn.addEventListener('click', ()=> deleteModal.style.display='flex');
    }
    if(cancelDelete){
      cancelDelete.addEventListener('click', ()=> deleteModal.style.display='none');
    }

    // Profile highlight
    const resumePreview = document.getElementById('resumePreview');
    const previewPhotoWrap = document.getElementById('previewPhotoWrap');
    if(resumePreview && previewPhotoWrap) resumePreview.addEventListener('click', ()=> previewPhotoWrap.classList.toggle('active'));

    // Spotlight crop logic
    const spotlightWrap = document.getElementById('spotlightWrap');
    const photoInput = document.getElementById('photoInput');
    const cropModal = document.getElementById('cropModalBackdrop');
    const cropImage = document.getElementById('cropImage');
    const applyCrop = document.getElementById('applyCrop');
    const cancelCrop = document.getElementById('cancelCrop');
    let currentSpotlight = document.getElementById('currentSpotlight');
    const photoDataInput = document.getElementById('photoDataInput');
    const photoUploadForm = document.getElementById('photoUploadForm');
    let cropper = null;

    if (spotlightWrap) spotlightWrap.addEventListener('click', ()=> photoInput.click());
    photoInput.addEventListener('change', e=>{
      const file = e.target.files[0];
      if(!file) return;
      cropImage.src = URL.createObjectURL(file);
      cropModal.style.display='flex';
      if(cropper) cropper.destroy();
      cropper = new Cropper(cropImage, { aspectRatio:1, viewMode:1, movable:true, zoomable:true, rotatable:false, scalable:false, autoCropArea:1, background:false });
    });
    applyCrop.addEventListener('click', ()=>{
      if(!cropper) return;
      const canvas = cropper.getCroppedCanvas({ width:280, height:280, imageSmoothingQuality:'high' });
      const croppedDataUrl = canvas.toDataURL('image/png');
      if(currentSpotlight){
        currentSpotlight.style.opacity=0;
        setTimeout(()=>{ currentSpotlight.src=croppedDataUrl; currentSpotlight.style.opacity=1; },180);
      }else{
        const wrap = document.getElementById('spotlightWrap');
        wrap.innerHTML='<img id="currentSpotlight" class="spotlight-photo" src="'+croppedDataUrl+'" alt="Spotlight photo"><div class="spotlight-overlay">Change Photo</div>';
        currentSpotlight=document.getElementById('currentSpotlight');
      }
      photoDataInput.value=croppedDataUrl;
      cropModal.style.display='none';
      cropper.destroy(); cropper=null; photoInput.value=null;
      photoUploadForm.submit();
    });
    cancelCrop.addEventListener('click', ()=>{
      cropModal.style.display='none'; if(cropper) cropper.destroy(); cropper=null; photoInput.value=null;
    });
    document.addEventListener('keydown', e=>{
      if(e.key==='Escape' && cropModal.style.display==='flex'){ cropModal.style.display='none'; if(cropper) cropper.destroy(); cropper=null; photoInput.value=null; }
      if(e.key==='Escape' && deleteModal.style.display==='flex'){ deleteModal.style.display='none'; }
    });
  </script>
</body>
</html>
