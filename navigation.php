<?php
session_start();
?>

<!-- Navigation -->
<nav>
    <a href="index.php">Explore</a>

    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="dashboard.php">Dashboard</a>
        <a href="upload_image.php">Bild hochladen</a>
        <a href="manage_subscription.php">Abonnement</a>
        <a href="logout.php">Logout</a>  <!-- Nur anzeigen, wenn der Benutzer eingeloggt ist -->
    <?php else: ?>
        <a href="register.php">Registrieren</a>
        <a href="login.php">Login</a>
    <?php endif; ?>
</nav>

