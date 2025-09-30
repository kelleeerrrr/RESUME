<!DOCTYPE html>
<html>
<head>
    <title>Welcome - Resume App</title>
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

        /* Container with image background */
        .container {
            background: url('{{ asset("images/form-bg.jpg") }}') no-repeat center center;
            background-size: cover;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
            max-width: 400px;
            width: 100%;
            text-align: center;
            padding: 40px 30px;
            color: #fff;
        }

        h1 {
            color: #fff;
            margin-bottom: 20px;
            font-size: 2em;
        }

        p {
            color: #000000ff;
            font-size: 1.1em;
            margin-bottom: 30px;
        }

        .btn {
            display: block;
            width: 80%;
            margin: 10px auto;
            padding: 12px 0;
            font-size: 1em;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            color: #c45858ff;
        }

        .btn-signup { background: #303030ff; }
        .btn-signup:hover { background: #303030ff; transform: translateY(-2px); }

        .btn-login { background: #ffffffff; }
        .btn-login:hover { background: #ffffffff; transform: translateY(-2px); }

        .icon { margin-right: 8px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome</h1>
        <p>Please choose an option to get started</p>
        <a href="/signup" class="btn btn-signup"><i class="fas fa-user-plus icon"></i> Sign Up</a>
        <a href="/login" class="btn btn-login"><i class="fas fa-sign-in-alt icon"></i> Login</a>
    </div>
</body>
</html>
