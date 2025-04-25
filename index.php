<?php
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header('Location: auth/login.php');
        exit;
    }
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
                    <!-- PHP will populate tasks here -->
                </ul>
            </section>

            <section>
                <h2>Marketplace</h2>
                <ul>
                    <!-- PHP will populate marketplace items here -->
                </ul>
            </section>

            <section>
                <h2>Community Posts</h2>
                <!-- PHP will populate community posts here -->
            </section>
        </main>
    </body>
    </html>