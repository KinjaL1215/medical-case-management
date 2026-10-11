<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Medical Case Management</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .login-card {
            background: white;
            width: 100%;
            max-width: 420px;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 8px 30px #0f172a15;
        }

        h1 {
            color: #1d4ed8;
            text-align: center;
            margin-bottom: 8px;
        }

        .subtitle {
            color: #64748b;
            text-align: center;
            margin-bottom: 28px;
        }

        label {
            display: block;
            margin: 16px 0 7px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 15px;
        }

        input:focus {
            outline: 2px solid #93c5fd;
            border-color: #2563eb;
        }

        button {
            width: 100%;
            padding: 12px;
            margin-top: 24px;
            border: none;
            border-radius: 6px;
            background: #1d4ed8;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background: #1e40af;
        }

        .error {
            color: #b91c1c;
            background: #fee2e2;
            border-radius: 6px;
            padding: 10px;
            margin-bottom: 12px;
        }
    </style>
</head>

<body>
<div class="login-card">
    <h1>Medical Case Management</h1>
    <p class="subtitle">Sign in to your account</p>

    @if ($errors->any())
        <div class="error">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('login.submit') }}">
        @csrf

        <label for="email">Email Address</label>
        <input
            id="email"
            type="email"
            name="email"
            value="{{ old('email') }}"
            placeholder="Enter your email"
            required
            autofocus
        >

        <label for="password">Password</label>
        <input
            id="password"
            type="password"
            name="password"
            placeholder="Enter your password"
            required
        >

        <button type="submit">Login</button>
    </form>
</div>
</body>
</html>
