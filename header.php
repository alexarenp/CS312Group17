<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Color Map Generator</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="<?php echo $bodyClass ?? ''; ?>">
    <?php if (($bodyClass ?? '') != 'print-page') { ?>
    <header>
        <nav class="navbar">
            <img src="pictures/color_map.jpeg" alt="Logo" class="logo">
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="color.php">Color Coordinator</a></li>
                <li><a href="colors.php">Color Selection</a></li>
            </ul>
        </nav>
    </header>
    <?php } ?>
<main class = "main-content">
