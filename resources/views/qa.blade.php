<!DOCTYPE html>
<html>
    <head>
        <title>Happinest - FAQs</title>
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <link rel="stylesheet" href="{{ asset('css/header.css') }}">
        <link rel="stylesheet" href="{{ asset('css/qa.css') }}">
        <script src="{{ asset('js/qa_filter.js') }}"></script>
    </head>
    <body>
        
        <!-- Header Container -->
        <div id="header-container"></div>

        <!-- Content -->
        <div class="qa-hero">
            <h1>Frequently Asked Questions</h1>
            <p>Find answers to common questions about pet adoption, our services, and caring for your new companion</p>
        </div>

        <section class="qa-container">
            <!-- Category Filters -->
            <div class="qa-categories">
                <button class="category-btn active" onclick="filterByCategory('all')">All Questions</button>
                <button class="category-btn" onclick="filterByCategory('adoption')">Adoption Process</button>
                <button class="category-btn" onclick="filterByCategory('care')">Pet Care</button>
                <button class="category-btn" onclick="filterByCategory('fees')">Fees & Costs</button>
                <button class="category-btn" onclick="filterByCategory('requirements')">Requirements</button>
            </div>

            <!-- Q&A Items -->
            <section class="qa-list">
                <!-- Adoption Process Questions -->
                <div class="qa-item" data-category="adoption">
                    <header class="question" onclick="toggleAnswer(this)">
                        <h3>How do I adopt a pet from Happinest?</h3>
                        <span class="toggle-icon">+</span>
                    </header>
                    <article class="answer">
                        <p>Our adoption process is designed to ensure the best match between pets and families:</p>
                        <ol>
                            <li>Browse our available pets online or visit our shelter</li>
                            <li>Fill out an adoption application</li>
                            <li>Meet and spend time with your chosen pet</li>
                            <li>Complete a brief interview with our adoption counselors</li>
                            <li>Finalize the adoption and take your new companion home!</li>
                        </ol>
                        <p>The entire process typically takes 1-3 days, depending on availability and scheduling.</p>
                    </article>
                </div>

                <div class="qa-item" data-category="adoption">
                    <header class="question" onclick="toggleAnswer(this)">
                        <h3>What should I bring for the adoption?</h3>
                        <span class="toggle-icon">+</span>
                    </header>
                    <article class="answer">
                        <p>Please bring the following items when adopting:</p>
                        <ul>
                            <li>Valid photo ID</li>
                            <li>Proof of address (utility bill or lease agreement)</li>
                            <li>If renting, written permission from landlord</li>
                            <li>Adoption fee (cash, card, or check accepted)</li>
                            <li>Carrier or leash for your new pet</li>
                        </ul>
                    </article>
                </div>

                <div class="qa-item" data-category="requirements">
                    <header class="question" onclick="toggleAnswer(this)">
                        <h3>What are the requirements to adopt a pet?</h3>
                        <span class="toggle-icon">+</span>
                    </header>
                    <article class="answer">
                        <p>To ensure our pets go to loving homes, we require:</p>
                        <ul>
                            <li>Must be 18 years or older</li>
                            <li>All household members must meet the pet</li>
                            <li>Current pets must be up-to-date on vaccinations</li>
                            <li>Landlord approval if renting</li>
                            <li>Commitment to provide proper care, exercise, and medical attention</li>
                        </ul>
                    </article>
                </div>

                <!-- Pet Care Questions -->
                <div class="qa-item" data-category="care">
                    <header class="question" onclick="toggleAnswer(this)">
                        <h3>How do I prepare my home for a new pet?</h3>
                        <span class="toggle-icon">+</span>
                    </header>
                    <article class="answer">
                        <p>Preparing your home ensures a smooth transition for your new pet:</p>
                        <ul>
                            <li><strong>Safety first:</strong> Remove toxic plants, secure loose wires, and pet-proof cabinets</li>
                            <li><strong>Supplies:</strong> Get food/water bowls, bed, toys, collar with ID tag</li>
                            <li><strong>Safe space:</strong> Set up a quiet area where your pet can retreat and feel secure</li>
                            <li><strong>Routine:</strong> Establish feeding times, walking schedules, and play sessions</li>
                            <li><strong>Veterinarian:</strong> Schedule a check-up within the first week</li>
                        </ul>
                    </article>
                </div>

                <div class="qa-item" data-category="care">
                    <header class="question" onclick="toggleAnswer(this)">
                        <h3>What if my new pet has behavioral issues?</h3>
                        <span class="toggle-icon">+</span>
                    </header>
                    <article class="answer">
                        <p>We're here to help! Many behavioral issues are normal during the adjustment period:</p>
                        <ul>
                            <li>Allow 2-4 weeks for your pet to fully adjust to their new home</li>
                            <li>Contact our adoption counselors for free behavioral support</li>
                            <li>We offer training resources and referrals to certified trainers</li>
                            <li>In rare cases, we accept returns within 30 days if it's not a good match</li>
                        </ul>
                        <p><em>Remember: patience, consistency, and positive reinforcement work wonders!</em></p>
                    </article>
                </div>

                <!-- Fees & Costs Questions -->
                <div class="qa-item" data-category="fees">
                    <header class="question" onclick="toggleAnswer(this)">
                        <h3>What are your adoption fees?</h3>
                        <span class="toggle-icon">+</span>
                    </header>
                    <article class="answer">
                        <p>Our adoption fees help cover medical care and shelter operations:</p>
                        <ul>
                            <li><strong>Dogs:</strong> $150 - $300 (depending on age and size)</li>
                            <li><strong>Cats:</strong> $75 - $150 (depending on age)</li>
                            <li><strong>Kittens/Puppies under 6 months:</strong> Higher fee due to additional medical needs</li>
                            <li><strong>Senior pets (7+ years):</strong> Reduced fees or special promotions</li>
                        </ul>
                        <p>All pets come spayed/neutered, vaccinated, and microchipped. This represents a significant savings compared to these services separately!</p>
                    </article>
                </div>

                <div class="qa-item" data-category="fees">
                    <header class="question" onclick="toggleAnswer(this)">
                        <h3>Do you offer payment plans or discounts?</h3>
                        <span class="toggle-icon">+</span>
                    </header>
                    <article class="answer">
                        <p>We want to make adoption accessible to loving families:</p>
                        <ul>
                            <li><strong>Senior citizen discount:</strong> 20% off for adopters 65+</li>
                            <li><strong>Military discount:</strong> 15% off with valid military ID</li>
                            <li><strong>Special events:</strong> Periodic adoption fee reductions</li>
                            <li><strong>Payment plans:</strong> Available for qualified adopters on a case-by-case basis</li>
                        </ul>
                        <p>Contact us to discuss options that work for your situation!</p>
                    </article>
                </div>

                <!-- Additional Questions -->
                <div class="qa-item" data-category="adoption">
                    <header class="question" onclick="toggleAnswer(this)">
                        <h3>Can I visit the pets before adopting?</h3>
                        <span class="toggle-icon">+</span>
                    </header>
                    <article class="answer">
                        <p>Absolutely! We encourage visits to ensure a good match:</p>
                        <ul>
                            <li><strong>Open hours:</strong> Monday-Friday, 8 AM - 6 PM Saturday-Sunday, 10 AM - 4 PM</li>
                            <li><strong>No appointment needed</strong> for general visits</li>
                            <li><strong>Private meet-and-greets</strong> available by appointment</li>
                            <li><strong>Bring your family</strong> and current pets to meet potential new companions</li>
                        </ul>
                    </article>
                </div>

                <div class="qa-item" data-category="care">
                    <header class="question" onclick="toggleAnswer(this)">
                        <h3>Do you provide medical records for adopted pets?</h3>
                        <span class="toggle-icon">+</span>
                    </header>
                    <article class="answer">
                        <p>Yes! Complete medical transparency is important to us:</p>
                        <ul>
                            <li>Full vaccination records</li>
                            <li>Spay/neuter certificates</li>
                            <li>Microchip information</li>
                            <li>Any known medical conditions or treatments</li>
                            <li>Behavioral assessments and notes</li>
                        </ul>
                        <p>We also provide a starter pack with food samples and care instructions!</p>
                    </article>
                </div>
            </section>

            <!-- Contact Section for More Questions -->
            <section class="qa-contact">
                <h2>Still Have Questions?</h2>
                <p>Our friendly staff is here to help! Contact us through any of these methods:</p>
                <a href="contact.html" class="contact-btn">Get In Touch</a>
            </section>
        </section>

        <!-- Footer Container -->
        <footer id="footer-container"></footer>
        
        <!-- Script to load components -->
        <script src="js/loadComponents.js"></script>       
    </body>
</html>