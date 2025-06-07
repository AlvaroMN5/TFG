<?php
// contact.php – versión final sin funciones, solo formulario + JS

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/header.php';
?>

<section class="contact-section">
  <h1>Contacto</h1>
  <form id="contact-form" action="#" method="post">
    <div class="form-group">
      <label for="email">Email:</label>
      <input
        type="email"
        id="email"
        name="email"
        placeholder="Ingresa tu correo"
        required
      >
    </div>
  
    <div class="form-group">
      <label for="message">Mensaje:</label>
      <textarea
        id="message"
        name="message"
        placeholder="Escribe tu mensaje"
        required
        minlength="10"
      ></textarea>
    </div>
  
    <button type="submit" class="btn btn-primary">Enviar Mensaje</button>
  </form>
</section>

<script>
document.addEventListener("DOMContentLoaded", function() {
  const form = document.getElementById("contact-form");

  form.addEventListener("submit", function(e) {
    e.preventDefault(); // Evita que el navegador recargue o muestre JSON

    // Mostrar ventana emergente
    alert("✅ Mensaje enviado con éxito.\n\nLe responderemos brevemente.");

    // Limpiar el formulario (opcional)
    form.reset();
  });
});
</script>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
