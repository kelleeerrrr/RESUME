<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password - Resume App</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Full-page background */
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: Arial, sans-serif;
            background: url('{{ asset("images/bg.jpg") }}') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Container with image background and shadow */
        .form-container {
            position: relative;
            z-index: 1;
            background: url('{{ asset("images/form-bg.jpg") }}') no-repeat center center;
            background-size: cover;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.3);
            width: 350px;
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

        /* Button style */
        button {
            width: 100%;
            padding: 10px;
            background: #292828ff;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background: #000000ff;
        }

        /* Link style */
        a {
            text-decoration: none;
            display: block;
            text-align: center;
            margin-top: 10px;
            color: #fff;
        }

        /* Message styles */
        .error { color: #d21414ff; font-size: 0.9em; margin-bottom: 10px; }
        .success { color: #008332ff; font-size: 0.9em; margin-bottom: 10px; }
    </style>
</head>
<body>
<div class="form-container">
    <h2><i class="fas fa-key"></i> Forgot Password</h2>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    {{-- Error Message --}}
    @if($errors->any())
        <div class="error">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        {{-- Email input with always required message --}}
        <input type="email" name="email" placeholder="Enter your email" required
               oninvalid="this.setCustomValidity('All fields are required!')"
               oninput="this.setCustomValidity('')">

        {{-- Submit button --}}
        <button type="submit">Send Reset Link</button>
    </form>

    {{-- Back to login link --}}
    <a href="/login">Back to Login</a>
</div>
</body>
</html>
