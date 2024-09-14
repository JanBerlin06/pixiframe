<?php
session_start();
session_destroy();  // Alle Session-Daten löschen
header("Location: index.php");  // Zurück zur Startseite
exit();
?>
