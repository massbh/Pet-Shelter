<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Home Page</title>
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <script src="{{ asset('js/home_filter.js') }}" defer></script>
        <script src="{{ asset('js/loadComponents.js') }}" defer></script>
    </head>
    <body>
        <!-- Header Container -->
        <div id="header-container"></div>

        <main>
            <section class="hero-frontpage">
                <img src="{{ asset('assets/Front-image.png') }}" id="frontpage-hero-image" alt="Cuddling cat and dog">
                <div id="front-image-bar"></div>
                <div id="hero-frontpage-overlay">
                    <h1>Every Pet Deserves a Second Chance — Maybe You are Theirs.</h1>
                    <p>Adopt pets ready to fill your home with love and laughter. Help us make a difference.</p>

                    <div>
                        <div class="query-search">
                            <button class="filter-btn filter-btn-design" data-species="Dog">
                                <img src="{{ asset('assets/Query-dog.png') }}" alt="Dogs face">
                                <p>Dogs</p>
                            </button>

                            <button class="filter-btn filter-btn-design" data-species="Cat">
                                <img src="{{ asset('assets/Query-cat.png') }}" alt="Cats face">
                                <p>Cats</p>
                            </button>

                            <button class="filter-btn filter-btn-design" data-species="Other">
                                <img src="{{ asset('assets/Query-paw.png') }}" alt="Paw">
                                <p>Other Animals</p>
                            </button>

                            <button class="filter-btn filter-btn-design" id="detailed-search-btn">
                                <img src="{{ asset('assets/Filter-logo.png') }}" alt="Filter icon">
                                <p>Detailed search</p>
                            </button>
                        </div>

                        <!-- Reset button centered below filters -->
                        <div class="reset-search-container">
                            <button id="reset-search-btn">
                                <p>Reset Filters</p>
                            </button>
                        </div>

                        <!-- Detailed search form -->
                        <form class="detailed-search-form hidden" id="advanced-filter-form">
                            <button type="button" id="close-detailed-search">&lt;</button> 
                            <h2 id="filter-title">Detailed Search</h2>
                            
                            <div class="filter-fieldsets">
                                <!-- Filter by Animal -->
                                <fieldset>
                                    <Legend>Animal</Legend>
                                    <div id="speciesButtons" class="dse-btns"></div>
                                </fieldset>

                                <!-- Filter by Sex-->
                                <fieldset>
                                    <Legend>Gender</Legend>
                                    <div class="dse-btns">
                                        <input type="radio" id="sex-male" name="sex" value="Male">
                                        <label for="sex-male">Male</label>

                                        <input type="radio" id="sex-female" name="sex" value="Female">
                                        <label for="sex-female">Female</label>
                                    </div>
                                </fieldset>
                            </div>

                            <!-- Filter by Age: Slider -->
                            <fieldset class="age-slider">
                                <legend>Age</legend>
                                <div class="drange">
                                    <input type="range" min="1" max="20" value="1" id="sliderMinValue">
                                    <input type="range" min="1" max="20" value="20" id="sliderMaxValue">
                                    <div class="dmin">0</div>
                                    <div class="dmax">0</div>
                                </div>
                            </fieldset>

                            <button type="submit" class="advanced-filter-btn">Filter</button>
                        </form>
                    </div>
                </div>
            </section>

            <section>
                <h2 class="animal-adoption">Pets Available for Adoption &gt;</h2>
                <div class="cards" id="cards"></div>
            </section>
        </main>
        
        <!-- Footer Container -->
        <div id="footer-container"></div>
    </body>
</html>