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

