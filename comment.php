<?php
include 'db.php';
session_start();
ob_start(); // Output-Pufferung starten

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment_text'], $_POST['image_id'])) {
    $commentText = $_POST['comment_text'];
    $imageId = (int)$_POST['image_id'];

    // Überprüfen, ob der Benutzer eingeloggt ist
    if (isset($_SESSION['user_id'])) {
        // Benutzer ist eingeloggt, hole den Namen aus der Tabelle "users" anhand der user_id
        $sql = "SELECT name FROM users WHERE user_id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['user_id' => $_SESSION['user_id']]);
        $user = $stmt->fetch();
        $author = $user['name'];  // Den "name" aus der Datenbank verwenden
    } else {
        // Benutzer ist nicht eingeloggt, setze den Standardwert "Gast"
        $author = 'Gast';
    }

    // Kommentar in die Datenbank einfügen
    $sql = "INSERT INTO comment (image_id, text, author, user_id, release_date) 
            VALUES (:image_id, :text, :author, :user_id, NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'image_id' => $imageId,
        'text' => $commentText,
        'author' => $author,
        'user_id' => isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null  // Benutzer-ID oder null für Gäste
    ]);

    // Nach dem Kommentar zurück zur Bilddetailseite leiten
    header("Location: detail.php?image_id=" . $imageId);
    exit;
} else {
    echo "Ungültige Anfrage. Kommentartext oder Bild-ID fehlt.";
}

ob_end_flush(); // Output-Pufferung beenden und ausgeben
?>



