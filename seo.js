// SEO Component for Safai Sathi Landing Page

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

// Export the component
// This can be imported in app.js and used in the App component

