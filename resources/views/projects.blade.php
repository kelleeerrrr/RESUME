@extends('layout')

@section('title', 'Projects | Irish Rivera')

@section('content')
<style>
    /* ===== Projects Container ===== */
    .projects-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 30px;
        max-width: 1100px;
        margin: 40px auto;
    }

    /* ===== Project Card ===== */
    .project-card {
        display: flex;
        flex-direction: column;
        background: #ffffffcc;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 14px rgba(0,0,0,0.1);
        transition: transform 0.3s, box-shadow 0.3s;
        min-height: 480px; /* uniform card height */
    }

    body.dark .project-card {
        background: #2a2a2acc;
    }

    .project-card img {
        width: 100%;
        height: 180px;
        object-fit: cover;
    }

    .project-card-content {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 20px;
        flex: 1;
    }

    .project-card h3 {
        font-size: 1.3em;
        color: var(--accent);
        margin-bottom: 5px;
    }

    .tagline {
        font-style: italic;
        color: #555;
        margin-bottom: 10px;
        font-size: 0.95em;
    }

    body.dark .tagline {
        color: #ddd;
    }

    .description {
        font-size: 1em;
        line-height: 1.5;
        margin-bottom: 15px;
        color: #333;
    }

    body.dark .description {
        color: #ffffffdd;
    }

    .tech-icons span {
        margin-right: 8px;
        font-size: 0.9em;
    }

    .project-links {
        display: flex;
        gap: 10px;
    }

    .project-links a {
        text-decoration: none;
        background-color: var(--accent);
        color: white;
        padding: 8px 12px;
        border-radius: 5px;
        font-weight: 500;
        transition: 0.3s;
        text-align: center;
        flex: 1;
    }

    .project-links a:hover {
        opacity: 0.9;
        transform: translateY(-2px);
    }

    /* See More Button */
    .see-more {
        display: block;
        max-width: 200px;
        margin: 30px auto 0 auto;
        text-align: center;
        text-decoration: none;
        background-color: #ff7f7f;
        color: white;
        padding: 10px 20px;
        border-radius: 6px;
        font-weight: 500;
        transition: 0.3s;
    }

    .see-more:hover {
        opacity: 0.9;
        transform: translateY(-2px);
    }

    /* ===== Responsive ===== */
    @media (max-width: 768px) {
        .projects-container {
            grid-template-columns: 1fr;
        }
        .project-card {
            min-height: auto;
        }
    }
</style>

<h2 style="color:var(--accent); text-align:center;">Featured Projects</h2>
<p style="text-align:center; max-width:700px; margin: 10px auto 30px auto; font-size:1.1em; color:#fffff;">
    These are my key projects demonstrating skills in software, automation, and embedded systems.
</p>

<div class="projects-container">
    {{-- PRESENCE Project --}}
    <div class="project-card">
        <img src="{{ asset('images/presence.jpg') }}" alt="PRESENCE Project Image">
        <div class="project-card-content">
            <div>
                <h3>💡 PRESENCE: Smart Appliance Controller</h3>
                <p class="tagline">Automating home energy usage intelligently with presence detection.</p>
                <p class="description">
                    Detects human presence using OpenCV and controls appliances automatically, saving energy and improving convenience.
                </p>
                <div class="tech-icons">
                    <span>🐍 Python</span>
                    <span>🖥️ Tkinter</span>
                    <span>📷 OpenCV</span>
                </div>
            </div>
            <div class="project-links">
                <a href="#">View Details</a>
                <a href="https://github.com/yourusername/presence" target="_blank">GitHub</a>
            </div>
        </div>
    </div>

    {{-- APAC Project --}}
    <div class="project-card">
        <img src="{{ asset('images/apac.jpg') }}" alt="APAC Project Image">
        <div class="project-card-content">
            <div>
                <h3>🚦 APAC: Arduino-Powered Pedestrian & Crosswalk Lights</h3>
                <p class="tagline">Smart traffic management system to improve pedestrian safety.</p>
                <p class="description">
                    Uses Arduino and sensors to automate crosswalk signals, monitoring pedestrian flow for safer road usage.
                </p>
                <div class="tech-icons">
                    <span>⚡ Arduino</span>
                    <span>📡 Sensors</span>
                    <span>C/C++</span>
                </div>
            </div>
            <div class="project-links">
                <a href="#">View Details</a>
                <a href="https://github.com/yourusername/apac" target="_blank">GitHub</a>
            </div>
        </div>
    </div>
</div>

<a href="/projects/all" class="see-more">See More Projects →</a>
@endsection
