<?php
session_start();
require 'db.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_GET['remove'])) {
    unset($_SESSION['cart'][(int)$_GET['remove']]);
    header("Location: cart.php");
    exit;
}

$total = 0;
$items = [];

foreach ($_SESSION['cart'] as $id => $qty) {
    $stmt = $conn->prepare(
        "SELECT id, name, price FROM products WHERE id = ?"
    );
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $p = $stmt->get_result()->fetch_assoc();

    if ($p) {
        $p['qty'] = $qty;
        $p['subtotal'] = $p['price'] * $qty;
        $total += $p['subtotal'];
        $items[] = $p;
    }
}

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <h1>My Shopping Cart</h1>
    <nav><a href="index.php">Continue Shopping</a></nav>
</header>

<div class="cart">
<?php if (!$items): ?>
    <p>Your cart is empty.</p>
<?php else: ?>
    <?php foreach ($items as $item): ?>
        <div class="cart-item">
            <span><?= e($item['name']) ?>
                × <?= $item['qty'] ?></span>
            <span>₹<?= number_format($item['subtotal'], 2) ?></span>
            <a href="cart.php?remove=<?= $item['id'] ?>">Remove</a>
        </div>
    <?php endforeach; ?>

    <h2>Total: ₹<?= number_format($total, 2) ?></h2>
    <a class="button" href="checkout.php">Proceed to Checkout</a>
<?php endif; ?>
</div>
</body>
</html>