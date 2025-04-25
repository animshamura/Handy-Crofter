<?php
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header('Location: auth/login.php');
        exit;
    }
    $conn = new mysqli("localhost", "root", "", "farmiq");
    $tasks = $conn->query("SELECT * FROM tasks WHERE user_id = {$_SESSION['user_id']}");
    $marketplace_items = $conn->query("SELECT * FROM marketplace_items");
    $community_posts = $conn->query("SELECT * FROM community_posts");
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>FarmIQ Dashboard</title>
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <header>
            <h1>🌱 FarmIQ Dashboard</h1>
        </header>
        <main>
            <section>
                <h2>Your Tasks</h2>
                <ul>
                    <?php while ($task = $tasks->fetch_assoc()): ?>
                        <li><?php echo $task['task_name']; ?> - Due: <?php echo $task['due_date']; ?></li>
                    <?php endwhile; ?>
                </ul>
            </section>

            <section>
                <h2>Marketplace</h2>
                <ul>
                    <?php while ($item = $marketplace_items->fetch_assoc()): ?>
                        <li><?php echo $item['product_name']; ?> - Price: <?php echo $item['price']; ?> - Quantity: <?php echo $item['quantity']; ?></li>
                    <?php endwhile; ?>
                </ul>
            </section>

            <section>
                <h2>Community Posts</h2>
                <?php while ($post = $community_posts->fetch_assoc()): ?>
                    <div>
                        <h3><?php echo $post['post_title']; ?></h3>
                        <p><?php echo $post['post_content']; ?></p>
                    </div>
                <?php endwhile; ?>
            </section>
        </main>
    </body>
    </html>