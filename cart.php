<?php
session_start();
require 'db-initializer.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['username'])) {
        header('Location: login.php');
        exit;
    }
    
    $username = $_SESSION['username'];
    $gameID = isset($_POST['gameID']) ? (int)$_POST['gameID'] : 0;

    try {
        // Fetch the user ID
        $stmt = $pdo->prepare('SELECT UserID FROM entity_accounts WHERE Username = ?');
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        $userID = $user['UserID'];

        // Check if the user already has a cart
        $stmt = $pdo->prepare('SELECT CartID FROM entity_cart WHERE UserID = ?');
        $stmt->execute([$userID]);
        $cart = $stmt->fetch();
        if (!$cart) {
            // Create a new cart for the user
            $stmt = $pdo->prepare('INSERT INTO entity_cart (UserID, Size) VALUES (?, 0)');
            $stmt->execute([$userID]);
            $cartID = $pdo->lastInsertId();
        } else {
            $cartID = $cart['CartID'];
        }

        // Add the item to the cart
        $stmt = $pdo->prepare('INSERT INTO junction_cartitem (CartID, ItemSKU) VALUES (?, ?)');
        $stmt->execute([$cartID, $gameID]);

        // Update cart size
        $stmt = $pdo->prepare('UPDATE entity_cart SET Size = Size + 1 WHERE CartID = ?');
        $stmt->execute([$cartID]);

        // Redirect to the cart page
        header('Location: view_cart.php');
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit;
    }
}

