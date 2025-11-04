@extends('layout')

@section('title', 'Edit Resume | Irish Rivera')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/cropperjs@1.5.13/dist/cropper.min.css" rel="stylesheet"/>

<style>
    /* ===== Base ===== */
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap');

    :root{
        --accent-pink: #ff1f84;
        --accent-pink-2: #ff66a3;
        --muted: #9aa1a8;
        --soft-white: #fbfbff;
        --danger: #e74c3c;
    }

    body {
        font-family: "Poppins", sans-serif;
        background-color: #f9f9f9; 
        transition: background 0.3s, color 0.3s;
        -webkit-font-smoothing:antialiased;
        -moz-osx-font-smoothing:grayscale;
    }
    body.dark {
        background-color: #1b1b1b;
        color: #f5f5f5;
    }

    .container {
        max-width: 900px;
        margin: 40px auto;
        position: relative;
        overflow: visible;
        border-radius: 18px;
        padding: 34px;
        color: #d70b67ff;
        transition: background 0.3s, color 0.3s, transform 0.12s;
        border: 1px solid rgba(16,24,40,0.04);

        background: linear-gradient(135deg, rgba(212, 170, 185, 0.95) 0%, rgba(248, 198, 217, 0.9) 50%, rgba(255,241,245,0.95) 100%);
        box-shadow: 0 18px 40px rgba(16,24,40,0.06), inset 0 -6px 24px rgba(255,31,132,0.01);
        animation: fadeInUp 600ms ease both;
    }

    .container:hover { transform: translateY(-6px); box-shadow: 0 28px 50px rgba(16,24,40,0.08); }

    body.dark .container {
        background: linear-gradient(135deg, rgba(97, 94, 95, 0.6) 0%, rgba(119, 109, 119, 0.6) 60%);
        box-shadow: 0 18px 40px rgba(0,0,0,0.6);
        border: 1px solid rgba(255,255,255,0.03);
    }

    /* Floating decorative bubbles inside container */
    .bubble {
        position: absolute;
        border-radius: 50%;
        opacity: 0.14;
        filter: blur(6px);
        pointer-events: none;
        transform: translate3d(0,0,0);
        mix-blend-mode: screen;
        animation: bubbleFloat linear infinite;
    }

    /* different sizes & positions */
    .bubble.b1 { width: 120px; height: 120px; left: 18%; top: 6%; background: radial-gradient(circle at 30% 30%, rgba(255,31,132,0.18), rgba(255,102,163,0.06)); animation-duration: 12s; }
    .bubble.b2 { width: 90px; height: 90px; right: 6%; top: 18%; background: radial-gradient(circle at 30% 30%, rgba(255,102,163,0.14), rgba(255,31,132,0.04)); animation-duration: 10s; }
    .bubble.b3 { width: 60px; height: 60px; left: 8%; bottom: 12%; background: radial-gradient(circle at 30% 30%, rgba(255,31,132,0.12), rgba(255,102,163,0.03)); animation-duration: 8s; }
    .bubble.b4 { width: 40px; height: 40px; right: 22%; bottom: 6%; background: radial-gradient(circle at 30% 30%, rgba(255,31,132,0.10), rgba(255,102,163,0.02)); animation-duration: 14s; }
    .bubble.b5 { width: 180px; height: 180px; left: 50%; top: -30px; background: radial-gradient(circle at 30% 30%, rgba(255,31,132,0.06), rgba(255,102,163,0.02)); animation-duration: 18s; }

    body.dark .bubble { opacity: 0.12; filter: blur(8px); }

    @keyframes bubbleFloat {
        0% { transform: translateY(0) translateX(0) scale(1); }
        50% { transform: translateY(-14px) translateX(6px) scale(1.03); }
        100% { transform: translateY(0) translateX(0) scale(1); }
    }

    /* ===== Headings & small touches ===== */
    h1 {
        color: #d41467ff;
        text-align: center;
        font-weight: 700;
        margin-bottom: 12px;
        text-shadow: 0 6px 20px rgba(223, 52, 100, 0.71);
        letter-spacing: -0.3px;
    }
    body.dark h1 { color: #ff66a3; }

    .subtitle { text-align:center; color: #fffff; margin-bottom: 18px; }

    label {
        font-weight: 600;
        margin-top: 10px;
        display: block;
    }

    /* ===== Inputs ===== (kept same logic, just look) */
    input[type="text"], input[type="email"], textarea, select {
        width: 100%;
        padding: 10px 12px;
        margin: 5px 0 15px 0;
        border-radius: 10px;
        border: 1px solid rgba(16,24,40,0.06);
        transition: all 0.18s;
        font-size: 0.95rem;
        background: #fff;
        box-shadow: inset 0 -2px 8px rgba(16,24,40,0.02);
    }
    body.dark input, body.dark textarea, body.dark select {
        background: #151515;
        color: #e9eef2;
        border: 1px solid rgba(255,255,255,0.04);
        box-shadow: none;
    }

    input::placeholder, textarea::placeholder { color: #bfc6cc; }

    input:focus, textarea:focus, select:focus {
        border-color: var(--accent-pink);
        outline: none;
        box-shadow: 0 10px 30px rgba(255,31,132,0.10);
        transform: translateY(-1px);
    }

    textarea { min-height: 80px; resize:vertical; }

    /* ===== Section cards (soft separators + hover glow) ===== */
    .section {
        margin-bottom: 22px;
        padding: 18px;
        border-radius: 12px;
        background-color: rgba(255,255,255,0.95); /* still slightly tinted */
        box-shadow: 0 8px 26px rgba(16,24,40,0.04);
        display: none;
        opacity: 0;
        transform: translateY(8px);
        transition: opacity 0.36s ease, transform 0.36s ease, box-shadow 0.18s ease;
        border-top: 1px solid rgba(16,24,40,0.03);
    }
    body.dark .section {
        background-color: #2c2c2c;
        border-top: 1px solid rgba(255,255,255,0.02);
        box-shadow: 0 8px 26px rgba(0,0,0,0.6);
    }
    .section.visible {
        display:block;
        opacity:1;
        transform: translateY(0);
        animation: fadeIn 420ms ease both;
    }

    /* hover glow for the section */
    .section:hover {
        box-shadow: 0 18px 40px rgba(255,31,132,0.06);
        border-top-color: rgba(255,31,132,0.04);
    }
    body.dark .section:hover {
        box-shadow: 0 18px 40px rgba(255,66,130,0.06);
        border-top-color: rgba(255,66,163,0.03);
    }

    .section h2 { color: #c8205eff; margin-top:0; }
    body.dark .section h2 { color: #f3f3f3; }

    /* array input layout */
    .array-input {
        display:flex;
        gap:10px;
        margin-bottom: 8px;
        flex-wrap:wrap;
        align-items:center;
    }
    .array-input input { flex:1; min-width:140px; }

    /* ===== Buttons: improved gradients + shadow glow ===== */
    .btn {
        display:inline-flex;
        align-items:center;
        gap:8px;
        padding:10px 18px;
        border-radius:10px;
        text-decoration:none;
        font-weight:600;
        border:none;
        cursor:pointer;
        color:white;
        transition: transform 0.12s ease, box-shadow 0.12s ease, opacity 0.12s;
    }
    .btn:hover { transform: translateY(-3px); }

    .btn-save {
        background: linear-gradient(90deg, var(--accent-pink), var(--accent-pink-2));
        box-shadow: 0 12px 36px rgba(255,31,132,0.14), 0 6px 20px rgba(255,102,163,0.06);
    }
    .btn-save:active { transform: translateY(-1px) scale(0.995); }

    .btn-add {
        background: linear-gradient(90deg, #ff8fcf, #ff4da6);
        box-shadow: 0 10px 30px rgba(255,77,166,0.10);
    }

    .btn-remove {
        background: linear-gradient(90deg, #ff7b7b, #e74c3c);
        box-shadow: 0 10px 28px rgba(231,76,60,0.10);
    }

    /* small muted helper */
    .small-muted { font-size:0.9rem; color:var(--muted); }

    /* profile preview */
    #profile-preview {
        width:150px;
        height:150px;
        border-radius:50%;
        object-fit:cover;
        border:3px solid var(--accent-pink);
        box-shadow: 0 12px 36px rgba(255,31,132,0.08);
        margin-bottom:10px;
    }
    body.dark #profile-preview { border-color: rgba(255,102,163,0.16); box-shadow: 0 12px 36px rgba(0,0,0,0.6); }

    /* section-filter style */
    #section-filter {
        font-size:1rem;
        padding:12px;
        margin-bottom:20px;
        background-color:#fff;
        border-radius:10px;
        border:1px solid rgba(16,24,40,0.05);
    }
    body.dark #section-filter { background:#171717; border:1px solid rgba(255,255,255,0.03); color:#e9eef2; }

    /* Cropper modal styling kept, but tuned for glow */
    #cropper-modal {
        display: none;
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0,0,0,0.72);
        justify-content: center;
        align-items: center;
        z-index: 9999;
        padding: 28px;
    }
    #cropper-container {
        background: white;
        padding: 20px;
        border-radius: 10px;
        width: 420px;
        max-width: 95%;
        display:flex;
        flex-direction:column;
        align-items:center;
        box-shadow: 0 22px 48px rgba(16,24,40,0.16);
        border:1px solid rgba(16,24,40,0.05);
    }
    body.dark #cropper-container { background:#222; border:1px solid rgba(255,255,255,0.03); box-shadow: 0 22px 48px rgba(0,0,0,0.6); }
    #cropper-image { max-width:100%; max-height:320px; border-radius:6px; }

    /* File preview modal (for awards) */
    #file-modal {
        display: none;
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0,0,0,0.72);
        justify-content: center;
        align-items: center;
        z-index: 10000;
        padding: 20px;
    }
    #file-modal .fm-content {
        position: relative;
        width: 80%;
        max-width: 900px;
        background: #fff;
        border-radius: 12px;
        padding: 12px;
        box-shadow: 0 24px 60px rgba(0,0,0,0.5);
        display:flex;
        flex-direction:column;
        gap:10px;
        overflow: hidden;
    }
    #file-modal .fm-body { flex:1; min-height:300px; display:flex; align-items:center; justify-content:center; }
    /* Make images fit without scrollbars */
    #file-modal img {
        width: 100%;
        height: auto;
        max-height: 80vh;
        object-fit: contain;
        display: block;
    }
    /* PDF iframe sizing */
    #file-modal iframe {
        width: 100%;
        height: 80vh;
        border: none;
    }
    /* top-right close button */
    #file-modal-close {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 101;
        border-radius: 8px;
        padding: 8px 12px;
    }
    /* hide bottom actions (we use the top-right button) */
    #file-modal .fm-actions { display: none; }

    /* fade and lift animations */
    @keyframes fadeInUp {
        0% { opacity: 0; transform: translateY(14px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeIn {
        0% { opacity:0; transform: translateY(8px); }
        100% { opacity:1; transform: translateY(0); }
    }

    /* Toast for maxlength reached */
    #max-toast {
        position: fixed;
        left: 50%;
        transform: translateX(-50%) translateY(12px);
        bottom: 22px;
        background: rgba(32,32,32,0.94);
        color: #fff;
        padding: 10px 14px;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.4);
        z-index: 11000;
        font-weight: 600;
        opacity: 0;
        pointer-events: none;
        transition: opacity .18s ease, transform .18s ease;
    }
    #max-toast.show {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
        pointer-events: auto;
    }

    /* Respect reduced motion */
    @media (prefers-reduced-motion: reduce) {
        .container, .section, .bubble { animation: none !important; transition: none !important; transform: none !important; }
    }

    /* Responsive tweaks */
    @media (max-width:880px){
        .container { margin:24px 18px; padding:20px; }
    }
    @media (max-width:560px){
        .array-input { flex-direction:column; align-items:stretch; }
    }
</style>

<div class="container">
    <!-- decorative bubbles inside the container (purely visual) -->
    <div class="bubble b1" aria-hidden="true"></div>
    <div class="bubble b2" aria-hidden="true"></div>
    <div class="bubble b3" aria-hidden="true"></div>
    <div class="bubble b4" aria-hidden="true"></div>
    <div class="bubble b5" aria-hidden="true"></div>

    <h1>Edit Your Resume</h1>
    <p class="subtitle">Update your details — profile, education, skills, projects and more.</p>

    @if(session('success'))
        <p style="color:green;">{{ session('success') }}</p>
    @endif

    <form id="resume-form" action="{{ route('resume.update', ['id' => $resume->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Profile + Personal Info always visible --}}
        <div class="section visible" data-section="profile">
            <h2>Profile Picture</h2>
            <img id="profile-preview" src="{{ $resume->profile_photo ? asset('storage/'.$resume->profile_photo) : 'https://via.placeholder.com/150' }}" alt="Profile Preview">
            <input type="file" id="profile-input" accept="image/*">

            {{-- Hidden input that contains the cropped image as data URL. Controller should detect and save if present on final form submit. --}}
            <input type="hidden" name="cropped_image" id="cropped_image" value="">
        </div>

        <div class="section visible" data-section="personal">
            <h2>Personal Information</h2>
            <label>Full Name</label>
            <input type="text" name="fullname" value="{{ $resume->fullname ?? '' }}" required>

            <label>Date of Birth</label>
            <input type="text" name="dob" value="{{ $resume->dob ?? '' }}">

            <label>Place of Birth</label>
            <input type="text" name="pob" value="{{ $resume->pob ?? '' }}">

            <label>Civil Status</label>
            <input type="text" name="civil_status" value="{{ $resume->civil_status ?? '' }}">

            <label>Field of Specialization</label>
            <input type="text" name="specialization" value="{{ $resume->specialization ?? '' }}">

            <label>Email</label>
            <input type="email" name="email" value="{{ $resume->email ?? '' }}">

            <label>Phone</label>
            <input type="text" name="phone" value="{{ $resume->phone ?? '' }}">

            <label>Address</label>
            <input type="text" name="address" value="{{ $resume->address ?? '' }}">
        </div>

        {{-- Section Filter --}}
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

        {{-- ========== All other sections ========== --}}
        <div class="section" data-section="organization">
            <h2>Organization</h2>
            <div id="organization-container">
                @php $org = $resume->organization ?? []; @endphp
                @if(!empty($org['name']))
                    @foreach($org['name'] as $i => $name)
                        <div class="array-input">
                            <input type="text" name="organization[name][]" value="{{ $name }}" placeholder="Organization Name">
                            <input type="text" name="organization[position][]" value="{{ $org['position'][$i] ?? '' }}" placeholder="Position">
                            <input type="text" name="organization[year][]" value="{{ $org['year'][$i] ?? '' }}" placeholder="Year">
                            <button type="button" class="btn btn-remove" onclick="this.parentElement.remove()">Remove</button>
                        </div>
                    @endforeach
                @endif
            </div>
            <button type="button" class="btn btn-add" onclick="addOrganization()" data-section="organization">+ Add Organization</button>
        </div>

        <div class="section" data-section="education">
            <h2>Education</h2>
            @php $edu = $resume->education ?? []; @endphp
            @foreach(['Elementary','Secondary','Tertiary'] as $level)
                <div class="array-input">
                    <input type="text" name="education[{{ $level }}][school]" value="{{ $edu[$level]['school'] ?? '' }}" placeholder="{{ $level }} School" oninput="toggleNextInput(this)">
                    <input type="text" name="education[{{ $level }}][year]" value="{{ $edu[$level]['year'] ?? '' }}" placeholder="Year" @if(empty($edu[$level]['school'])) disabled @endif>
                </div>
            @endforeach
        </div>

        <div class="section" data-section="interests">
            <h2>Field of Interest</h2>
            <div id="interests-container">
                @foreach($resume->interests ?? [] as $interest)
                    <div class="array-input">
                        <input type="text" name="interests[]" value="{{ $interest }}" maxlength="30" placeholder="Interest (max 30 chars)">
                        <button type="button" class="btn btn-remove" onclick="this.parentElement.remove()">Remove</button>
                    </div>
                @endforeach
            </div>
            <button type="button" class="btn btn-add" onclick="addInput('interests-container','interests[]')" data-section="interests">+ Add Interest</button>
        </div>

        <div class="section" data-section="skills">
            <h2>Skills</h2>
            <div id="skills-container">
                @foreach($resume->skills['key'] ?? [] as $i => $skill)
                    <div class="array-input">
                        <input type="text" name="skills[key][]" value="{{ $skill }}" placeholder="Skill" oninput="toggleNextInput(this)" maxlength="20">
                        <input type="text" name="skills[value][]" value="{{ $resume->skills['value'][$i] ?? '' }}" placeholder="Description" @if(empty($skill)) disabled @endif maxlength="100">
                        <button type="button" class="btn btn-remove" onclick="this.parentElement.remove()">Remove</button>
                    </div>
                @endforeach
            </div>
            <button type="button" class="btn btn-add" onclick="addSkill()" data-section="skills">+ Add Skill</button>
        </div>

        <div class="section" data-section="programming">
            <h2>Programming Languages</h2>
            <div id="programming-container">
                @foreach($resume->programming['key'] ?? [] as $i => $lang)
                    <div class="array-input">
                        <input type="text" name="programming[key][]" value="{{ $lang }}" placeholder="Language" oninput="toggleNextInput(this)" maxlength="15">
                        <input type="text" name="programming[value][]" value="{{ $resume->programming['value'][$i] ?? '' }}" placeholder="Description" @if(empty($lang)) disabled @endif maxlength="50">
                        <button type="button" class="btn btn-remove" onclick="this.parentElement.remove()">Remove</button>
                    </div>
                @endforeach
            </div>
            <button type="button" class="btn btn-add" onclick="addProgramming()" data-section="programming">+ Add Programming</button>
        </div>

        <div class="section" data-section="projects">
            <h2>Projects</h2>
            <div id="projects-container">
                @foreach($resume->projects ?? [] as $project)
                    <div class="array-input">
                        <input type="text" name="projects[]" value="{{ $project }}">
                        <button type="button" class="btn btn-remove" onclick="this.parentElement.remove()">Remove</button>
                    </div>
                @endforeach
            </div>
            <button type="button" class="btn btn-add" onclick="addInput('projects-container','projects[]')" data-section="projects">+ Add Project</button>
        </div>

        {{-- Awards section with Add Award inside the white container --}}
        <div class="section" data-section="awards">
            <h2>Awards & Recognitions</h2>
            <div id="awards-container">
                @php
                    $awards = $resume->awardFiles ?? collect();
                @endphp

            @forelse($awards as $award)
                <div class="array-input">
                    {{-- Award Name --}}
                    <input type="text" name="awards[name][]" value="{{ $award->award_name ?? '' }}" placeholder="Award Name" required>

                    {{-- Certificate Upload --}}
                    <input type="file" name="awards[file][]" accept=".pdf,.jpg,.png" class="award-file-input">

                    {{-- Preserve existing file path so controller can keep it if no new file is uploaded --}}
                    @if(!empty($award->file_path))
                        <input type="hidden" name="awards[existing_file][]" value="{{ $award->file_path }}">
                        {{-- View Certificate as a button that opens modal --}}
                        <button type="button" class="btn btn-add view-certificate-btn" data-file="{{ asset('storage/'.$award->file_path) }}">View Certificate</button>
                    @else
                        {{-- still keep hidden placeholder so arrays align --}}
                        <input type="hidden" name="awards[existing_file][]" value="">
                        {{-- create a placeholder view button that will be toggled by JS when user selects a file --}}
                        <button type="button" class="btn btn-add view-certificate-btn" style="display:none;">View Certificate</button>
                    @endif

                    {{-- Remove Button --}}
                    <button type="button" class="btn btn-remove" onclick="this.parentElement.remove()">Remove</button>
                </div>
            @empty
                {{-- Show one empty row if no awards --}}
                <div class="array-input">
                    <input type="text" name="awards[name][]" placeholder="Award Name" required>
                    <input type="file" name="awards[file][]" accept=".pdf,.jpg,.png" class="award-file-input">
                    <input type="hidden" name="awards[existing_file][]" value="">
                    <button type="button" class="btn btn-add view-certificate-btn" style="display:none;">View Certificate</button>
                    <button type="button" class="btn btn-remove" onclick="this.parentElement.remove()">Remove</button>
                </div>
            @endforelse
            </div>

            {{-- Add Award button INSIDE awards section (visible only when section shown) --}}
            <div style="margin-top:10px;">
                <button id="add-award-btn" type="button" class="btn btn-add" onclick="addAward()" style="display:inline-block;">+ Add Award</button>
            </div>
        </div>

        <div style="text-align:center; margin-top:40px;">
            <button id="save-btn" type="submit" class="btn btn-save">💾 Save Changes</button>
        </div>
    </form>
</div>

<!-- Cropper Modal -->
<div id="cropper-modal">
    <div id="cropper-container">
        <img id="cropper-image" alt="Cropper Image">
        <div style="margin-top:10px; display:flex; justify-content:center; gap:10px;">
            <!-- set type=button so it cannot submit any forms accidentally -->
            <button id="crop-btn" type="button" class="btn btn-save">Apply</button>
            <button id="cancel-btn" type="button" class="btn btn-remove">Cancel</button>
        </div>
    </div>
</div>

<!-- File preview modal for awards -->
<div id="file-modal" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="fm-content">
        <button id="file-modal-close" type="button" class="btn btn-remove" aria-label="Close certificate preview">Close</button>

        <div class="fm-body" id="file-modal-body">
            <!-- Content injected dynamically: iframe for pdf, img for images -->
        </div>
    </div>
</div>

<!-- Toast for maxlength -->
<div id="max-toast" aria-live="polite" role="status"></div>

<script src="https://cdn.jsdelivr.net/npm/cropperjs@1.5.13/dist/cropper.min.js"></script>
<script>
let cropper;
const input = document.getElementById('profile-input');
const preview = document.getElementById('profile-preview');
const modal = document.getElementById('cropper-modal');
const cropperImage = document.getElementById('cropper-image');
const croppedInput = document.getElementById('cropped_image');
const resumeForm = document.getElementById('resume-form');

// BASE for storage assets (so JS can build full URLs for existing storage-relative paths)
const STORAGE_BASE = "{{ asset('storage') }}";

// prevent Enter from submitting the form unexpectedly.
// Allow Enter in textarea, but prevent in single-line inputs.
resumeForm.addEventListener('keydown', function(e){
    const tag = e.target.tagName.toLowerCase();
    const type = e.target.type ? e.target.type.toLowerCase() : '';
    if(e.key === 'Enter' && tag !== 'textarea' && type !== 'submit' && type !== 'button') {
        e.preventDefault();
    }
});

/* ----------------------------
   Dependent inputs logic
   ---------------------------- */
// Toggle the next text input inside the same row: enable it only when the first has content.
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

// Initialize dependent inputs on page load (so existing rows are correct)
document.addEventListener('DOMContentLoaded', function(){
    document.querySelectorAll('.array-input').forEach(row => {
        const textInputs = Array.from(row.querySelectorAll('input[type="text"]'));
        if(textInputs.length >= 2){
            // ensure second input disabled state matches first input's current value
            textInputs[1].disabled = textInputs[0].value.trim() === '';
        }
    });

    // Also re-enable any description fields that have value even if the first was empty (defensive)
    document.querySelectorAll('input[type="text"]').forEach(inp => {
        const row = inp.closest('.array-input');
        if(!row) return;
        const t = Array.from(row.querySelectorAll('input[type="text"]'));
        if(t.length >= 2){
            // if second has content but first is empty, enable second so user can keep it
            if(t[1].value.trim() !== '' && t[0].value.trim() === ''){
                t[1].disabled = false;
            }
        }
    });

    // Initialize maxlength watcher to attach handlers to existing inputs
    initMaxLengthWatcher();
});

// Event delegation: when user types into any first input, toggle the next one
document.addEventListener('input', function(e){
    if(!e.target) return;
    if(e.target.matches('.array-input input[type="text"]')){
        toggleNextInput(e.target);
    }
});

/* ----------------------------
   Profile cropper logic
   ---------------------------- */
// PROFILE CROPPER: open modal on file select, but DO NOT auto-submit
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
    if(!cropper) { modal.style.display='none'; return; }
    const canvas = cropper.getCroppedCanvas({ width: 300, height: 300 });
    preview.src = canvas.toDataURL('image/png');
    croppedInput.value = canvas.toDataURL('image/png');
    modal.style.display = 'none';
    modal.setAttribute('aria-hidden','true');
    if(cropperImage.src.startsWith('blob:')) { try{ URL.revokeObjectURL(cropperImage.src); } catch(e){} }
    cropper.destroy(); cropper = null;
});

document.getElementById('cancel-btn').addEventListener('click', () => {
    modal.style.display = 'none';
    modal.setAttribute('aria-hidden','true');
    if(cropper) { cropper.destroy(); cropper = null; }
});

/* ----------------------------
   Section filter logic
   ---------------------------- */
const filter = document.getElementById('section-filter');
filter.addEventListener('change', function(){
    const val = this.value;
    document.querySelectorAll('.section').forEach(sec => {
        if(val === 'all' || sec.dataset.section === val || (val === '' && (sec.dataset.section === 'profile' || sec.dataset.section === 'personal'))) {
            sec.classList.add('visible');
        } else {
            sec.classList.remove('visible');
        }
    });
    // Because add-award is inside awards section, it will only be visible when that section is visible
});

/* ----------------------------
   Dynamic input helpers
   ---------------------------- */
function addInput(id,name){
    const c=document.getElementById(id);
    const d=document.createElement('div');
    d.className='array-input';
    d.innerHTML=`<input type="text" name="${name}" placeholder="Enter value">\n    <button type="button" class="btn btn-remove" onclick="this.parentElement.remove()">Remove</button>`;
    c.appendChild(d);
}

function addOrganization(){
    const c=document.getElementById('organization-container');
    const d=document.createElement('div');
    d.className='array-input';
    d.innerHTML=`<input type="text" name="organization[name][]" placeholder="Organization Name">\n    <input type="text" name="organization[position][]" placeholder="Position">\n    <input type="text" name="organization[year][]" placeholder="Year">\n    <button type="button" class="btn btn-remove" onclick="this.parentElement.remove()">Remove</button>`;
    c.appendChild(d);
}

function addSkill(){
    const c=document.getElementById('skills-container');
    const d=document.createElement('div');
    d.className='array-input';
    d.innerHTML=`<input type="text" name="skills[key][]" placeholder="Skill" oninput="toggleNextInput(this)" maxlength="20">\n    <input type="text" name="skills[value][]" placeholder="Description" disabled maxlength="100">\n    <button type="button" class="btn btn-remove" onclick="this.parentElement.remove()">Remove</button>`;
    c.appendChild(d);

    // attach maxlength watcher for the newly created inputs
    attachMaxWatcherToRow(d);
}

function addProgramming(){
    const c=document.getElementById('programming-container');
    const d=document.createElement('div');
    d.className='array-input';
    d.innerHTML=`<input type="text" name="programming[key][]" placeholder="Language" oninput="toggleNextInput(this)" maxlength="15">\n    <input type="text" name="programming[value][]" placeholder="Description" disabled maxlength="50">\n    <button type="button" class="btn btn-remove" onclick="this.parentElement.remove()">Remove</button>`;
    c.appendChild(d);

    // attach maxlength watcher for the newly created inputs
    attachMaxWatcherToRow(d);
}

function addAward() {
    const container = document.getElementById('awards-container');
    const div = document.createElement('div');
    div.classList.add('array-input');
    div.innerHTML = `
        <input type="text" name="awards[name][]" placeholder="Award Name" required>
        <input type="file" name="awards[file][]" accept=".pdf,.jpg,.png" class="award-file-input">
        <input type="hidden" name="awards[existing_file][]" value="">
        <button type="button" class="btn btn-add view-certificate-btn" style="display:none;">View Certificate</button>
        <button type="button" class="btn btn-remove" onclick="this.parentElement.remove()">Remove</button>
    `;
    container.appendChild(div);
}

/* -----------------------------
   Awards: file preview modal
   ----------------------------- */

const fileModal = document.getElementById('file-modal');
const fileModalBody = document.getElementById('file-modal-body');
const fileCloseBtn = document.getElementById('file-modal-close');

// helper: open modal with given URL and type detection (pdf or image)
// accepts optional mimeType (e.g. 'application/pdf') when previewing blob URLs
function openFilePreview(url, mimeType = '') {
    fileModalBody.innerHTML = ''; // clear

    // prefer mimeType when provided (blob URLs won't end with .pdf)
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

    fileModal.style.display = 'flex';
    fileModal.setAttribute('aria-hidden','false');
}

// Event delegation: clicking any View Certificate button should open preview.
// Prefer dataset.blobUrl (newly selected) and its dataset.blobType for MIME detection,
// otherwise fallback to data-file (existing storage URL).
document.addEventListener('click', function(e){
    if(e.target.matches('.view-certificate-btn')) {
        const btn = e.target;
        const blobUrl = btn.dataset.blobUrl;
        const blobType = btn.dataset.blobType || '';
        const storedUrl = btn.getAttribute('data-file');

        if(blobUrl) {
            // newly selected file: use blob + mime type
            openFilePreview(blobUrl, blobType);
            return;
        }

        if(storedUrl) {
            // previously uploaded file: rely on extension check
            openFilePreview(storedUrl, '');
            return;
        }
        // nothing to preview
    }
});

document.addEventListener('change', function(e){
    if(e.target && e.target.matches('.award-file-input')) {
        const input = e.target;
        const file = input.files && input.files[0];
        // find or create the view-certificate button in the same array-input row
        const row = input.closest('.array-input');
        if(!row) return;
        let vcBtn = row.querySelector('.view-certificate-btn');

        // revoke previous blob url stored on the button if any
        if(vcBtn && vcBtn.dataset.blobUrl) {
            try { URL.revokeObjectURL(vcBtn.dataset.blobUrl); } catch(err) {}
            delete vcBtn.dataset.blobUrl;
        }

        if(!file) {
            // user cleared selection: if there is an existing stored file (existing_file hidden input), keep button pointing to it
            // otherwise hide the button
            if(vcBtn) {
                const existingHidden = row.querySelector('input[type="hidden"][name^="awards[existing_file]"]');
                if(existingHidden && existingHidden.value) {
                    // Build full asset URL if existingHidden.value is storage-relative
                    let val = existingHidden.value;
                    if(!/^https?:\/\//i.test(val)) {
                        val = STORAGE_BASE.replace(/\/$/, '') + '/' + val.replace(/^\/+/, '');
                    }
                    vcBtn.setAttribute('data-file', val);
                    vcBtn.style.display = 'inline-block';
                } else {
                    vcBtn.removeAttribute('data-file');
                    vcBtn.style.display = 'none';
                }
            }
            return;
        }

        // create blob url for this file and update/create button
        const blobUrl = URL.createObjectURL(file);
        if(!vcBtn) {
            vcBtn = document.createElement('button');
            vcBtn.type = 'button';
            vcBtn.className = 'btn btn-add view-certificate-btn';
            vcBtn.textContent = 'View Certificate';
            // insert after input
            input.insertAdjacentElement('afterend', vcBtn);
        }
        // store blob url on dataset so open handler can pick either dataset.blobUrl (new) or data-file (existing)
        vcBtn.dataset.blobUrl = blobUrl;
        vcBtn.dataset.blobType = file.type || '';
        // remove any old data-file attribute because we now preview the newly selected file
        vcBtn.removeAttribute('data-file');
        vcBtn.style.display = 'inline-block';
    }
});

// Close file modal
fileCloseBtn.addEventListener('click', () => {
    fileModal.style.display = 'none';
    fileModal.setAttribute('aria-hidden','true');
    // revoke any blob URLs attached to view-certificate buttons to avoid memory leaks
    document.querySelectorAll('.view-certificate-btn').forEach(b => {
        if(b.dataset && b.dataset.blobUrl) {
            try { URL.revokeObjectURL(b.dataset.blobUrl); } catch(err) {}
            delete b.dataset.blobUrl;
            delete b.dataset.blobType;
        }
    });
    fileModalBody.innerHTML = '';
});

// Accessibility: close modals on Escape key
document.addEventListener('keydown', function(e){
    if(e.key === 'Escape') {
        if(modal.style.display === 'flex') {
            modal.style.display = 'none';
            modal.setAttribute('aria-hidden','true');
            if(cropper){ cropper.destroy(); cropper=null; }
        }
        if(fileModal.style.display === 'flex') {
            fileModal.style.display = 'none';
            fileModal.setAttribute('aria-hidden','true');
            // revoke any blob urls
            document.querySelectorAll('.view-certificate-btn').forEach(b => {
                if(b.dataset && b.dataset.blobUrl) {
                    try { URL.revokeObjectURL(b.dataset.blobUrl); } catch(err) {}
                    delete b.dataset.blobUrl;
                }
            });
            fileModalBody.innerHTML = '';
        }
    }
});

/* ----------------------------
   Maxlength toast behavior
   ---------------------------- */
const toast = document.getElementById('max-toast');
let toastTimer = null;

// show toast with message; auto hide after 1600ms
function showMaxToast(msg){
    if(!toast) return;
    toast.textContent = msg;
    toast.classList.add('show');
    if(toastTimer) clearTimeout(toastTimer);
    toastTimer = setTimeout(()=> {
        toast.classList.remove('show');
        toastTimer = null;
    }, 1600);
}

// attach watcher for inputs with maxlength in a given row element
function attachMaxWatcherToRow(row){
    if(!row) return;
    const inputs = Array.from(row.querySelectorAll('input[type="text"]'));
    inputs.forEach(inp => {
        if(!inp.hasAttribute('maxlength')) return;
        // ensure we only attach one listener
        if(inp._maxlengthWatcherAttached) return;
        inp._maxlengthWatcherAttached = true;

        inp.addEventListener('input', function(){
            const max = parseInt(inp.getAttribute('maxlength') || '0', 10);
            if(max > 0 && inp.value.length >= max){
                // only trigger when reaching exact max (not on every input)
                if(!inp.dataset._notified){
                    inp.dataset._notified = '1';
                    showMaxToast(`Maximum length reached (${max})`);
                }
            } else {
                // clear the notified flag so it can trigger again later
                if(inp.dataset._notified) delete inp.dataset._notified;
            }
        });

        // also watch paste events (to catch paste larger than maxlength)
        inp.addEventListener('paste', function(){
            setTimeout(()=> {
                const max = parseInt(inp.getAttribute('maxlength') || '0', 10);
                if(max > 0 && inp.value.length >= max){
                    if(!inp.dataset._notified){
                        inp.dataset._notified = '1';
                        showMaxToast(`Maximum length reached (${max})`);
                    }
                }
            }, 50);
        });
    });
}

// scan and attach watchers to existing rows/inputs
function initMaxLengthWatcher(){
    document.querySelectorAll('.array-input').forEach(row => attachMaxWatcherToRow(row));

    // also attach to independently-added single inputs like interests (they may not be in pair rows)
    document.querySelectorAll('input[type="text"][maxlength]').forEach(inp => {
        const parentRow = inp.closest('.array-input');
        if(parentRow) attachMaxWatcherToRow(parentRow);
        else attachMaxWatcherToRow(document.createElement('div')); // no-op but ensures code runs
    });
}

// helper to attach watchers when rows are dynamically appended (used by addSkill/addProgramming)
function attachWatcherToNewElements(targetNode){
    // targetNode is the newly inserted element (row)
    attachMaxWatcherToRow(targetNode);
}

// When new nodes are inserted (e.g., addSkill/addProgramming/addInput), attach watchers.
// We'll use a MutationObserver on the containers that can get new children.
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

// Helper: when creating awards server-side, your controller should preserve awards[existing_file][] if no new file uploaded.
// Frontend does not overwrite existing_file hidden inputs unless user selects a new file and you process it on save.

</script>
@endsection
