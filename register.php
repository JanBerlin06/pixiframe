<?php

include 'db.php';
include 'header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];  // Passwort im Klartext speichern
    $role = 'Kunde';  // Standardrolle für neue Benutzer

    $sql = "INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute(['name' => $name, 'email' => $email, 'password' => $password, 'role' => $role])) {
        echo "Registrierung erfolgreich!";
    } else {
        echo "Fehler bei der Registrierung!";
    }
}
?>

<main class="register-page">
    <h1>PixiFrame - Registrierung</h1>
<!-- HTML Formular für die Registrierung -->
<form class="form-default-design" method="POST">
    <input type="text" name="name" placeholder="Name" required><br>
    <input type="email" name="email" placeholder="E-Mail" required><br>
    <input type="password" name="password" placeholder="Passwort" required><br>
    <button type="submit">Registrieren</button>
</form>
</main>

<?php
include 'footer.php';
?>
