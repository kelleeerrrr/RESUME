@extends('layout')

@section('title', 'Home | Irish Rivera')

@section('content')
<style>
    .container {
        max-width: 1100px;
        margin: 5px auto 15px auto;
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

    section {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 40px;
        animation: fadeIn 0.8s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    section h1 {
        font-size: 2.6em;
        color: var(--accent);
        margin-bottom: 10px;
        font-weight: 700;
    }

    section p {
        font-size: 1.1em;
        line-height: 1.7;
        color: #333;
    }

    body.dark section p {
        color: #ddd;
    }

    .tagline {
        font-style: italic;
        color: var(--accent);
        margin-top: 10px;
        font-size: 1.05em;
    }

    .btn {
        display: inline-block;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        background-color: var(--accent);
        color: white;
        transition: 0.3s;
        margin-right: 10px;
    }

    .btn:hover {
        opacity: 0.9;
        transform: translateY(-2px);
    }

    .btn-alt {
        background-color: #ff7f7f;
    }

    section img {
        width: 280px;
        border-radius: 50%;
        box-shadow: 0 0 12px rgba(0, 0, 0, 0.2);
        transition: 0.3s;
    }

    body.dark section img {
        box-shadow: 0 0 15px rgba(255, 255, 255, 0.15);
    }

    .contact-section {
        margin-top: 25px;
        font-size: 1.1em;
    }

    .contact-section p {
        margin-bottom: 10px;
        color: #333;
    }

    body.dark .contact-section p {
        color: #ddd;
    }

    .contact-section a {
        color: #ff69b4; /* Pink color */
        text-decoration: none;
        font-weight: 500;
        transition: 0.3s;
    }

    .contact-section a:hover {
        text-decoration: underline;
    }

    @media (max-width: 768px) {
        section {
            flex-direction: column;
            text-align: center;
            gap: 30px;
        }
        section img {
            margin-top: 20px;
        }
        .container {
            padding: 25px;
            margin: 20px;
        }
    }
</style>

<div class="container">
    <section>
        <div style="max-width:500px;">
            <h1>Hi, I’m Irish Rivera 👋</h1>
            <p>
                Bachelor of Science in Computer Science<br>
                <strong>Batangas State University TNEU – Alangilan Campus</strong><br>
                Aspiring <span style="color:var(--accent);">Cloud Engineer</span> & 
                <span style="color:var(--accent);">Cybersecurity Enthusiast</span>.
            </p>

            <p class="tagline">“Designing with logic, coding with purpose.”</p>

            <div style="margin-top:20px;">
                <a href="/projects" class="btn">View My Projects</a>
                <a href="/resume" class="btn btn-alt">View My Resume</a>
            </div>

            <div class="contact-section">
                <p>You can contact me here for collaboration or inquiries: 
                    <a href="mailto:23-00679@g.batstate-u.edu.ph">23-00679@g.batstate-u.edu.ph</a>
                </p>
            </div>
        </div>

        <img src="{{ asset('keller.jpg') }}" alt="Profile Picture">
    </section>
</div>

<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
@endsection

