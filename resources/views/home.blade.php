<!DOCTYPE html>
<html>
    <head>
        <title>Home Page</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <script src="{{ asset('js/home_filter.js') }}"></script>
    </head>
    <body>

        <!-- Header Container -->
        <div id="header-container"></div>

        <main>
            <section>
                <div class="container">
                    <img src="../assets/Front-image.png" class="front-image">
                    <div class="front-image-bar"></div>

                    <!-- Overlayed Text -->
                    <div class="text-overlay">
                        <h1 style="color: white; text-shadow: 2px 2px 8px rgba(0,0,0,0.8), 0px 0px 20px rgba(0,0,0,0.6);">Every Pet Deserves a Second Chance - Maybe You are Theirs.</h1>
                        <p style="text-shadow: 0 2px 6px #0000008f;">Adopt pets ready to fill your home with love and laughter. Help us make a difference.</p>

                        <!-- Overlayed Query -->
                     <div class="query-search">
                        <button onclick="filterState.species = 'Dog'; advancedFilter(filterState)">
                            <img src="../assets/Query-dog.png">
                            <p>Dogs</p>
                        </button>
                        <button onclick="filterState.species = 'Cat'; advancedFilter(filterState)">
                            <img src="../assets/Query-cat.png">
                            <p>Cat</p>
                        </button>
                        <button onclick="filterState.species = 'Other'; advancedFilter(filterState)">
                            <img src="../assets/Query-paw.png">
                            <p>Other Animals</p>
                        </button>
                        <button onclick="toggleDetailedSearch()">
                            <img src="../assets/Filter-logo.png">
                            <p>Detailed search</p>
                        </button>
                    </div> 
                    
                    <div class="detailed-search hidden">
                        <button onclick="toggleDetailedSearch()" class="close-detailed-search">&lt;</button>
                        <h2>Detailed Search</h2>

                        <div>
                            <!-- Select animal -->
                            <section>
                                <p>Animal</p>
                                <details>
                                    <summary id="animalTextSpecies">Any</summary>
                                    <div class="dse-buttons" id="speciesButtons"></div>
                                </details>
                            </section>
                            
                           
                            <!-- Select sex -->
                            <section>
                                <p>Gender</p>
                                <details>
                                    <summary id="animalTextGender">Any</summary>
                                    <div class="dse-buttons">
                                        <button onclick="filterState.sex = 'Male'; setAnimalText(2, 'Male')">Male</Button>
                                        <button onclick="filterState.sex = 'Female'; setAnimalText(2, 'Female')">Female</Button>
                                    </div>
                                </details>
                             </section>
                        </div>

                        <!-- Select age -->
                        <section>
                            <p>Age</p>
                            <div class="drange">
                                <input type="range" min="1" max="20" value="1" id="sliderMinValue">
                                <input type="range" min="1" max="20" value="20" id="sliderMaxValue">
                                <div class="dmin">0</div>
                                <div class="dmax">0</div>
                            </div>
                         </section>

                        <button onclick="
                            filterState.age.minAge = +document.getElementById('sliderMinValue').value;
                            filterState.age.maxAge = +document.getElementById('sliderMaxValue').value;
                            advancedFilter(filterState)" class="advanced-filter">Filter</button>



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
        
        <!-- Script to load components -->
        <script src="js/loadComponents.js"></script>
    </body>
</html>