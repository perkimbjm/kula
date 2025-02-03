
if ('serviceWorker' in navigator) {  
    window.addEventListener('load', () => {  
      navigator.serviceWorker.register('/js/service-worker.js')  
        .then(registration => {  
          console.log('ServiceWorker registration successful with scope: ', registration.scope);  
        })  
        .catch(error => {  
          console.log('ServiceWorker registration failed: ', error);  
        });  
    });  
  }
document.addEventListener('DOMContentLoaded', () => {
    // Tambahkan smooth scroll ke HTML
    document.documentElement.style.scrollBehavior = 'smooth';

    // Handler untuk tombol back-to-top
    const backToTop = document.querySelector('.back-to-top');
    

    // Toggle visibility tombol back-to-top
    const toggleBackToTop = () => {
        if (window.scrollY > 200) {
            backToTop.classList.add('opacity-100');
            backToTop.classList.remove('opacity-0');
        } else {
            backToTop.classList.add('opacity-0');
            backToTop.classList.remove('opacity-100');
        }
    };

    // Listen untuk scroll event
    window.addEventListener('scroll', toggleBackToTop);
});