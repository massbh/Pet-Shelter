<header class="main-header">
    <div>
        <a href="/"
            ><img
                class="logo"
                src="../assets/Logo.png"
                style="height: 70px; width: 70px"
        /></a>
        <a href="/" class="logotext">HAPPINEST</a>
    </div>

<div class="header-buttons">
    @if(Auth::check())
        <a href="/dashboard" class="sign-in-btn">
            <img class="sign-in-btn-icon" src="{{ asset('assets/Sign-in-profile.png') }}" />
            <p class="sign-in-text">{{ Auth::user()->name }}</p>
        </a>
    @else
        <a href="/signin" class="sign-in-btn">
            <img class="sign-in-btn-icon" src="{{ asset('assets/Sign-in-profile.png') }}" />
            <p class="sign-in-text">Sign-In</p>
        </a>
    @endif
</div>

</header>

<nav class="header-nav">
    <a href="/">HOME</a>
    <a href="/about">ABOUT US</a>
    <a href="/contact">CONTACT US</a>
    <a href="/qa">Q&A</a>
</nav>

