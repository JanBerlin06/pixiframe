<?php
include 'db.php';  // Datenbankverbindung einbinden
include 'header.php';
session_start();

// Überprüfen, ob das Formular gesendet wurde und ob ein Bild hochgeladen wurde
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
    $userId = $_SESSION['user_id'];
    $categoryId = $_POST['category_id'];
    $description = $_POST['description'];

    // Dateiinhalt als BLOB auslesen
    $imageData = file_get_contents($_FILES['image']['tmp_name']);

    // Bilddaten in die Datenbank speichern (als BLOB)
    $sql = "INSERT INTO image (user_id, category_id, image_data, description, upload_date) 
            VALUES (:user_id, :category_id, :image_data, :description, NOW())";
    $stmt = $pdo->prepare($sql);

    // Bild in die Datenbank einfügen
    if ($stmt->execute([
        'user_id' => $userId,
        'category_id' => $categoryId,
        'image_data' => $imageData,  // Bild als BLOB in die Datenbank einfügen
        'description' => $description
    ])) {
        // Erfolgsmeldung
        echo "<p class='result-upload'>Bild erfolgreich hochgeladen! <a href='dashboard.php'>Gehe zum Dashboard</a></p>";
    } else {
        // Fehlerbehandlung
        echo "<p>Fehler beim Hochladen des Bildes. Bitte versuche es erneut.</p>";
    }
}
?>

<main class="register-page">
    <!-- Bild-Upload -->
    <form class="form-default-design" method="POST" enctype="multipart/form-data">
        <input type="file" name="image" required><br>
        <label for="category_id">Kategorie wählen:</label><br>
        <select name="category_id" required>
            <option value="1">Street Photography</option>
            <option value="2">Landscape</option>
            <option value="3">Portrait</option>
        </select><br>
        <textarea name="description" placeholder="Beschreibung" required></textarea><br>
        <button type="submit">hochladen</button>
    </form>
</main>

<?php
include 'footer.php';
?>


