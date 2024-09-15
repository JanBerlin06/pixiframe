<?php
include 'db.php';
include 'header.php';
session_start();  // Session starten

// Abfrage: Bilddetails mit der image_id
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

    // Überprüfen, ob der Benutzer ein Premium-Abo hat
    $userId = $_SESSION['user_id'];
    $premiumSql = "SELECT is_premium FROM users WHERE user_id = :user_id";
    $premiumStmt = $pdo->prepare($premiumSql);
    $premiumStmt->execute(['user_id' => $userId]);
    $user = $premiumStmt->fetch();
    $isPremium = $user['is_premium'];
}
?>

<main class="image-detail-page">
    <?php if ($image): ?>
        <div class="image-detail-container">
            <!-- Bilddetails -->
            <div class="image-preview">
                <?php $imgData = base64_encode($image['image_data']); ?>
                <img src="data:image/jpeg;base64,<?php echo $imgData; ?>" alt="Bilddetails">
            </div>

            <!-- Bildinformationen -->
            <div class="image-info">
                <p><?php echo htmlspecialchars($image['description']); ?></p>
                <p class="category">Kategorie: <?php echo htmlspecialchars($image['category_name']); ?></p>

                <!-- Download-Button anzeigen, wenn der Benutzer Premium ist -->
                <?php if ($isPremium == 1): ?>
                    <a href="download.php?image_id=<?php echo $image['image_id']; ?>" class="btn btn-download">Bild herunterladen</a>
                <?php endif; ?>

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
        </div>
    <?php else: ?>
        <p>Bild nicht gefunden.</p>
    <?php endif; ?>
</main>

<?php
include 'footer.php';
?>
