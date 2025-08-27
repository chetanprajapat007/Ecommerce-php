// Script for Safai Sathi Landing Page

// Wait for the DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
  // Smooth scrolling for anchor links
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      e.preventDefault();
      
      const targetId = this.getAttribute('href');
      const targetElement = document.querySelector(targetId);
      
      if (targetElement) {
        window.scrollTo({
          top: targetElement.offsetTop - 80, // Offset for fixed header
          behavior: 'smooth'
        });
      }
    });
  });
  
  // Intersection Observer for scroll animations
  const animatedElements = document.querySelectorAll('.fade-in, .slide-up, .slide-in-left, .slide-in-right');
  
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('animate');
        observer.unobserve(entry.target);
      }
    });
  }, {
    threshold: 0.1
  });
  
  animatedElements.forEach(element => {
    observer.observe(element);
  });
  
  // Mobile menu toggle
  const menuToggle = document.querySelector('.menu-toggle');
  const mobileMenu = document.querySelector('.mobile-menu');
  
  if (menuToggle && mobileMenu) {
    menuToggle.addEventListener('click', () => {
      mobileMenu.classList.toggle('open');
    });
  }
  
  // Before & After image slider functionality
  const sliders = document.querySelectorAll('.before-after-slider');
  
  sliders.forEach(slider => {
    const container = slider.closest('.before-after-container');
    const beforeImage = container.querySelector('.before-image');
    
    let isActive = false;
    
    // Mouse events
    slider.addEventListener('mousedown', () => {
      isActive = true;
    });
    
    window.addEventListener('mouseup', () => {
      isActive = false;
    });
    
    window.addEventListener('mousemove', (e) => {
      if (!isActive) return;
      
      const rect = container.getBoundingClientRect();
      const x = e.clientX - rect.left;
      const percent = Math.max(0, Math.min(100, x / rect.width * 100));
      
      slider.style.left = `${percent}%`;
      beforeImage.style.width = `${percent}%`;
    });
    
    // Touch events for mobile
    slider.addEventListener('touchstart', () => {
      isActive = true;
    });
    
    window.addEventListener('touchend', () => {
      isActive = false;
    });
    
    window.addEventListener('touchmove', (e) => {
      if (!isActive) return;
      
      const rect = container.getBoundingClientRect();
      const x = e.touches[0].clientX - rect.left;
      const percent = Math.max(0, Math.min(100, x / rect.width * 100));
      
      slider.style.left = `${percent}%`;
      beforeImage.style.width = `${percent}%`;
    });
  });
  
  // FAQ accordion functionality
  const faqQuestions = document.querySelectorAll('.faq-question');
  
  faqQuestions.forEach(question => {
    question.addEventListener('click', () => {
      const answer = question.nextElementSibling;
      const isOpen = answer.style.maxHeight;
      
      // Close all other answers
      document.querySelectorAll('.faq-answer').forEach(item => {
        item.style.maxHeight = null;
      });
      
      // Toggle current answer
      if (!isOpen) {
        answer.style.maxHeight = answer.scrollHeight + 'px';
      }
    });
  });
  
  // Form validation for contact form
  const contactForm = document.querySelector('#contact-form');
  
  if (contactForm) {
    contactForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      const nameInput = contactForm.querySelector('#name');
      const phoneInput = contactForm.querySelector('#phone');
      const serviceInput = contactForm.querySelector('#service');
      
      let isValid = true;
      
      // Simple validation
      if (!nameInput.value.trim()) {
        showError(nameInput, 'Name is required');
        isValid = false;
      } else {
        clearError(nameInput);
      }
      
      if (!phoneInput.value.trim()) {
        showError(phoneInput, 'Phone number is required');
        isValid = false;
      } else if (!isValidPhone(phoneInput.value.trim())) {
        showError(phoneInput, 'Please enter a valid phone number');
        isValid = false;
      } else {
        clearError(phoneInput);
      }
      
      if (!serviceInput.value) {
        showError(serviceInput, 'Please select a service');
        isValid = false;
      } else {
        clearError(serviceInput);
      }
      
      if (isValid) {
        // Show success message
        const successMessage = document.createElement('div');
        successMessage.className = 'bg-lime-green text-white p-4 rounded-lg mt-4';
        successMessage.textContent = 'Thank you! We will contact you shortly.';
        
        contactForm.reset();
        contactForm.appendChild(successMessage);
        
        // Remove success message after 5 seconds
        setTimeout(() => {
          successMessage.remove();
        }, 5000);
      }
    });
  }
  
  // Helper functions for form validation
  function showError(input, message) {
    const formGroup = input.closest('.form-group');
    const errorElement = formGroup.querySelector('.error-message') || document.createElement('div');
    
    errorElement.className = 'error-message text-red-500 text-sm mt-1';
    errorElement.textContent = message;
    
    if (!formGroup.querySelector('.error-message')) {
      formGroup.appendChild(errorElement);
    }
    
    input.classList.add('border-red-500');
  }
  
  function clearError(input) {
    const formGroup = input.closest('.form-group');
    const errorElement = formGroup.querySelector('.error-message');
    
    if (errorElement) {
      errorElement.remove();
    }
    
    input.classList.remove('border-red-500');
  }
  
  function isValidPhone(phone) {
    // Basic phone validation for Indian numbers
    const phoneRegex = /^[6-9]\d{9}$/;
    return phoneRegex.test(phone);
  }
  
  // Testimonial carousel for mobile
  const testimonialContainer = document.querySelector('.testimonial-container');
  const testimonials = document.querySelectorAll('.testimonial-card');
  
  if (testimonialContainer && testimonials.length > 1 && window.innerWidth < 768) {
    let currentIndex = 0;
    
    // Create navigation dots
    const dotsContainer = document.createElement('div');
    dotsContainer.className = 'flex justify-center mt-4 space-x-2';
    
    for (let i = 0; i < testimonials.length; i++) {
      const dot = document.createElement('button');
      dot.className = 'w-3 h-3 rounded-full bg-gray-300';
      dot.setAttribute('aria-label', `Go to testimonial ${i + 1}`);
      
      if (i === 0) {
        dot.classList.add('bg-royal-blue');
      }
      
      dot.addEventListener('click', () => {
        goToTestimonial(i);
      });
      
      dotsContainer.appendChild(dot);
    }
    
    testimonialContainer.parentNode.appendChild(dotsContainer);
    
    // Function to show specific testimonial
    function goToTestimonial(index) {
      currentIndex = index;
      
      testimonials.forEach((testimonial, i) => {
        testimonial.style.transform = `translateX(${100 * (i - index)}%)`;
        
        // Update dots
        dotsContainer.children[i].classList.toggle('bg-royal-blue', i === index);
        dotsContainer.children[i].classList.toggle('bg-gray-300', i !== index);
      });
    }
    
    // Initialize positions
    testimonials.forEach((testimonial, i) => {
      testimonial.style.position = 'absolute';
      testimonial.style.width = '100%';
      testimonial.style.transition = 'transform 0.3s ease-in-out';
      testimonial.style.transform = `translateX(${100 * (i - currentIndex)}%)`;
    });
    
    // Add swipe functionality for mobile
    let startX = 0;
    let currentX = 0;
    
    testimonialContainer.addEventListener('touchstart', (e) => {
      startX = e.touches[0].clientX;
    });
    
    testimonialContainer.addEventListener('touchmove', (e) => {
      currentX = e.touches[0].clientX;
    });
    
    testimonialContainer.addEventListener('touchend', () => {
      const diff = startX - currentX;
      
      if (Math.abs(diff) > 50) { // Minimum swipe distance
        if (diff > 0 && currentIndex < testimonials.length - 1) {
          // Swipe left
          goToTestimonial(currentIndex + 1);
        } else if (diff < 0 && currentIndex > 0) {
          // Swipe right
          goToTestimonial(currentIndex - 1);
        }
      }
    });
  }
});

