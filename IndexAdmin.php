<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GHOST - Home</title>
    <link rel="stylesheet" href="main.css">
</head>
<body>
    <?php require 'db-initializer.php' ?>
    <header class="navbar">
        <div class = "container">
            <a href = "IndexAdmin.php">
                <img class="logo" src="Logo.webp" >
            </a>
            <h1>GHOST</h1>
        </div>
        <div class="user">
            <?php
            session_start();
            if (isset($_SESSION['username'])) {
                echo '<span class="username"><a href="UserMenuAdmin.php">' . htmlspecialchars($_SESSION['username']) .  '</a></span>';
            }
            ?>
            
        </div>
    </header>
    <main>
        <section class="banner">
            <div class="carousel">
                <div class="slides">
                    <div class="slide active" style="background-image: url('Cyberpunk.jpg');"></div>
                    <div class="slide" style="background-image: url('MarioW.jpg');"></div>
                    <div class="slide" style="background-image: url('PS3R.jpg');"></div>
                </div>
                <div class="dots">
                    <span class="dot active" data-slide="0"></span>
                    <span class="dot" data-slide="1"></span>
                    <span class="dot" data-slide="2"></span>
                </div>
            </div>
        </section>
        <div class="section">
            <h1>Browse Genres</h1>
        </div>
        <section class="categories">
            <a href="action.php" class="category-link">
                <div class="category">
                    <h3>Action</h3>
                </div>
            </a>
            <a href="adventure.php" class="category-link">
                <div class="category">
                    <h3>Adventure</h3>
                </div>
            </a>
            <a href="rpg.php" class="category-link">
                <div class="category">
                    <h3>RPG</h3>
                </div>
            </a>
            <a href="simulation.php" class="category-link">
                <div class="category">
                    <h3>Simulation</h3>
                </div>
            </a>
            <a href="strategy.php" class="category-link">
                <div class="category">
                    <h3>Strategy</h3>
                </div>
            </a>
            <a href="sports.php" class="category-link">
                <div class="category">
                    <h3>Sports</h3>
                </div>
            </a>
        </section>
    </main>
    <footer class = "footer">
        <p>&copy; 2024 GHOST. All rights reserved.</p>
    </footer>
    <script src="script.js"></script>
</body>
</html>
