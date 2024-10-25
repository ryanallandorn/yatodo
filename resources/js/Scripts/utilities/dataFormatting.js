import DOMPurify from 'dompurify'
  

// Function to sanitize HTML content
export const sanitizeHTML = (html) => {
  return DOMPurify.sanitize(html);
}