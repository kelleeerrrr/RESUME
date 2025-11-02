@extends('layout')

@section('title', 'Resume | Irish Rivera')

@section('content')
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

    @media print {
        body { background: #fff !important; color: #000 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; zoom: 0.9; }
        .container { background: #fff !important; box-shadow: none; }
        .left { background: #f7d6e0 !important; color: #000 !important; }
        .right { background: #fff !important; color: #000 !important; }
        .left h2, .right h2 { background: #d6417aff !important; color: white !important; }
        .download-btn, .edit-btn, .navbar { display: none !important; }
    }
</style>

<div class="container">
    <div class="left">
        <img src="{{ asset('profile.jpg') }}" alt="Profile Picture">

        {{-- Personal Information --}}
        @if(!empty($resume->fullname) || !empty($resume->dob) || !empty($resume->pob) || !empty($resume->civil_status) || !empty($resume->specialization))
            <h2>Personal Information</h2>
            @if(!empty($resume->fullname)) <p><strong>Full Name:</strong> {{ $resume->fullname }}</p> @endif
            @if(!empty($resume->dob)) <p><strong>Date of Birth:</strong> {{ $resume->dob }}</p> @endif
            @if(!empty($resume->pob)) <p><strong>Place of Birth:</strong> {{ $resume->pob }}</p> @endif
            @if(!empty($resume->civil_status)) <p><strong>Civil Status:</strong> {{ $resume->civil_status }}</p> @endif
            @if(!empty($resume->specialization)) <p><strong>Field of Specialization:</strong> {{ $resume->specialization }}</p> @endif
        @endif

        {{-- Organizations --}}
        @if(!empty($resume->organization['name']))
            <h2>Organization</h2>
            @foreach($resume->organization['name'] as $i => $name)
                @if($name)
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
        <p>
            @if(!empty($resume->address)) {{ $resume->address }}<br>@endif
            @if(!empty($resume->email)) {{ $resume->email }}<br>@endif
            @if(!empty($resume->phone)) {{ $resume->phone }}@endif
        </p>

        {{-- Educational Background --}}
        @php
            $eduLevels = ['Elementary','Secondary','Tertiary'];
            $education = $resume->education ?? [];
        @endphp
        @if(!empty($education))
            <h2>Educational Background</h2>
            @foreach($eduLevels as $level)
                @php
                    $school = $education[$level]['school'] ?? '';
                    $year = $education[$level]['year'] ?? '';
                @endphp
                <p>
                    <strong>{{ $level }}:</strong>
                    @if(!$school && !$year)
                        N/A
                    @else
                        {{ $school ?: '' }}{{ $year && $school ? ' (' . $year . ')' : '' }}
                    @endif
                </p>
            @endforeach
        @endif

        {{-- Skills --}}
        @if(!empty($resume->skills['key']))
            <h2>Skills</h2>
            <ul>
                @foreach($resume->skills['key'] as $i => $skill)
                    @php $desc = $resume->skills['value'][$i] ?? ''; @endphp
                    @if($skill)
                        <li><strong>{{ $skill }}:</strong> {{ $desc }}</li>
                    @endif
                @endforeach
            </ul>
        @endif

        {{-- Programming Languages --}}
        @if(!empty($resume->programming['key']))
            <h2>Programming Languages</h2>
            <ul>
                @foreach($resume->programming['key'] as $i => $lang)
                    @php $desc = $resume->programming['value'][$i] ?? ''; @endphp
                    @if($lang)
                        <li><strong>{{ $lang }}:</strong> {{ $desc }}</li>
                    @endif
                @endforeach
            </ul>
        @endif

        {{-- Field of Interest --}}
        @if(!empty($resume->interests))
            <h2>Field of Interest</h2>
            <ul>
                @foreach($resume->interests as $interest)
                    @if($interest) <li>{{ $interest }}</li> @endif
                @endforeach
            </ul>
        @endif

        {{-- Projects --}}
        @if(!empty($resume->projects))
            <h2>Projects</h2>
            <ul>
                @foreach($resume->projects as $project)
                    @if($project) <li>{{ $project }}</li> @endif
                @endforeach
            </ul>
        @endif

        {{-- Awards --}}
        @if(!empty($resume->awards))
            <h2>Awards and Recognitions</h2>
            <ul>
                @foreach($resume->awards as $award)
                    @if($award) <li>{{ $award }}</li> @endif
                @endforeach
            </ul>
        @endif
    </div>
</div>
@endsection
