<?php
    session_start();
    $conn = new mysqli("localhost", "root", "", "farmiq");

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $product_name = $_POST['product-name'];
        $price = $_POST['price'];
        $quantity = $_POST['quantity'];
        $user_id = $_SESSION['user_id'];
        $stmt = $conn->prepare("INSERT INTO marketplace_items (user_id, product_name, price, quantity) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("issi", $user_id, $product_name, $price, $quantity);
        $stmt->execute();
        $stmt->close();
    }
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Farm Marketplace - FarmIQ</title>
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <header>
            <h1>🌾 FarmIQ - Marketplace</h1>
        </header>
        <main>
            <form method="POST">
                <label for="product-name">Product Name:</label>
                <input type="text" id="product-name" name="product-name" required>

                <label for="price">Price:</label>
                <input type="number" id="price" name="price" required>

                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" name="quantity" required>

                <button type="submit">Add Product</button>
            </form>

            <section>
                <h2>Available Products</h2>
                <table>
                    <tr>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                    </tr>
                    <?php
                    $result = $conn->query("SELECT * FROM marketplace_items");
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr><td>{$row['product_name']}</td><td>{$row['price']}</td><td>{$row['quantity']}</td></tr>";
                    }
                    ?>
                </table>
            </section>
        </main>
    </body>
    </html>