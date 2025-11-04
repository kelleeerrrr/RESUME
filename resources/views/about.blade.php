@extends('layout')

@section('title', 'About | Irish Rivera')

@section('content')
<style>
    .container {
        max-width: 1000px;
        margin: -30px auto 15px auto;
        background: #ffffff;
        padding: 35px 40px;
        border-radius: 14px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        transition: background 0.4s, box-shadow 0.3s;
    }

    body.dark .container {
        background: #2e2e2e;
        box-shadow: 0 0 25px rgba(255, 255, 255, 0.05);
    }

    h2 {
        color: var(--accent);
        margin-top: 10px;
        font-size: 2em;
        font-weight: 700;
    }

    h3 {
        margin-top: 50px;
        color: var(--accent);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    p {
        max-width: 750px;
        line-height: 1.8;
        font-size: 1.05em;
        color: #333;
    }

    body.dark p {
        color: #ddd;
    }

    ul {
        list-style: none;
        padding-left: 0;
        margin-top: 15px;
    }

    ul li {
        background: rgba(255, 182, 193, 0.15);
        margin-bottom: 8px;
        padding: 10px 15px;
        border-radius: 8px;
        transition: background 0.3s;
    }

    body.dark ul li {
        background: rgba(255, 255, 255, 0.05);
    }

    ul li:hover {
        background: rgba(255, 182, 193, 0.3);
    }

    .quote {
        font-style: italic;
        color: var(--accent);
        margin-top: 25px;
        font-size: 1.05em;
    }

    @media (max-width: 768px) {
        .container {
            padding: 25px;
            margin: 20px;
        }
    }
</style>

<div class="container">
    <h2><i class="fas fa-user"></i> About Me</h2>
    <p>
        I’m <strong>Irish Rivera</strong>, a passionate student currently pursuing a 
        <strong>Bachelor of Science in Computer Science</strong> at 
        <strong>Batangas State University TNEU – Alangilan Campus</strong>.
        I’m deeply interested in technology, particularly in 
        <strong>software engineering</strong>, <strong>web development</strong>, and <strong>cybersecurity</strong>.
        I believe that innovation and creativity can empower people to build a smarter, safer, and more connected digital world.
    </p>

    <p class="quote">“Empowered by curiosity, driven by code.”</p>

    <h3><i class="fas fa-graduation-cap"></i> Educational Background</h3>
    <ul>
        <li>🎓 Batangas State University – TNEU, Alangilan Campus (BSCS, Present)</li>
        <li>🏫 STI College – Batangas (Senior High School, 2023)</li>
        <li>📘 Tinga Itaas Elementary School (Elementary, 2017)</li>
    </ul>
</div>

<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
@endsection
