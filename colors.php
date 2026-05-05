<?php include 'header.php'; ?>
<?php require 'db.php'; ?>

<header>
    <h1>Color Selection</h1>
</header>

<p class= "selec_info"> Manage the color available in the Color Coordinator. You can add, edit, or remove colors from the list. </p>

<h2> Add a Color </h2>
<form class='addColor' action="colors.php" method="POST">
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
if (isset($_POST['colorName']) && isset($_POST['hexValue'])) {
    $colorName = $_POST['colorName'];
    $hexValue = $_POST['hexValue'];

    if (!preg_match('/^#/', $hexValue)) {
        $hexValue = '#' . $hexValue;
    }
    
    
    if (!preg_match('/^#[0-9A-Fa-f]{6}$/', $hexValue)) {
        echo "<div class='error-msg'>Invalid hex format. Use like 00FFFF or #00FFFF</div>";
    } else {
        if (in_array($colorName, $colors_flat) || in_array($hexValue, $hex_flat)) {
            echo "<div class='error-msg'>Color already exists (name or hex must be unique).</div>";
        } else {
            $insertColor = "INSERT INTO colors (name, hex_value) VALUES ('$colorName', '$hexValue')";
            $result = $conn->query($insertColor);
            echo "<div class='success-msg'>Color added successfully!</div>";
            echo "<meta http-equiv='refresh' content='1'>";
        }
    }
}
?>
<h2> Edit a Color </h2>
<p class = "selec_info"> Select Color: </p>
<form class='editColor1' action="colors.php" method="POST">
<select name="select_color1">
    <?php foreach ($colors as $color): ?>
        <option value="<?php echo $color['name']; ?>">
            <?php echo $color['name']; ?>
        </option>
    <?php endforeach; ?>
</select>
<form class='editColor2' action="colors.php" method="POST">
        New Name: <input type="text" name="colorName2" placeholder="e.g. Cyan" required>
        New Hex Value: <input type="text" name="hexValue2" placeholder="e.g. 00FFFF" required>
        <button type="submit">Submit</button>
</form>
<?php
if (isset($_POST['select_color1']) && isset($_POST['colorName2']) && isset($_POST['hexValue2'])) {
    $colorName2 = $_POST['colorName2'];
    $hexValue2 = $_POST['hexValue2'];
    $select_color1 = $_POST['select_color1'];

     if (!preg_match('/^#/', $hexValue2)) {
        $hexValue2 = '#' . $hexValue2;
    }

     if (!preg_match('/^#[0-9A-Fa-f]{6}$/', $hexValue2)) {
        echo "<div class='error-msg'>Invalid hex format for edit.</div>";
    } else {
  
        $conflict = false;
        foreach ($colors as $color) {
            if ($color['name'] !== $select_color1) {
                if (strtolower($color['name']) === strtolower($colorName2) || $color['hex_value'] === $hexValue2) {
                    $conflict = true;
                    break;
                }
            }
        }
        
        if ($conflict) {
            echo "<div class='error-msg'>That name or hex value already exists for another color.</div>";
        } else {
            $updateColor = "UPDATE colors SET name = '$colorName2', hex_value = '$hexValue2' WHERE name = '$select_color1'";
            $result = $conn->query($updateColor);
            echo "<div class='success-msg'>Color updated successfully!</div>";
            echo "<meta http-equiv='refresh' content='1'>";
        }
    }
}
?>
<h2> Delete a Color </h2>
<p class="selec_info"> Select Color: </p>
<form class="deleteColor" action="colors.php" method="POST">
    <select name="select_color2">
        <?php foreach ($colors as $color): ?>
            <option value="<?php echo $color['name']; ?>">
                <?php echo $color['name']; ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit" name="delete_request" value="1">Delete</button>

    <?php if (isset($_POST['delete_request']) && isset($_POST['select_color2']) && count($colors) > 2): ?>
        <p class="selec_info">Click confirm to delete <?php echo $_POST['select_color2']; ?>.</p>
        <input type="hidden" name="select_color2" value="<?php echo $_POST['select_color2']; ?>">
        <button type="submit" name="confirm_delete" value="1">Confirm Delete</button>
    <?php endif; ?>
</form>

<?php
if (isset($_POST['delete_request']) && count($colors) < 2) {
    echo "At least 2 colors must remain in the database. Deletion is not allowed.";
}

if (isset($_POST['confirm_delete']) && isset($_POST['select_color2']) && count($colors) > 2) {
    $select_color2 = $_POST['select_color2'];
    $deleteColor = "DELETE FROM colors WHERE name = '$select_color2'";
    $result = $conn->query($deleteColor);
    echo "Color deleted successfully!";
}
?>
<h2> Current Colors </h2>
<?php
echo "<table class='color-selec-grid' border='1' cellpadding='10' style='border-collapse: collapse;'>";
echo "<tr><th>Name</th><th>Hex Value</th><th>Preview</th></tr>";
for($i = 0; $i < count($colors); $i++) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($colors[$i]['name']) . "</td>";
    echo "<td>" . htmlspecialchars($hex_values[$i]['hex_value']) . "</td>";
    echo "<td style='background-color: " . $hex_values[$i]['hex_value'] . "; text-align: center;'>&nbsp;&nbsp;&nbsp;&nbsp;</td>";
    echo "</tr>";
}
echo "</table>";
?>

<?php include 'footer.php'; ?>
