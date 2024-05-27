<?php
require 'db-initializer.php';

$gameID = isset($_GET['id']) ? (int)$_GET['id'] : 0;

try {
    $sql = "SELECT * FROM items WHERE ItemSKU = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$gameID]);
    $game = $stmt->fetch();
    
    if (!$game) {
        echo "Game not found!";
        exit;
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage(); 
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">1
    <title><?php echo htmlspecialchars($game['Name']); ?></title>
    <link rel="stylesheet" href="game.css">
</head>
<body>
    <header class="navbar">
        <div class="container">
            <img class="logo" src="Logo.webp" alt="Logo">
            <h1><?php echo htmlspecialchars($game['Name']); ?></h1>
        </div>
    </header>
    <main>
        <section class="game-detail">
            <img src="<?php echo $game['image']; ?>" alt="<?php echo htmlspecialchars($game['Name']); ?>">
            <h2><?php echo htmlspecialchars($game['Name']); ?></h2>
            <p><?php echo htmlspecialchars($game['Description']); ?></p>
            <p>Price: $<?php echo htmlspecialchars($game['Price']); ?></p>
            <form action="cart.php" method="post">
                <input type="hidden" name="gameID" value="<?php echo $game['ItemSKU']; ?>">
                <button type="submit">Add to Cart</button>
            </form>
        </section>
    </main>
</body>
</html>

