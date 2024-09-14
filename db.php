<?php

// Datenbankverbindung
$host = 'localhost';
$dbname = 'pixiframe_db';  // Dein Datenbankname
$username = 'root';  // Standardbenutzer in XAMPP
$password = '';  // Standardmäßig kein Passwort in XAMPP

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Verbindung zur Datenbank fehlgeschlagen: " . $e->getMessage());
}

?>