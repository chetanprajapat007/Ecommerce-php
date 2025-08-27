// Mobile Menu Component for Safai Sathi Landing Page

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

// Export the component
// This can be imported in app.js and used in the Header component

