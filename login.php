<?php

include 'db.php';
include 'navigation.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user && $password === $user['password']) {  // Passwort im Klartext
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role'] = $user['role'];
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Falsche Anmeldedaten!";
    }
}
?>

<!-- HTML Formular für den Login -->
<form method="POST">
    <input type="email" name="email" placeholder="E-Mail" required><br>
    <input type="password" name="password" placeholder="Passwort" required><br>
    <button type="submit">Einloggen</button>
</form>
