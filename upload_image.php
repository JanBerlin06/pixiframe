<?php
include 'db.php';  // Datenbankverbindung einbinden
include 'navigation.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
    $userId = $_SESSION['user_id'];
    $categoryId = $_POST['category_id'];
    $description = $_POST['description'];

    // Dateiinhalt als BLOB auslesen
    $imageData = file_get_contents($_FILES['image']['tmp_name']);

    // Debugging: Überprüfen, ob die Variablen richtig gesetzt sind
    echo "User ID: $userId<br>";
    echo "Category ID: $categoryId<br>";
    echo "Description: $description<br>";
    echo "Image Data Length: " . strlen($imageData) . " bytes<br>";

    // Bilddaten in die Datenbank speichern (als BLOB)
    $sql = "INSERT INTO image (user_id, category_id, image_data, description, upload_date) 
            VALUES (:user_id, :category_id, :image_data, :description, NOW())";
    $stmt = $pdo->prepare($sql);

    // Debugging: Zeige die SQL-Abfrage und die gebundenen Werte
    var_dump($sql);
    var_dump($stmt);

    // Bild in die Datenbank einfügen
    if ($stmt->execute([
        'user_id' => $userId,
        'category_id' => $categoryId,
        'image_data' => $imageData,  // Bild als BLOB in die Datenbank einfügen
        'description' => $description
    ])) {
        echo "Bild erfolgreich in die Datenbank eingefügt!";
    } else {
        // Debugging: Zeige die Fehlermeldung aus der SQL-Abfrage
        $errorInfo = $stmt->errorInfo();
        echo "Fehler beim Einfügen des Bildes in die Datenbank: <br>";
        echo "SQLSTATE-Fehlercode: " . $errorInfo[0] . "<br>";
        echo "Fehlercode: " . $errorInfo[1] . "<br>";
        echo "Fehlerbeschreibung: " . $errorInfo[2] . "<br>";
    }
}
?>




<!-- HTML Formular für den Bild-Upload -->
<form method="POST" enctype="multipart/form-data">
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


