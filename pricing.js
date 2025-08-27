// Pricing Component for Safai Sathi Landing Page

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

// Export the component
// This can be imported in app.js and used in the appropriate section

