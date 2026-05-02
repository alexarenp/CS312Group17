<?php include 'header.php'; ?>

<header>
    <h1>Color Selection</h1>
    <link rel="stylesheet" href="style.css">
</header>

<p id = "selec_info"> Manage the color available in the Color Coordinator. You can add, edit, or remove colors from the list. </p>

<h2> Add a Color </h2>
<form action="color_select.php" method="GET">
        Color Name: <input type="text" name="colorName" placeholder="e.g. Cyan" required>
        Hex Value: <input type="text" name="hexValue" placeholder="e.g. 00FFFF" required>
        <button type="submit">Submit</button>
</form>
<h2> Edit a Color </h2>
<p id = "selec_info"> Select Color: </p>
<form action="color_select.php" method="GET">
        New Name: <input type="text" name="colorName" placeholder="e.g. Cyan" required>
        New Hex Value: <input type="text" name="hexValue" placeholder="e.g. 00FFFF" required>
        <button type="submit">Submit</button>
</form>
<h2> Delete a Color </h2>
<p id = "selec_info"> Select Color: </p>

<h2> Current Colors </h2>
<?php
$colors = ["Red", "Orange", "Yellow", "Green", "Blue", "Purple", "Grey", "Brown", "Black", "Teal"];
$hex_values = ["#e74c3c", "#e67e22", "#f1c40f", "#27ae60", "#2980b9", "#8e44ad", "#95a5a6", "#7f5539","#2c2c2c", "#008080"];
$numColors = 10;
echo "<table class='color-selec-grid'>";
echo "<tr><th>Name</th><th>Hex Value</th><th>Preview</th></tr>";
for($i = 0; $i < $numColors; $i++) {
    echo "<tr>";
    echo "<td>$colors[$i]</td>";
    echo "<td>$hex_values[$i]</td>";
    echo "<td>";
}
?>

<?php include 'footer.php'; ?>
