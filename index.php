
<?php
require 'connection.php';

// Haal de biertjes op uit de database
$sql = "SELECT name, brewer, id_brouwer2 
        FROM beers";
$sql = "SELECT name, brewer, id_brouwer2, brouwer.naam
        FROM beers
        LEFT JOIN brouwer ON brouwer.id = beers.id_brouwer2";
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
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
            text-align: left;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #ddd;
        }
    </style>
</head>
<body>

<h1>Onze Biertjes</h1>
<table>
    <tr>
        <th>Naam</th>
        <th>Brouwer</th>
        <th>ID Brouwer</th>
    </tr>
    <?php
        foreach ($biertjes as $bier) {
            echo "<tr>";
            echo "<td>".$bier["name"]."</td>";
            echo "<td>".$bier["brewer"]."</td>";
            echo "<td>".$bier["id_brouwer2"]."</td>";
            echo "<td>".$bier["naam"]."</td>";
            echo "</tr>";
        }
    ?>
</table>

</body>
</html>
</ul>

</body>
</html>

<!-- 

SELECT name, brewer, id_brouwer2
FROM beers 



-->