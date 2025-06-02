document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contactForm');
    const responseDiv = document.getElementById('contactResponse');
    
    contactForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const email = document.getElementById('contactEmail').value;
        const message = document.getElementById('contactMessage').value;
        
        // Validación simple
        if (!email || !message) {
            showResponse('Por favor completa todos los campos', 'error');
            return;
        }
        
        if (!validateEmail(email)) {
            showResponse('Por favor ingresa un email válido', 'error');
            return;
        }
        
        // Simular envío (en producción usaría fetch() a un backend)
        setTimeout(() => {
            showResponse('Mensaje enviado con éxito. Te responderemos lo antes posible.', 'success');
            contactForm.reset();
        }, 1000);
    });
    
    function showResponse(message, type) {
        responseDiv.textContent = message;
        responseDiv.className = type;
        responseDiv.classList.remove('hidden');
        
        setTimeout(() => {
            responseDiv.classList.add('hidden');
        }, 5000);
    }
    
    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
});