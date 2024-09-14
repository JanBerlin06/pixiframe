<?php
include 'db.php';
include 'navigation.php';
session_start();

// Kategorien aus der Datenbank abrufen
$sql = "SELECT * FROM category";
$stmt = $pdo->query($sql);
$categories = $stmt->fetchAll();
?>

    <!DOCTYPE html>
    <html lang="de">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Kategorien anzeigen</title>
    </head>
    <body>

    <h1>Verfügbare Kategorien</h1>

    <!-- Vorhandene Kategorien anzeigen -->
    <?php if ($categories): ?>
        <ul>
            <?php foreach ($categories as $category): ?>
                <li><?php echo $category['name']; ?> - <?php echo $category['description']; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Es sind keine Kategorien verfügbar.</p>
    <?php endif; ?>

    </body>
    </html>
<?php
