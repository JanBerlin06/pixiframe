<?php
include 'db.php';
include 'navigation.php';  // Navigation einbinden
session_start();  // Session starten

// Anzahl der Bilder pro Seite
$limit = 10;
$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;

// Abfrage: 10 Bilder aus der Datenbank abrufen
$sql = "SELECT * FROM image LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$images = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PixiFrame - Explore</title>

    <style>
        .gallery-container {
            width: 80%;
            margin: auto;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }

        .gallery-item {
            margin: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            width: 200px;
            text-align: center;
        }

        .gallery-item img {
            width: 100%;
            height: auto;
            cursor: pointer;
        }

        .load-more {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            text-align: center;
        }

        .load-more:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<h1>PixiFrame - Explore</h1>

<div class="gallery-container">
    <?php if ($images): ?>
        <?php foreach ($images as $image): ?>
            <div class="gallery-item">
                <a href="detail.php?image_id=<?php echo $image['image_id']; ?>">
                    <?php $imgData = base64_encode($image['image_data']); ?>
                    <img src="data:image/jpeg;base64,<?php echo $imgData; ?>" alt="Kundenbild">
                </a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Es wurden noch keine Bilder hochgeladen.</p>
    <?php endif; ?>
</div>

<!-- Button zum Laden von mehr Bildern -->
<a href="?offset=<?php echo $offset + $limit; ?>" class="load-more">Mehr Bilder laden</a>

</body>
</html>
