// Stats Section Component for Safai Sathi Landing Page

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

// Export the component
// This can be imported in app.js and used in the appropriate section

