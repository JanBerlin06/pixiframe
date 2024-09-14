<?php
include 'db.php';
session_start();

// Prüfen, ob ein Kommentar gesendet wurde
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment_text'], $_POST['image_id'])) {
    $commentText = $_POST['comment_text'];
    $imageId = (int)$_POST['image_id'];

    // Benutzername aus der Session nehmen, falls vorhanden, ansonsten Standardwert für Gäste setzen
    $author = isset($_SESSION['username']) ? $_SESSION['username'] : 'Gast';  // Standardname 'Gast'

    // SQL-Abfrage zum Einfügen des Kommentars
    $sql = "INSERT INTO comment (image_id, text, author, user_id, release_date) VALUES (:image_id, :text, :author, :user_id, NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'image_id' => $imageId,
        'text' => $commentText,
        'author' => $author,
        'user_id' => isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null  // Benutzer-ID oder null für Gäste
    ]);

    // Nach dem Kommentar zurück zur Bildseite
    header("Location: index.php");
    exit;
}
?>

