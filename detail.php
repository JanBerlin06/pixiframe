<?php
include 'db.php';
include 'navigation.php';
session_start();  // Session starten

// Abfrage: Bilddetails basierend auf der image_id
if (isset($_GET['image_id'])) {
    $imageId = (int)$_GET['image_id'];

    // Bild und Kategorie abrufen
    $sql = "SELECT image.*, category.name AS category_name 
            FROM image 
            LEFT JOIN category ON image.category_id = category.category_id 
            WHERE image_id = :image_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['image_id' => $imageId]);
    $image = $stmt->fetch();

    // Kommentare abrufen
    $commentSql = "SELECT * FROM comment WHERE image_id = :image_id ORDER BY release_date DESC";
    $commentStmt = $pdo->prepare($commentSql);
    $commentStmt->execute(['image_id' => $imageId]);
    $comments = $commentStmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bilddetails</title>

    <style>
        .gallery-item img {
            max-width: 800px;  /* Maximale Breite für Querformat */
            max-height: 600px; /* Maximale Höhe für Hochformat */
            width: auto;  /* Breite proportional zur Höhe */
            height: auto; /* Höhe proportional zur Breite */
        }


        .comments {
            margin-top: 15px;
        }

        .comment {
            border-top: 1px solid #ddd;
            padding: 5px 0;
        }

        .comment-author {
            font-weight: bold;
        }

        .comment-form {
            margin-top: 15px;
        }

        .category {
            font-weight: bold;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<?php if ($image): ?>
    <div class="gallery-item">
        <?php $imgData = base64_encode($image['image_data']); ?>
        <img src="data:image/jpeg;base64,<?php echo $imgData; ?>" alt="Bilddetails">
        <p><?php echo htmlspecialchars($image['description']); ?></p>
        <p class="category">Kategorie: <?php echo htmlspecialchars($image['category_name']); ?></p>

        <!-- Kommentare zu diesem Bild anzeigen -->
        <div class="comments">
            <h3>Kommentare</h3>
            <?php if ($comments): ?>
                <?php foreach ($comments as $comment): ?>
                    <div class="comment">
                        <span class="comment-author"><?php echo htmlspecialchars($comment['author']); ?></span> schrieb:<br>
                        <?php echo htmlspecialchars($comment['text']); ?>
                        <small><?php echo $comment['release_date']; ?></small>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Keine Kommentare vorhanden.</p>
            <?php endif; ?>
        </div>

        <!-- Kommentarformular -->
        <div class="comment-form">
            <h4>Kommentar schreiben:</h4>
            <form action="comment.php" method="POST">
                <input type="hidden" name="image_id" value="<?php echo $image['image_id']; ?>">
                <textarea name="comment_text" placeholder="Dein Kommentar" required></textarea><br>
                <button type="submit">Kommentar absenden</button>
            </form>
        </div>
    </div>
<?php else: ?>
    <p>Bild nicht gefunden.</p>
<?php endif; ?>

</body>
</html>
