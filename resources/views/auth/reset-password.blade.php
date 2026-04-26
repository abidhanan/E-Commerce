<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #ffffff;
            margin: 0;
        }

        .container {
            max-width: 420px;
            margin: 80px auto;
            padding: 32px;
            border: 1px solid #e5e5e5;
        }

        h1 {
            font-size: 22px;
            margin-bottom: 10px;
        }

        p {
            font-size: 13px;
            color: #666;
            margin-bottom: 20px;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 14px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        input:focus {
            outline: none;
            border-color: #000;
        }

        button {
            width: 100%;
            padding: 13px;
            background: #000;
            color: #fff;
            border: none;
            cursor: pointer;
            font-weight: 500;
        }

        button:hover {
            background: #333;
        }

        .error {
            color: red;
            font-size: 12px;
            margin-bottom: 10px;
        }

        .success {
            color: green;
            font-size: 13px;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

<div class="container">

    <h1>Reset Password</h1>
    <p>Enter your new password below.</p>

    {{-- SUCCESS --}}
    @if (session('status'))
        <div class="success">{{ session('status') }}</div>
    @endif

    {{-- ERROR --}}
    @if ($errors->any())
        <div class="error">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        {{-- TOKEN (WAJIB) --}}
        <input type="hidden" name="token" value="{{ request()->route('token') }}">

        {{-- EMAIL --}}
        <input type="email" name="email" value="{{ request('email') }}" required>

        {{-- PASSWORD --}}
        <input type="password" name="password" placeholder="New Password" required>

        {{-- CONFIRM PASSWORD --}}
        <input type="password" name="password_confirmation" placeholder="Confirm Password" required>

        <button type="submit">RESET PASSWORD</button>
    </form>

</div>

</body>
</html>