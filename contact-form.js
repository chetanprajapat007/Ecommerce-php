// Contact Form Component for Safai Sathi Landing Page

const ContactForm = () => {
  return (
    <div className="bg-white rounded-lg shadow-xl p-6 md:p-8">
      <h3 className="text-xl font-bold mb-6 text-royal-blue">Book Your Cleaning Service</h3>
      
      <form id="contact-form" className="space-y-4">
        <div className="form-group">
          <label htmlFor="name" className="block text-gray-700 mb-2">Your Name</label>
          <input 
            type="text" 
            id="name" 
            className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-royal-blue" 
            placeholder="Enter your full name"
            required
          />
        </div>
        
        <div className="form-group">
          <label htmlFor="phone" className="block text-gray-700 mb-2">Phone Number</label>
          <input 
            type="tel" 
            id="phone" 
            className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-royal-blue" 
            placeholder="Enter your phone number"
            required
          />
        </div>
        
        <div className="form-group">
          <label htmlFor="address" className="block text-gray-700 mb-2">Address</label>
          <textarea 
            id="address" 
            rows="3" 
            className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-royal-blue" 
            placeholder="Enter your complete address"
          ></textarea>
        </div>
        
        <div className="form-group">
          <label htmlFor="service" className="block text-gray-700 mb-2">Service Required</label>
          <select 
            id="service" 
            className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-royal-blue"
            required
          >
            <option value="">Select a service</option>
            <option value="home-cleaning">Home Deep Cleaning</option>
            <option value="sofa-cleaning">Sofa & Upholstery Cleaning</option>
            <option value="office-cleaning">Office Cleaning</option>
            <option value="hotel-cleaning">Hotel Cleaning</option>
          </select>
        </div>
        
        <div className="form-group">
          <label htmlFor="date" className="block text-gray-700 mb-2">Preferred Date</label>
          <input 
            type="date" 
            id="date" 
            className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-royal-blue" 
          />
        </div>
        
        <div className="form-group">
          <label htmlFor="time" className="block text-gray-700 mb-2">Preferred Time</label>
          <select 
            id="time" 
            className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-royal-blue"
          >
            <option value="">Select a time slot</option>
            <option value="morning">Morning (8 AM - 12 PM)</option>
            <option value="afternoon">Afternoon (12 PM - 4 PM)</option>
            <option value="evening">Evening (4 PM - 8 PM)</option>
          </select>
        </div>
        
        <div className="form-group">
          <label htmlFor="message" className="block text-gray-700 mb-2">Special Instructions (Optional)</label>
          <textarea 
            id="message" 
            rows="3" 
            className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-royal-blue" 
            placeholder="Any specific requirements or instructions"
          ></textarea>
        </div>
        
        <div className="mt-6">
          <button 
            type="submit" 
            className="w-full btn-primary py-3 px-6 text-lg"
          >
            Book Now
          </button>
        </div>
      </form>
    </div>
  );
};

// Export the component
// This can be imported in app.js and used in the appropriate section

