<?php
include "config.php";

if (isset($_GET['id'])) {
    $imageId = $_GET['id'];
    
    // Connexion à la base de données

    if ($mysqli->connect_error) {
        die("Erreur de connexion à la base de données : " . $mysqli->connect_error);
    }

    // Récupérez le nom du fichier à partir de la base de données
    $query = "SELECT file_name FROM Images WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $imageId);
    $stmt->execute();
    $stmt->bind_result($fileName);
    $stmt->fetch();
    $stmt->close();

    // Chemin complet du fichier à télécharger
    $filePath = 'uploads/' . $fileName;

    if (file_exists($filePath)) {
        // Définissez les en-têtes HTTP pour indiquer le type de contenu et forcer le téléchargement
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));

        // Lisez le fichier et transmettez-le au navigateur
        readfile($filePath);
        exit;
    } else {
        echo "Le fichier n'existe pas.";
    }

    $mysqli->close();
} else {
    echo "ID d'image manquant.";
}
?>
