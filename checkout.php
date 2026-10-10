<?php
session_start();
require 'db.php';

if (empty($_SESSION['cart'])) {
    exit("Your cart is empty. <a href='index.php'>Shop now</a>");
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['customer_name'] ?? '');
    $address = trim($_POST['address'] ?? '');

    if ($name === '' || $address === '') {
        $message = "Please enter your name and address.";
    } else {
        $conn->begin_transaction();

        try {
            $total = 0;
            $items = [];

            foreach ($_SESSION['cart'] as $id => $qty) {
                $id = (int)$id;
                $qty = (int)$qty;

                $stmt = $conn->prepare(
                    "SELECT id, price FROM products WHERE id = ?"
                );
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $p = $stmt->get_result()->fetch_assoc();

                if (!$p || $qty < 1) {
                    throw new Exception("Invalid cart item.");
                }

                $total += $p['price'] * $qty;
                $items[] = [
                    'id' => $p['id'],
                    'price' => $p['price'],
                    'qty' => $qty
                ];
            }

            $stmt = $conn->prepare(
                "INSERT INTO orders (customer_name, address, total)
                 VALUES (?, ?, ?)"
            );
            $stmt->bind_param("ssd", $name, $address, $total);
            $stmt->execute();
            $orderId = $conn->insert_id;

            $stmt = $conn->prepare(
                "INSERT INTO order_items
                 (order_id, product_id, quantity, price)
                 VALUES (?, ?, ?, ?)"
            );

            foreach ($items as $item) {
                $pid = $item['id'];
                $qty = $item['qty'];
                $price = $item['price'];

                $stmt->bind_param(
                    "iiid", $orderId, $pid, $qty, $price
                );
                $stmt->execute();
            }

            $conn->commit();
            $_SESSION['cart'] = [];

            $message = "Order placed successfully! Order ID: "
                     . $orderId;
        } catch (Throwable $ex) {
            $conn->rollback();
            $message = "Could not place order. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header><h1>Checkout</h1></header>

<div class="checkout">
    <p><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>

    <?php if (!empty($_SESSION['cart'])): ?>
    <form method="post">
        <label>Customer Name</label>
        <input type="text" name="customer_name" required>

        <label>Address</label>
        <textarea name="address" required></textarea>

        <button type="submit">Place Order</button>
    </form>
    <?php endif; ?>

    <p><a href="index.php">Back to Home</a></p>
</div>
</body>
</html>