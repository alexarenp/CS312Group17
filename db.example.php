?php

/**
 * DATABASE CONFIGURATION TEMPLATE
 * 
 * INSTRUCTIONS FOR EACH TEAM MEMBER:
 * 1. Copy this file to db.php
 * 2. Replace YOUR_EID with your actual CS eID
 * 3. Replace YOUR_PASSWORD with your MySQL password
 * 4. DO NOT commit db.php to git (it's already in .gitignore)
 * 
 */
$host = 'helmi.cs.colostate.edu';
$username = 'YOUR_EID';
$password = 'YOUR_PASSWORD';
$database = 'YOUR_EID';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8");

?>