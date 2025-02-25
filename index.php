<?php
require 'connection.php';

// Haal de biertjes op uit de database, gesorteerd op aantal likes (hoog naar laag)
$sql = "SELECT id, name, likes, dislikes FROM beers ORDER BY likes DESC";
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
        <table>
            <?php foreach ($biertjes as $bier){
            
             }
            ?>
        </table>
    </body>
</html>
