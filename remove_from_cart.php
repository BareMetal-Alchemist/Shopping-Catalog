<?php
session_start();
require 'db-initializer.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['username'])) {
        header('Location: login.php');
        exit;
    }

    $cartItemID = isset($_POST['cartItemID']) ? (int)$_POST['cartItemID'] : 0;

    try {
        // Fetch the CartID before deleting the item
        $stmt = $pdo->prepare('SELECT CartID FROM junction_cartitem WHERE CartItemID = ?');
        $stmt->execute([$cartItemID]);
        $cartItem = $stmt->fetch();
        if (!$cartItem) {
            echo "Item not found.";
            exit;
        }
        $cartID = $cartItem['CartID'];

        // Delete the item from the cart
        $stmt = $pdo->prepare('DELETE FROM junction_cartitem WHERE CartItemID = ?');
        $stmt->execute([$cartItemID]);

        // Update cart size
        $stmt = $pdo->prepare('UPDATE entity_cart SET Size = Size - 1 WHERE CartID = ?');
        $stmt->execute([$cartID]);

        // Redirect back to the cart page
        header('Location: view_cart.php');
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit;
    }
}

