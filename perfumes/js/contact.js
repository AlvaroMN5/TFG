document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contact-form');
    const responseMessage = document.getElementById('response-message');
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la red');
                }
                return response.json();
            })
            .then(data => {
                const messageElement = document.createElement('p');
                messageElement.classList.add(data.success ? 'success' : 'error');
                messageElement.textContent = data.message;
                
                responseMessage.innerHTML = '';
                responseMessage.appendChild(messageElement);
                
                if (data.success) {
                    contactForm.reset();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                responseMessage.innerHTML = '<p class="error">Error al enviar el mensaje</p>';
            });
        });
    }
});