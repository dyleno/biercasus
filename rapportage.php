<?php
require 'connection.php';
session_start();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapportage</title>
    <link rel="stylesheet" href="style9.css">
</head>
<body>

<h1>Rapportage</h1>
<a href="index.php">← Terug naar de startpagina</a>

<h2>Top 10 Bieren met de Hoogste Rating</h2>
<table>
    <tr><th>Naam</th><th>Gemiddelde Rating</th></tr>
    <?php
    $sql = "SELECT name, AVG(bierrating.rating) AS avg_rating
            FROM beers
            LEFT JOIN bierrating ON beers.id = bierrating.bierId
            GROUP BY beers.id
            ORDER BY avg_rating DESC
            LIMIT 10";
    $stmt = $conn->query($sql);
    foreach ($stmt as $row) {
        echo "<tr><td>" . htmlspecialchars($row["name"]) . "</td><td>" . number_format($row["avg_rating"], 1) . " ⭐</td></tr>";
    }
    ?>
</table>

<h2>Top 10 Beste Brouwers</h2>
<table>
    <tr><th>Brouwer</th><th>Gemiddelde Rating</th></tr>
    <?php
    $sql = "SELECT brewer, AVG(bierrating.rating) AS avg_rating
            FROM beers
            LEFT JOIN bierrating ON beers.id = bierrating.bierId
            GROUP BY brewer
            ORDER BY avg_rating DESC
            LIMIT 10";
    $stmt = $conn->query($sql);
    foreach ($stmt as $row) {
        echo "<tr><td>" . htmlspecialchars($row["brewer"]) . "</td><td>" . number_format($row["avg_rating"], 1) . " ⭐</td></tr>";
    }
    ?>
</table>



<h2>Top 10 Meest Beoordeelde Bieren</h2>
<table>
    <tr><th>Naam</th><th>Aantal Ratings</th></tr>
    <?php
    $sql = "SELECT name, COUNT(bierrating.id) AS rating_count
            FROM beers
            LEFT JOIN bierrating ON beers.id = bierrating.bierId
            GROUP BY beers.id
            ORDER BY rating_count DESC
            LIMIT 10";
    $stmt = $conn->query($sql);
    foreach ($stmt as $row) {
        echo "<tr><td>" . htmlspecialchars($row["name"]) . "</td><td>" . $row["rating_count"] . "</td></tr>";
    }
    ?>
</table>

</body>
</html>
