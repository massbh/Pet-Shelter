<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Happinest - Pet Gallery</title>
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <link rel="stylesheet" href="{{ asset('css/header.css') }}">
        <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
        <script src="{{ asset('js/home_filter.js') }}" defer></script>
    </head>
    <body>
        <main>
            <section class="hero gallery">
                <div class="hero-inner">
                    <h1>Pet Gallery</h1>
                    <p>Here you can view and manage pets. Use the quick filters below to narrow results.</p>
                </div>
            
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
                    <button class="filter-btn filter-btn-design detailed-search-btn">
                        <img src="{{ asset('assets/Filter-logo.png') }}" alt="Filter icon">
                        <p>Detailed search</p>
                    </button>
                </div>
            </section>

            <form class="detailed-search-form hidden" id="advanced-filter-form">
                <button type="button" class="close-detailed-search">&lt;</button> 
                <h2 id="filter-title">Detailed Search</h2>
                <div class="filter-fieldsets">
                    <fieldset class="fieldset-animal">
                        <Legend>Animal</Legend>
                        <div id="speciesButtons" class="dse-btns"></div>
                    </fieldset>
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

            <section class="controls-row">
                <a href="{{ route('dashboard') }}" class="btn go-back-btn"><- Dashboard</a>
                <button id="reset-search-btn" class="btn reset-btn">
                    <p>Reset Filters</p>
                </button>
                <a href="{{ route('pets.create') }}" class="btn create-btn">+ Add New Pet</a>
            </section>

            @if(session('success'))
                <div class="flash-message success">{{ session('success') }}</div>
            @endif
            <h2 class="caption">Pets Available &gt;</h2>
            <div class="cards" id="cards"></div>
        </main>
    </body>
</html>