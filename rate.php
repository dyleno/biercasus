<?php
require 'connection.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    die("Je moet ingelogd zijn om een rating te geven.");
}

$user_id = $_SESSION['user_id'];
$bier_id = $_POST['bier_id'] ?? null;
$rating = $_POST['rating'] ?? null;
$note = $_POST['notitie'] ?? null;

if (!$bier_id || !$rating) {
    die("Ongeldige invoer.");
}

// Controleer of de gebruiker al een rating heeft gegeven
$check_stmt = $conn->prepare("SELECT * FROM bierrating WHERE userId = ? AND bierId = ?");
$check_stmt->execute([$user_id, $bier_id]);
$existing_rating = $check_stmt->fetch();

if ($existing_rating) {
    // Update bestaande rating
    $stmt = $conn->prepare("UPDATE bierrating SET rating = ?, note = ? WHERE userId = ? AND bierId = ?");
    $stmt->execute([$rating, $note, $user_id, $bier_id]);
} else {
    // Voeg nieuwe rating toe
    $stmt = $conn->prepare("INSERT INTO bierrating (userId, bierId, rating, note) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $bier_id, $rating, $note]);
}

// Redirect terug naar index.php
header("Location: index.php");
exit;
?>

