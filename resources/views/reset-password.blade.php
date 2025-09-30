<!DOCTYPE html>
<html>
<head>
    <title>Reset Password - Resume App</title>
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

        button {
            width: 100%;
            padding: 10px;
            background: #343434ff;
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
            display: block;
            text-align: center;
            margin-top: 10px;
            color: #fff; /* white color for consistency */
        }

        .error {
            color: #d21414ff; /* red for errors */
            font-size: 0.9em;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<div class="form-container">
    <h2><i class="fas fa-key"></i> Reset Password</h2>

    {{-- Error Message --}}
    @if($errors->any())
        <div class="error">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="New Password" required>
        <input type="password" name="password_confirmation" placeholder="Confirm New Password" required>
        <button type="submit">Reset Password</button>
    </form>

    <a href="/login">Back to Login</a>
</div>
</body>
</html>

