<?php
require 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['type'])) {
    $id = intval($_POST['id']);
    $type = $_POST['type'];

    if ($type === 'like') {
        $stmt = $conn->prepare("UPDATE beers SET likes = likes + 1 WHERE id = ?");
    } elseif ($type === 'dislike') {
        $stmt = $conn->prepare("UPDATE beers SET dislikes = dislikes + 1 WHERE id = ?");
    } else {
        exit;
    }

    $stmt->execute([$id]);

    // Nieuwe waarden ophalen
    $stmt = $conn->prepare("SELECT likes, dislikes FROM beers WHERE id = ?");
    $stmt->execute([$id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // JSON-response terugsturen
    echo json_encode($result);
}
?>
