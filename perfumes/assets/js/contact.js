document.addEventListener("DOMContentLoaded", function() {
  const form = document.getElementById("contact-form");
  const responseDiv = document.getElementById("response-message");

  form.addEventListener("submit", function(e) {
    e.preventDefault();

    // Desactivar botón para evitar doble envío
    const submitBtn = form.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.textContent = "Enviando...";

    // Limpiar mensajes anteriores
    responseDiv.textContent = "";
    responseDiv.className = "response-message";

    // Enviar datos con fetch (AJAX)
    fetch(form.action, {
      method: form.method,
      body: new FormData(form),
    })
      .then(response => response.json())
      .then(data => {
        // data = { success: true|false, message: "..." }
        if (data.success) {
          responseDiv.textContent = data.message;
          responseDiv.classList.add("success");
          form.reset();
        } else {
          responseDiv.textContent = data.message;
          responseDiv.classList.add("error");
        }
      })
      .catch(err => {
        console.error("Error en fetch():", err);
        responseDiv.textContent = "Ocurrió un error inesperado. Intenta nuevamente.";
        responseDiv.classList.add("error");
      })
      .finally(() => {
        submitBtn.disabled = false;
        submitBtn.textContent = "Enviar Mensaje";
      });
  });
});
