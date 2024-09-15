<?php
include 'header.php';
session_start();
require 'db.php';

// Überprüfen, ob der Benutzer eingeloggt ist
if (!isset($_SESSION['user_id'])) {
    die('Du musst eingeloggt sein, um deine Abonnements zu verwalten.');
}

// Hole den Benutzerstatus aus der Datenbank
$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT is_premium FROM users WHERE user_id = :user_id");
$stmt->execute(['user_id' => $userId]);
$user = $stmt->fetch();

// Wenn der Benutzer keinen Eintrag hat
if (!$user) {
    die('Benutzer nicht gefunden.');
}

$isPremium = $user['is_premium'];
?>


<div class="subscription-container">
    <h2>Verwalte dein Premium-Abo</h2>

    <?php if ($isPremium == 1): ?>
        <!-- Der Benutzer ist Premium -->
        <p>Du hast ein aktives Premium-Abonnement.</p>
        <p>Du kannst alle Bilder herunterladen.</p>
        <form action="cancel_subscription.php" method="POST">
            <button type="submit">Premium-Abo kündigen</button>
        </form>
    <?php else: ?>
        <!-- Der Benutzer ist kein Premium-Mitglied -->
        <p>Schließe ein Premium-Abo ab, um für nur 4,99 € pro Monat alle Bilder herunterladen zu können!</p>
        <form action="checkout.php" method="POST">
            <button type="submit">Premium-Abo abschließen</button>
        </form>
    <?php endif; ?>
</div>

<?php
include 'footer.php';
?>