<!DOCTYPE html>

<head>
    <title>Happinest - Sign Up</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body class="sign-in-body">
    <header class="sign-in-header">
        <a href="/"><img src="../assets/Logo.png" style="height: 70px; width: 70px;"></a>
        <a href="/" class="logotext">HAPPINEST</a>
    </header>

    <hr class="solid">

    <div class="sign-in-form-container">
        <form class="sign-in">
            <h2 class="sign-in">Create Account</h2>

            <label for="fullname">Full Name</label>
            <input type="text" id="fullname" name="fullname" placeholder="Enter your full name" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>

            <label for="phone">Phone Number</label>
            <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Create a password" required>

            <label for="confirm-password">Confirm Password</label>
            <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm your password" required>

            <div class="form-options">
                <label class="checkbox-label">
                    <input type="checkbox" name="newsletter" checked>
                    <span>Send me updates about pets and events</span>
                </label>
                <label class="checkbox-label">
                    <input type="checkbox" name="terms" required>
                    <span>I agree to the <a href="/terms">Terms & Conditions</a> and <a href="/privacy-policy">Privacy Policy</a></span>
                </label>
            </div>

            <button type="submit" class="sign-up-submit">Create Account</button>

            <div class="signup-link">
                <p>Already have an account? <a href="/signin">Sign In</a></p>
            </div>
        </form>
    </div>

</body>
