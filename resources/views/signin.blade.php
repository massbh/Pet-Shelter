<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Happinest - SignIn</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 0.75rem;
            border-radius: 4px;
            margin-bottom: 1rem;
            border: 1px solid #f5c6cb;
            font-size: 14px;
        }
        .field-error {
            color: #dc3545;
            font-size: 12px;
            margin-top: 0.25rem;
            display: block;
        }
        .input-error {
            border-color: #dc3545 !important;
        }
    </style>
</head>

<body class="sign-in-body">
    <header class="sign-in-header">
        <a href="/"><img src="{{ asset('assets/Logo.png') }}" style="height: 70px; width: 70px;" alt="Happinest Logo"></a>
        <a href="/" class="logotext">HAPPINEST</a>
    </header>

    <hr class="solid">

    <div class="sign-in-form-container">
        <form class="sign-in" method="POST" action="{{ route('login') }}">
            @csrf
            
            <h2 class="sign-in">Sign-In</h2>

            @if ($errors->any())
                <div class="error-message">
                    @if ($errors->has('email'))
                        {{ $errors->first('email') }}
                    @else
                        <strong>Error:</strong> Please check your input and try again.
                    @endif
                </div>
            @endif

            <label for="email">Email</label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                placeholder="Enter your email" 
                value="{{ old('email') }}"
                class="@error('email') input-error @enderror"
                required
                autocomplete="email">
            @error('email')
                <span class="field-error">{{ $message }}</span>
            @enderror

            <label for="password">Password</label>
            <input 
                style="margin-bottom: 5px;" 
                type="password" 
                id="password" 
                name="password" 
                placeholder="Enter your password" 
                class="@error('password') input-error @enderror"
                required
                autocomplete="current-password">
            @error('password')
                <span class="field-error">{{ $message }}</span>
            @enderror
            
            <a class="forgotpass" href="#">Forgot password?</a>

            <button type="submit" class="sign-in-submit">Sign in</button>

            <p style="font-size: 14px; font-weight: 300;">You don't have an account? <a class="sign-in-to-sign-up" href="{{ route('register') }}"> Create one</a></p>
        </form>
    </div>

</body>
</html>