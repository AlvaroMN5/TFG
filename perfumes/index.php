<?php include 'includes/header.php'; ?>

<div class="hero">
    <h1>Bienvenido a nuestra tienda de perfumes</h1>
    <p>Descubre las mejores fragancias</p>
</div>

<div class="product-grid">
    <?php
    require_once 'includes/db.php';
    $stmt = $pdo->query("SELECT * FROM products LIMIT 6");
    while ($product = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo '<div class="product-card">';
        echo '<img src="images/products/'.$product['image'].'" alt="'.$product['name'].'">';
        echo '<h3>'.$product['name'].'</h3>';
        echo '<p>'.substr($product['description'], 0, 100).'...</p>';
        echo '<span class="price">$'.$product['price'].'</span>';
        echo '<a href="product.php?id='.$product['id'].'" class="btn">Ver Detalles</a>';
        echo '</div>';
    }
    ?>
</div>

<?php include 'includes/footer.php'; ?>