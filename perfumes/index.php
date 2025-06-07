<?php include 'includes/header.php'; ?>

<!-- Carrusel de 5 fotos de perfumes -->
<div class="carousel-container fade-in">
  <div class="carousel-slides">
    <div class="carousel-slide">
      <img src="<?php echo BASE_URL; ?>images/products/hero/carousel1.jpg" alt="Perfume 1">
    </div>
    <div class="carousel-slide">
      <img src="<?php echo BASE_URL; ?>images/products/hero/carousel2.jpg" alt="Perfume 2">
    </div>
    <div class="carousel-slide">
      <img src="<?php echo BASE_URL; ?>images/products/hero/carousel3.jpg" alt="Perfume 3">
    </div>
    <div class="carousel-slide">
      <img src="<?php echo BASE_URL; ?>images/products/hero/carousel4.jpg" alt="Perfume 4">
    </div>
    <div class="carousel-slide">
      <img src="<?php echo BASE_URL; ?>images/products/hero/carousel5.jpg" alt="Perfume 5">
    </div>
  </div>
  <!-- Botones de navegación -->
  <button class="carousel-btn prev">&#10094;</button>
  <button class="carousel-btn next">&#10095;</button>
  <!-- Indicadores (5 puntos) -->
  <div class="carousel-indicators">
    <span class="indicator active" data-slide="0"></span>
    <span class="indicator" data-slide="1"></span>
    <span class="indicator" data-slide="2"></span>
    <span class="indicator" data-slide="3"></span>
    <span class="indicator" data-slide="4"></span>
  </div>
</div>


<div class="product-list">
    <?php
    require_once 'includes/db.php';
    $stmt = $pdo->query("SELECT * FROM products LIMIT 6");
    while ($product = $stmt->fetch(PDO::FETCH_ASSOC)):
    ?>
        <!-- Envolvemos la tarjeta entera en un <a> -->
        <a href="product.php?id=<?= (int)$product['id'] ?>" class="product-card-link">
            <div class="product-card fade-in">
                <img src="<?= BASE_URL ?>images/products/<?= htmlspecialchars($product['image']) ?>"
                     alt="<?= htmlspecialchars($product['name']) ?>">
                <h3><?= htmlspecialchars($product['name']) ?></h3>
                <p class="price">$<?= number_format($product['price'], 2) ?></p>
            </div>
        </a>
        <!-- No incluimos aquí ningún botón “Añadir al Carrito” -->
    <?php endwhile; ?>
</div>



<?php include 'includes/footer.php'; ?>
