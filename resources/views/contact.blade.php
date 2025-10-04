<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Happinest - Contact Us</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  </head>
  <body>

    <div id="header-container"></div>

    <main id="main-content">
      <section class="contact-hero" aria-labelledby="contact-page-title">
        <h1 id="contact-page-title">Contact Happinest</h1>
        <p>We're here to help you find your perfect companion or answer any questions</p>
      </section>

      <section class="contact-container" aria-labelledby="contact-form-title">
        <div class="contact-content">
          <article class="contact-form-section">
            <h2 id="contact-form-title">Send us a Message</h2>

            <form id="contactForm" class="contact-form" method="post" novalidate>
              @csrf
              <div class="form-group">
                <label for="name">Full Name *</label>
                <input type="text" id="name" name="name" required autocomplete="name" placeholder="Enter your full name">
              </div>

              <div class="form-group">
                <label for="email">Email Address *</label>
                <input type="email" id="email" name="email" required autocomplete="email" placeholder="Enter your email address">
              </div>

              <div class="form-group">
                <label for="subject">Subject *</label>
                <select id="subject" name="subject" required>
                  <option value="">Select a reason for contacting us</option>
                  <option value="adoption">I want to adopt</option>
                  <option value="volunteer">I want to volunteer</option>
                  <option value="donation">I want to make a donation</option>
                  <option value="surrender">I need to surrender a pet</option>
                  <option value="general">General inquiry</option>
                  <option value="complaint">Complaint or suggestion</option>
                  <option value="other">Other</option>
                </select>
              </div>

              <!-- Visit schedule -->
              <fieldset id="visitSchedule" class="visit-schedule hidden">
                <legend>Schedule a Visit</legend>

                <div class="form-row">
                  <div class="form-group">
                    <label for="visitDate">Preferred Date *</label>
                    <input type="date" id="visitDate" name="visitDate" min="">
                  </div>
                  <div class="form-group">
                    <label for="visitTime">Preferred Time *</label>
                    <select id="visitTime" name="visitTime">
                      <option value="">Select a time</option>
                      <option value="09:00">9:00 AM</option>
                      <option value="10:00">10:00 AM</option>
                      <option value="11:00">11:00 AM</option>
                      <option value="12:00">12:00 PM</option>
                      <option value="13:00">1:00 PM</option>
                      <option value="14:00">2:00 PM</option>
                      <option value="15:00">3:00 PM</option>
                      <option value="16:00">4:00 PM</option>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <label for="petInterest">Pet you're interested in (if any)</label>
                  <input type="text" id="petInterest" name="petInterest" placeholder="Pet name or ID">
                </div>
              </fieldset>

              <div class="form-group">
                <label for="message">Message *</label>
                <textarea id="message" name="message" required placeholder="Tell us how we can help you (max 500 characters)" maxlength="500" aria-describedby="messageHelp"></textarea>
                <p class="char-counter" id="messageHelp" aria-live="polite">
                  <span id="charCount">0</span>/500 characters
                </p>
              </div>

              <button type="submit" class="submit-btn">Send Message</button>
            </form>
          </article>

          <!-- Sidebar info -->
          <aside class="contact-info-section" aria-label="Other ways to reach us">
            <h2>Other Ways to Reach Us</h2>

            <section class="contact-method">
              <h3>📍 Visit Our Shelter</h3>
              <p>Alsion, 6400 Sønderborg</p>
            </section>

            <section class="contact-method">
              <h3>📞 Call Us</h3>
              <p>Main: <a href="tel:+4520865858">+45 20865858</a></p>
            </section>

            <section class="contact-method">
              <h3>🕒 Opening Hours</h3>
              <p>Monday-Friday: 8:00 AM - 6:00 PM<br>Saturday-Sunday: 10:00 AM - 4:00 PM</p>
            </section>

            <section class="contact-method">
              <h3>✉️ Email</h3>
              <p>
                General: <a href="mailto:info@happinest.org">info@happinest.org</a><br>
                Adoptions: <a href="mailto:adopt@happinest.org">adopt@happinest.org</a>
              </p>
            </section>
          </aside>
        </div>

        <!-- Confirmation modal -->
        <div id="confirmationModal" class="modal hidden" role="dialog" aria-modal="true" aria-labelledby="confirmTitle">
          <div class="modal-content">
            <button type="button" class="close-modal" aria-label="Close modal">&times;</button>
            <h2 id="confirmTitle">Message Sent Successfully!</h2>
            <div class="confirmation-details">
              <p><strong>Thank you for contacting Happinest!</strong></p>
              <p>A copy of your message has been sent to your email address.</p>
              <div class="message-summary">
                <h3>Message Summary:</h3>
                <p><strong>Name:</strong> <span id="summaryName"></span></p>
                <p><strong>Email:</strong> <span id="summaryEmail"></span></p>
                <p><strong>Subject:</strong> <span id="summarySubject"></span></p>
                <div id="summaryVisit" class="hidden">
                  <p><strong>Visit Date:</strong> <span id="summaryDate"></span></p>
                  <p><strong>Visit Time:</strong> <span id="summaryTime"></span></p>
                  <p><strong>Pet Interest:</strong> <span id="summaryPet"></span></p>
                </div>
                <p><strong>Message:</strong> <span id="summaryMessage"></span></p>
              </div>
            </div>
            <button class="modal-btn" type="button" onclick="closeModal()">Close</button>
          </div>
        </div>
      </section>
    </main>

    <!-- Footer -->
    <footer id="footer-container"></footer>

    <!-- Scripts -->
    <script src="{{ asset('js/loadComponents.js') }}"></script>
    <script>
      // Set minimum date for visit scheduling to today
      document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('visitDate').min = today;

        // Character counter for message
        const messageTextarea = document.getElementById('message');
        const charCount = document.getElementById('charCount');
        messageTextarea.addEventListener('input', function() {
          charCount.textContent = this.value.length;
        });

        // Show/hide visit schedule based on subject selection
        const subjectSelect = document.getElementById('subject');
        const visitSchedule = document.getElementById('visitSchedule');
        subjectSelect.addEventListener('change', function() {
          if (this.value === 'adoption') {
            visitSchedule.classList.remove('hidden');
          } else {
            visitSchedule.classList.add('hidden');
          }
        });

        // Form submission
        const contactForm = document.getElementById('contactForm');
        contactForm.addEventListener('submit', function(e) {
          e.preventDefault();
          showConfirmation();
        });
      });

      function showConfirmation() {
        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const subject = document.getElementById('subject').options[document.getElementById('subject').selectedIndex].text;
        const message = document.getElementById('message').value;
        const visitDate = document.getElementById('visitDate').value;
        const visitTime = document.getElementById('visitTime').options[document.getElementById('visitTime').selectedIndex].text;
        const petInterest = document.getElementById('petInterest').value;

        document.getElementById('summaryName').textContent = name;
        document.getElementById('summaryEmail').textContent = email;
        document.getElementById('summarySubject').textContent = subject;
        document.getElementById('summaryMessage').textContent = message;

        if (document.getElementById('subject').value === 'adoption') {
          document.getElementById('summaryVisit').classList.remove('hidden');
          document.getElementById('summaryDate').textContent = visitDate || 'Not specified';
          document.getElementById('summaryTime').textContent = visitTime || 'Not specified';
          document.getElementById('summaryPet').textContent = petInterest || 'Not specified';
        } else {
          document.getElementById('summaryVisit').classList.add('hidden');
        }

        document.getElementById('confirmationModal').classList.remove('hidden');
        console.log('Form submitted:', { name, email, subject, message, visitDate, visitTime, petInterest });

        document.getElementById('contactForm').reset();
        document.getElementById('charCount').textContent = '0';
        document.getElementById('visitSchedule').classList.add('hidden');
      }

      function closeModal() {
        document.getElementById('confirmationModal').classList.add('hidden');
      }

      window.addEventListener('click', function(e) {
        const modal = document.getElementById('confirmationModal');
        if (e.target === modal) {
          closeModal();
        }
      });
    </script>
  </body>
</html>