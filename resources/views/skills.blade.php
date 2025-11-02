@extends('layout')

@section('title', 'Skills | Irish Rivera')

@section('content')
<style>
    .skills-container {
        max-width: 1000px;
        margin: 5px auto;
        padding: 20px;
    }

    h2.section-title {
        color: var(--accent);
        font-size: 2em;
        margin-bottom: 10px;
    }

    p.section-desc {
        font-size: 1.1em;
        line-height: 1.6;
        color: var(--text-light);
        margin-bottom: 30px;
    }

    body.dark p.section-desc {
        color: var(--text-dark);
    }

    /* Soft Skills */
    .soft-skills {
        background-color: #f4f4f4;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 30px;
        transition: 0.3s;
    }
    body.dark .soft-skills { background-color: #2a2a2a; color: #fff; }

    .soft-skills ul { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 15px; }
    .soft-skills li { display: flex; align-items: flex-start; gap: 10px; font-size: 1em; }
    .soft-skills li span.icon { font-size: 1.3em; }

    /* Skill bars */
    .skill-group { margin-bottom: 25px; }
    .skill-label { font-weight: bold; margin-bottom: 8px; }
    .skill-bar-container { width: 100%; background-color: #ddd; border-radius: 8px; overflow: hidden; height: 25px; margin-bottom: 10px; }

    .skill-bar {
        height: 25px;
        border-radius: 8px;
        line-height: 25px;
        padding-left: 10px;
        font-weight: bold;
        color: white;
        position: relative;
        cursor: pointer;
        width: 0; /* start from 0 for animation */
        transition: width 1.5s ease-in-out;
    }

    /* Tooltip on hover */
    .skill-bar::after {
        content: attr(data-percent);
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0,0,0,0.7);
        color: #fff;
        padding: 2px 6px;
        font-size: 0.9em;
        border-radius: 4px;
        opacity: 0;
        transition: 0.3s;
        pointer-events: none;
    }

    .skill-bar:hover::after { opacity: 1; }

    /* Pink shades based on percentage */
    .pink-25 { background-color: #ffb6c1; } /* 25% */
    .pink-50 { background-color: #ff82a9; } /* 50% */
    .pink-75 { background-color: #ff4f88; } /* 75% */
    .pink-100 { background-color: #ff1f84; } /* 100% */

    /* Responsive */
    @media (max-width:768px){
        .skills-container { padding: 10px; }
        .skill-bar-container { height: 20px; }
        .skill-bar { line-height: 20px; padding-left: 5px; }
    }
</style>

<div class="skills-container">
    <h2 class="section-title">My Skills & Tools</h2>
    <p class="section-desc">These are the technologies, tools, and soft skills I’ve been mastering to build digital solutions.</p>

    <!-- Soft Skills -->
    <div class="soft-skills">
        <h3 style="margin-bottom:15px;">Soft Skills</h3>
        <ul>
            <li><span class="icon">🤝</span><span><strong>Teamwork:</strong> Collaborate effectively to achieve shared goals.</span></li>
            <li><span class="icon">🎨</span><span><strong>UI/UX Thinking:</strong> Design user-friendly and accessible interfaces.</span></li>
            <li><span class="icon">⚡</span><span><strong>Adaptability:</strong> Quickly adjust to new tools, environments, and challenges.</span></li>
            <li><span class="icon">💬</span><span><strong>Communication:</strong> Share ideas clearly and listen actively.</span></li>
        </ul>
    </div>

    <!-- Frontend -->
    <div class="skill-group">
        <div class="skill-label">Frontend</div>
        <div class="skill-bar-container">
            <div class="skill-bar pink-100" data-percent="90%" data-width="90%">HTML5</div>
        </div>
        <div class="skill-bar-container">
            <div class="skill-bar pink-75" data-percent="85%" data-width="85%">CSS3</div>
        </div>
        <div class="skill-bar-container">
            <div class="skill-bar pink-75" data-percent="80%" data-width="80%">JavaScript</div>
        </div>
        <div class="skill-bar-container">
            <div class="skill-bar pink-50" data-percent="60%" data-width="60%">React (Basics)</div>
        </div>
    </div>

    <!-- Backend -->
    <div class="skill-group">
        <div class="skill-label">Backend</div>
        <div class="skill-bar-container">
            <div class="skill-bar pink-75" data-percent="80%" data-width="80%">PHP (Laravel)</div>
        </div>
        <div class="skill-bar-container">
            <div class="skill-bar pink-50" data-percent="70%" data-width="70%">Python</div>
        </div>
        <div class="skill-bar-container">
            <div class="skill-bar pink-75" data-percent="75%" data-width="75%">PostgreSQL</div>
        </div>
    </div>

    <!-- Tools -->
    <div class="skill-group">
        <div class="skill-label">Tools</div>
        <div class="skill-bar-container">
            <div class="skill-bar pink-75" data-percent="85%" data-width="85%">Git & GitHub</div>
        </div>
        <div class="skill-bar-container">
            <div class="skill-bar pink-100" data-percent="90%" data-width="90%">VS Code</div>
        </div>
        <div class="skill-bar-container">
            <div class="skill-bar pink-50" data-percent="70%" data-width="70%">Figma</div>
        </div>
    </div>
</div>

<script>
    // Animate bars on page load
    document.addEventListener("DOMContentLoaded", function() {
        const bars = document.querySelectorAll(".skill-bar");
        bars.forEach(bar => {
            const width = bar.getAttribute("data-width");
            setTimeout(() => {
                bar.style.width = width;
            }, 200); // slight delay for smooth effect
        });
    });
</script>
@endsection
