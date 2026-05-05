<?php
$bodyClass = "print-page";
include 'header.php';
require 'db.php';
?>

<div class="print-header">
    <img src="pictures/color_map.jpeg" alt="Company Logo" class="greyscale-logo">
    <h2>Color Map</h2>
</div>

<header>
    <h1>Print view</h1>
</header>

<?php
    $gridSize = $_GET["gridSize"];
    $numColors = $_GET["numColors"];
    
    $colorQuery = $conn->query("SELECT name, hex_value FROM colors ORDER BY id");
    $colorMap = [];
    while ($row = $colorQuery->fetch_assoc()) {
        $colorMap[$row['name']] = $row['hex_value'];
    }
    
    $colorList = [];
    for($i = 0; $i < $numColors; $i++) {
        $colorList[] = $_GET["color" . (string) $i];
    }
    echo "<h2>Color Selection</h2>";
    echo "<table class='color-table'>";
    for($i = 0; $i < $numColors; $i++) {
        $nowColor = $colorList[$i];
        $colorCode = $colorMap[$nowColor] ?? "#000000";
        $coordinateVals = $_GET["coords" . $i] ?? "";

        echo "<tr>";
        echo "<td>" . $nowColor . " — " . $colorCode . "</td>";
        echo "<td>" . $coordinateVals . "</td>";
        echo "</tr>";
    }

    echo "</table>";
    $alphabet = range('A', 'Z');
    echo "<h2>Coordinate Grid</h2>";
    echo "<table class='coordinate-grid'>";
    echo "<tr>";
    echo "<td></td>";
    for($col = 0; $col < $gridSize; $col++) {
        echo "<td>" . $alphabet[$col] . "</td>";
    }
    echo "</tr>";
    for($row = 0; $row < $gridSize; $row++) {
        echo "<tr>";
        echo "<td>" . ($row + 1) . "</td>";
        for($col = 0; $col < $gridSize; $col++) {
            echo "<td></td>";
        }
            echo "</tr>";
    }
    echo "</table>";
?>

<?php include 'footer.php'; ?>
