    </main>

<?php
// includes/footer.php
$current = basename($_SERVER['SCRIPT_NAME']); 
?>
<footer class="footer">
  <div class="footer-content">
    <div class="footer-links">
      <a href="<?php echo BASE_URL; ?>privacy.php"
         class="<?php echo $current==='privacy.php' ? 'active' : ''; ?>">
        Privacidad
      </a>
      <span class="divider">|</span>
      <a href="<?php echo BASE_URL; ?>legal.php"
         class="<?php echo $current==='legal.php' ? 'active' : ''; ?>">
        Aviso Legal
      </a>
      <span class="divider">|</span>
      <a href="<?php echo BASE_URL; ?>cookies.php"
         class="<?php echo $current==='cookies.php' ? 'active' : ''; ?>">
        Cookies
      </a>
      <div class="cookie-reset">
  <a href="#" id="reset-cookies">Cambiar preferencias de cookies</a>
</div>

    </div>
    <p>© <?php echo date('Y'); ?> Todo Perfumes. Todos los derechos reservados.</p>
  </div>
</footer>


  <!-- FontAwesome (para los iconos) -->
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous" defer></script>
  <!-- Tu JavaScript principal -->
  <script src="<?php echo BASE_URL; ?>js/main.js" defer></script>
</body>
</html>
