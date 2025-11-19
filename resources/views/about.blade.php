<!DOCTYPE html>
<html>
    <head>
        <title>Happinest - About Us</title>
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <link rel="stylesheet" href="{{ asset('css/header.css') }}">
        <link rel="stylesheet" href="{{ asset('css/about.css') }}">

    </head>
    <body>
        
        <!-- Header Container -->
  
        @include('components.header')

        
        <!-- Content -->
        <div class="about-hero">
            <h1>About Happinest Pet Shelter</h1>
            <p>Creating forever homes for pets in need since 2018</p>
        </div>

        <div class="about-container">
            <!-- Mission & Vision Section -->
            <div class="about-section">
                <div class="about-content">
                    <div class="about-text">
                        <h2>Our Mission & Vision</h2>
                        <p>At Happinest, our mission is to rescue, rehabilitate, and rehome abandoned and neglected animals while promoting responsible pet ownership through community education and outreach programs.</p>
                        <p>We envision a world where every pet has a loving home and no animal suffers from neglect or abandonment. Through our comprehensive adoption programs, innovative initiatives, and educational efforts, we're working toward creating a more compassionate community for all animals.</p>
                    </div>
                    <div class="about-image">
                        <img src="{{ asset('assets/about/mission.jpg') }}" alt="Kitten after being recued">
                    </div>
                </div>
            </div>

            <!-- Shelter History Section -->
            <div class="about-section bg-light">
                <div class="about-content reverse">
                    <div class="about-text">
                        <h2>Our Story</h2>
                        <p>Founded in 2018 by Dr. Mubashrah Saddiqa, a commited cat lover, Happinest began as a small foster-based rescue operating out of the SDU university campus. After witnessing the overwhelming number of homeless pets in our community, Dr. Saddiqa dedicated her life to creating a safe haven for animals in need.</p>
                        <p>What started with just a handful of volunteers has grown into a comprehensive animal shelter that has helped over 5,000 pets find their forever homes. In 2023, we opened our current facility, which includes medical facilities, spacious adoption areas, and dedicated training spaces.</p>
                    </div>
                    <div class="about-image">
                        <img src="{{ asset('assets/about/history.jpg') }}" alt="Our shelter">
                    </div>
                </div>
            </div>

            <!-- Team Section -->
            <div class="about-section">
                <div class="about-content">
                    <div class="about-text">
                        <h2>Our Dedicated Team</h2>
                        <p>Our team consists of passionate animal lovers including veterinarians, veterinary technicians, animal behaviorists, adoption counselors, and dedicated volunteers who work tirelessly to ensure the wellbeing of every animal in our care.</p>
                        <p>We believe that every pet deserves individual attention and personalized care. Our staff-to-animal ratio ensures that each resident receives the love, training, and medical care they need to thrive while waiting for their forever family.</p>
                    </div>
                    <div class="about-image">
                        <img src="{{ asset('assets/about/team.jpg') }}" alt="Our team members">
                    </div>
                </div>
            </div>

            <!-- Impact & Achievements Section -->
            <div class="about-section bg-light">
                <div class="about-content reverse">
                    <div class="about-text">
                        <h2>Our Impact & Achievements</h2>
                        <p>Since our founding, we've made significant strides in improving animal welfare in our community:</p>
                        <ul>
                            <li>Over 5,000 successful adoptions</li>
                            <li>98% live release rate for all animals in our care</li>
                            <li>Provided low-cost services to 3,000+ pets</li>
                            <li>Educational programs reaching 10,000+ community members annually</li>
                            <li>Partnerships with 25+ local businesses and organizations</li>
                        </ul>
                        <p>These achievements are only possible thanks to the generous support of our donors, volunteers, and community partners.</p>
                    </div>
                    <div class="about-image">
                        <img src="{{ asset('assets/about/impact.jpeg') }}" alt="Happy family adopting a dog">
                    </div>
                </div>
            </div>

            <!-- Values & Philosophy Section -->
            <div class="about-section">
                <div class="about-content">
                    <div class="about-text">
                        <h2>Our Values & Philosophy</h2>
                        <p>At the heart of everything we do are our core values:</p>
                        <ul>
                            <li><strong>Compassion:</strong> We treat every animal with dignity, respect, and kindness</li>
                            <li><strong>Responsibility:</strong> We are accountable to our animals, supporters, and community</li>
                            <li><strong>Transparency:</strong> We operate with openness and honesty in all our endeavors</li>
                            <li><strong>Collaboration:</strong> We work together with other organizations to maximize our impact</li>
                            <li><strong>Education:</strong> We believe knowledge is key to creating lasting change</li>
                        </ul>
                        <p>Our philosophy is simple: every life matters. We never give up on an animal, no matter their age, medical needs, or behavioral challenges.</p>
                    </div>
                    <div class="about-image">
                        <img src="{{ asset('assets/about/values.jpg') }}" alt="Volunteer taking care of the dogs">
                    </div>
                </div>
            </div>

            <!-- Call to Action Section -->
            <div class="about-cta">
                <h2>Join Our Mission</h2>
                <p>Whether you're looking to adopt, volunteer, or support our work in other ways, there are many opportunities to get involved and make a difference in the lives of animals in need.</p>
                <div class="cta-buttons">
                    <a href="/contact" class="cta-btn primary">Contact Us</a>
                    <a href="/qa" class="cta-btn secondary">View FAQs</a>
                </div>
            </div>
        </div>

        <!-- Footer Container -->

        @include('components.footer')
        
    

    </body>
</html>