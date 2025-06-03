    </main>
    
    <footer>
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Tienda de Perfumes. Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Modal de Contacto -->
    <div id="contactModal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h3>Contáctanos</h3>
            <form id="contactForm">
                <div class="form-group">
                    <input type="email" id="contactEmail" placeholder="Tu correo electrónico" required>
                </div>
                <div class="form-group">
                    <textarea id="contactMessage" placeholder="Tu mensaje..." required></textarea>
                </div>
                <button type="submit" class="btn">Enviar Mensaje</button>
            </form>
            <div id="contactResponse" class="response-message"></div>
        </div>
    </div>

    <script src="assets/js/script.js"></script>
</body>
</html>