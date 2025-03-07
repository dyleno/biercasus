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

if ($rating) {
    $stmt = $conn->prepare("INSERT INTO bierrating (userId, bierId, rating, note) 
                            VALUES ($user_id , $bier_id, $rating, '$note') 
                            ON DUPLICATE KEY UPDATE rating = VALUES(rating), note = VALUES(note)");
    $stmt->execute();

    return header("Location: index.php");
}
?>

