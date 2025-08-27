// Process Section Component for Safai Sathi Landing Page

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

// Export the component
// This can be imported in app.js and used in the appropriate section

