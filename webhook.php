<?php

/*
  Zusammengefasst:
  - Webhook-Secret wird zur Validierung verwendet, um sicherzustellen, dass das Ereignis von Stripe stammt.
  - Bei einer erfolgreichen Zahlung (checkout.session.completed) wird der Benutzer in der Datenbank als Premium markiert.
  - Bei einer fehlgeschlagenen Zahlung (invoice.payment_failed) wird der Benutzer in der Datenbank als nicht Premium markiert.
  - Der Webhook sorgt dafür, dass Stripe-Ereignisse in Echtzeit verarbeitet werden und die Benutzerrechte in der Anwendung automatisch aktualisiert werden.
*/

require 'vendor/autoload.php';  // Stripe SDK laden
\Stripe\Stripe::setApiKey('sk_test_51PzCvyH2xV5hWVmgLWy5W0HvGzyCkVtda0fbgWLImA61GJdOS6yWO1QxUfhnmNVKxigkofwnA3FVPXp43PFv9svv00SKMtcQko');  // API Secret Key

// Webhook-Secret, das du von Stripe erhältst (erhältlich im Stripe-Dashboard unter Webhooks)
$endpoint_secret = 'we_1PzDw7H2xV5hWVmgjsP00o9U';

// Inhalt des Webhooks (POST-Anfrage)
$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
$event = null;

try {
    // Webhook-Ereignis validieren
    $event = \Stripe\Webhook::constructEvent(
        $payload, $sig_header, $endpoint_secret
    );
} catch (\UnexpectedValueException $e) {
    // Ungültige Payload
    http_response_code(400);
    exit();
} catch (\Stripe\Exception\SignatureVerificationException $e) {
    // Ungültige Signatur
    http_response_code(400);
    exit();
}

// Webhook-Ereignis behandeln
if ($event['type'] == 'checkout.session.completed') {
    $session = $event['data']['object'];

    // Beispiel: Benutzer als Premium markieren
    $customerEmail = $session['customer_email'];

    // Datenbank-Logik (Beispiel)
    $pdo = new PDO('mysql:host=localhost;dbname=pixiframe_db', 'root', '');  // Deine Datenbankverbindung
    $stmt = $pdo->prepare("UPDATE users SET is_premium = 1 WHERE email = :email");
    $stmt->execute(['email' => $customerEmail]);

    // Weitere Logik nach erfolgreicher Zahlung
} elseif ($event['type'] == 'invoice.payment_failed') {
    // Hier könntest du Benutzer in der Datenbank als "nicht premium" markieren, wenn die Zahlung fehlschlägt
    $session = $event['data']['object'];
    $customerEmail = $session['customer_email'];
    $pdo = new PDO('mysql:host=localhost;dbname=pixiframe_db', 'root', '');  // Datenbankverbindung
    $stmt = $pdo->prepare("UPDATE users SET is_premium = 0 WHERE email = :email");
    $stmt->execute(['email' => $customerEmail]);

    // Benachrichtige den Benutzer oder setze das Konto auf "nicht Premium"
}

// 200 OK zurückgeben, um den erfolgreichen Empfang des Webhooks zu bestätigen
http_response_code(200);
