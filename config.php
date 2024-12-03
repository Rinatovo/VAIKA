<?php
// Database configuration
$host = "localhost";
$username = "root";
$password = "root";
$database = "vaika";

// Create a database connection
$mysqli = new mysqli($host, $username, $password, $database);

if ($mysqli->connect_error) {
    die("Erreur de connexion à la base de données : " . $mysqli->connect_error);
}

// Other configuration settings
$siteName = "Your Site Name";
$siteURL = "index.php";
?>
