@extends('layout')

@section('title', 'Resume | Irish Rivera')

@section('content')
@php
    // Defensive normalization (so blade works if controller didn't supply $resume)
    use App\Models\Resume;

    $resume = $resume ?? (function() {
        $r = Resume::with('awardFiles')->latest()->first();
        return $r ?: null;
    })();

    if (!$resume) {
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
        if (!isset($resume->awardFiles)) {
            $resume->load('awardFiles');
        }
    }

    // Normalize JSON fields (strings or arrays)
    $fields = ['organization','education','skills','programming','projects','interests'];
    foreach ($fields as $f) {
        if (isset($resume->$f)) {
            $decoded = is_string($resume->$f) ? json_decode($resume->$f, true) : $resume->$f;
            $resume->$f = is_array($decoded) ? $decoded : [];
        } else {
            $resume->$f = [];
        }
    }

    // helper: check if a structure contains any non-empty value
    if (!function_exists('hasValues')) {
        function hasValues($arr) {
            if (!$arr) return false;
            if (is_object($arr) && method_exists($arr, 'toArray')) $arr = $arr->toArray();
            $found = false;
            $walker = function($x) use (&$walker, &$found) {
                if ($found) return;
                if (is_array($x)) {
                    foreach ($x as $v) $walker($v);
                } else {
                    if (is_string($x) && trim($x) !== '') { $found = true; return; }
                    if (!is_string($x) && !empty($x)) { $found = true; return; }
                }
            };
            $walker($arr);
            return $found;
        }
    }
@endphp

<style>
    .container {
        display: flex;
        max-width: 1000px;
        margin: 40px auto;
        background: white;
        box-shadow: 0 0 10px rgba(0,0,0,0.2);
        border-radius: 8px;
        overflow: hidden;
        color: #333;
        position: relative;
        transition: 0.3s;
    }
    body.dark .container { background: #2a2a2a; color: #f5f5f5; }
    .left { background: #ce8596ff; color: white; width: 35%; padding: 20px; transition: 0.3s; }
    body.dark .left { background: #b3586a; }
    .left img { width: 150px; height: 150px; border-radius: 50%; display: block; margin: 0 auto 20px auto; border: 3px solid white; object-fit: cover; }
    .left h2 { border-bottom: 2px solid white; padding-bottom: 5px; margin-bottom: 10px; }
    .left p, .left ul li { margin: 5px 0; }
    .right { width: 65%; padding: 20px; background: #fff; transition: 0.3s; position: relative; }
    body.dark .right { background: #1e1e1e; }
    .right h1 { color: #d6417aff; transition: 0.3s; }
    body.dark .right h1 { color: #ff76a6; }
    .right h2 { background: #d6799cff; color: white; padding: 5px 10px; border-radius: 5px; margin-top: 15px; transition: 0.3s; }
    body.dark .right h2 { background: #ff1f84ff; }
    ul { list-style: none; padding-left: 15px; }
    ul li { margin: 8px 0; }
    .skill-inline { margin: 8px 0; }
    .skill-inline strong { font-weight: bold; }
    .download-btn, .edit-btn {
        display: inline-block;
        padding: 10px 20px;
        border-radius: 5px;
        text-decoration: none;
        font-weight: bold;
        border: none;
        cursor: pointer;
        transition: background 0.3s, color 0.3s;
        color: white;
    }
    .download-btn { background-color: #2ecc71; }
    .edit-btn { background-color: #3498db; }
    body.dark .download-btn { background-color: #ff1f84ff; color: white; }
    body.dark .edit-btn { background-color: #9b59b6; color: white; }
    .download-btn:hover, .edit-btn:hover { opacity: 0.9; }

    /* Pop-up modal for certificate */
    .modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.8);
        justify-content: center;
        align-items: center;
    }
    .modal-content {
        background-color: #fff;
        padding: 10px;
        width: 90%;
        height: 90%;
        border-radius: 8px;
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .modal-content iframe {
        width: 100%;
        height: 100%;
        border: none;
    }
    .close-modal {
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 18px;
        font-weight: bold;
        cursor: pointer;
        background: #d6417aff;
        color: white;
        border: none;
        padding: 5px 10px;
        border-radius: 5px;
    }

    /* Hide UI elements when printing; also hide "View Certificate" text */
    @media print {
        body { background: #fff !important; color: #000 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; zoom: 0.9; }
        .container { background: #fff !important; box-shadow: none; }
        .left { background: #f7d6e0 !important; color: #000 !important; }
        .right { background: #fff !important; color: #000 !important; }
        .left h2, .right h2 { background: #d6417aff !important; color: white !important; }
        .download-btn, .edit-btn, .navbar, .modal, .no-print { display: none !important; }
        /* ensure certificate modal content doesn't print */
        .modal, .modal * { display: none !important; }
    }

    .no-print { display: inline; }
</style>

<div class="container">
    <div class="left">
        {{-- Profile Picture --}}
        <img src="{{ $resume->profile_photo ? asset('storage/' . $resume->profile_photo) . '?t=' . time() : 'https://via.placeholder.com/150' }}" alt="Profile Picture">

        {{-- Personal Information --}}
        @if(hasValues([$resume->fullname, $resume->dob, $resume->pob, $resume->civil_status, $resume->specialization]))
            <h2>Personal Information</h2>
            @if(!empty($resume->fullname)) <p><strong>Full Name:</strong> {{ $resume->fullname }}</p> @endif
            @if(!empty($resume->dob)) <p><strong>Date of Birth:</strong> {{ $resume->dob }}</p> @endif
            @if(!empty($resume->pob)) <p><strong>Place of Birth:</strong> {{ $resume->pob }}</p> @endif
            @if(!empty($resume->civil_status)) <p><strong>Civil Status:</strong> {{ $resume->civil_status }}</p> @endif
            @if(!empty($resume->specialization)) <p><strong>Field of Specialization:</strong> {{ $resume->specialization }}</p> @endif
        @endif

        {{-- Organizations --}}
        @if(hasValues($resume->organization['name'] ?? []))
            <h2>Organization</h2>
            @foreach($resume->organization['name'] as $i => $name)
                @if(trim((string)$name) !== '')
                    <p>{{ $name }} 
                        @if(!empty($resume->organization['position'][$i])) - {{ $resume->organization['position'][$i] }} @endif
                        @if(!empty($resume->organization['year'][$i])) ({{ $resume->organization['year'][$i] }}) @endif
                    </p>
                @endif
            @endforeach
        @endif
    </div>

    <div class="right">
        {{-- Buttons --}}
        <div style="position: absolute; top: 20px; right: 20px; display: flex; gap: 10px;">
            <button class="download-btn" onclick="window.print()">📄 Download My CV</button>
            @if(session()->has('user_id'))
                <a href="{{ route('resume.edit', ['id' => session('user_id')]) }}" class="edit-btn">✏️ Edit Resume</a>
            @endif
        </div>

        {{-- Name and Contact --}}
        @if(!empty($resume->fullname)) <h1>{{ strtoupper($resume->fullname) }}</h1> @endif
        @if(hasValues([$resume->address, $resume->email, $resume->phone]))
            <p>
                @if(!empty($resume->address)) {{ $resume->address }}<br>@endif
                @if(!empty($resume->email)) {{ $resume->email }}<br>@endif
                @if(!empty($resume->phone)) {{ $resume->phone }}@endif
            </p>
        @endif

        {{-- Educational Background --}}
        @php
            $eduLevels = ['Elementary','Secondary','Tertiary'];
            $education = $resume->education ?? [];
            $eduHas = false;
            foreach($eduLevels as $l) {
                if (!empty($education[$l]['school'] ?? '') || !empty($education[$l]['year'] ?? '')) { $eduHas = true; break; }
            }
        @endphp
        @if($eduHas)
            <h2>Educational Background</h2>
            @foreach($eduLevels as $level)
                @php
                    $school = $education[$level]['school'] ?? '';
                    $year = $education[$level]['year'] ?? '';
                @endphp
                @if(trim((string)$school) !== '' || trim((string)$year) !== '')
                <p>
                    <strong>{{ $level }}:</strong>
                    {{ $school ?: '' }}{{ $school && $year ? ' (' . $year . ')' : ($year && !$school ? ' (' . $year . ')' : '') }}
                </p>
                @endif
            @endforeach
        @endif

        {{-- Skills --}}
        @if(hasValues($resume->skills['key'] ?? []))
            <h2>Skills</h2>
            <ul>
                @foreach($resume->skills['key'] as $i => $skill)
                    @php $desc = $resume->skills['value'][$i] ?? ''; @endphp
                    @if(trim((string)$skill) !== '')
                        <li><strong>{{ $skill }}:</strong> {{ $desc }}</li>
                    @endif
                @endforeach
            </ul>
        @endif

        {{-- Programming Languages --}}
        @if(hasValues($resume->programming['key'] ?? []))
            <h2>Programming Languages</h2>
            <ul>
                @foreach($resume->programming['key'] as $i => $lang)
                    @php $desc = $resume->programming['value'][$i] ?? ''; @endphp
                    @if(trim((string)$lang) !== '')
                        <li><strong>{{ $lang }}:</strong> {{ $desc }}</li>
                    @endif
                @endforeach
            </ul>
        @endif

        {{-- Field of Interest --}}
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
        @php $awards = $resume->awardFiles ?? collect(); @endphp
        @if($awards->isNotEmpty())
            @php
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
                            $filePath = $award->file_path ?? $award->certificate ?? $award->file ?? null;
                        @endphp

                        @if(trim((string)$awardName) !== '')
                            <li>
                                {{ $awardName }}
                                @if(!empty($filePath))
                                    <span class="no-print"> - 
                                        <a href="javascript:void(0)" onclick="openModal('{{ asset('storage/' . ltrim($filePath, '/')) }}')">
                                            📄 <span class="no-print">View Certificate</span>
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
<div id="certificateModal" class="modal" aria-hidden="true" role="dialog">
    <div class="modal-content" role="document">
        <button class="close-modal" onclick="closeModal()">Close</button>
        <iframe id="certificateContent" src="" type="application/pdf" title="Certificate preview"></iframe>
    </div>
</div>

<script>
    function openModal(fileUrl) {
        const modal = document.getElementById('certificateModal');
        const content = document.getElementById('certificateContent');
        if(!fileUrl) return;
        // set iframe src
        content.src = fileUrl;
        modal.style.display = 'flex';
        modal.setAttribute('aria-hidden','false');
    }
    function closeModal() {
        const modal = document.getElementById('certificateModal');
        const content = document.getElementById('certificateContent');
        modal.style.display = 'none';
        modal.setAttribute('aria-hidden','true');
        // clear src to stop loading/playing and free memory
        content.src = '';
    }
    window.onclick = function(event) {
        const modal = document.getElementById('certificateModal');
        if(event.target == modal) { closeModal(); }
    };
    // close on Escape
    document.addEventListener('keydown', function(e){
        if(e.key === 'Escape') closeModal();
    });
</script>
@endsection
