<?php
require 'connection.php';

// Uniek cookie-ID instellen als deze nog niet bestaat
if (!isset($_COOKIE["user_id"])) {
    $userId = uniqid("user_", true); 
    setcookie("user_id", $userId, time() + (86400 * 30), "/"); // 30 dagen geldig
} else {
    $userId = $_COOKIE["user_id"];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['type'])) {
    $id = intval($_POST['id']);
    $type = $_POST['type'];

    // Controleer of de gebruiker al een stem heeft uitgebracht
    $checkVote = $conn->prepare("SELECT vote_type FROM beer_likes WHERE cookie_id = ? AND bier_id = ?");
    $checkVote->execute([$userId, $id]);
    $existingVote = $checkVote->fetch(PDO::FETCH_ASSOC);

    // Als gebruiker al een like heeft gegeven en opnieuw like drukt -> verwijder de stem
    if ($existingVote && $existingVote['vote_type'] === $type) {
        $deleteVote = $conn->prepare("DELETE FROM beer_likes WHERE cookie_id = ? AND bier_id = ?");
        $deleteVote->execute([$userId, $id]);

        // Update de tellers in de beers tabel
        if ($type === 'like') {
            $conn->prepare("UPDATE beers SET likes = likes - 1 WHERE id = ?")->execute([$id]);
        } elseif ($type === 'dislike') {
            $conn->prepare("UPDATE beers SET dislikes = dislikes - 1 WHERE id = ?")->execute([$id]);
        }

    } else {
        // Verwijder vorige stem als die bestond
        if ($existingVote) {
            if ($existingVote['vote_type'] === 'like') {
                $conn->prepare("UPDATE beers SET likes = likes - 1 WHERE id = ?")->execute([$id]);
            } elseif ($existingVote['vote_type'] === 'dislike') {
                $conn->prepare("UPDATE beers SET dislikes = dislikes - 1 WHERE id = ?")->execute([$id]);
            }
            $conn->prepare("DELETE FROM beer_likes WHERE cookie_id = ? AND bier_id = ?")->execute([$userId, $id]);
        }

        // Voeg nieuwe stem toe
        $insertVote = $conn->prepare("INSERT INTO beer_likes (cookie_id, bier_id, vote_type) VALUES (?, ?, ?)");
        $insertVote->execute([$userId, $id, $type]);

        // Update de tellers in de beers tabel
        if ($type === 'like') {
            $conn->prepare("UPDATE beers SET likes = likes + 1 WHERE id = ?")->execute([$id]);
        } elseif ($type === 'dislike') {
            $conn->prepare("UPDATE beers SET dislikes = dislikes + 1 WHERE id = ?")->execute([$id]);
        }
    }

    // Haal de bijgewerkte likes en dislikes op
    $getCounts = $conn->prepare("SELECT likes, dislikes FROM beers WHERE id = ?");
    $getCounts->execute([$id]);
    $result = $getCounts->fetch(PDO::FETCH_ASSOC);

    echo json_encode($result);
}
?>
