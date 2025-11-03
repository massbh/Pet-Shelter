<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Happinest - Contact Us</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/contact.css') }}">
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
                <input type="text" id="name" name="name" required autocomplete="name" placeholder="Enter your full name" value="{{ auth()->check() ? auth()->user()->name : '' }}">
                <div class="error-message" id="nameError"></div>
              </div>

              <div class="form-group">
                <label for="email">Email Address *</label>
                <input type="email" id="email" name="email" required autocomplete="email" placeholder="Enter your email address" value="{{ auth()->check() ? auth()->user()->email : '' }}">
                <div class="error-message" id="emailError"></div>
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
                <div class="error-message" id="subjectError"></div>
              </div>

              <!-- Visit schedule -->
              <fieldset id="visitSchedule" class="visit-schedule hidden">
                <legend>Schedule a Visit</legend>

                <div class="form-row">
                  <div class="form-group">
                    <label for="visitDate">Preferred Date *</label>
                    <input type="date" id="visitDate" name="visitDate" min="">
                    <div class="error-message" id="visitDateError"></div>
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
                    <div class="error-message" id="visitTimeError"></div>
                  </div>
                </div>

                <div class="form-group">
                  <label for="petInterest">Pet you're interested in (if any)</label>
                  <input type="text" id="petInterest" name="petInterest" placeholder="Pet name or ID" value="{{ request('petId') }}">
                </div>
              </fieldset>

              <div class="form-group">
                <label for="message">Message *</label>
                <textarea id="message" name="message" required placeholder="Tell us how we can help you (max 500 characters)" maxlength="500" aria-describedby="messageHelp"></textarea>
                <p class="char-counter" id="messageHelp" aria-live="polite">
                  <span id="charCount">0</span>/500 characters
                </p>
                <div class="error-message" id="messageError"></div>
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

        <!-- Sign in pop-up -->
        <div id="authRequiredModal" class="modal hidden" role="dialog" aria-modal="true">
          <div class="modal-content ultra-compact-modal">
            <button type="button" class="close-modal" onclick="closeAuthModal()">&times;</button>
            <div style="font-size: 1.5rem; margin-bottom: 0.5rem;">🔒</div>
            <p style="margin: 0 0 1rem 0; font-weight: 500;">Authentication required</p>
            <button class="sign-in-btn" onclick="redirectToSignIn()">
              Sign In to continue
            </button>
          </div>
        </div>

        <!-- Confirmation modal -->
        <div id="confirmationModal" class="modal hidden" role="dialog" aria-modal="true" aria-labelledby="confirmTitle">
          <div class="modal-content">
            <button type="button" class="close-modal" aria-label="Close modal" onclick="closeModal()">&times;</button>
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
      // Check if user is authenticated (PHP variable passed to JavaScript)
      const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};

      // Set minimum date for visit scheduling to today
      document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('visitDate').min = today;

        // Load saved form data if exists
        loadSavedFormData();

        // Character counter for message
        const messageTextarea = document.getElementById('message');
        const charCount = document.getElementById('charCount');
        messageTextarea.addEventListener('input', function() {
          charCount.textContent = this.value.length;
          saveFormData(); // Save on every input
        });

        // Define variables for Show/hide visit schedule based on subject selection
        const subjectSelect = document.getElementById('subject');
        const visitSchedule = document.getElementById('visitSchedule');

        // First check if page is entered through adoption
        if (document.getElementById('petInterest').value !== "") {
          subjectSelect.value = "adoption";
          visitSchedule.classList.remove('hidden');
        }

        // Show/hide visit schedule based on subject selection
        subjectSelect.addEventListener('change', function() {
          if (this.value === 'adoption') {
            visitSchedule.classList.remove('hidden');
          } else {
            visitSchedule.classList.add('hidden');
          }
          saveFormData(); // Save on change
        });

        // Auto-save form data on input
        const formInputs = document.querySelectorAll('#contactForm input, #contactForm select, #contactForm textarea');
        formInputs.forEach(input => {
          input.addEventListener('input', saveFormData);
          input.addEventListener('change', saveFormData);
        });

        // Form submission
        const contactForm = document.getElementById('contactForm');
        contactForm.addEventListener('submit', function(e) {
          e.preventDefault();
          
          // Validate form before checking authentication
          if (!validateForm()) {
            return; // Stop if form is invalid
          }
          
          // Check if user is authenticated
          if (!isAuthenticated) {
            saveFormData(); // Save before redirecting to sign in
            showAuthRequiredModal();
            return;
          }
          
          // If authenticated and form is valid, proceed with submission
          showConfirmation();
        });

        // Close modals when clicking outside
        setupModalCloseListeners();
      });

      // Save form data to localStorage
      function saveFormData() {
        const formData = {
          name: document.getElementById('name').value,
          email: document.getElementById('email').value,
          subject: document.getElementById('subject').value,
          message: document.getElementById('message').value,
          visitDate: document.getElementById('visitDate').value,
          visitTime: document.getElementById('visitTime').value,
          petInterest: document.getElementById('petInterest').value,
          charCount: document.getElementById('charCount').textContent
        };
        localStorage.setItem('contactFormData', JSON.stringify(formData));
      }

      // Load saved form data from localStorage
      function loadSavedFormData() {
        const savedData = localStorage.getItem('contactFormData');
        if (savedData) {
          const formData = JSON.parse(savedData);
          
          document.getElementById('name').value = formData.name || '';
          document.getElementById('email').value = formData.email || '';
          document.getElementById('subject').value = formData.subject || '';
          document.getElementById('message').value = formData.message || '';
          document.getElementById('visitDate').value = formData.visitDate || '';
          document.getElementById('visitTime').value = formData.visitTime || '';
          document.getElementById('petInterest').value = formData.petInterest || '';
          document.getElementById('charCount').textContent = formData.charCount || '0';

          // Show/hide visit schedule based on loaded subject
          if (formData.subject === 'adoption') {
            document.getElementById('visitSchedule').classList.remove('hidden');
          }
        }
      }

      // Clear saved form data after successful submission
      function clearSavedFormData() {
        localStorage.removeItem('contactFormData');
      }

      function validateForm() {
        let isValid = true;
        
        // Clear previous errors
        clearErrors();
        
        // Validate name
        const name = document.getElementById('name').value.trim();
        if (!name) {
          showError('name', 'This field is required');
          isValid = false;
        }
        
        // Validate email
        const email = document.getElementById('email').value.trim();
        if (!email) {
          showError('email', 'This field is required');
          isValid = false;
        }
        
        // Validate subject
        const subject = document.getElementById('subject').value;
        if (!subject) {
          showError('subject', 'Please select a subject');
          isValid = false;
        }
        
        // Validate visit fields if adoption is selected
        if (subject === 'adoption') {
          const visitDate = document.getElementById('visitDate').value;
          const visitTime = document.getElementById('visitTime').value;
          
          if (!visitDate) {
            showError('visitDate', 'This field is required');
            isValid = false;
          }
          
          if (!visitTime) {
            showError('visitTime', 'This field is required');
            isValid = false;
          }
        }
        
        // Validate message
        const message = document.getElementById('message').value.trim();
        if (!message) {
          showError('message', 'This field is required');
          isValid = false;
        }
        
        return isValid;
      }

      function showError(fieldId, message) {
        const field = document.getElementById(fieldId);
        const errorElement = document.getElementById(fieldId + 'Error');
        
        // Add error styles
        field.classList.add('input-error');
        
        // Show error message
        errorElement.textContent = message;
        errorElement.style.display = 'block';
      }

      function clearErrors() {
        // Remove all error styles and messages
        const errorElements = document.querySelectorAll('.error-message');
        const errorInputs = document.querySelectorAll('.input-error');
        
        errorElements.forEach(el => {
          el.style.display = 'none';
        });
        
        errorInputs.forEach(input => {
          input.classList.remove('input-error');
        });
      }

      function showAuthRequiredModal() {
        document.getElementById('authRequiredModal').classList.remove('hidden');
      }

      function closeAuthModal() {
        document.getElementById('authRequiredModal').classList.add('hidden');
      }

      function redirectToSignIn() {
        // Save form data one more time before redirecting
        saveFormData();
        window.location.href = '/signin';
      }

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

        // Clear saved data after successful submission
        clearSavedFormData();
        
        document.getElementById('contactForm').reset();
        document.getElementById('charCount').textContent = '0';
        document.getElementById('visitSchedule').classList.add('hidden');
      }

      function closeModal() {
        document.getElementById('confirmationModal').classList.add('hidden');
      }

      function setupModalCloseListeners() {
        // Close modals when clicking outside
        window.addEventListener('click', function(e) {
          const authModal = document.getElementById('authRequiredModal');
          const confirmModal = document.getElementById('confirmationModal');
          
          if (e.target === authModal) {
            closeAuthModal();
          }
          if (e.target === confirmModal) {
            closeModal();
          }
        });

        // Close modals with escape key
        document.addEventListener('keydown', function(e) {
          if (e.key === 'Escape') {
            closeAuthModal();
            closeModal();
          }
        });
      }
    </script>
  </body>
</html>