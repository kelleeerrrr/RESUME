{{-- resources/views/resume_edit.blade.php (updated full file) --}}
@php
    use Illuminate\Support\Carbon;
    $isNew = empty($resume) || empty($resume->id);
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>{{ $isNew ? 'Create Resume' : 'Edit Resume' }}</title>

<link href="https://cdn.jsdelivr.net/npm/cropperjs@1.5.13/dist/cropper.min.css" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet"/>

<style>
/* -------------------------
   Colors & overall look
   Match sign-up/login/welcome vibes (soft pink gradients)
   ------------------------- */
:root{
    --pink-1: #ffe6eb;
    --pink-2: #ffd6f6;
    --accent-pink: #d63384;
    --accent-pink-2: #ff66a3;
    --container-pink: rgba(206,133,150,0.95);
    --muted: #9aa1a8;
    --success: #1f8a2f;
    --danger: #e74c3c;
}

/* page background aligned with welcome/login */
html,body{height:100%; margin:0; font-family:"Poppins",sans-serif; -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale;}
body{
    background: linear-gradient(135deg, var(--pink-1), var(--pink-2));
    color:#222;
    min-height:100vh;
}

/* container card uses the same container-pink with subtle white panel */
.container {
    max-width: 950px;
    margin: 40px auto;
    position: relative;
    overflow: visible;
    border-radius: 18px;
    padding: 34px;
    transition: background 0.3s, color 0.3s, transform 0.12s;
    border: 1px solid rgba(16,24,40,0.04);
    background: linear-gradient(135deg, rgba(255,255,255,0.85), rgba(255,245,248,0.9));
    box-shadow: 0 18px 40px rgba(16,24,40,0.06), inset 0 -6px 24px rgba(255,31,132,0.02);
}

/* subtle hover lift */
.container:hover { transform: translateY(-6px); box-shadow: 0 28px 50px rgba(16,24,40,0.08); }

/* decorative soft pink bubbles (kept) */
.bubble{ position:absolute;border-radius:50%;opacity:0.12;filter:blur(6px);pointer-events:none;mix-blend-mode:screen; }
.b1{ width:120px;height:120px; left:12%; top:2%; background: radial-gradient(circle at 30% 30%, rgba(255,115,165,0.18), rgba(255,200,220,0.06)); }
.b2{ width:90px;height:90px; right:6%; top:14%; background: radial-gradient(circle at 30% 30%, rgba(255,200,220,0.14), rgba(255,115,165,0.04)); }
.b3{ width:60px;height:60px; left:6%; bottom:12%; background: radial-gradient(circle at 30% 30%, rgba(255,115,165,0.12), rgba(255,200,220,0.03)); }
.b4{ width:40px;height:40px; right:22%; bottom:6%; background: radial-gradient(circle at 30% 30%, rgba(255,115,165,0.10), rgba(255,200,220,0.02)); }
.b5{ width:180px;height:180px; left:50%; top:-30px; background: radial-gradient(circle at 30% 30%, rgba(255,115,165,0.06), rgba(255,200,220,0.02)); }

/* header + back button */
/* UPDATED .back-btn so entire pill is clickable and above the title */
.back-btn {
  position: absolute;
  left: 18px;
  top: 18px;
  display: inline-flex;                 /* makes the whole box clickable */
  align-items: center;
  gap: 8px;
  padding: 10px 14px;                  /* larger hit area */
  border-radius: 12px;
  background: linear-gradient(90deg,var(--accent-pink),var(--accent-pink-2));
  color: #fff;
  border: none;
  text-decoration: none;
  font-weight: 600;
  box-shadow: 0 10px 30px rgba(214,49,104,0.12);
  z-index: 9999;                        /* sit above centered title */
  cursor: pointer;
  transition: transform 0.12s ease, box-shadow 0.12s ease, opacity 0.12s ease;
}
.back-btn:hover,
.back-btn:focus {
  transform: translateY(-2px);
  box-shadow: 0 18px 40px rgba(214,49,104,0.16);
  opacity: 0.98;
  outline: 2px solid rgba(255,255,255,0.06);
}
.back-btn .back-arrow,
.back-btn .back-text { pointer-events: none; }

/* header centered */
.header-row { display:flex; align-items:center; justify-content:center; gap:12px; margin-bottom:8px; position:relative; }
.title-wrap { text-align:center; }
h1 { color: var(--accent-pink); margin:0; font-weight:800; letter-spacing:-0.3px; }
.subtitle { color: rgba(107,42,74,0.9); margin-top:6px; margin-bottom:8px; }

/* success banner (green) — appears under title */
#successBanner {
    display:none;
    margin: 12px 0 18px 0;
    padding:10px 14px;
    border-radius:10px;
    background: linear-gradient(90deg,#e6f9ea,#dff3df);
    color: #0b5f2a;
    font-weight:700;
    border:1px solid rgba(30,120,60,0.08);
}

/* inputs and sections */
label{ font-weight:600; margin-top:10px; display:block; }
input[type="text"], input[type="email"], input[type="date"], textarea, select {
    width:100%;
    padding:10px 12px;
    margin:5px 0 15px 0;
    border-radius:10px;
    border:1px solid rgba(16,24,40,0.06);
    transition:all 0.18s;
    font-size:0.95rem;
    background:#fff;
    box-shadow: inset 0 -2px 8px rgba(16,24,40,0.02);
}
input::placeholder, textarea::placeholder { color:#bfc6cc; }
input:focus, textarea:focus, select:focus { border-color: var(--accent-pink); outline:none; box-shadow: 0 10px 30px rgba(255,115,165,0.08); transform: translateY(-1px); }

/* date input styling: keep consistent with others */
input[type="date"]::-webkit-inner-spin-button,
input[type="date"]::-webkit-calendar-picker-indicator { cursor: pointer; }

/* sections */
.section { margin-bottom:22px; padding:18px; border-radius:12px; background-color: rgba(255,255,255,0.96); box-shadow:0 8px 26px rgba(16,24,40,0.04); display:none; opacity:0; transform:translateY(8px); transition:opacity 0.36s, transform 0.36s; border-top:1px solid rgba(16,24,40,0.03); }
.section.visible { display:block; opacity:1; transform:translateY(0); }
.section:hover { box-shadow:0 18px 40px rgba(255,115,165,0.06); }

/* array input layout */
.array-input { display:flex; gap:10px; margin-bottom:8px; flex-wrap:wrap; align-items:center; }
.array-input input { flex:1; min-width:140px; }

/* buttons */
.btn { display:inline-flex; align-items:center; gap:8px; padding:10px 18px; border-radius:10px; text-decoration:none; font-weight:600; border:none; cursor:pointer; color:white; transition: transform 0.12s, box-shadow 0.12s; }
.btn:hover { transform: translateY(-3px); }
.btn-save { background: linear-gradient(90deg,var(--accent-pink),var(--accent-pink-2)); box-shadow: 0 12px 36px rgba(214,49,104,0.14); }
.btn-add { background: linear-gradient(90deg,#ff8fcf,#ff4da6); box-shadow: 0 10px 30px rgba(255,77,166,0.10); }
.btn-remove { background: linear-gradient(90deg,#ff7b7b,#e74c3c); box-shadow: 0 10px 28px rgba(231,76,60,0.10); }

/* profile preview */
#profile-preview { width:150px; height:150px; border-radius:50%; object-fit:cover; border:3px solid var(--accent-pink); box-shadow: 0 12px 36px rgba(255,115,165,0.08); margin-bottom:10px; }

/* modals */
#cropper-modal{ display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.72); justify-content:center; align-items:center; z-index:9999; padding:28px; }
#cropper-container{ background:white; padding:20px; border-radius:10px; width:420px; max-width:95%; display:flex; flex-direction:column; align-items:center; box-shadow:0 22px 48px rgba(16,24,40,0.16); border:1px solid rgba(16,24,40,0.05); }
#cropper-image{ max-width:100%; max-height:320px; border-radius:6px; }

#file-modal{ display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.72); justify-content:center; align-items:center; z-index:10000; padding:20px; }
#file-modal .fm-content { position:relative; width:80%; max-width:900px; background:#fff; border-radius:12px; padding:12px; box-shadow:0 24px 60px rgba(0,0,0,0.5); display:flex; flex-direction:column; gap:10px; overflow:hidden; }
#file-modal img{ width:100%; height:auto; max-height:80vh; object-fit:contain; display:block; }
#file-modal iframe{ width:100%; height:80vh; border:none; }
#file-modal-close{ position:absolute; top:10px; right:10px; z-index:101; border-radius:8px; padding:8px 12px; }

/* unsaved changes modal */
#unsavedModal { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.45); z-index:12000; align-items:center; justify-content:center; padding:18px; }
#unsavedModal .card { background:#fff; padding:18px; border-radius:10px; max-width:420px; text-align:center; color:#111; }
#unsavedModal .actions { margin-top:14px; display:flex; gap:10px; justify-content:center; }

/* small toast fallback */
#max-toast { position:fixed; left:50%; transform:translateX(-50%) translateY(12px); bottom:22px; background:rgba(32,32,32,0.94); color:#fff; padding:10px 14px; border-radius:10px; z-index:11000; opacity:0; pointer-events:none; transition:opacity .18s, transform .18s; }
#max-toast.show{ opacity:1; transform:translateX(-50%) translateY(0); pointer-events:auto; }

/* responsive tweaks */
@media (max-width:880px){ .container{ margin:24px 18px; padding:20px } .back-btn{ left:12px; top:14px; padding:8px 12px; border-radius:10px } }
</style>
</head>
<body>

<div class="container" role="main" aria-labelledby="pageTitle">
    <!-- bubbles -->
    <div class="bubble b1" aria-hidden="true"></div>
    <div class="bubble b2" aria-hidden="true"></div>
    <div class="bubble b3" aria-hidden="true"></div>
    <div class="bubble b4" aria-hidden="true"></div>
    <div class="bubble b5" aria-hidden="true"></div>

    <!-- Back to Home + Title -->
    <!-- UPDATED: full pill clickable (arrow + text) -->
    <a href="{{ route('home') }}" id="backBtn" class="back-btn" title="Back to Home" role="link" aria-label="Back to Home">
      <span class="back-arrow">←</span>
      <span class="back-text">Back</span>
    </a>

    <div class="header-row">
        <div class="title-wrap">
            <h1 id="pageTitle">{{ $isNew ? 'Create Your Resume' : 'Edit Your Resume' }}</h1>
            <p class="subtitle">{{ $isNew ? 'Start building your public resume.' : 'Update your details — profile, education, skills, projects and more.' }}</p>
        </div>
    </div>

    <!-- Success banner (green) -->
    <div id="successBanner" role="status" aria-live="polite">{{ session('success') ?? '' }}</div>

    <script>
      (function(){
        @if(session('success'))
          document.addEventListener('DOMContentLoaded', function(){
            const b = document.getElementById('successBanner');
            if(b){
              b.style.display = 'block';
              setTimeout(()=> { b.style.transition = 'opacity .4s'; b.style.opacity = '0'; setTimeout(()=> b.style.display='none', 400); }, 4000);
            }
          });
        @endif
      })();
    </script>

    {{-- Form --}}
    @if($isNew)
      <form id="resume-form" action="{{ route('resume.store') }}" method="POST" enctype="multipart/form-data">
    @else
      <form id="resume-form" action="{{ route('resume.update', ['id' => $resume->id]) }}" method="POST" enctype="multipart/form-data">
        @method('PUT')
    @endif
    @csrf

    <p class="small-muted" style="text-align:center; margin-bottom:16px;">Tip: Make sure your resume reflects your latest achievements. You can save whenever you've made a change.</p>

    {{-- Profile --}}
    <div class="section visible" data-section="profile">
        <h2>Profile Picture</h2>
        <img id="profile-preview" src="{{ old('cropped_image') ?: (!empty($resume->profile_photo) ? asset('storage/'.$resume->profile_photo) : 'https://via.placeholder.com/150') }}" alt="Profile Preview">
        <input type="file" id="profile-input" accept="image/*" aria-label="Upload profile photo">
        <input type="hidden" name="cropped_image" id="cropped_image" value="{{ old('cropped_image', '') }}">
    </div>

    {{-- Personal info (DOB uses calendar) --}}
    <div class="section visible" data-section="personal">
        <h2>Personal Information</h2>
        <label>Full Name</label>
        <input type="text" name="fullname" value="{{ old('fullname', $resume->fullname ?? '') }}" required>

        <label>Date of Birth</label>
        <input type="date" name="dob" id="dob" value="{{ old('dob', isset($resume->dob) ? \Carbon\Carbon::parse($resume->dob)->format('Y-m-d') : '') }}" placeholder="YYYY-MM-DD">

        <label>Place of Birth</label>
        <input type="text" name="pob" value="{{ old('pob', $resume->pob ?? '') }}">

        <label>Gender</label>
        <select name="civil_status" id="civil_status">
            <option value="">Prefer not to say</option>
            <option value="Female" {{ old('civil_status', $resume->civil_status ?? '') === 'Female' ? 'selected' : '' }}>Female</option>
            <option value="Male" {{ old('civil_status', $resume->civil_status ?? '') === 'Male' ? 'selected' : '' }}>Male</option>
        </select>
        <small class="small-muted">If you choose "Prefer not to say" the gender will not appear on your public resume.</small>

        <label>Field of Specialization</label>
        <input type="text" name="specialization" value="{{ old('specialization', $resume->specialization ?? '') }}">

        <label>Email</label>
        <input type="email" name="email" value="{{ old('email', $resume->email ?? '') }}">

        <label>Phone</label>
        <input type="text" name="phone" value="{{ old('phone', $resume->phone ?? '') }}">

        <label>Address</label>
        <input type="text" name="address" value="{{ old('address', $resume->address ?? '') }}">
    </div>

    <label for="section-filter">Select Section to Edit:</label>
    <select id="section-filter">
        <option value="">Personal Information</option>
        <option value="organization">Organization</option>
        <option value="education">Education</option>
        <option value="interests">Field of Interest</option>
        <option value="skills">Skills</option>
        <option value="programming">Programming Languages</option>
        <option value="projects">Projects</option>
        <option value="awards">Awards & Recognitions</option>
        <option value="all">All Sections</option>
    </select>

    {{-- Organization --}}
    <div class="section" data-section="organization">
        <h2>Organization</h2>
        <div id="organization-container">
            @php $org = old('organization', $resume->organization ?? []); $orgNames = $org['name'] ?? []; @endphp
            @if(!empty($orgNames))
                @foreach($orgNames as $i => $name)
                    <div class="array-input">
                        <input type="text" name="organization[name][]" value="{{ $name }}" placeholder="Organization Name">
                        <input type="text" name="organization[position][]" value="{{ $org['position'][$i] ?? '' }}" placeholder="Position">
                        <input type="text" name="organization[year][]" value="{{ $org['year'][$i] ?? '' }}" placeholder="Year">
                        <button type="button" class="btn btn-remove" onclick="this.parentElement.remove()">Remove</button>
                    </div>
                @endforeach
            @endif
        </div>
        <button type="button" class="btn btn-add" onclick="addOrganization()">+ Add Organization</button>
    </div>

    {{-- Education --}}
    <div class="section" data-section="education">
        <h2>Education</h2>
        @php $edu = old('education', $resume->education ?? []); @endphp
        @foreach(['Elementary','Secondary','Tertiary'] as $level)
            <div class="array-input">
                <input type="text" name="education[{{ $level }}][school]" value="{{ $edu[$level]['school'] ?? '' }}" placeholder="{{ $level }} School" oninput="toggleNextInput(this)">
                <input type="text" name="education[{{ $level }}][year]" value="{{ $edu[$level]['year'] ?? '' }}" placeholder="Year" @if(empty($edu[$level]['school'])) disabled @endif>
            </div>
        @endforeach
    </div>

    {{-- Interests --}}
    <div class="section" data-section="interests">
        <h2>Field of Interest</h2>
        <div id="interests-container">
            @foreach(old('interests', $resume->interests ?? []) as $interest)
                <div class="array-input">
                    <input type="text" name="interests[]" value="{{ $interest }}" maxlength="30" placeholder="Interest (max 30 chars)">
                    <button type="button" class="btn btn-remove" onclick="this.parentElement.remove()">Remove</button>
                </div>
            @endforeach
        </div>
        <button type="button" class="btn btn-add" onclick="addInput('interests-container','interests[]')">+ Add Interest</button>
    </div>

    {{-- Skills --}}
    <div class="section" data-section="skills">
        <h2>Skills</h2>
        <div id="skills-container">
            @php
                $skillsKey = old('skills.key', $oldSkillsKey ?? []);
                $skillsValue = old('skills.value', $oldSkillsValue ?? []);
            @endphp
            @foreach($skillsKey as $i => $skill)
                <div class="array-input">
                    <input type="text" name="skills[key][]" value="{{ $skill }}" placeholder="Skill" oninput="toggleNextInput(this)" maxlength="20">
                    <input type="text" name="skills[value][]" value="{{ $skillsValue[$i] ?? '' }}" placeholder="Description" @if(empty($skill)) disabled @endif maxlength="100">
                    <button type="button" class="btn btn-remove" onclick="this.parentElement.remove()">Remove</button>
                </div>
            @endforeach
        </div>
        <button type="button" class="btn btn-add" onclick="addSkill()">+ Add Skill</button>
    </div>

    {{-- Programming --}}
    <div class="section" data-section="programming">
        <h2>Programming Languages</h2>
        <div id="programming-container">
            @php
                $progKey = old('programming.key', $oldProgrammingKey ?? []);
                $progValue = old('programming.value', $oldProgrammingValue ?? []);
            @endphp
            @foreach($progKey as $i => $lang)
                <div class="array-input">
                    <input type="text" name="programming[key][]" value="{{ $lang }}" placeholder="Language" oninput="toggleNextInput(this)" maxlength="15">
                    <input type="text" name="programming[value][]" value="{{ $progValue[$i] ?? '' }}" placeholder="Description" @if(empty($lang)) disabled @endif maxlength="50">
                    <button type="button" class="btn btn-remove" onclick="this.parentElement.remove()">Remove</button>
                </div>
            @endforeach
        </div>
        <button type="button" class="btn btn-add" onclick="addProgramming()">+ Add Programming</button>
    </div>

    {{-- Projects --}}
    <div class="section" data-section="projects">
        <h2>Projects</h2>
        <div id="projects-container">
            @foreach(old('projects', $oldProjects ?? []) as $project)
                <div class="array-input">
                    <input type="text" name="projects[]" value="{{ $project }}">
                    <button type="button" class="btn btn-remove" onclick="this.parentElement.remove()">Remove</button>
                </div>
            @endforeach
        </div>
        <button type="button" class="btn btn-add" onclick="addInput('projects-container','projects[]')">+ Add Project</button>
    </div>

    {{-- Awards --}}
    <div class="section" data-section="awards">
        <h2>Awards & Recognitions</h2>
        <div id="awards-container">
            @php $awardsList = $awards ?? []; @endphp
            @if(!empty($awardsList) && count($awardsList))
                @foreach($awardsList as $award)
                    <div class="array-input">
                        <input type="text" name="awards[name][]" value="{{ $award->award_name ?? '' }}" placeholder="Award Name">
                        <input type="file" name="awards[file][]" accept=".pdf,.jpg,.png" class="award-file-input">
                        @if(!empty($award->file_path))
                            <input type="hidden" name="awards[existing_file][]" value="{{ $award->file_path }}">
                            <button type="button" class="btn btn-add view-certificate-btn" data-file="{{ asset('storage/'.$award->file_path) }}">View Certificate</button>
                        @else
                            <input type="hidden" name="awards[existing_file][]" value="">
                            <button type="button" class="btn btn-add view-certificate-btn" style="display:none;">View Certificate</button>
                        @endif
                        <button type="button" class="btn btn-remove" onclick="this.parentElement.remove()">Remove</button>
                    </div>
                @endforeach
            @else
                <div class="array-input">
                    <input type="text" name="awards[name][]" placeholder="Award Name">
                    <input type="file" name="awards[file][]" accept=".pdf,.jpg,.png" class="award-file-input">
                    <input type="hidden" name="awards[existing_file][]" value="">
                    <button type="button" class="btn btn-add view-certificate-btn" style="display:none;">View Certificate</button>
                    <button type="button" class="btn btn-remove" onclick="this.parentElement.remove()">Remove</button>
                </div>
            @endif
        </div>

        <div style="margin-top:10px;">
            <button id="add-award-btn" type="button" class="btn btn-add" onclick="addAward()">+ Add Award</button>
        </div>
    </div>

    <div style="text-align:center; margin-top:40px;">
        <button id="save-btn" type="submit" class="btn btn-save">💾 {{ $isNew ? 'Create Resume' : 'Save Changes' }}</button>
    </div>

    </form>
</div>

<!-- Cropper modal -->
<div id="cropper-modal">
    <div id="cropper-container">
        <img id="cropper-image" alt="Cropper Image">
        <div style="margin-top:10px; display:flex; justify-content:center; gap:10px;">
            <button id="crop-btn" type="button" class="btn btn-save">Apply</button>
            <button id="cancel-btn" type="button" class="btn btn-remove">Cancel</button>
        </div>
    </div>
</div>

<!-- File preview modal -->
<div id="file-modal" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="fm-content">
        <button id="file-modal-close" type="button" class="btn btn-remove" aria-label="Close certificate preview">Close</button>
        <div class="fm-body" id="file-modal-body"></div>
    </div>
</div>

<!-- Unsaved changes modal -->
<div id="unsavedModal" aria-hidden="true">
    <div class="card" role="dialog" aria-labelledby="unsavedTitle">
        <h3 id="unsavedTitle">You have unsaved changes</h3>
        <p>Are you sure you want to leave this page? Your changes will be lost.</p>
        <div class="actions">
            <button id="leaveBtn" class="btn btn-remove">Leave</button>
            <button id="stayBtn" class="btn btn-save">Stay & Save</button>
        </div>
    </div>
</div>

<!-- Max toast -->
<div id="max-toast" aria-live="polite" role="status"></div>

<script src="https://cdn.jsdelivr.net/npm/cropperjs@1.5.13/dist/cropper.min.js"></script>
<script>
/* ============================
   JS: cropper, dynamic inputs,
   file preview, unsaved-changes
   ============================ */

let cropper = null;
const input = document.getElementById('profile-input');
const preview = document.getElementById('profile-preview');
const modal = document.getElementById('cropper-modal');
const cropperImage = document.getElementById('cropper-image');
const croppedInput = document.getElementById('cropped_image');
const resumeForm = document.getElementById('resume-form');
const STORAGE_BASE = "{{ asset('storage') }}";

// isDirty tracking
let isDirty = false;
function markDirty(){ isDirty = true; toggleSaveVisual(true); }

// visual hint on save-button when dirty (subtle)
function toggleSaveVisual(active){
    const btn = document.getElementById('save-btn');
    if(!btn) return;
    if(active) btn.style.boxShadow = '0 18px 46px rgba(31,138,47,0.16)';
    else btn.style.boxShadow = '';
}

// Prevent accidental enter submit
if(resumeForm){
  resumeForm.addEventListener('keydown', function(e){
      const tag = e.target.tagName.toLowerCase();
      const type = e.target.type ? e.target.type.toLowerCase() : '';
      if(e.key === 'Enter' && tag !== 'textarea' && type !== 'submit' && type !== 'button') {
          e.preventDefault();
      }
  });
}

/* ----------------- change tracking ----------------- */
document.addEventListener('input', function(e){
    if(!e.target) return;
    if(!resumeForm.contains(e.target)) return;
    const tag = e.target.tagName.toLowerCase();
    if(tag === 'input' || tag === 'textarea' || tag === 'select') {
        markDirty();
    }
});
document.addEventListener('change', function(e){
    if(!e.target) return;
    if(!resumeForm.contains(e.target)) return;
    if(e.target.tagName.toLowerCase() === 'input' && e.target.type === 'file') markDirty();
});

/* ----------------- beforeunload prompt ----------------- */
window.addEventListener('beforeunload', function(e){
    if(!isDirty) return;
    e.preventDefault();
    e.returnValue = '';
    return '';
});

/* ----------------- Back button: intercept if dirty ----------------- */
const backBtn = document.getElementById('backBtn');
const unsavedModal = document.getElementById('unsavedModal');
const leaveBtn = document.getElementById('leaveBtn');
const stayBtn = document.getElementById('stayBtn');

if(backBtn){
    backBtn.addEventListener('click', function(ev){
        if(isDirty){
            ev.preventDefault();
            showUnsavedModal();
            unsavedModal.dataset.target = this.getAttribute('href');
            return false;
        }
    });
}
function showUnsavedModal(){
    if(!unsavedModal) return;
    unsavedModal.style.display = 'flex';
    unsavedModal.setAttribute('aria-hidden','false');
}
function hideUnsavedModal(){
    if(!unsavedModal) return;
    unsavedModal.style.display = 'none';
    unsavedModal.setAttribute('aria-hidden','true');
}
if(leaveBtn){
    leaveBtn.addEventListener('click', function(){
        const target = unsavedModal.dataset.target || "{{ route('home') }}";
        hideUnsavedModal();
        isDirty = false;
        window.location.href = target;
    });
}
if(stayBtn){
    stayBtn.addEventListener('click', function(){
        hideUnsavedModal();
        const first = resumeForm.querySelector('input, textarea, select');
        if(first) first.focus();
    });
}

/* Clear dirty when form submits */
if(resumeForm){
    resumeForm.addEventListener('submit', function(){
        isDirty = false;
        const sb = document.getElementById('successBanner');
        if(sb){
            sb.textContent = 'Saving...';
            sb.style.display = 'block';
        }
    });
}

/* ---------------------------------------
   Profile cropper
   --------------------------------------- */
if(input){
  input.addEventListener('change', function(e){
      const file = e.target.files[0];
      if(!file) return;
      const url = URL.createObjectURL(file);
      cropperImage.src = url;
      modal.style.display = 'flex';
      modal.setAttribute('aria-hidden','false');
      if(cropper) cropper.destroy();
      cropper = new Cropper(cropperImage, { aspectRatio: 1, viewMode: 1, autoCropArea:1 });
  });
}

document.getElementById('crop-btn').addEventListener('click', function(){
    if(!cropper){ modal.style.display='none'; return; }
    const canvas = cropper.getCroppedCanvas({ width: 300, height: 300 });
    preview.src = canvas.toDataURL('image/png');
    croppedInput.value = canvas.toDataURL('image/png');
    modal.style.display = 'none';
    modal.setAttribute('aria-hidden','true');
    if(cropperImage.src.startsWith('blob:')) { try{ URL.revokeObjectURL(cropperImage.src); } catch(e){} }
    cropper.destroy(); cropper = null;
    markDirty();
});

document.getElementById('cancel-btn').addEventListener('click', () => {
    modal.style.display = 'none';
    modal.setAttribute('aria-hidden','true');
    if(cropper){ cropper.destroy(); cropper = null; }
    try{ document.getElementById('profile-input').value = ''; } catch(e){}
});

/* ---------------------------------------
   Section filter logic
   --------------------------------------- */
const filter = document.getElementById('section-filter');
if(filter){
  filter.addEventListener('change', function(){
      const val = this.value;
      document.querySelectorAll('.section').forEach(sec => {
          if(val === 'all' || sec.dataset.section === val || (val === '' && (sec.dataset.section === 'profile' || sec.dataset.section === 'personal'))) {
              sec.classList.add('visible');
          } else {
              sec.classList.remove('visible');
          }
      });
  });
}

/* ---------------------------------------
   Dynamic helpers (kept) plus markDirty after add
   --------------------------------------- */
function addInput(id,name){
    const c=document.getElementById(id);
    const d=document.createElement('div');
    d.className='array-input';
    d.innerHTML=`<input type="text" name="${name}" placeholder="Enter value">\n    <button type="button" class="btn btn-remove" onclick="this.parentElement.remove()">Remove</button>`;
    c.appendChild(d);
    markDirty();
}
function addOrganization(){
    const c=document.getElementById('organization-container');
    const d=document.createElement('div');
    d.className='array-input';
    d.innerHTML=`<input type="text" name="organization[name][]" placeholder="Organization Name">\n    <input type="text" name="organization[position][]" placeholder="Position">\n    <input type="text" name="organization[year][]" placeholder="Year">\n    <button type="button" class="btn btn-remove" onclick="this.parentElement.remove()">Remove</button>`;
    c.appendChild(d);
    markDirty();
}
function addSkill(){
    const c=document.getElementById('skills-container');
    const d=document.createElement('div');
    d.className='array-input';
    d.innerHTML=`<input type="text" name="skills[key][]" placeholder="Skill" oninput="toggleNextInput(this)" maxlength="20">\n    <input type="text" name="skills[value][]" placeholder="Description" disabled maxlength="100">\n    <button type="button" class="btn btn-remove" onclick="this.parentElement.remove()">Remove</button>`;
    c.appendChild(d);
    attachMaxWatcherToRow(d);
    markDirty();
}
function addProgramming(){
    const c=document.getElementById('programming-container');
    const d=document.createElement('div');
    d.className='array-input';
    d.innerHTML=`<input type="text" name="programming[key][]" placeholder="Language" oninput="toggleNextInput(this)" maxlength="15">\n    <input type="text" name="programming[value][]" placeholder="Description" disabled maxlength="50">\n    <button type="button" class="btn btn-remove" onclick="this.parentElement.remove()">Remove</button>`;
    c.appendChild(d);
    attachMaxWatcherToRow(d);
    markDirty();
}
function addAward(){
    const container = document.getElementById('awards-container');
    const div = document.createElement('div');
    div.classList.add('array-input');
    div.innerHTML = `
        <input type="text" name="awards[name][]" placeholder="Award Name">
        <input type="file" name="awards[file][]" accept=".pdf,.jpg,.png" class="award-file-input">
        <input type="hidden" name="awards[existing_file][]" value="">
        <button type="button" class="btn btn-add view-certificate-btn" style="display:none;">View Certificate</button>
        <button type="button" class="btn btn-remove" onclick="this.parentElement.remove()">Remove</button>
    `;
    container.appendChild(div);
    markDirty();
}

/* ---------------------------------------
   Awards file preview logic (kept)
   --------------------------------------- */
const fileModal = document.getElementById('file-modal');
const fileModalBody = document.getElementById('file-modal-body');
const fileCloseBtn = document.getElementById('file-modal-close');

function openFilePreview(url, mimeType = '') {
    if(!fileModalBody) return;
    fileModalBody.innerHTML = '';
    const isPdf = (mimeType === 'application/pdf') || url.split('?')[0].toLowerCase().endsWith('.pdf');
    if (isPdf) {
        const iframe = document.createElement('iframe');
        iframe.src = url;
        iframe.style.minHeight = '480px';
        iframe.style.border = 'none';
        fileModalBody.appendChild(iframe);
    } else {
        const img = document.createElement('img');
        img.src = url;
        img.alt = 'Certificate preview';
        fileModalBody.appendChild(img);
    }
    if(fileModal){
      fileModal.style.display = 'flex';
      fileModal.setAttribute('aria-hidden','false');
    }
}

document.addEventListener('click', function(e){
    if(e.target.matches('.view-certificate-btn')) {
        const btn = e.target;
        const blobUrl = btn.dataset.blobUrl;
        const blobType = btn.dataset.blobType || '';
        const storedUrl = btn.getAttribute('data-file');

        if(blobUrl) { openFilePreview(blobUrl, blobType); return; }
        if(storedUrl) { openFilePreview(storedUrl, ''); return; }
    }
});

document.addEventListener('change', function(e){
    if(e.target && e.target.matches('.award-file-input')) {
        const input = e.target;
        const file = input.files && input.files[0];
        const row = input.closest('.array-input');
        if(!row) return;
        let vcBtn = row.querySelector('.view-certificate-btn');

        if(vcBtn && vcBtn.dataset.blobUrl) {
            try { URL.revokeObjectURL(vcBtn.dataset.blobUrl); } catch(err) {}
            delete vcBtn.dataset.blobUrl;
        }

        if(!file) {
            if(vcBtn) {
                const existingHidden = row.querySelector('input[type="hidden"][name^="awards[existing_file]"]');
                if(existingHidden && existingHidden.value) {
                    let val = existingHidden.value;
                    if(!/^https?:\/\//i.test(val)) { val = STORAGE_BASE.replace(/\/$/, '') + '/' + val.replace(/^\/+/, ''); }
                    vcBtn.setAttribute('data-file', val);
                    vcBtn.style.display = 'inline-block';
                } else {
                    vcBtn.removeAttribute('data-file');
                    vcBtn.style.display = 'none';
                }
            }
            return;
        }

        const blobUrl = URL.createObjectURL(file);
        if(!vcBtn) {
            vcBtn = document.createElement('button');
            vcBtn.type = 'button';
            vcBtn.className = 'btn btn-add view-certificate-btn';
            vcBtn.textContent = 'View Certificate';
            input.insertAdjacentElement('afterend', vcBtn);
        }
        vcBtn.dataset.blobUrl = blobUrl;
        vcBtn.dataset.blobType = file.type || '';
        vcBtn.removeAttribute('data-file');
        vcBtn.style.display = 'inline-block';
        markDirty();
    }
});

if(fileCloseBtn){
  fileCloseBtn.addEventListener('click', () => {
      if(fileModal){
        fileModal.style.display = 'none';
        fileModal.setAttribute('aria-hidden','true');
      }
      document.querySelectorAll('.view-certificate-btn').forEach(b => {
          if(b.dataset && b.dataset.blobUrl) {
              try { URL.revokeObjectURL(b.dataset.blobUrl); } catch(err) {}
              delete b.dataset.blobUrl;
              delete b.dataset.blobType;
          }
      });
      if(fileModalBody) fileModalBody.innerHTML = '';
  });
}

/* ESC to close modals */
document.addEventListener('keydown', function(e){
    if(e.key === 'Escape') {
        if(modal && modal.style.display === 'flex') {
            modal.style.display = 'none';
            modal.setAttribute('aria-hidden','true');
            if(cropper){ cropper.destroy(); cropper=null; }
        }
        if(fileModal && fileModal.style.display === 'flex') {
            fileModal.style.display = 'none';
            fileModal.setAttribute('aria-hidden','true');
            document.querySelectorAll('.view-certificate-btn').forEach(b => {
                if(b.dataset && b.dataset.blobUrl) {
                    try { URL.revokeObjectURL(b.dataset.blobUrl); } catch(err) {}
                    delete b.dataset.blobUrl;
                }
            });
            if(fileModalBody) fileModalBody.innerHTML = '';
        }
    }
});

/* --------------------------------
   maxlength toast (kept)
   -------------------------------- */
const toast = document.getElementById('max-toast');
let toastTimer = null;
function showMaxToast(msg){
    if(!toast) return;
    toast.textContent = msg;
    toast.classList.add('show');
    if(toastTimer) clearTimeout(toastTimer);
    toastTimer = setTimeout(()=> { toast.classList.remove('show'); toastTimer = null; }, 1600);
}
function attachMaxWatcherToRow(row){
    if(!row) return;
    const inputs = Array.from(row.querySelectorAll('input[type="text"]'));
    inputs.forEach(inp => {
        if(!inp.hasAttribute('maxlength')) return;
        if(inp._maxlengthWatcherAttached) return;
        inp._maxlengthWatcherAttached = true;
        inp.addEventListener('input', function(){
            const max = parseInt(inp.getAttribute('maxlength') || '0', 10);
            if(max > 0 && inp.value.length >= max){
                if(!inp.dataset._notified){ inp.dataset._notified = '1'; showMaxToast(`Maximum length reached (${max})`); }
            } else {
                if(inp.dataset._notified) delete inp.dataset._notified;
            }
        });
        inp.addEventListener('paste', function(){
            setTimeout(()=> {
                const max = parseInt(inp.getAttribute('maxlength') || '0', 10);
                if(max > 0 && inp.value.length >= max){
                    if(!inp.dataset._notified){ inp.dataset._notified = '1'; showMaxToast(`Maximum length reached (${max})`); }
                }
            }, 50);
        });
    });
}
function initMaxLengthWatcher(){
    document.querySelectorAll('.array-input').forEach(row => attachMaxWatcherToRow(row));
    document.querySelectorAll('input[type="text"][maxlength]').forEach(inp => {
        const parentRow = inp.closest('.array-input');
        if(parentRow) attachMaxWatcherToRow(parentRow);
        else attachMaxWatcherToRow(document.createElement('div'));
    });
}
document.addEventListener('DOMContentLoaded', function(){
    document.querySelectorAll('.array-input').forEach(row => {
        const textInputs = Array.from(row.querySelectorAll('input[type="text"]'));
        if(textInputs.length >= 2){
            textInputs[1].disabled = textInputs[0].value.trim() === '';
        }
        if(textInputs.length >= 2 && textInputs[1].value.trim() !== '' && textInputs[0].value.trim() === ''){
            textInputs[1].disabled = false;
        }
    });
    initMaxLengthWatcher();
});

/* toggle next input */
function toggleNextInput(el){
    if(!el) return;
    const row = el.closest('.array-input');
    if(!row) return;
    const textInputs = Array.from(row.querySelectorAll('input[type="text"]'));
    const idx = textInputs.indexOf(el);
    if(idx >= 0 && textInputs[idx+1]){
        textInputs[idx+1].disabled = el.value.trim() === '';
    }
}

/* observe containers to attach watchers on newly added rows */
['skills-container','programming-container','interests-container','projects-container','organization-container','awards-container'].forEach(id => {
    const el = document.getElementById(id);
    if(!el) return;
    const mo = new MutationObserver((mutations) => {
        for(const m of mutations){
            if(m.addedNodes && m.addedNodes.length){
                m.addedNodes.forEach(n => {
                    if(n.nodeType === 1) attachMaxWatcherToRow(n);
                });
            }
        }
    });
    mo.observe(el, { childList: true });
});
</script>
</body>
</html>
