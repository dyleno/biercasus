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
        echo json_encode(["error" => "Ongeldig type"]);
        exit;
    }

    if ($stmt->execute([$id])) {
        // Haal de bijgewerkte like en dislike tellingen op
        $stmt = $conn->prepare("SELECT likes, dislikes FROM beers WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode($result);
    } else {
        echo json_encode(["error" => "Databasefout"]);
    }
}
?>
