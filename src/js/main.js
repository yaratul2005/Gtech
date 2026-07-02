/**
 * Great Endured Technology — Main JS Entry Point
 * Governed by RBP (AGENTS.md)
 */

import Particles from './modules/particles.js';
import initScrollReveal from './modules/reveal.js';

document.addEventListener('DOMContentLoaded', () => {
  // 1. Initialize Scroll Reveals
  initScrollReveal();

  // 2. Initialize Particles (if canvas is present)
  if (document.getElementById('particles-canvas')) {
    new Particles('particles-canvas');
  }

  // 3. Navbar scroll listener
  const navbar = document.querySelector('.navbar');
  const handleScroll = () => {
    if (window.scrollY > 40) {
      navbar.classList.add('scrolled');
    } else {
      navbar.classList.remove('scrolled');
    }
  };
  window.addEventListener('scroll', handleScroll);
  handleScroll(); // Initial check

  // 4. Mobile navigation toggle
  const menuToggle = document.querySelector('.menu-toggle');
  const navLinks = document.querySelector('.nav-links');
  if (menuToggle && navLinks) {
    menuToggle.addEventListener('click', () => {
      menuToggle.classList.toggle('active');
      navLinks.classList.toggle('active');
      
      // Prevent body scroll when menu is active on mobile
      if (navLinks.classList.contains('active')) {
        document.body.style.overflow = 'hidden';
      } else {
        document.body.style.overflow = '';
      }
    });

    // Close mobile menu when clicking a link
    navLinks.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', () => {
        menuToggle.classList.remove('active');
        navLinks.classList.remove('active');
        document.body.style.overflow = '';
      });
    });
  }

  // 5. Contact Form Handler (AJAX submission)
  const contactForm = document.getElementById('contact-form');
  if (contactForm) {
    const successAlert = document.getElementById('form-success');
    const errorAlert = document.getElementById('form-error');
    const submitBtn = contactForm.querySelector('button[type="submit"]');
    const originalBtnText = submitBtn ? submitBtn.innerHTML : 'Send Message';

    contactForm.addEventListener('submit', async (e) => {
      e.preventDefault();

      if (successAlert) successAlert.style.display = 'none';
      if (errorAlert) errorAlert.style.display = 'none';

      // Button loading state
      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = 'Sending...';
      }

      const formData = new FormData(contactForm);
      
      try {
        const response = await fetch(contactForm.action, {
          method: 'POST',
          body: formData,
          headers: {
            'Accept': 'application/json'
          }
        });

        const data = await response.json();

        if (response.ok && data.success) {
          if (successAlert) {
            successAlert.textContent = data.message || 'Thank you! Your message has been sent successfully.';
            successAlert.style.display = 'block';
            contactForm.reset();
          }
        } else {
          throw new Error(data.message || 'There was an issue processing your request.');
        }
      } catch (err) {
        if (errorAlert) {
          errorAlert.textContent = err.message || 'Network error. Please try again later.';
          errorAlert.style.display = 'block';
        }
      } finally {
        if (submitBtn) {
          submitBtn.disabled = false;
          submitBtn.innerHTML = originalBtnText;
        }
      }
    });
  }
});
