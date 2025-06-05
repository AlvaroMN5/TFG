document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contact-form');
    const responseMessage = document.getElementById('response-message');
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('contact.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    responseMessage.innerHTML = `<p class="success">${data.message}</p>`;
                    contactForm.reset();
                } else {
                    responseMessage.innerHTML = `<p class="error">${data.message}</p>`;
                }
            })
            .catch(error => {
                responseMessage.innerHTML = `<p class="error">Error al enviar el mensaje</p>`;
                console.error('Error:', error);
            });
        });
    }
});