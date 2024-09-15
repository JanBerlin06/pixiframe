<?php
include 'db.php';
include 'header.php';
session_start();  // Session starten

// Kategorie-Filter (standardmäßig keine Kategorie ausgewählt)
$categoryFilter = isset($_GET['category']) ? (int)$_GET['category'] : null;

// SQL-Abfrage für das Laden von Bildern, abhängig von der gewählten Kategorie
if ($categoryFilter) {
    $sql = "SELECT * FROM image WHERE category_id = :category_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':category_id', $categoryFilter, PDO::PARAM_INT);
} else {
    $sql = "SELECT * FROM image";
    $stmt = $pdo->prepare($sql);
}

$stmt->execute();
$images = $stmt->fetchAll();

// Kategorien für die Suche
$categories = [
    1 => 'Street Photography',
    2 => 'Landscape',
    3 => 'Portrait'
];
?>

<h1>PixiFrame</h1>

<!-- Suchformular für die Kategorien -->
<div class="search-container">
    <form method="GET" action="">
        <select name="category">
            <option value="">Alle Kategorien</option>
            <?php foreach ($categories as $id => $categoryName): ?>
                <option value="<?php echo $id; ?>" <?php echo ($categoryFilter == $id) ? 'selected' : ''; ?>>
                    <?php echo $categoryName; ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Suchen</button>
    </form>
</div>

<!-- Galerie der Bilder -->
<div class="gallery-container">
    <?php if ($images): ?>
        <?php foreach ($images as $image): ?>
            <div class="gallery-item gallery-item-hover">
                <a href="detail.php?image_id=<?php echo $image['image_id']; ?>">
                    <?php $imgData = base64_encode($image['image_data']); ?>
                    <img src="data:image/jpeg;base64,<?php echo $imgData; ?>" alt="Kundenbild">
                </a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Es wurden keine Bilder für die ausgewählte Kategorie gefunden.</p>
    <?php endif; ?>
</div>

<?php
include 'footer.php';
?>
