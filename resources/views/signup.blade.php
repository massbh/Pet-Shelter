<!DOCTYPE html>
<html lang="en">
      <script>
      window.user = @json(auth()->user() ? ['name' => auth()->user()->name] :
      null);
  </script>;
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Happinest - Sign Up</title>
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
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 0.75rem;
            border-radius: 4px;
            margin-bottom: 1rem;
            border: 1px solid #c3e6cb;
            font-size: 14px;
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
        <form class="sign-in" method="POST" action="{{ route('register') }}">
            @csrf
            
            <h2 class="sign-in">Create Account</h2>

            @if ($errors->any())
                <div class="error-message">
                    <strong>Please correct the following errors:</strong>
                    <ul style="margin: 0.5rem 0 0 0; padding-left: 1.25rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <label for="name">Full Name</label>
            <input 
                type="text" 
                id="name" 
                name="name" 
                placeholder="Enter your full name" 
                value="{{ old('name') }}"
                class="@error('name') input-error @enderror"
                required
                autocomplete="name"
                maxlength="255">
            @error('name')
                <span class="field-error">{{ $message }}</span>
            @enderror

            <label for="email">Email</label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                placeholder="Enter your email" 
                value="{{ old('email') }}"
                class="@error('email') input-error @enderror"
                required
                autocomplete="email"
                maxlength="255">
            @error('email')
                <span class="field-error">{{ $message }}</span>
            @enderror

            <label for="password">Password</label>
            <input 
                type="password" 
                id="password" 
                name="password" 
                placeholder="Create a password (min. 8 characters)" 
                class="@error('password') input-error @enderror"
                required
                autocomplete="new-password"
                minlength="8"
                maxlength="255">
            <small style="color: #666; font-size: 11px; display: block; margin-top: 0.25rem;">
                Must contain uppercase, lowercase, and number
            </small>
            @error('password')
                <span class="field-error">{{ $message }}</span>
            @enderror

            <label for="password_confirmation">Confirm Password</label>
            <input 
                type="password" 
                id="password_confirmation" 
                name="password_confirmation" 
                placeholder="Confirm your password" 
                class="@error('password_confirmation') input-error @enderror"
                required
                autocomplete="new-password"
                minlength="8"
                maxlength="255">
            @error('password_confirmation')
                <span class="field-error">{{ $message }}</span>
            @enderror

            <div class="form-options">
                <label class="checkbox-label">
                    <input type="checkbox" name="terms" required>
                    <span>I agree to the <a href="{{ route('terms') }}">Terms & Conditions</a> and <a href="{{ url('/privacy-policy') }}">Privacy Policy</a></span>
                </label>
            </div>

            <button type="submit" class="sign-up-submit">Create Account</button>

            <div class="signup-link">
                <p>Already have an account? <a href="{{ route('login') }}">Sign In</a></p>
            </div>
        </form>
    </div>

</body>


</html>