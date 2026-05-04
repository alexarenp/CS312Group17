<?php include 'header.php'; ?>
<?php require 'db.php'; ?>

<header>
    <h1>Color Selection</h1>
    <link rel="stylesheet" href="style.css">
</header>

<p id = "selec_info"> Manage the color available in the Color Coordinator. You can add, edit, or remove colors from the list. </p>

<h2> Add a Color </h2>
<form action="colors.php" method="GET">
        Color Name: <input type="text" name="colorName" placeholder="e.g. Cyan" required>
        Hex Value: <input type="text" name="hexValue" placeholder="e.g. 00FFFF" required>
        <button type="submit">Submit</button>
</form>
<?php
$selectName = "SELECT name FROM colors";
$result = $conn->query($selectName);
$colors = $result -> fetch_all(MYSQLI_ASSOC);
$selectHex = "SELECT hex_value FROM colors";
$result = $conn->query($selectHex);
$hex_values = $result -> fetch_all(MYSQLI_ASSOC);
$colors_flat = array_column($colors, 'name');
$hex_flat = array_column($hex_values, 'hex_value');
if (isset($_GET['colorName']) && isset($_GET['hexValue'])) {
    $colorName = $_GET['colorName'];
    $hexValue = $_GET['hexValue'];
    if (in_array($colorName, $colors_flat) && in_array($hexValue, $hex_flat)) {
        echo "Color already exists in list of colors. Please add one that doesn't already exist.";
    }
    else {
        $insertColor = "INSERT INTO colors VALUES(NULL, '$colorName', '$hexValue')";
        $result = $conn->query($insertColor);
        echo "Color added successfully!";
    }
}
?>
<h2> Edit a Color </h2>
<p id = "selec_info"> Select Color: </p>
<form action="colors.php" method="GET">
<select name="select_color1">
    <?php foreach ($colors as $color): ?>
        <option value="<?php echo $color['name']; ?>">
            <?php echo $color['name']; ?>
        </option>
    <?php endforeach; ?>
</select>
<form action="colors.php" method="GET">
        New Name: <input type="text" name="colorName2" placeholder="e.g. Cyan" required>
        New Hex Value: <input type="text" name="hexValue2" placeholder="e.g. 00FFFF" required>
        <button type="submit">Submit</button>
</form>
<?php
if (isset($_GET['select_color1']) && isset($_GET['colorName2']) && isset($_GET['hexValue2'])) {
    $colorName2 = $_GET['colorName2'];
    $hexValue2 = $_GET['hexValue2'];
    $select_color1 = $_GET['select_color1'];
    if (in_array($colorName2, $colors_flat) && in_array($hexValue2, $hex_flat)) {
        echo "Color already exists in list of colors. Please add one that doesn't already exist.";
    }
    else {
        $insertColor = "UPDATE colors SET name = '$colorName2', hex_value = '$hexValue2' WHERE name = '$select_color1'";
        $result = $conn->query($insertColor);
        echo "Color updated successfully!";
    }
}
?>
<h2> Delete a Color </h2>
<p id = "selec_info"> Select Color: </p>
<form action="colors.php" method="GET">
<select name="select_color2">
    <?php foreach ($colors as $color): ?>
        <option value="<?php echo $color['name']; ?>">
            <?php echo $color['name']; ?>
        </option>
    <?php endforeach; ?>
</select>
<button type="submit">Submit</button>
</form>
<?php
if (isset($_GET['select_color2'])) {
    $select_color2 = $_GET['select_color2'];
    $deleteColor = "DELETE FROM colors WHERE name = '$select_color2'";
    $result = $conn->query($deleteColor);
    echo "Color deleted successfully!";
}  
?>
<h2> Current Colors </h2>
<?php
echo "<table class='color-selec-grid'>";
echo "<tr><th>Name</th><th>Hex Value</th><th>Preview</th></tr>";
for($i = 0; $i < count($colors); $i++) {
    echo "<tr>";
    echo "<td>{$colors[$i]['name']}</td>";
    echo "<td>{$hex_values[$i]['hex_value']}</td>";
    echo "<td>";
}
?>

<?php include 'footer.php'; ?>
