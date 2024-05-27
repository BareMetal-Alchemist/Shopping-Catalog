<?php
session_start();
require 'db-initializer.php';

const RECORDS_PER_PAGE = 10; //set records per page

// Current page
$userPage = isset($_GET['userPage']) ? (int)$_GET['userPage'] : 1;
$itemPage = isset($_GET['itemPage']) ? (int)$_GET['itemPage'] : 1;

// Calculate offset 
$userOffset = ($userPage - 1) * RECORDS_PER_PAGE;
$itemOffset = ($itemPage - 1) * RECORDS_PER_PAGE;

// Delete and add
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $activeSection = $_POST['activeSection'] ?? 'users';

    if (isset($_POST['delete'])) {
        $userID = $_POST['userID'];
        $sql = "DELETE FROM entity_accounts WHERE UserID = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$userID]);
    } elseif (isset($_POST['addUser'])) {
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $isAdmin = isset($_POST['isAdmin']) ? 1 : 0;
        $sql = "INSERT INTO entity_accounts (Username, Password, isAdmin) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$username, $password, $isAdmin]);
    } elseif (isset($_POST['deleteItem'])) {
        $itemSKU = $_POST['itemSKU'];
        $sql = "DELETE FROM items WHERE ItemSKU = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$itemSKU]);
    } elseif (isset($_POST['addItem'])) {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $image = $_POST['image'];
        $sql = "INSERT INTO items (Name, Description, price, Image) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name, $description, $price ,$image]);
    }
} else {
    $activeSection = $_GET['section'] ?? 'users';
}

// Fetch num of usrs and items
$totalUsers = $pdo->query("SELECT COUNT(*) FROM entity_accounts")->fetchColumn();
$totalItems = $pdo->query("SELECT COUNT(*) FROM items")->fetchColumn();

// Pagination
try {
    $usersStmt = $pdo->prepare("SELECT * FROM entity_accounts LIMIT :limit OFFSET :offset");
    $usersStmt->bindValue(':limit', RECORDS_PER_PAGE, PDO::PARAM_INT);
    $usersStmt->bindValue(':offset', $userOffset, PDO::PARAM_INT);
    $usersStmt->execute();
    $users = $usersStmt->fetchAll();

    $itemsStmt = $pdo->prepare("SELECT * FROM items LIMIT :limit OFFSET :offset");
    $itemsStmt->bindValue(':limit', RECORDS_PER_PAGE, PDO::PARAM_INT);
    $itemsStmt->bindValue(':offset', $itemOffset, PDO::PARAM_INT);
    $itemsStmt->execute();
    $items = $itemsStmt->fetchAll();
} catch (\PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}

// Calculate total pages
$totalUserPages = ceil($totalUsers / RECORDS_PER_PAGE);
$totalItemPages = ceil($totalItems / RECORDS_PER_PAGE);
?>






<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin User Menu</title>
    <link rel="stylesheet" href="admin.css">

   
</head>
<body>
    <header class="navbar">
        <div class="container">
            <a href="IndexAdmin.php">
                <img class="logo" src="Logo.webp">
            </a>
            <h1>Admin Activities</h1>
        </div>
        <div class="user">
            <span class="username"><a href="index.php"><?php echo htmlspecialchars($_SESSION['username']); ?></a></span>
        </div>
    </header>

    <main>
        <h2>Admin User Menu</h2>
        <nav>
            <button id="showUsersBtn">Users</button>
            <button id="showItemsBtn">Items</button>
        </nav>

        <section id="usersSection" class="section <?php if ($activeSection == 'users') echo 'active'; ?>">
            <h3>All Users</h3>
            <table border="1">
                <tr><th>ID</th><th>Username</th><th>Admin</th><th>Action</th></tr>
                <?php foreach ($users as $row) { ?>
                    <tr>
                        <td><?php echo $row['UserID']; ?></td>
                        <td><?php echo htmlspecialchars($row['Username']); ?></td>
                        <td><?php echo $row['isAdmin'] ? 'Yes' : 'No'; ?></td>
                        <td>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="userID" value="<?php echo $row['UserID']; ?>">
                                <input type="hidden" name="activeSection" value="users">
                                <button type="submit" name="delete" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </table>
            <div class="pagination">
                <?php for ($i = 1; $i <= $totalUserPages; $i++): ?>
                    <a href="?userPage=<?php echo $i; ?>&section=users" class="<?php if ($i == $userPage) { echo 'active'; } ?>"><?php echo $i; ?></a>
                <?php endfor; ?>
            </div>

            <h3>Add New User</h3>
            <form method="post">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <label for="isAdmin">Admin:</label>
                <input type="checkbox" id="isAdmin" name="isAdmin">
                <input type="hidden" name="activeSection" value="users">
                <button type="submit" name="addUser">Add User</button>
            </form>
        </section>

        <section id="itemsSection" class="section <?php if ($activeSection == 'items') echo 'active'; ?>">
            <h3>All Items</h3>
            <table border="1">
                <tr><th>SKU</th><th>Name</th><th>Description</th><th>Price</th><th>Image</th><th>Action</th></tr>
                <?php foreach ($items as $row) { ?>
                    <tr>
                        <td><?php echo $row['ItemSKU']; ?></td>
                        <td><?php echo htmlspecialchars($row['Name']); ?></td>
                        <td><?php echo htmlspecialchars($row['Description']); ?></td>
                        <td><?php echo htmlspecialchars($row['Price']); ?></td>
                        <td><?php echo htmlspecialchars($row['image']); ?></td>
                        <td>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="itemSKU" value="<?php echo $row['ItemSKU']; ?>">
                                <input type="hidden" name="activeSection" value="items">
                                <button type="submit" name="deleteItem" onclick="return confirm('Are you sure you want to delete this item?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </table>
            <div class="pagination">
                <?php for ($i = 1; $i <= $totalItemPages; $i++): ?>
                    <a href="?itemPage=<?php echo $i; ?>&section=items" class="<?php if ($i == $itemPage) {echo 'active';} ?>"><?php echo $i; ?></a>
                <?php endfor; ?>
            </div>

            <h3>Add New Item</h3>
            <form method="post">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
                <label for="description">Description:</label>
                <textarea id="description" name="description" required></textarea>
                <label for="price">Price:</label>
                <input type="text" id="price" name="price" required>
                <label for="image">Image Path:</label>
                <input type="text" id="image" name="image" required>
                <input type="hidden" name="activeSection" value="items">
                <button type="submit" name="addItem">Add Item</button>
            </form>
        </section>
    </main>

    <footer class="footer">
        <p>&copy; 2024 GHOST. All rights reserved.</p>
    </footer>

    <script>
        document.getElementById('showUsersBtn').addEventListener('click', function() {
            document.getElementById('usersSection').classList.add('active');
            document.getElementById('itemsSection').classList.remove('active');
            window.history.pushState({}, '', '?section=users');
        });

        document.getElementById('showItemsBtn').addEventListener('click', function() {
            document.getElementById('usersSection').classList.remove('active');
            document.getElementById('itemsSection').classList.add('active');
            window.history.pushState({}, '', '?section=items');
        });
    </script>
</body>
</html>
