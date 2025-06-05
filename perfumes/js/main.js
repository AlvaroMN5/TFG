document.addEventListener('DOMContentLoaded', function() {
  // Menú hamburguesa para móviles
  const hamburger = document.querySelector('.hamburger');
  const navMenu = document.querySelector('.nav-menu');
  const navButtons = document.querySelector('.nav-buttons');
  
  if (hamburger) {
    hamburger.addEventListener('click', function() {
      navMenu.classList.toggle('active');
      navButtons.classList.toggle('active');
      this.classList.toggle('active');
    });
  }
  
  // Efecto activo para el nav
  const currentPage = window.location.pathname.split('/').pop() || 'index.php';
  const navLinks = document.querySelectorAll('.nav-link');
  
  navLinks.forEach(link => {
    const linkPage = link.getAttribute('href');
    if (currentPage === linkPage) {
      link.classList.add('active');
      link.style.color = 'var(--color-primario)';
      link.style.fontWeight = '600';
    }
    
    // Animación al hacer hover
    link.addEventListener('mouseenter', function() {
      if (!this.classList.contains('active')) {
        this.style.color = 'var(--color-primario)';
      }
    });
    
    link.addEventListener('mouseleave', function() {
      if (!this.classList.contains('active')) {
        this.style.color = 'var(--color-texto)';
      }
    });
  });
  
  // Smooth scroll para enlaces internos
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      e.preventDefault();
      document.querySelector(this.getAttribute('href')).scrollIntoView({
        behavior: 'smooth'
      });
    });
  });
  
  // Cargar header y footer dinámicamente
  function loadTemplate(file, elementId) {
    fetch(file)
      .then(response => response.text())
      .then(data => {
        document.getElementById(elementId).innerHTML = data;
      })
      .catch(err => console.error('Error loading template:', err));
  }
  
  // Descomenta estas líneas si quieres cargar header y footer dinámicamente
  // loadTemplate('includes/header.php', 'header');
  // loadTemplate('includes/footer.php', 'footer');
});