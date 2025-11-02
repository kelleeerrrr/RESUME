@extends('layout')

@section('title', 'Edit Resume | Irish Rivera')

@section('content')
<style>
    .container { max-width: 900px; margin: 40px auto; background: white; box-shadow: 0 0 10px rgba(0,0,0,0.2); border-radius: 8px; padding: 20px; color: #333; }
    body.dark .container { background: #2a2a2a; color: #f5f5f5; }
    h1 { color: #d6417aff; margin-bottom: 20px; }
    body.dark h1 { color: #ff76a6; }
    label { font-weight: bold; margin-top: 10px; display: block; }
    input[type="text"], input[type="email"], textarea { width: 100%; padding: 8px; margin: 5px 0 15px 0; border-radius: 4px; border: 1px solid #ccc; }
    body.dark input[type="text"], body.dark input[type="email"], body.dark textarea { background: #1e1e1e; color: #f5f5f5; border: 1px solid #555; }
    .section { margin-bottom: 20px; }
    .array-input { display: flex; gap: 10px; margin-bottom: 5px; }
    .array-input input { flex: 1; }
    .btn { display: inline-block; padding: 10px 20px; border-radius: 5px; text-decoration: none; font-weight: bold; border: none; cursor: pointer; color: white; margin-top: 10px; }
    .btn-save { background-color: #2ecc71; }
    .btn-save:hover { opacity: 0.9; }
    .btn-add { background-color: #3498db; }
    .btn-add:hover { opacity: 0.9; }
    .btn-remove { background-color: #e74c3c; }
    .btn-remove:hover { opacity: 0.9; }
</style>

<div class="container">
    <h1>Edit Your Resume</h1>

    @if(session('success'))
        <p style="color:green;">{{ session('success') }}</p>
    @endif

    <form action="{{ route('resume.update', ['id' => $resume->id]) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Personal Info --}}
        <div class="section">
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

        {{-- Organization --}}
        <div class="section" id="organization-section">
            <h2>Organization</h2>
            <div id="organization-container">
                @php $org = $resume->organization ?? []; @endphp
                @if(!empty($org['name']))
                    @foreach($org['name'] as $i => $name)
                        <div class="array-input">
                            <input type="text" name="organization[name][]" placeholder="Organization Name" value="{{ $name }}">
                            <input type="text" name="organization[position][]" placeholder="Position" value="{{ $org['position'][$i] ?? '' }}">
                            <input type="text" name="organization[year][]" placeholder="Year" value="{{ $org['year'][$i] ?? '' }}">
                            <button type="button" class="btn btn-remove" onclick="this.parentElement.remove()">Remove</button>
                        </div>
                    @endforeach
                @endif
            </div>
            <button type="button" class="btn btn-add" onclick="addOrganization()">+ Add Organization</button>
        </div>

        {{-- Education --}}
        <div class="section">
            <h2>Education</h2>
            @php $edu = $resume->education ?? []; @endphp
            @foreach(['Elementary','Secondary','Tertiary'] as $level)
                <div class="array-input">
                    <input type="text" name="education[{{ $level }}][school]" placeholder="{{ $level }} School" 
                           value="{{ $edu[$level]['school'] ?? '' }}">
                    <input type="text" name="education[{{ $level }}][year]" placeholder="Year" 
                           value="{{ $edu[$level]['year'] ?? '' }}"
                           @if(empty($edu[$level]['school'])) disabled @endif>
                </div>
            @endforeach
        </div>

        {{-- Field of Interest --}}
        <div class="section" id="interests-section">
            <h2>Field of Interest</h2>
            <div id="interests-container">
                @foreach($resume->interests ?? [] as $interest)
                    <div class="array-input">
                        <input type="text" name="interests[]" value="{{ $interest }}">
                        <button type="button" class="btn btn-remove" onclick="this.parentElement.remove()">Remove</button>
                    </div>
                @endforeach
            </div>
            <button type="button" class="btn btn-add" onclick="addInput('interests-container','interests[]')">+ Add Interest</button>
        </div>

        {{-- Skills --}}
        <div class="section" id="skills-section">
            <h2>Skills</h2>
            <div id="skills-container">
                @foreach($resume->skills['key'] ?? [] as $i => $skill)
                    <div class="array-input">
                        <input type="text" name="skills[key][]" value="{{ $skill }}" placeholder="Skill">
                        <input type="text" name="skills[value][]" value="{{ $resume->skills['value'][$i] ?? '' }}" placeholder="Description">
                        <button type="button" class="btn btn-remove" onclick="this.parentElement.remove()">Remove</button>
                    </div>
                @endforeach
            </div>
            <button type="button" class="btn btn-add" onclick="addSkill()">+ Add Skill</button>
        </div>

        {{-- Programming Languages --}}
        <div class="section" id="programming-section">
            <h2>Programming Languages</h2>
            <div id="programming-container">
                @foreach($resume->programming['key'] ?? [] as $i => $lang)
                    <div class="array-input">
                        <input type="text" name="programming[key][]" value="{{ $lang }}" placeholder="Language">
                        <input type="text" name="programming[value][]" value="{{ $resume->programming['value'][$i] ?? '' }}" placeholder="Description">
                        <button type="button" class="btn btn-remove" onclick="this.parentElement.remove()">Remove</button>
                    </div>
                @endforeach
            </div>
            <button type="button" class="btn btn-add" onclick="addProgramming()">+ Add Programming</button>
        </div>

        {{-- Projects --}}
        <div class="section" id="projects-section">
            <h2>Projects</h2>
            <div id="projects-container">
                @foreach($resume->projects ?? [] as $project)
                    <div class="array-input">
                        <input type="text" name="projects[]" value="{{ $project }}">
                        <button type="button" class="btn btn-remove" onclick="this.parentElement.remove()">Remove</button>
                    </div>
                @endforeach
            </div>
            <button type="button" class="btn btn-add" onclick="addInput('projects-container','projects[]')">+ Add Project</button>
        </div>

        {{-- Awards --}}
        <div class="section" id="awards-section">
            <h2>Awards & Recognitions</h2>
            <div id="awards-container">
                @foreach($resume->awards ?? [] as $award)
                    <div class="array-input">
                        <input type="text" name="awards[]" value="{{ $award }}">
                        <button type="button" class="btn btn-remove" onclick="this.parentElement.remove()">Remove</button>
                    </div>
                @endforeach
            </div>
            <button type="button" class="btn btn-add" onclick="addInput('awards-container','awards[]')">+ Add Award</button>
        </div>

        <button type="submit" class="btn btn-save">💾 Save Changes</button>
    </form>
</div>

<script>
    function addInput(containerId, name){
        const container = document.getElementById(containerId);
        const div = document.createElement('div');
        div.className = 'array-input';
        div.innerHTML = `<input type="text" name="${name}" placeholder="Enter value">
                         <button type="button" class="btn btn-remove" onclick="this.parentElement.remove()">Remove</button>`;
        container.appendChild(div);
    }

    function addOrganization(){
        const container = document.getElementById('organization-container');
        const div = document.createElement('div');
        div.className = 'array-input';
        div.innerHTML = `<input type="text" name="organization[name][]" placeholder="Organization Name">
                         <input type="text" name="organization[position][]" placeholder="Position">
                         <input type="text" name="organization[year][]" placeholder="Year">
                         <button type="button" class="btn btn-remove" onclick="this.parentElement.remove()">Remove</button>`;
        container.appendChild(div);
    }

    function addSkill(){
        const container = document.getElementById('skills-container');
        const div = document.createElement('div');
        div.className = 'array-input';
        div.innerHTML = `<input type="text" name="skills[key][]" placeholder="Skill">
                         <input type="text" name="skills[value][]" placeholder="Description">
                         <button type="button" class="btn btn-remove" onclick="this.parentElement.remove()">Remove</button>`;
        container.appendChild(div);
    }

    function addProgramming(){
        const container = document.getElementById('programming-container');
        const div = document.createElement('div');
        div.className = 'array-input';
        div.innerHTML = `<input type="text" name="programming[key][]" placeholder="Language">
                         <input type="text" name="programming[value][]" placeholder="Description">
                         <button type="button" class="btn btn-remove" onclick="this.parentElement.remove()">Remove</button>`;
        container.appendChild(div);
    }

    // Automatically enable/disable year input based on school name
    document.querySelectorAll('input[name^="education"]').forEach(input => {
        if(input.name.includes('[school]')) {
            input.addEventListener('input', function() {
                const yearInput = this.parentElement.querySelector('input[name$="[year]"]');
                if(this.value.trim() !== '') {
                    yearInput.disabled = false;
                } else {
                    yearInput.disabled = true;
                    yearInput.value = '';
                }
            });
        }
    });
</script>
@endsection
