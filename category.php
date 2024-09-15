<?php
include 'db.php';
session_start();

// Kategorien aus der Datenbank abrufen
$sql = "SELECT * FROM category";
$stmt = $pdo->query($sql);
$categories = $stmt->fetchAll();




