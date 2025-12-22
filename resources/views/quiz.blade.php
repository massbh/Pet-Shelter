<!DOCTYPE html>
<html>

    <head>
        <title>Happinest</title>
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <link rel="stylesheet" href="{{ asset('css/header.css') }}">
        <link rel="stylesheet" href="{{ asset('css/quiz.css') }}">
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    <body>
        
        <!-- Header Container -->
        @include('components.header')

        <div class="form-container">
            <section id="quiz-intro" class="title-container">
                <h1 class="title">You can't decide?</h1>
                <h3 class="subtitle">Take the quiz to find your soulmate and save a life today.</h3>
                <img class="image" src="{{ asset('assets/collie-high-five.png') }}">
                <button class="start-button" id="start-quiz">Click here to begin</button>
            </section>
            
            <section id="quiz-container" class="quiz-section hidden">
                <div id="question-container">
                    <!-- Questions will load here if I don't mess up -->
                </div>
                <div id="progress-bar" class="progress-bar">
                    <div id="progress" class="progress"></div>
                </div>
            </section>

            <section id="results-container" class="results-section hidden">
                <h2 class="results-title">Your Perfect Match!</h2>
                <div id="results-content">
                    <!-- Results will load here -->
                </div>
            </section>
        </div>
        

        <!-- Footer Container -->
        @include('components.footer')
        
        <script src="{{ asset('js/quiz.js') }}"></script>
    </body>
</html>