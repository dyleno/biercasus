<?php
require 'connection.php';
session_start();

$user_id = $_SESSION['user_id'] ?? null;

// Haal biertjes op, gesorteerd op gemiddelde rating
$sql = "SELECT beers.*, 
               COALESCE(AVG(bierrating.rating), 0) AS avg_rating 
        FROM beers 
        LEFT JOIN bierrating ON beers.id = bierrating.bierId 
        GROUP BY beers.id 
        ORDER BY avg_rating DESC";
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
</head>
<body>

<h1>Onze Biertjes</h1>

<?php if ($user_id): ?>
    <p>Welkom, <?= htmlspecialchars($_SESSION['username']) ?>! <a href="logout.php">Uitloggen</a></p>
<?php else: ?>
    <p><a href="login.php">Inloggen</a> of <a href="registreren.php">Registreren</a></p>
<?php endif; ?>

<table>
    <tr>
        <th>Naam</th>
        <th>Brouwer</th>
        <th>Gemiddelde Rating</th>
        <th>Jouw Rating</th>
        <th>Notitie</th>
    </tr>
    <?php foreach ($biertjes as $bier): ?>
        <tr>
            <td><?= htmlspecialchars($bier["name"]) ?></td>
            <td><?= htmlspecialchars($bier["brewer"]) ?></td>
            <td><?= number_format($bier["avg_rating"], 1) ?> ⭐</td>
            <td>
                <?php if ($user_id): ?>
                    <form method="POST" action="rate.php">
                        <input type="hidden" name="bier_id" value="<?= $bier['id'] ?>">
                        <select name="rating">             
                            <option value="1">1 ⭐</option>
                            <option value="2">2 ⭐</option>
                            <option value="3">3 ⭐</option>
                            <option value="4">4 ⭐</option>
                            <option value="5">5 ⭐</option>
                        </select>
                            <input type="text" name="notitie" placeholder="Notitie">
                        <button type="submit">Geef Rating</button>
                    </form>
                <?php else: ?>
                    <p>Log in om te raten</p>
                <?php endif; ?>
            </td>
            
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
