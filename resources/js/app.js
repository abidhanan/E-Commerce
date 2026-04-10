import './bootstrap';
// Di dalam resources/js/app.js

window.showLoginModal = function(e) {
    if(e) e.preventDefault(); 
    const modal = document.getElementById('auth-modal');
    const content = document.getElementById('auth-modal-content');
    
    modal.classList.remove('hidden');
    setTimeout(() => {
        content.classList.remove('scale-95');
        content.classList.add('scale-100');
    }, 10);
};

window.hideLoginModal = function() {
    const modal = document.getElementById('auth-modal');
    const content = document.getElementById('auth-modal-content');
    
    content.classList.remove('scale-100');
    content.classList.add('scale-95');
    
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 150);
};