// assets/js/main_custom.js

document.addEventListener("DOMContentLoaded", () => {
  // 1) Toggle del dropdown de usuario
  function toggleUserDropdown() {
    const dropdown = document.getElementById("userDropdown");
    dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
  }
  window.toggleUserDropdown = toggleUserDropdown;

  // Cerrar dropdown al hacer clic fuera
  document.addEventListener("click", function(event) {
    const menu = document.querySelector(".user-menu");
    const dropdown = document.getElementById("userDropdown");
    if (menu && !menu.contains(event.target)) {
      dropdown.style.display = "none";
    }
  });

  // 2) Animaciones 'fade-in' al hacer scroll
  const faders = document.querySelectorAll(".fade-in");
  const appearOptions = { threshold: 0.1 };
  const appearOnScroll = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
      if (!entry.isIntersecting) return;
      entry.target.classList.add("visible");
      observer.unobserve(entry.target);
    });
  }, appearOptions);

  faders.forEach(fader => {
    appearOnScroll.observe(fader);
  });

  // 3) Zoom suave en imágenes de producto (opcional)
  const productImgs = document.querySelectorAll(".product-card img");
  productImgs.forEach(img => {
    img.addEventListener("mouseenter", () => {
      img.style.transform = "scale(1.05)";
    });
    img.addEventListener("mouseleave", () => {
      img.style.transform = "scale(1)";
    });
  });
});
// assets/js/main_custom.js

document.addEventListener("DOMContentLoaded", () => {
  const slidesContainer = document.querySelector(".carousel-slides");
  const slides = document.querySelectorAll(".carousel-slide");
  const prevBtn = document.querySelector(".carousel-btn.prev");
  const nextBtn = document.querySelector(".carousel-btn.next");
  const indicators = document.querySelectorAll(".carousel-indicators .indicator");

  let currentIndex = 0;
  const totalSlides = slides.length; // Debe ser 5

  function updateCarousel() {
    // Cada slide ocupa 20% del ancho total (500%), así que:
    // index 0 => translateX(0%)
    // index 1 => translateX(-20%)
    // index 2 => translateX(-40%)
    // index 3 => translateX(-60%)
    // index 4 => translateX(-80%)
    const offset = currentIndex * 20;
    slidesContainer.style.transform = `translateX(-${offset}%)`;

    indicators.forEach((dot, idx) => {
      dot.classList.toggle("active", idx === currentIndex);
    });
  }

  function showPrevSlide() {
    currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
    updateCarousel();
  }

  function showNextSlide() {
    currentIndex = (currentIndex + 1) % totalSlides;
    updateCarousel();
  }

  if (prevBtn) prevBtn.addEventListener("click", showPrevSlide);
  if (nextBtn) nextBtn.addEventListener("click", showNextSlide);

  indicators.forEach(dot => {
    dot.addEventListener("click", () => {
      currentIndex = parseInt(dot.dataset.slide, 10);
      updateCarousel();
    });
  });

  // Auto-avance cada 5 segundos
  setInterval(showNextSlide, 5000);

  // Inicializa en el slide 0
  updateCarousel();
});
