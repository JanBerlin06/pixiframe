<?php


require 'vendor/autoload.php'; // Stripe SDK laden
\Stripe\Stripe::setApiKey('sk_test_51PzCvyH2xV5hWVmgLWy5W0HvGzyCkVtda0fbgWLImA61GJdOS6yWO1QxUfhnmNVKxigkofwnA3FVPXp43PFv9svv00SKMtcQko'); // API Secret Key

session_start();  // Session starten

// Sicherstellen, dass der Benutzer eingeloggt ist
if (!isset($_SESSION['user_id'])) {
    die('Du musst eingeloggt sein, um ein Premium-Abo abzuschließen.');
}

// Stripe-Preis-ID (dies wird in Stripe für das Premium-Abo erstellt)
$priceId = 'price_1PzDZWH2xV5hWVmg6uI0CP1u'; // Stripe Preis-ID

try {
    // Stripe-Checkout-Session erstellen
    $checkoutSession = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],  // Zahlungsmethoden
        'line_items' => [[
            'price' => $priceId,  // Preis-ID des Premium-Abos
            'quantity' => 1,
        ]],
        'mode' => 'subscription',  // Abonnement-Modus
        'success_url' => 'https://66f3-2a02-8109-bd0d-cc00-3503-6d61-c1b6-ca2e.ngrok-free.app/pixiframe/index.php',  // Erfolgs-URL
        'cancel_url' => 'https://66f3-2a02-8109-bd0d-cc00-3503-6d61-c1b6-ca2e.ngrok-free.app/pixiframe/manage_subscription.php', // Abbruch-URL
        'customer_email' => $_SESSION['email'],  // E-Mail des Benutzers
    ]);

    // Weiterleitung zur Stripe-Checkout-Seite
    header("Location: " . $checkoutSession->url);
} catch (Exception $e) {
    echo 'Fehler bei der Erstellung der Checkout-Session: ' . $e->getMessage();
}

?>