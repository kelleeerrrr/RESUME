<!DOCTYPE html>
<html>
<head>
    <title>Sign Up - Resume App</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Full-page background */
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: Arial, sans-serif;
            background: url('{{ asset("images/signupbg.jpg") }}') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Signup container with image background */
        .form-container {
            position: relative;
            z-index: 1;
            background: url('{{ asset("images/form-bg.jpg") }}') no-repeat center center;
            background-size: cover;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.3);
            width: 380px;
            color: #fff;
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #fff;
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #212324ff;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background: #000000ff;
        }

        a {
            text-decoration: none;
            color: #fff; /* Default link color (white) */
            display: block;
            text-align: center;
            margin-top: 10px;
        }

        /* Black color for Back to Welcome link */
        a.back-welcome {
            color: #000;
        }

        .error { color: #d21414ff; font-size: 0.9em; margin-bottom: 10px; }
        .success { color: #008332ff; font-size: 0.9em; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2><i class="fas fa-user-plus"></i> Sign Up</h2>

        {{-- Error Message --}}
        @if ($errors->any())
            <div class="error">
                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        {{-- Success Message --}}
        @if (session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="/register">
            @csrf
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="password_confirmation" placeholder="Confirm Password" required>

            <button type="submit">Create Account</button>
        </form>

        <a href="/login"><i class="fas fa-sign-in-alt"></i> Already have an account? Login</a>
        <a href="/" class="back-welcome">Back to Welcome</a>
    </div>
</body>
</html>
