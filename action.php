<?php
require 'db-initializer.php';

try {
    $sql = "SELECT * FROM items WHERE Genre = 'Action'";
    $stmt = $pdo->query($sql);
    $games = $stmt->fetchAll();
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
    <title>Action Games</title>
    <link rel="stylesheet" href="game_list.css">
</head>
<body>
    <header class="navbar">
        <div class="container">
            <img class="logo" src="Logo.webp" alt="Logo">
            <h1>Action Games</h1>
        </div>
    </header>
    <main>
        <section class="game-list">
            <h2>Action Games</h2>
            <ul>
                <?php foreach ($games as $game): ?>
                    <li>
                        <a href="game.php?id=<?php echo $game['ItemSKU']; ?>">
                            <img src="<?php echo $game['image']; ?>" alt="<?php echo htmlspecialchars($game['Name']); ?>">
                            <h3><?php echo htmlspecialchars($game['Name']); ?></h3>
                            <p><?php echo htmlspecialchars($game['Description']); ?></p>
                            <p>Price: $<?php echo htmlspecialchars($game['Price']); ?></p>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    </main>
</body>
</html>

