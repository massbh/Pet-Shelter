<!DOCTYPE html>
<html>
    <head>
        <title>Home Page</title>
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
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
                        <h1>Find your New Best Friend and Adopt a Pet with Petfinder</h1>
                        <p>Browse pets from our network of over 14,500 shelters and rescues.</p>

                        <!-- Overlayed Query -->
                     <div class="query-search">
                        <a href="">
                            <img src="../assets/Query-dog.png">
                            <p>Dogs</p>
                        </a>
                        <a href="">
                            <img src="../assets/Query-cat.png">
                            <p>Cats</p>
                        </a>
                        <a href="">
                            <img src="../assets/Query-paw.png">
                            <p>Other Animals</p>
                        </a>
                    </div>                
                </div>
            </section>

            <section class="main">
                <h3>Pets Available for Adoption ></h3>
            </section>


        </main>

        <!-- Footer Container -->
        <div id="footer-container"></div>
        
        <!-- Script to load components -->
        <script src="js/loadComponents.js"></script>
    </body>
</html>