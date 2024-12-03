<?php
include "config.php";

if ($mysqli->connect_error) {
    die("Erreur de connexion à la base de données : " . $mysqli->connect_error);
}

$message = ""; // Initialisation du message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $description = $_POST["description"];
    $file = $_FILES["file"];
    
    $fileExtension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));

    if ($fileExtension == "jpg" || $fileExtension == "jpeg" || $fileExtension == "png") {
        $uploadDir = "uploads/";
        $fileName = uniqid() . "_" . $file["name"];
        $uploadPath = $uploadDir . $fileName;

        if (move_uploaded_file($file["tmp_name"], $uploadPath)) {
            // Enregistrez les informations de l'image dans la base de données
            session_start();
            $user_id = $_SESSION['id']; // Récupérer l'ID de l'utilisateur actuellement connecté
            $insertQuery = "INSERT INTO images (title, description, file_name, user_id) VALUES (?, ?, ?, ?)";
            $stmt = $mysqli->prepare($insertQuery);
            $stmt->bind_param("sssi", $title, $description, $fileName, $user_id);

            if ($stmt->execute()) {
                $message = "Image téléchargée avec succès !";
            } else {
                $message = "Erreur lors de l'insertion dans la base de données : " . $stmt->error;
            }

            $stmt->close();
        } else {
            $message = "Erreur lors du téléchargement de l'image.";
        }
    } else {
        $message = "Seules les images au format JPEG ou PNG sont autorisées.";
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Résultat du téléchargement</title>
</head>
<body>
    <h1>Résultat du téléchargement</h1>
    <p><?php echo $message; ?></p>
    <a href="vaika.php">Retour au formulaire</a>
</body>
</html>
