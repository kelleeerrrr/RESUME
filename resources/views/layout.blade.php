<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        /* 🌸 Root Variables (Theme & Color System) */
        :root {
            --accent: #ff1f84;
            --accent-light: #ff8db5;
            --bg-light: #f9f9fb;
            --bg-dark: #1e1e1e;
            --text-light: #222;
            --text-dark: #ffffff;
        }

        /* 🌸 Base Layout */
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: url('{{ asset("images/resumebg.jpg") }}') no-repeat center center fixed;
            background-size: cover;
            color: var(--text-light);
            transition: background 0.4s, color 0.4s;
        }

        body.dark {
            background: linear-gradient(to bottom right, #1a1a1a, #2a2a2a),
                        url('{{ asset("images/resumebg.jpg") }}') no-repeat center center fixed;
            background-blend-mode: multiply;
            color: var(--text-dark);
        }

        /* 🌸 Top Navigation Bar */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: var(--accent);
            padding: 12px 30px;
            position: sticky;
            top: 0;
            z-index: 999;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        /* Logo */
        .navbar .logo {
            height: 50px;
            width: auto;
            margin-right: 15px;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-links a {
            text-decoration: none;
            color: white;
            font-weight: 500;
            padding: 6px 10px;
            border-radius: 6px;
            transition: 0.3s;
            position: relative;
        }

        /* 🌸 Hover & Active Link Feedback */
        .nav-links a:hover {
            background: rgba(255,255,255,0.2);
        }

        .nav-links a.active {
            border: 2px solid white;
        }

        body.dark .nav-links a.active {
            border: 2px solid var(--accent-light);
            color: var(--accent-light);
        }

        /* 🌸 Dark Mode Toggle */
        .dark-mode-toggle {
            background: none;
            border: none;
            color: white;
            font-size: 20px;
            cursor: pointer;
            margin-left: 10px;
            transition: transform 0.3s;
        }

        .dark-mode-toggle:hover {
            transform: rotate(20deg);
        }

        /* 🌸 Page Container */
        .page-container {
            max-width: 1100px;
            margin: 60px auto 40px auto;
            padding: 0 20px;
            animation: fadeIn 0.6s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* 🌸 Reusable Button Styles */
        .btn, .back-btn {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 6px;
            background-color: var(--accent);
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: 0.3s;
        }

        .btn:hover, .back-btn:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }

        /* 🌸 Success Message Feedback */
        .success-banner {
            background: #2ecc71;
            color: white;
            padding: 10px;
            text-align: center;
            font-weight: 600;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            animation: slideDown 0.5s ease;
        }

        @keyframes slideDown {
            from { transform: translateY(-100%); }
            to { transform: translateY(0); }
        }

        /* 🌸 Footer */
        footer {
            text-align: center;
            padding: 15px;
            background: rgba(255,255,255,0.7);
            color: #555;
            font-size: 0.9em;
        }

        body.dark footer {
            background: rgba(0,0,0,0.3);
            color: #ccc;
        }

        /* 🌸 Print Handling */
        @media print {
            body {
                background: white !important;
                color: black !important;
            }
            .navbar, .dark-mode-toggle, footer, .success-banner {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    {{-- ✅ Feedback Message --}}
    @if(session('success'))
        <div class="success-banner" id="successBanner">
            {{ session('success') }}
        </div>
    @endif

    {{-- 🌸 Navbar --}}
    <nav class="navbar">
        <a href="/home">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo">
        </a>
        <div class="nav-links">
            <a href="/home" class="{{ request()->is('home') ? 'active' : '' }}">Home</a>
            <a href="/about" class="{{ request()->is('about') ? 'active' : '' }}">About</a>
            <a href="/projects" class="{{ request()->is('projects') ? 'active' : '' }}">Projects</a>
            <a href="/skills" class="{{ request()->is('skills') ? 'active' : '' }}">Skills</a>
            <a href="/resume" class="{{ request()->is('resume') ? 'active' : '' }}">Resume</a>
            <a href="/contact" class="{{ request()->is('contact') ? 'active' : '' }}">Contact</a>
            <a href="/logout" class="btn" style="background:#6d6a6c;">Logout</a>
            <button class="dark-mode-toggle" id="themeToggle" title="Toggle Dark Mode">🌙</button>
        </div>
    </nav>

    {{-- 🌸 Page Content --}}
    <div class="page-container">
        @yield('content')
    </div>

    {{-- 🌸 Footer --}}
    <footer>
        © {{ date('Y') }} Irish Rivera • Designed with 💗 
    </footer>

    {{-- 🌸 Script for Interactivity & Feedback --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const banner = document.getElementById("successBanner");
            if (banner) setTimeout(() => banner.style.display = "none", 4000);

            const toggle = document.getElementById('themeToggle');
            const body = document.body;

            // Remember theme
            if (localStorage.getItem('theme') === 'dark') {
                body.classList.add('dark');
                toggle.textContent = '🌞';
            }

            toggle.addEventListener('click', () => {
                body.classList.toggle('dark');
                const isDark = body.classList.contains('dark');
                toggle.textContent = isDark ? '🌞' : '🌙';
                localStorage.setItem('theme', isDark ? 'dark' : 'light');
            });
        });
    </script>
</body>
</html>
