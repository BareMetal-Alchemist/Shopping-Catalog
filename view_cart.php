<?php
session_start();
require 'db-initializer.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'];

try {
    // Fetch the user ID
    $stmt = $pdo->prepare('SELECT UserID FROM entity_accounts WHERE Username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    $userID = $user['UserID'];

    // Fetch the cart ID
    $stmt = $pdo->prepare('SELECT CartID FROM entity_cart WHERE UserID = ?');
    $stmt->execute([$userID]);
    $cart = $stmt->fetch();
    if (!$cart) {
        echo "Your cart is empty.";
        exit;
    }
    $cartID = $cart['CartID'];

    // Fetch items in the cart
    $stmt = $pdo->prepare('
        SELECT j.CartItemID, i.ItemSKU, i.Name, i.Description, i.Price, i.Image
        FROM junction_cartitem j
        JOIN items i ON j.ItemSKU = i.ItemSKU
        WHERE j.CartID = ?
    ');
    $stmt->execute([$cartID]);
    $items = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart</title>
    <link rel="stylesheet" href="cart.css">
</head>
<body>
    <header class="navbar">
        <div class="container">
            <a href = "IndexAdmin.php">
                <img class="logo" src="Logo.webp" >
            </a>
            <h1>My Cart</h1>
        </div>
    </header>
    <main>
        <section class="cart">
            <h2>Your Cart</h2>
            <?php if (empty($items)): ?>
                <p>Your cart is empty.</p>
            <?php else: ?>
                <ul>
                    <?php foreach ($items as $item): ?>
                        <li>
                            <img src="<?php echo htmlspecialchars($item['Image']); ?>" alt="<?php echo htmlspecialchars($item['Name']); ?>">
                            <h3><?php echo htmlspecialchars($item['Name']); ?></h3>
                            <p><?php echo htmlspecialchars($item['Description']); ?></p>
                            <p>Price: $<?php echo htmlspecialchars($item['Price']); ?></p>
                            <form action="remove_from_cart.php" method="post" style="display:inline;">
                                <input type="hidden" name="cartItemID" value="<?php echo $item['CartItemID']; ?>">
                                <button class = "removebtn" type="submit">Remove</button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>

