// Loading Animation Component for Safai Sathi Landing Page

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

// Export the component
// This can be imported in app.js and used in the App component

