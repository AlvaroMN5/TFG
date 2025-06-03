document.addEventListener('DOMContentLoaded', function() {
    // Elementos del modal
    const contactBtn = document.getElementById('contactBtn');
    const modal = document.getElementById('contactModal');
    const closeBtn = document.querySelector('.close-modal');
    const contactForm = document.getElementById('contactForm');
    const responseDiv = document.getElementById('contactResponse');

    // Mostrar modal
    contactBtn.addEventListener('click', function(e) {
        e.preventDefault();
        modal.style.display = 'flex';
    });

    // Ocultar modal
    closeBtn.addEventListener('click', function() {
        modal.style.display = 'none';
        responseDiv.style.display = 'none';
    });

    // Cerrar al hacer clic fuera
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.style.display = 'none';
            responseDiv.style.display = 'none';
        }
    });

    // Enviar formulario
    contactForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const email = document.getElementById('contactEmail').value;
        const message = document.getElementById('contactMessage').value;
        const submitBtn = this.querySelector('button[type="submit"]');
        
        // Validación
        if (!validateEmail(email)) {
            showResponse('Por favor ingresa un email válido', 'error');
            return;
        }
        
        if (message.length < 10) {
            showResponse('El mensaje debe tener al menos 10 caracteres', 'error');
            return;
        }
        
        // Deshabilitar botón durante el envío
        submitBtn.disabled = true;
        submitBtn.textContent = 'Enviando...';
        
        // Envío con Fetch API
        fetch('contact.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `email=${encodeURIComponent(email)}&message=${encodeURIComponent(message)}`
        })
        .then(response => {
            if (!response.ok) throw new Error('Error en la red');
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showResponse('Mensaje enviado con éxito. Te responderemos pronto.', 'success');
                contactForm.reset();
                setTimeout(() => {
                    modal.style.display = 'none';
                    responseDiv.style.display = 'none';
                }, 3000);
            } else {
                showResponse(data.message || 'Error al enviar el mensaje', 'error');
            }
        })
        .catch(error => {
            showResponse('Error de conexión: ' + error.message, 'error');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Enviar Mensaje';
        });
    });
    
    // Mostrar mensaje de respuesta
    function showResponse(msg, type) {
        responseDiv.textContent = msg;
        responseDiv.className = 'response-message ' + type;
        responseDiv.style.display = 'block';
    }
    
    // Validar email
    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
});