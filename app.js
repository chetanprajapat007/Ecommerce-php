// WhatsApp Floating Button Component
const WhatsAppButton = () => {
  return (
    <a 
      href="https://wa.me/919876543210?text=Hi%20Safai%20Sathi,%20I%20would%20like%20to%20book%20a%20cleaning%20service." 
      className="fixed bottom-6 right-6 bg-green-500 text-white rounded-full w-14 h-14 flex items-center justify-center shadow-lg hover:bg-green-600 transition-colors z-50"
      target="_blank"
      rel="noopener noreferrer"
      aria-label="Contact us on WhatsApp"
    >
      <svg 
        className="w-8 h-8" 
        fill="currentColor" 
        xmlns="http://www.w3.org/2000/svg" 
        viewBox="0 0 24 24"
      >
        <path 
          d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"
        />
      </svg>
    </a>
  );
};

// SEO Component
const SEO = () => {
  // This component doesn't render anything visible
  // It's used to add SEO-related meta tags to the document head
  
  React.useEffect(() => {
    // Set page title
    document.title = "Safai Sathi - Professional Cleaning Services in India";
    
    // Set meta description
    const metaDescription = document.querySelector('meta[name="description"]');
    if (metaDescription) {
      metaDescription.setAttribute("content", "Safai Sathi provides professional, eco-friendly cleaning services for homes, offices & hotels across India. Book your cleaning service today!");
    }
    
    // Create and add additional meta tags
    const metaTags = [
      {
        name: "keywords",
        content: "cleaning services, home cleaning, office cleaning, professional cleaners, eco-friendly cleaning, India cleaning service, Safai Sathi"
      },
      {
        property: "og:title",
        content: "Safai Sathi - Professional Cleaning Services in India"
      },
      {
        property: "og:description",
        content: "Professional, eco-friendly cleaning for homes, offices & hotels. A spotless space is just one call away."
      },
      {
        property: "og:type",
        content: "website"
      },
      {
        property: "og:url",
        content: "https://www.safaisathi.com"
      },
      {
        property: "og:image",
        content: "https://www.safaisathi.com/images/og-image.jpg"
      },
      {
        name: "twitter:card",
        content: "summary_large_image"
      },
      {
        name: "twitter:title",
        content: "Safai Sathi - Professional Cleaning Services in India"
      },
      {
        name: "twitter:description",
        content: "Professional, eco-friendly cleaning for homes, offices & hotels. A spotless space is just one call away."
      },
      {
        name: "twitter:image",
        content: "https://www.safaisathi.com/images/twitter-image.jpg"
      }
    ];
    
    metaTags.forEach(tag => {
      const metaTag = document.createElement("meta");
      
      if (tag.name) {
        metaTag.setAttribute("name", tag.name);
      } else if (tag.property) {
        metaTag.setAttribute("property", tag.property);
      }
      
      metaTag.setAttribute("content", tag.content);
      document.head.appendChild(metaTag);
    });
    
    // Add canonical link
    const canonicalLink = document.createElement("link");
    canonicalLink.setAttribute("rel", "canonical");
    canonicalLink.setAttribute("href", "https://www.safaisathi.com");
    document.head.appendChild(canonicalLink);
    
    // Clean up function to remove added tags when component unmounts
    return () => {
      metaTags.forEach(tag => {
        const selector = tag.name 
          ? `meta[name="${tag.name}"]` 
          : `meta[property="${tag.property}"]`;
        
        const metaTag = document.querySelector(selector);
        if (metaTag) {
          document.head.removeChild(metaTag);
        }
      });
      
      const canonical = document.querySelector('link[rel="canonical"]');
      if (canonical) {
        document.head.removeChild(canonical);
      }
    };
  }, []);
  
  return null; // This component doesn't render anything visible
};

// Scroll to Top Button Component
const ScrollToTopButton = () => {
  const [isVisible, setIsVisible] = React.useState(false);
  
  // Show button when page is scrolled down
  const toggleVisibility = () => {
    if (window.pageYOffset > 300) {
      setIsVisible(true);
    } else {
      setIsVisible(false);
    }
  };
  
  // Scroll to top function
  const scrollToTop = () => {
    window.scrollTo({
      top: 0,
      behavior: "smooth"
    });
  };
  
  React.useEffect(() => {
    window.addEventListener("scroll", toggleVisibility);
    
    return () => {
      window.removeEventListener("scroll", toggleVisibility);
    };
  }, []);
  
  return (
    <button
      className={`fixed bottom-6 left-6 bg-royal-blue text-white rounded-full w-12 h-12 flex items-center justify-center shadow-lg hover:bg-lime-green transition-all duration-300 z-50 ${isVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10 pointer-events-none'}`}
      onClick={scrollToTop}
      aria-label="Scroll to top"
    >
      <svg 
        className="w-6 h-6" 
        fill="none" 
        stroke="currentColor" 
        viewBox="0 0 24 24" 
        xmlns="http://www.w3.org/2000/svg"
      >
        <path 
          strokeLinecap="round" 
          strokeLinejoin="round" 
          strokeWidth={2} 
          d="M5 10l7-7m0 0l7 7m-7-7v18" 
        />
      </svg>
    </button>
  );
};

// Loading Animation Component
const LoadingAnimation = () => {
  return (
    <div className="fixed inset-0 flex items-center justify-center bg-white z-[100]">
      <div className="flex flex-col items-center">
        <div className="relative w-24 h-24">
          <div className="absolute top-0 left-0 w-full h-full border-4 border-lime-green rounded-full opacity-25"></div>
          <div className="absolute top-0 left-0 w-full h-full border-4 border-transparent border-t-royal-blue rounded-full animate-spin"></div>
        </div>
        <img 
          src="images/logo.svg" 
          alt="Safai Sathi Logo" 
          className="h-16 mt-6 animate-pulse" 
        />
        <p className="mt-4 text-royal-blue font-medium">Loading...</p>
      </div>
    </div>
  );
};

// Page Loading Component
const PageLoading = () => {
  const [isLoading, setIsLoading] = React.useState(true);
  
  React.useEffect(() => {
    // Simulate page loading
    const timer = setTimeout(() => {
      setIsLoading(false);
    }, 1500);
    
    return () => clearTimeout(timer);
  }, []);
  
  return isLoading ? <LoadingAnimation /> : null;
};

// React components for Safai Sathi Landing Page
const App = () => {
  return (
    <div className="App">
      <SEO />
      <PageLoading />
      <Header />
      <HeroSection />
      <BenefitsSection />
      <ServicesSection />
      <ProcessSection />
      <BeforeAfterSection />
      <PricingSection />
      <StatsSection />
      <TestimonialsSection />
      <TrustBadgesSection />
      <FAQSection />
      <FinalCTA />
      <Footer />
      <WhatsAppButton />
      <ScrollToTopButton />
    </div>
  );
};

// Mobile Menu Component
const MobileMenu = () => {
  const [isOpen, setIsOpen] = React.useState(false);
  
  const toggleMenu = () => {
    setIsOpen(!isOpen);
  };
  
  const closeMenu = () => {
    setIsOpen(false);
  };
  
  return (
    <div className="md:hidden">
      {/* Hamburger Button */}
      <button 
        className="menu-toggle flex items-center text-royal-blue p-2"
        onClick={toggleMenu}
        aria-label="Toggle mobile menu"
      >
        <svg 
          className="w-6 h-6" 
          fill="none" 
          stroke="currentColor" 
          viewBox="0 0 24 24" 
          xmlns="http://www.w3.org/2000/svg"
        >
          {isOpen ? (
            <path 
              strokeLinecap="round" 
              strokeLinejoin="round" 
              strokeWidth={2} 
              d="M6 18L18 6M6 6l12 12" 
            />
          ) : (
            <path 
              strokeLinecap="round" 
              strokeLinejoin="round" 
              strokeWidth={2} 
              d="M4 6h16M4 12h16M4 18h16" 
            />
          )}
        </svg>
      </button>
      
      {/* Mobile Menu Overlay */}
      <div 
        className={`fixed inset-0 bg-black bg-opacity-50 z-40 transition-opacity duration-300 ${isOpen ? 'opacity-100' : 'opacity-0 pointer-events-none'}`}
        onClick={closeMenu}
      ></div>
      
      {/* Mobile Menu Panel */}
      <div 
        className={`fixed top-0 left-0 bottom-0 w-64 bg-white z-50 shadow-xl transform transition-transform duration-300 ease-in-out ${isOpen ? 'translate-x-0' : '-translate-x-full'}`}
      >
        <div className="p-4 border-b">
          <img src="images/logo.svg" alt="Safai Sathi Logo" className="h-10" />
        </div>
        
        <nav className="p-4">
          <ul className="space-y-4">
            <li>
              <a 
                href="#services" 
                className="block text-royal-blue hover:text-lime-green font-medium py-2"
                onClick={closeMenu}
              >
                Services
              </a>
            </li>
            <li>
              <a 
                href="#pricing" 
                className="block text-royal-blue hover:text-lime-green font-medium py-2"
                onClick={closeMenu}
              >
                Pricing
              </a>
            </li>
            <li>
              <a 
                href="#testimonials" 
                className="block text-royal-blue hover:text-lime-green font-medium py-2"
                onClick={closeMenu}
              >
                Testimonials
              </a>
            </li>
            <li>
              <a 
                href="#faq" 
                className="block text-royal-blue hover:text-lime-green font-medium py-2"
                onClick={closeMenu}
              >
                FAQ
              </a>
            </li>
            <li>
              <a 
                href="#contact" 
                className="block text-royal-blue hover:text-lime-green font-medium py-2"
                onClick={closeMenu}
              >
                Contact
              </a>
            </li>
          </ul>
        </nav>
        
        <div className="p-4 border-t">
          <a 
            href="tel:+919876543210" 
            className="btn-primary w-full text-center"
            onClick={closeMenu}
          >
            📞 Call Now
          </a>
        </div>
      </div>
    </div>
  );
};

// Header Component
const Header = () => {
  return (
    <header className="bg-white shadow-md py-4 sticky top-0 z-50">
      <div className="container mx-auto px-4 flex justify-between items-center">
        <div className="flex items-center">
          <img src="images/logo.svg" alt="Safai Sathi Logo" className="h-12" />
        </div>
        <nav className="hidden md:flex space-x-6">
          <a href="#services" className="text-royal-blue hover:text-lime-green font-medium">Services</a>
          <a href="#pricing" className="text-royal-blue hover:text-lime-green font-medium">Pricing</a>
          <a href="#testimonials" className="text-royal-blue hover:text-lime-green font-medium">Testimonials</a>
          <a href="#faq" className="text-royal-blue hover:text-lime-green font-medium">FAQ</a>
          <a href="#contact" className="text-royal-blue hover:text-lime-green font-medium">Contact</a>
        </nav>
        <div className="flex items-center">
          <a href="tel:+919876543210" className="hidden md:block btn-primary text-sm md:text-base mr-4">📞 Call Now</a>
          <MobileMenu />
        </div>
      </div>
    </header>
  );
};

// Hero Section Component
const HeroSection = () => {
  return (
    <section className="py-12 md:py-20 bg-gradient-to-r from-royal-blue/5 to-lime-green/5">
      <div className="container mx-auto px-4 flex flex-col md:flex-row items-center">
        <div className="md:w-1/2 mb-8 md:mb-0 fade-in">
          <h1 className="text-3xl md:text-4xl lg:text-5xl font-bold mb-4 text-royal-blue">
            Tired of Cleaning? Let Safai Sathi Do It For You.
          </h1>
          <p className="text-lg md:text-xl mb-8 text-gray-700">
            Professional, eco-friendly cleaning for homes, offices & hotels. A spotless space is just one call away.
          </p>
          <a href="tel:+919876543210" className="btn-primary text-lg inline-block">
            📞 Call Us Now: +91 9876 543 210
          </a>
        </div>
        <div className="md:w-1/2 md:pl-8 fade-in">
          <img 
            src="images/hero-image.jpg" 
            alt="Sparkling clean Indian home with happy family" 
            className="rounded-lg shadow-xl w-full"
          />
        </div>
      </div>
    </section>
  );
};

// Benefits Section Component
const BenefitsSection = () => {
  const benefits = [
    "Trained & Verified Professionals",
    "Advanced Machines & Imported Equipment",
    "90% Eco-Friendly Cleaning Products",
    "Flexible Scheduling & Same-Day Service",
    "100% Satisfaction Guarantee"
  ];

  return (
    <section className="py-16 bg-white">
      <div className="container mx-auto px-4">
        <h2 className="text-2xl md:text-3xl font-bold text-center mb-12">
          Experience a New Standard of Clean.
        </h2>
        
        <div className="flex flex-col md:flex-row items-center">
          <div className="md:w-1/2 mb-8 md:mb-0">
            <img 
              src="images/team-photo.jpg" 
              alt="Team of Indian cleaners in branded uniforms" 
              className="rounded-lg shadow-xl w-full"
            />
          </div>
          
          <div className="md:w-1/2 md:pl-12">
            <ul className="space-y-4">
              {benefits.map((benefit, index) => (
                <li key={index} className="flex items-start">
                  <span className="text-lime-green text-2xl mr-2">✅</span>
                  <span className="text-lg">{benefit}</span>
                </li>
              ))}
            </ul>
          </div>
        </div>
      </div>
    </section>
  );
};

// Services Section Component
const ServicesSection = () => {
  const services = [
    {
      icon: "🏠",
      title: "Home Deep Cleaning",
      description: "Reset your home to 'like-new' with our comprehensive deep cleaning service."
    },
    {
      icon: "🛋",
      title: "Sofa & Upholstery Cleaning",
      description: "Remove dirt, stains, and allergens from your furniture with our specialized cleaning."
    },
    {
      icon: "🏢",
      title: "Industrial & Corporate Cleaning",
      description: "Maintain a hygienic workplace with our professional corporate cleaning services."
    },
    {
      icon: "🏨",
      title: "Hotel & Hospitality Cleaning",
      description: "Ensure 5-star cleanliness for your guests with our hospitality cleaning solutions."
    }
  ];

  return (
    <section id="services" className="py-16 bg-light-gray">
      <div className="container mx-auto px-4">
        <h2 className="text-2xl md:text-3xl font-bold text-center mb-12">
          Our Professional Cleaning Services
        </h2>
        
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
          {services.map((service, index) => (
            <div key={index} className="service-card">
              <div className="text-4xl mb-4">{service.icon}</div>
              <h3 className="text-xl font-bold mb-2 text-royal-blue">{service.title}</h3>
              <p className="text-gray-700">{service.description}</p>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
};

// Before & After Section Component
const BeforeAfterSection = () => {
  return (
    <section className="py-16 bg-white">
      <div className="container mx-auto px-4">
        <h2 className="text-2xl md:text-3xl font-bold text-center mb-12">
          See the Difference With Your Own Eyes
        </h2>
        
        <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div className="before-after-card">
            <div className="relative">
              <img 
                src="images/before-after-sofa.jpg" 
                alt="Before and after sofa cleaning" 
                className="rounded-lg shadow-lg w-full"
              />
              <div className="absolute bottom-0 left-0 bg-royal-blue text-white py-2 px-4 rounded-tr-lg">
                Sofa Cleaning
              </div>
            </div>
          </div>
          
          <div className="before-after-card">
            <div className="relative">
              <img 
                src="images/before-after-bathroom.jpg" 
                alt="Before and after bathroom cleaning" 
                className="rounded-lg shadow-lg w-full"
              />
              <div className="absolute bottom-0 left-0 bg-royal-blue text-white py-2 px-4 rounded-tr-lg">
                Bathroom Cleaning
              </div>
            </div>
          </div>
          
          <div className="before-after-card">
            <div className="relative">
              <img 
                src="images/before-after-floor.jpg" 
                alt="Before and after floor cleaning" 
                className="rounded-lg shadow-lg w-full"
              />
              <div className="absolute bottom-0 left-0 bg-royal-blue text-white py-2 px-4 rounded-tr-lg">
                Floor Cleaning
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
};

// Testimonials Section Component
const TestimonialsSection = () => {
  const testimonials = [
    {
      name: "Priya Sharma",
      location: "Delhi",
      image: "images/testimonial-1.jpg",
      rating: 5,
      text: "Safai Sathi transformed my home! Their attention to detail is impressive. My kitchen and bathroom have never looked so clean."
    },
    {
      name: "Rajesh Patel",
      location: "Mumbai",
      image: "images/testimonial-2.jpg",
      rating: 5,
      text: "We hired Safai Sathi for our office cleaning, and the results were outstanding. Professional team, timely service, and excellent results."
    },
    {
      name: "Anita Desai",
      location: "Bangalore",
      image: "images/testimonial-3.jpg",
      rating: 4,
      text: "The sofa cleaning service was excellent. They removed stains I thought would never come out. Will definitely use their services again!"
    }
  ];

  return (
    <section id="testimonials" className="py-16 bg-light-gray">
      <div className="container mx-auto px-4">
        <h2 className="text-2xl md:text-3xl font-bold text-center mb-12">
          What Our Clients Say
        </h2>
        
        <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
          {testimonials.map((testimonial, index) => (
            <div key={index} className="testimonial-card">
              <div className="flex items-center mb-4">
                <img 
                  src={testimonial.image} 
                  alt={testimonial.name} 
                  className="w-16 h-16 rounded-full mr-4 object-cover"
                />
                <div>
                  <h4 className="font-bold">{testimonial.name}</h4>
                  <p className="text-gray-600">{testimonial.location}</p>
                </div>
              </div>
              <div className="mb-4 text-lime-green">
                {"★".repeat(testimonial.rating)}
                {"☆".repeat(5 - testimonial.rating)}
              </div>
              <p className="text-gray-700">{testimonial.text}</p>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
};

// Trust Badges Section Component
const TrustBadgesSection = () => {
  return (
    <section className="py-12 bg-white">
      <div className="container mx-auto px-4 text-center">
        <div className="flex flex-wrap justify-center items-center gap-8 mb-8">
          <img src="images/iso-badge.png" alt="ISO Certified" className="h-16" />
          <img src="images/eco-friendly-badge.png" alt="Eco-Friendly" className="h-16" />
          <img src="images/trusted-badge.png" alt="Trusted Service" className="h-16" />
        </div>
        
        <p className="text-xl mb-4">
          Trusted by 100+ Families & 25+ Businesses in Delhi NCR
        </p>
        
        <div className="text-2xl text-royal-blue font-bold">
          ⭐⭐⭐⭐⭐ <span className="text-lime-green">4.9/5</span> (Google Reviews)
        </div>
      </div>
    </section>
  );
};

// FAQ Section Component
const FAQSection = () => {
  const faqs = [
    {
      question: "Do I need to provide cleaning supplies?",
      answer: "No, we bring everything. Our team comes fully equipped with all necessary cleaning supplies, equipment, and eco-friendly products to ensure a thorough cleaning experience."
    },
    {
      question: "How long does a deep clean take?",
      answer: "3–5 hours depending on home size. The duration varies based on the size of your space, level of cleaning required, and specific services requested."
    },
    {
      question: "Are your cleaning products safe for kids and pets?",
      answer: "Yes, 90% of our products are eco-friendly and safe for children and pets. We prioritize using non-toxic cleaning solutions that effectively clean without harmful chemicals."
    },
    {
      question: "How do I schedule a cleaning service?",
      answer: "Simply call our customer service number or fill out the contact form on our website. Our team will get back to you promptly to schedule a convenient time for your cleaning service."
    },
    {
      question: "Do you offer recurring cleaning services?",
      answer: "Yes, we offer daily, weekly, bi-weekly, and monthly cleaning plans. Our recurring services come with special discounted rates and priority scheduling."
    }
  ];

  return (
    <section id="faq" className="py-16 bg-light-gray">
      <div className="container mx-auto px-4">
        <h2 className="text-2xl md:text-3xl font-bold text-center mb-12">
          Frequently Asked Questions
        </h2>
        
        <div className="max-w-3xl mx-auto">
          {faqs.map((faq, index) => (
            <div key={index} className="mb-6 bg-white rounded-lg shadow-md overflow-hidden">
              <div className="p-4 bg-royal-blue text-white font-semibold">
                {faq.question}
              </div>
              <div className="p-4 text-gray-700">
                {faq.answer}
              </div>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
};

// Final CTA Section Component
const FinalCTA = () => {
  return (
    <section id="contact" className="py-16 bg-royal-blue text-white">
      <div className="container mx-auto px-4 text-center">
        <h2 className="text-2xl md:text-3xl font-bold mb-6 text-white">
          Your Sparkling Space is Just One Call Away
        </h2>
        <p className="text-xl mb-8 max-w-2xl mx-auto">
          Experience the difference professional cleaning makes. Book your service today and enjoy a cleaner, healthier space.
        </p>
        <a href="tel:+919876543210" className="btn-secondary text-xl inline-block">
          📞 Call +91 9876 543 210 Now to Book
        </a>
      </div>
    </section>
  );
};

// Footer Component
const Footer = () => {
  return (
    <footer className="bg-gray-800 text-white py-8">
      <div className="container mx-auto px-4">
        <div className="grid grid-cols-1 md:grid-cols-4 gap-8">
          <div>
            <h3 className="text-xl font-bold mb-4 text-white">Safai Sathi</h3>
            <p>Professional cleaning services for homes, offices, and commercial spaces.</p>
          </div>
          
          <div>
            <h4 className="text-lg font-bold mb-4 text-white">Quick Links</h4>
            <ul className="space-y-2">
              <li><a href="#services" className="hover:text-lime-green">Services</a></li>
              <li><a href="#testimonials" className="hover:text-lime-green">Testimonials</a></li>
              <li><a href="#faq" className="hover:text-lime-green">FAQ</a></li>
              <li><a href="#contact" className="hover:text-lime-green">Contact</a></li>
            </ul>
          </div>
          
          <div>
            <h4 className="text-lg font-bold mb-4 text-white">Services</h4>
            <ul className="space-y-2">
              <li><a href="#" className="hover:text-lime-green">Home Cleaning</a></li>
              <li><a href="#" className="hover:text-lime-green">Office Cleaning</a></li>
              <li><a href="#" className="hover:text-lime-green">Sofa Cleaning</a></li>
              <li><a href="#" className="hover:text-lime-green">Hotel Cleaning</a></li>
            </ul>
          </div>
          
          <div>
            <h4 className="text-lg font-bold mb-4 text-white">Contact Us</h4>
            <ul className="space-y-2">
              <li>📞 +91 9876 543 210</li>
              <li>✉️ info@safaisathi.com</li>
              <li>🏢 123 Main Street, Delhi, India</li>
            </ul>
          </div>
        </div>
        
        <div className="border-t border-gray-700 mt-8 pt-8 text-center">
          <p>&copy; {new Date().getFullYear()} Safai Sathi. All rights reserved.</p>
        </div>
      </div>
    </footer>
  );
};

// Process Section Component
const ProcessSection = () => {
  const steps = [
    {
      number: "01",
      title: "Book Your Service",
      description: "Call us or fill out the booking form with your details and cleaning requirements.",
      icon: "📱"
    },
    {
      number: "02",
      title: "Confirm Details",
      description: "Our team will contact you to confirm the date, time, and specific cleaning needs.",
      icon: "📅"
    },
    {
      number: "03",
      title: "Professional Cleaning",
      description: "Our trained cleaning team arrives with all equipment and completes the service.",
      icon: "🧹"
    },
    {
      number: "04",
      title: "Quality Check",
      description: "We perform a thorough quality check to ensure everything meets our high standards.",
      icon: "✅"
    },
    {
      number: "05",
      title: "Feedback & Support",
      description: "Share your feedback and reach out anytime for post-service support.",
      icon: "🤝"
    }
  ];

  return (
    <section className="py-16 bg-white">
      <div className="container mx-auto px-4">
        <h2 className="text-2xl md:text-3xl font-bold text-center mb-4">
          How Safai Sathi Works
        </h2>
        <p className="text-center text-gray-700 mb-12 max-w-3xl mx-auto">
          Our simple 5-step process ensures a hassle-free cleaning experience from booking to completion.
        </p>
        
        <div className="relative">
          {/* Process timeline line */}
          <div className="hidden md:block absolute left-1/2 top-0 bottom-0 w-1 bg-light-gray transform -translate-x-1/2"></div>
          
          <div className="space-y-12 md:space-y-0">
            {steps.map((step, index) => (
              <div key={index} className={`flex flex-col md:flex-row items-center ${index % 2 === 0 ? 'md:flex-row' : 'md:flex-row-reverse'}`}>
                <div className={`md:w-1/2 mb-6 md:mb-0 ${index % 2 === 0 ? 'md:pr-12 md:text-right' : 'md:pl-12'}`}>
                  <div className="flex items-center mb-4 md:justify-end">
                    <span className="text-4xl mr-4">{step.icon}</span>
                    <div>
                      <div className="text-sm text-lime-green font-bold">STEP {step.number}</div>
                      <h3 className="text-xl font-bold text-royal-blue">{step.title}</h3>
                    </div>
                  </div>
                  <p className="text-gray-700">{step.description}</p>
                </div>
                
                <div className="md:w-0 relative">
                  <div className="w-12 h-12 rounded-full bg-royal-blue text-white flex items-center justify-center font-bold text-lg relative z-10">
                    {step.number}
                  </div>
                </div>
                
                <div className="md:w-1/2"></div>
              </div>
            ))}
          </div>
        </div>
        
        <div className="mt-16 text-center">
          <a href="tel:+919876543210" className="btn-primary text-lg inline-block">
            Start Your Cleaning Journey Today
          </a>
        </div>
      </div>
    </section>
  );
};

// Pricing Section Component
const PricingSection = () => {
  const pricingPlans = [
    {
      name: "Basic Home Cleaning",
      price: "₹1,499",
      description: "Perfect for regular maintenance of small homes",
      features: [
        "Dusting and wiping of all surfaces",
        "Vacuum cleaning of floors and carpets",
        "Bathroom cleaning and sanitization",
        "Kitchen cleaning and organization",
        "Up to 2 bedrooms"
      ],
      popular: false,
      buttonText: "Book Now"
    },
    {
      name: "Premium Deep Cleaning",
      price: "₹3,999",
      description: "Comprehensive cleaning for homes that need extra attention",
      features: [
        "Everything in Basic package",
        "Deep cleaning of kitchen appliances",
        "Sofa and upholstery cleaning",
        "Window and glass cleaning",
        "Wall spot cleaning",
        "Up to 3 bedrooms"
      ],
      popular: true,
      buttonText: "Most Popular"
    },
    {
      name: "Commercial Cleaning",
      price: "₹5,999",
      description: "Professional cleaning for offices and commercial spaces",
      features: [
        "Complete floor cleaning and polishing",
        "Workstation and furniture cleaning",
        "Restroom deep cleaning and sanitization",
        "Pantry and break room cleaning",
        "Window and glass cleaning",
        "Up to 2,000 sq. ft."
      ],
      popular: false,
      buttonText: "Contact Us"
    }
  ];

  return (
    <section id="pricing" className="py-16 bg-light-gray">
      <div className="container mx-auto px-4">
        <h2 className="text-2xl md:text-3xl font-bold text-center mb-4">
          Affordable Cleaning Packages
        </h2>
        <p className="text-center text-gray-700 mb-12 max-w-3xl mx-auto">
          Choose the perfect cleaning package that fits your needs and budget. All our services come with a 100% satisfaction guarantee.
        </p>
        
        <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
          {pricingPlans.map((plan, index) => (
            <div 
              key={index} 
              className={`bg-white rounded-lg shadow-lg overflow-hidden transition-transform duration-300 hover:transform hover:scale-105 ${plan.popular ? 'border-2 border-lime-green relative' : ''}`}
            >
              {plan.popular && (
                <div className="absolute top-0 right-0 bg-lime-green text-white py-1 px-4 rounded-bl-lg font-semibold">
                  Most Popular
                </div>
              )}
              
              <div className="p-6">
                <h3 className="text-xl font-bold mb-2 text-royal-blue">{plan.name}</h3>
                <div className="text-3xl font-bold mb-2">{plan.price}</div>
                <p className="text-gray-600 mb-6">{plan.description}</p>
                
                <ul className="space-y-3 mb-8">
                  {plan.features.map((feature, i) => (
                    <li key={i} className="flex items-start">
                      <span className="text-lime-green mr-2">✓</span>
                      <span>{feature}</span>
                    </li>
                  ))}
                </ul>
                
                <button 
                  className={`w-full py-3 px-6 rounded-lg font-bold ${plan.popular ? 'bg-lime-green text-white' : 'bg-royal-blue text-white'}`}
                >
                  {plan.buttonText}
                </button>
              </div>
            </div>
          ))}
        </div>
        
        <div className="mt-12 text-center">
          <p className="text-gray-700 mb-4">Need a custom cleaning solution?</p>
          <a href="tel:+919876543210" className="text-royal-blue font-bold hover:text-lime-green">
            Call us at +91 9876 543 210 for a personalized quote
          </a>
        </div>
      </div>
    </section>
  );
};

// Stats Section Component
const StatsSection = () => {
  const stats = [
    {
      number: "5000+",
      label: "Cleaning Jobs Completed",
      icon: "🏆"
    },
    {
      number: "100+",
      label: "Regular Clients",
      icon: "👨‍👩‍👧‍👦"
    },
    {
      number: "25+",
      label: "Professional Cleaners",
      icon: "👨‍🔧"
    },
    {
      number: "4.9/5",
      label: "Customer Rating",
      icon: "⭐"
    }
  ];

  return (
    <section className="py-16 bg-royal-blue text-white">
      <div className="container mx-auto px-4">
        <h2 className="text-2xl md:text-3xl font-bold text-center mb-12 text-white">
          Our Cleaning Impact
        </h2>
        
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
          {stats.map((stat, index) => (
            <div key={index} className="text-center bg-white/10 rounded-lg p-6 backdrop-blur-sm">
              <div className="text-4xl mb-4">{stat.icon}</div>
              <div className="text-3xl md:text-4xl font-bold mb-2 text-lime-green">{stat.number}</div>
              <div className="text-lg">{stat.label}</div>
            </div>
          ))}
        </div>
        
        <div className="mt-12 text-center">
          <p className="text-xl mb-6">Join our growing list of satisfied customers today!</p>
          <a href="tel:+919876543210" className="btn-secondary text-lg inline-block">
            Book Your Cleaning Service
          </a>
        </div>
      </div>
    </section>
  );
};

// Render the App component
ReactDOM.render(<App />, document.getElementById('root'));
// Cookie Consent Banner Component for Safai Sathi Landing Page

const CookieConsent = () => {
  const [isVisible, setIsVisible] = React.useState(false);
  
  React.useEffect(() => {
    // Check if user has already accepted cookies
    const hasAccepted = localStorage.getItem('cookieConsent') === 'accepted';
    
    if (!hasAccepted) {
      // Show banner after a short delay
      const timer = setTimeout(() => {
        setIsVisible(true);
      }, 2000);
      
      return () => clearTimeout(timer);
    }
  }, []);
  
  const acceptCookies = () => {
    localStorage.setItem('cookieConsent', 'accepted');
    setIsVisible(false);
  };
  
  if (!isVisible) {
    return null;
  }
  
  return (
    <div className="fixed bottom-0 left-0 right-0 bg-white shadow-lg border-t border-gray-200 p-4 z-50">
      <div className="container mx-auto flex flex-col md:flex-row items-center justify-between">
        <div className="mb-4 md:mb-0 md:mr-8">
          <p className="text-gray-700">
            We use cookies to improve your experience on our website. By browsing this website, you agree to our use of cookies.
          </p>
        </div>
        <div className="flex space-x-4">
          <a 
            href="#" 
            className="text-royal-blue hover:text-lime-green underline"
            onClick={(e) => {
              e.preventDefault();
              // Here you would typically open a privacy policy page
              alert('Privacy Policy would open here');
            }}
          >
            Privacy Policy
          </a>
          <button 
            className="bg-royal-blue hover:bg-lime-green text-white px-4 py-2 rounded-lg transition-colors"
            onClick={acceptCookies}
          >
            Accept
          </button>
        </div>
      </div>
    </div>
  );
};

// Export the component
// This can be imported in app.js and used in the App component

// Update App component to include CookieConsent
const AppWithCookieConsent = () => {
  return (
    <div className="App">
      <SEO />
      <PageLoading />
      <Header />
      <HeroSection />
      <BenefitsSection />
      <ServicesSection />
      <ProcessSection />
      <BeforeAfterSection />
      <PricingSection />
      <StatsSection />
      <TestimonialsSection />
      <TrustBadgesSection />
      <FAQSection />
      <FinalCTA />
      <Footer />
      <WhatsAppButton />
      <ScrollToTopButton />
      <CookieConsent />
    </div>
  );
};

// Replace the App component with AppWithCookieConsent
ReactDOM.render(<AppWithCookieConsent />, document.getElementById('root'));
