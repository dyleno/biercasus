<?php
require 'connection.php';

// Haal de biertjes op, gesorteerd op likes (hoog naar laag)
$sql = "SELECT id, name, brewer, likes, dislikes FROM beers ORDER BY likes DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$biertjes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biertjes</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function vote(beerId, type) {
            let formData = new FormData();
            formData.append("id", beerId);
            formData.append("type", type);

            fetch("vote.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.likes !== undefined && data.dislikes !== undefined) {
                    document.getElementById("likes-" + beerId).innerText = data.likes;
                    document.getElementById("dislikes-" + beerId).innerText = data.dislikes;
                } else {
                    console.error("Fout bij stemmen:", data.error);
                }
            })
            .catch(error => console.error("Fout bij het verwerken van de aanvraag:", error));
        }
    </script>
</head>
<body>

<h1>Onze Biertjes</h1>
<table>
    <tr>
        <th>Naam</th>
        <th>Brouwer</th>
        <th>Likes</th>
        <th>Dislikes</th>
        <th>Acties</th>
    </tr>
    <?php foreach ($biertjes as $bier): ?>
        <tr>    
            <td><?= htmlspecialchars($bier["name"]) ?></td>
            <td><?= htmlspecialchars($bier["brewer"]) ?></td>
            <td><span id="likes-<?= $bier['id'] ?>"><?= $bier["likes"] ?></span></td>
            <td><span id="dislikes-<?= $bier['id'] ?>"><?= $bier["dislikes"] ?></span></td>
            <td>
                <button onclick="vote(<?= $bier['id'] ?>, 'like')">👍</button>
                <button onclick="vote(<?= $bier['id'] ?>, 'dislike')">👎</button>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
