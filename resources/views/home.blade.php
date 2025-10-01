<!DOCTYPE html>
<html>
    <head>
        <title>Home Page</title>
        <link rel="stylesheet" href="../css/index.css">
        <script src="../js/main.js" defer></script>
    </head>
    <body>
        <header>
            <div>
                <img src="../assets/Logo.png">
                <h1>HAPPINESS</h1>
            </div>
        
            <button class="sign-in-btn">
                <img src="../assets/Sign-in-profile.png" >
                <p>Sign In</p>
            </button>
        </header>

        <nav>
            <a href="">HOME</a>
            <a href="">ABOUT US</a>
            <a href="">Q&A</a>
        </nav>

        <main>
            <section>
                <div class="container">
                    <img src="../assets/Front-image.png" class="front-image">
                    <div class="front-image-bar"></div>

                    <!-- Overlayed Text -->
                    <div class="text-overlay">
                        <h1>Find your New Best Friend and Adopt a Pet with Petfinder</h1>
                        <p>Browse pets from our network of over 14,500 shelters and rescues.</p>

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
                            <img src="../assets/Query-paw.png">
                            <p>Detailed search</p>
                        </button>
                    </div> 
                    
                    <div class="detailed-search hidden">
                        <button onclick="toggleDetailedSearch()" class="close-detailed-search">&lt;</button>
                        <h3>Detailed Search</h3>

                        <div class="detailed-search-selections">
                            <!-- Select sex -->
                            
                            <p>Animal</p>
                            <details>
                                <summary>Any</summary>
                                <div id="speciesButtons"></div>
                            </details>
                            
                            
                            <p>Gender</p>
                            <details>
                                <summary>Any</summary>
                                <div>
                                    <Button onclick="filterState.sex = 'Male'; advancedFilter(filterState)">Male</Button>
                                    <Button onclick="filterState.sex = 'Female'; advancedFilter(filterState)">Female</Button>
                                </div>
                            </details>



                        </div>

                    </div>


                </div>
            </section>

            <section>
                <h3 class="animal-adoption">Pets Available for Adoption &gt;</h3>

                <div id="cards"></div>

            </section>


        </main>

        <footer>
            <div>
                <img src="../assets/LogoWhite.png">
                <h1>HAPPINESS</h1>
            </div>
            <a href="">FAQ's</a>
            <a href="">Contact Us</a>

            <button class="sign-up-btn">Sign up</button>
        </footer>
    </body>
</html>