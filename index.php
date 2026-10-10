<?php
session_start();
require 'db.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['product_id'];

    $stmt = $conn->prepare("SELECT id FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();

    if ($product) {
        $_SESSION['cart'][$id] =
            ($_SESSION['cart'][$id] ?? 0) + 1;
    }

    header("Location: index.php");
    exit;
}

$products = $conn->query("SELECT * FROM products");
function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Online Shopping</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <h1>ShopEasy</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="cart.php">Cart (<?= array_sum($_SESSION['cart']) ?>)</a>
    </nav>
</header>

<h2>Our Products</h2>

<div class="products">
<?php while ($p = $products->fetch_assoc()): ?>
    <div class="product">
        <img src="images/<?= e($p['image']) ?>"
             alt="<?= e($p['name']) ?>">
        <h3><?= e($p['name']) ?></h3>
        <p>₹<?= number_format($p['price'], 2) ?></p>

        <form method="post">
            <input type="hidden" name="product_id"
                   value="<?= (int)$p['id'] ?>">
            <button type="submit">Add to Cart</button>
        </form>
    </div>
<?php endwhile; ?>
</div>
</body>
</html>