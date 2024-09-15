<?php
session_start();
include 'db.php';

// Überprüfen, ob der Benutzer eingeloggt und Premium ist
if (!isset($_SESSION['user_id']) || $_SESSION['is_premium'] != 1) {
    die("Nur Premium-Benutzer können Bilder herunterladen.");
}

// Bild anhand der image_id abrufen
if (isset($_GET['image_id'])) {
    $imageId = (int)$_GET['image_id'];
    $sql = "SELECT image_data FROM image WHERE image_id = :image_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['image_id' => $imageId]);
    $image = $stmt->fetch();

    if ($image) {
        // Bild im Original zum Download anbieten
        header('Content-Type: image/jpeg');
        header('Content-Disposition: attachment; filename="bild_' . $imageId . '.jpg"');
        echo $image['image_data'];
        exit;
    } else {
        echo "Bild nicht gefunden.";
    }
} else {
    echo "Keine Bild-ID angegeben.";
}
?>
