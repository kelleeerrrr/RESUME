<!DOCTYPE html>
<html>
<head>
    <title>Login - Resume App</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Full-page setup */
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: Arial, sans-serif;
            background: url('{{ asset("images/loginbg.jpg") }}') no-repeat center center fixed;
            background-size: cover;
        }

        /* Login container with image background */
        .form-container {
            position: relative;
            z-index: 1; /* Above the background */
            background: url('{{ asset("images/form-bg.jpg") }}') no-repeat center center;
            background-size: cover;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.3);
            width: 350px;
            margin: auto;
            top: 50%;
            transform: translateY(-50%);
            color: #fff; /* Default text color */
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
            background: #100e0eff;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background: #343b37ff;
        }

        a {
            text-decoration: none;
            color: #393b3aff; /* Default link color */
            display: block;
            text-align: center;
            margin-top: 10px;
        }

        /* White color for Back to Welcome link */
        a.back-welcome {
            color: #fff;
        }

        .error { color: #d21414ff; font-size: 0.9em; margin-bottom: 10px; }
        .success { color: #008332ff; font-size: 0.9em; margin-bottom: 10px; }
    </style>
</head>
<body>

    <!-- Login form -->
    <div class="form-container">
        <h2><i class="fas fa-sign-in-alt"></i> Login</h2>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        {{-- Error Message --}}
        @if($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="/login">
            @csrf
            <input type="text" name="login" placeholder="Username or Email" required
                   oninvalid="this.setCustomValidity('All fields are required!')"
                   oninput="this.setCustomValidity('')">
            <input type="password" name="password" placeholder="Password" required
                   oninvalid="this.setCustomValidity('All fields are required!')"
                   oninput="this.setCustomValidity('')">
            <button type="submit">Login</button>
        </form>

        <a href="/forgot-password"><i class="fas fa-key"></i> Forgot your password?</a>
        <a href="/signup"><i class="fas fa-user-plus"></i> Create new account</a>
        <a href="/" class="back-welcome">Back to Welcome</a>
    </div>

</body>
</html>
