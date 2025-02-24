<?php
require 'connection.php';

// Haal de biertjes op uit de database
$stmt = $conn->prepare("SELECT id, name, likes, dislikes FROM beers");
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
</head>
<body>

    <h1>Onze Biertjes</h1>

    <?php if (!empty($biertjes)): ?>
        <ul>
            <?php foreach ($biertjes as $bier): ?>
                <li>
                    <?= htmlspecialchars($bier['name']) ?>
                    <div class="like-dislike-container">
                        <button class="like-btn" data-id="<?= $bier['id'] ?>">
                            👍 <span id="likes-<?= $bier['id'] ?>" class="like-count"><?= $bier['likes'] ?></span>
                        </button>
                        <button class="dislike-btn" data-id="<?= $bier['id'] ?>">
                            👎 <span id="dislikes-<?= $bier['id'] ?>" class="dislike-count"><?= $bier['dislikes'] ?></span>
                        </button>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Geen biertjes gevonden.</p>
    <?php endif; ?>

</body>
</html>
