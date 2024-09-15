<?php
include 'db.php';
include 'header.php';
session_start();

// Bilder des Benutzers abrufen
$userId = $_SESSION['user_id'];
$sql = "SELECT * FROM image WHERE user_id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['user_id' => $userId]);
$images = $stmt->fetchAll();

// Bild löschen
if (isset($_GET['delete']) && isset($_GET['image_id'])) {
    $imageId = $_GET['image_id'];
    $deleteSql = "DELETE FROM image WHERE image_id = :image_id AND user_id = :user_id";
    $deleteStmt = $pdo->prepare($deleteSql);
    $deleteStmt->execute(['image_id' => $imageId, 'user_id' => $userId]);

    // Seite nach dem Löschen neu laden
    header("Location: dashboard.php");
    exit;
}

// Beschreibung aktualisieren
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_description'])) {
    $imageId = $_POST['image_id'];
    $newDescription = $_POST['new_description'];

    $updateSql = "UPDATE image SET description = :description WHERE image_id = :image_id AND user_id = :user_id";
    $updateStmt = $pdo->prepare($updateSql);
    $updateStmt->execute(['description' => $newDescription, 'image_id' => $imageId, 'user_id' => $userId]);

    // Seite nach dem Update neu laden
    header("Location: dashboard.php");
    exit;
}
?>

<h2>Deine hochgeladenen Bilder</h2>
<div class="gallery-container">
    <?php if ($images): ?>
        <?php foreach ($images as $image): ?>
            <div class="gallery-item">
                <!-- Bild aus der Datenbank anzeigen (BLOB in Base64 umwandeln) -->
                <?php $imgData = base64_encode($image['image_data']); ?>
                <img src="data:image/jpeg;base64,<?php echo $imgData; ?>" alt="Bild" style="width:200px;"><br>

                <!-- Bildbeschreibung anzeigen -->
                <p><?php echo $image['description']; ?></p>

                <!-- Formular zum Ändern der Beschreibung -->
                <form method="POST">
                    <input type="hidden" name="image_id" value="<?php echo $image['image_id']; ?>">
                    <textarea name="new_description"><?php echo $image['description']; ?></textarea><br>
                    <button type="submit" name="update_description">Beschreibung ändern</button>
                </form>

                <!-- Link zum Löschen des Bildes -->
                <a href="?delete=true&image_id=<?php echo $image['image_id']; ?>" onclick="return confirm('Bist du sicher, dass du dieses Bild löschen möchtest?');">Bild löschen</a>

                <hr>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Du hast noch keine Bilder hochgeladen.</p>
    <?php endif; ?>
</div>

<?php
include 'footer.php';