<!DOCTYPE html>

<head>
    <title>Happinest - SignIn</title>
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
            <h2 class="sign-in">Sign-In</h2>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>

            <label for="password">Password</label>
            <input style="margin-bottom: 5px;" type="password" id="password" name="password" placeholder="Enter your password" required>
            <a class="forgotpass" href="">Forgot password?</a>

            <button type="submit" class="sign-in-submit">Sign in</button>

            <p style="font-size: 14px; font-weight: 300;">You don't have an account? <a class="sign-in-to-sign-up" href="/signup"> Create one</a></p>
        </form>
    </div>

</body>