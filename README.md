# PixiFrame Projekt

## Projektbeschreibung:
PixiFrame ist eine Fotoplattform-Anwendung, die es Fotografen ermöglicht, Fotos hochzuladen, zu verwalten und mit anderen zu teilen. Es gibt eine Bildgalerie, eine Kommentarfunktion sowie die Möglichkeit, Premium-Abonnements über **Stripe** abzuschließen.

## Voraussetzungen:
- **XAMPP** (oder ein ähnlicher LAMP/WAMP-Stack)
- **MySQL**-Datenbank
- **PHP** 7.4 oder höher
- **ngrok** (für die Stripe-Integration)
- **Stripe** API-Schlüssel (für die Zahlungsabwicklung)
- **Composer** (für die Installation des Stripe SDK)

## Installationsanweisungen:

### 1. **Dateien extrahieren**:
- Extrahiere die ZIP-Datei (`PixiFrame_Projekt.zip`) in den `htdocs`-Ordner deines XAMPP-Installationsverzeichnisses:
    - Windows: `C:\xampp\htdocs\PixiFrame`
    - Mac: `/Applications/XAMPP/xamppfiles/htdocs/PixiFrame`

### 2. **Datenbank importieren**:
- Öffne **phpMyAdmin** (unter `http://localhost/phpmyadmin`).
- Erstelle eine neue Datenbank, z.B. `pixiframe_db`.
- Gehe zum Tab "Importieren" und lade die SQL-Datei (`pixiframe_db.sql`) hoch, die in der ZIP-Datei enthalten ist.
- Die Datenbank wird nun erstellt und mit den notwendigen Tabellen befüllt.

### 3. **Stripe API-Schlüssel einfügen**:
- Du benötigst einen **Stripe Account**. Melde dich bei [Stripe](https://dashboard.stripe.com/register) an, um deine **API-Schlüssel** zu erhalten.
- Kopiere den **API Secret Key** und füge ihn in die Datei **`checkout.php`** ein:
  ```php
  \Stripe\Stripe::setApiKey('sk_test_XXXXXXXXXXXXXXXXXXXXXXXX');  // Stripe Secret Key
  ```
- Stelle sicher, dass der **`success_url`** und die **`cancel_url`** in **`checkout.php`** korrekt sind (dazu weiter unten mehr).

### 4. **Stripe SDK installieren**:
- Das Stripe PHP SDK wird benötigt, um Zahlungen über Stripe zu verwalten. Installiere es mit **Composer**.
- Führe den folgenden Befehl im Stammverzeichnis deines Projekts (wo sich die `composer.json` befindet) aus:
  ```bash
  composer require stripe/stripe-php
  ```
- Dadurch wird ein **`vendor`**-Ordner erstellt, der das Stripe SDK enthält. **Dieser Ordner muss mit deinem Projekt weitergegeben werden**, damit die Stripe-Funktionalität funktioniert.

### 5. **ngrok einrichten (für die Stripe Webhooks und Live-URLs)**:
- Da Stripe eine öffentlich zugängliche URL für den Zahlungsprozess benötigt, verwenden wir **ngrok**, um deine lokale Anwendung öffentlich zu machen.
- Lade **ngrok** herunter und installiere es: [ngrok Download](https://ngrok.com/download).
- Starte ngrok im Terminal:
  ```bash
  ngrok http 80
  ```
- Es wird eine URL generiert, die etwa so aussieht:
  ```
  https://xxxxxx.ngrok-free.app
  ```
- Kopiere diese URL und ersetze sie in **`checkout.php`** an den Stellen **`success_url`** und **`cancel_url`**:
  ```php
  'success_url' => 'https://xxxxxx.ngrok-free.app/pixiframe/index.php',  // Erfolgs-URL
  'cancel_url' => 'https://xxxxxx.ngrok-free.app/pixiframe/manage_subscription.php',  // Abbruch-URL
  ```

**Wichtig:** Bei jedem Neustart von ngrok wird eine neue URL generiert. Du musst die URLs in **`checkout.php`** jedes Mal anpassen.

### 6. **Stripe Webhooks konfigurieren**:
- Stripe Webhooks ermöglichen es, Zahlungen und Abonnements zu verifizieren.
- Gehe in dein **Stripe-Dashboard** und füge einen neuen Webhook hinzu:
    - **URL**: Die ngrok-URL, gefolgt von `/webhook.php`, z.B.:
      ```
      https://xxxxxx.ngrok-free.app/webhook.php
      ```
    - Wähle die Ereignisse aus, die du verfolgen möchtest, z.B. `checkout.session.completed` und `invoice.payment_succeeded`.
- In **`webhook.php`** musst du den Stripe-Signing-Secret hinzufügen:
  ```php
  \Stripe\Webhook::constructEvent(
      $payload, $sig_header, 'whsec_XXXXXXXXXXXXXXXX'
  );
  ```

### 7. **Anwendung starten**:
- Öffne die Anwendung im Browser unter `http://localhost/PixiFrame`.
- Teste die verschiedenen Funktionen, z.B. das Hochladen von Bildern und die Verwaltung von Kommentaren.

### 8. **Anmerkungen zu Stripe und ngrok**:
- Beachte, dass **ngrok** bei jedem Neustart eine neue URL generiert. Du musst die URLs in **`checkout.php`** und **den Webhook-Einstellungen** in Stripe jedes Mal aktualisieren.
- **Stripe API-Schlüssel** sollten sicher verwahrt werden und nicht in öffentlichen Repositories landen.

---

## Dateistruktur:
