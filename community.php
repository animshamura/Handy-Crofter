<?php
    session_start();
    $conn = new mysqli("localhost", "root", "", "farmiq");

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $post_title = $_POST['post-title'];
        $post_content = $_POST['post-content'];
        $user_id = $_SESSION['user_id'];
        $stmt = $conn->prepare("INSERT INTO community_posts (user_id, post_title, post_content) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user_id, $post_title, $post_content);
        $stmt->execute();
        $stmt->close();
    }
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Community - FarmIQ</title>
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <header>
            <h1>🌿 FarmIQ - Community</h1>
        </header>
        <main>
            <form method="POST">
                <label for="post-title">Post Title:</label>
                <input type="text" id="post-title" name="post-title" required>

                <label for="post-content">Post Content:</label>
                <textarea id="post-content" name="post-content" required></textarea>

                <button type="submit">Post</button>
            </form>

            <section>
                <h2>Community Posts</h2>
                <div>
                    <?php
                    $result = $conn->query("SELECT * FROM community_posts");
                    while ($row = $result->fetch_assoc()) {
                        echo "<div><h3>{$row['post_title']}</h3><p>{$row['post_content']}</p></div>";
                    }
                    ?>
                </div>
            </section>
        </main>
    </body>
    </html>