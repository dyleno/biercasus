<?php
require 'connection.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['type'])) {
    $id = intval($_POST['id']);
    $type = $_POST['type'];

    // Controleer of de gebruiker al heeft gestemd
    if (isset($_COOKIE["voted_$id"])) {
        echo json_encode(["error" => "Je hebt al gestemd."]);
        exit;
    }

    if ($type === 'like') {
        $stem = $conn->prepare("UPDATE beers SET likes = likes + 1 WHERE id = ?");
    } elseif ($type === 'dislike') {
        $stem = $conn->prepare("UPDATE beers SET likes = likes - 1 WHERE id = ?");
    } else {
        echo json_encode(["error" => "Ongeldig type"]);
        exit;
    }

    if ($stem->execute([$id])) {
        // Stel een cookie in om te onthouden dat de gebruiker heeft gestemd
        setcookie("voted_$id", true, time() + 3600, "/");

        // Haal de bijgewerkte like en dislike tellingen op
        $stem = $conn->prepare("SELECT likes, dislikes FROM beers WHERE id = ?");
        $stem->execute([$id]);
        $result = $stem->fetch(PDO::FETCH_ASSOC);

        echo json_encode($result);
    } else {
        echo json_encode(["error" => "Databasefout"]);
    }}
?>
