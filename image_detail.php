<?php
include "config.php";

session_start();

if (isset($_GET['id'])) {
    $imageId = $_GET['id'];

    // Inclure le fichier de configuration
    require_once("config.php");
    $mysqli = new mysqli("localhost", "root", "root", "vaika");

    if ($mysqli->connect_error) {
        die("Erreur de connexion à la base de données : " . $mysqli->connect_error);
    }

    $selectImageQuery = "SELECT * FROM Images WHERE id = $imageId";
    $result = $mysqli->query($selectImageQuery);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $imageFilename = $row["file_name"];
        $imageTitle = $row["title"];
        $imageDescription = $row["description"];
    } else {
        echo "Image non trouvée dans la base de données.";
        exit;
    }
} else {
    echo "ID de l'image non spécifié dans l'URL.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #fafafa;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            background-color: #405DE6;
            color: #fff;
            padding: 20px;
            margin: 0;
        }

        .navbar {
            background-color: #007BFF;
            padding: 10px 0;
        }

        .navbar-brand {
            font-size: 24px;
            color: #fff;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        .image-details {
            text-align: center;
            padding: 20px;
        }

        img {
            max-width: 100%;
            height: auto;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin: 20px 0;
        }

        .comments {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            margin: 20px 0;
        }

        .user-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            margin-right: 10px;
            object-fit: cover;
        }

        .comment {
            margin: 10px 0;
        }

        .btn-download {
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 8px 12px;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }

        .add-comment {
            margin-top: 20px;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">VAIKA</a>
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="index.php">Retour à la galerie</a>
            </li>
        </ul>
    </div>
</nav>
<div class="container">
    <div class="image-details">
        <h1><?php echo $imageTitle; ?></h1>
        <img src="uploads/<?php echo $imageFilename; ?>" alt="<?php echo $imageTitle; ?>">
        <p><?php echo $imageDescription; ?></p>
        <a href="download.php?id=<?php echo $imageId; ?>" class="btn-download">Télécharger</a>
        <div class="comments">
            <h4>Commentaires :</h4>
            <ul class="list-group">
                <?php
                // Modifier la requête pour récupérer l'ID de l'utilisateur avec le commentaire et son nom
                $selectCommentsQuery = "SELECT c.commentaire, u.nom FROM Commentaires c JOIN Utilisateurs u ON c.id_utilisateur = u.id WHERE id_image = $imageId";
                $result = $mysqli->query($selectCommentsQuery);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $comment = $row["commentaire"];
                        $userName = $row["nom"]; // Nom de l'utilisateur

                        echo '<li class="list-group-item">' . $comment . ' - ' . $userName . '</li>';
                    }
                } else {
                    echo '<li class="list-group-item">Aucun commentaire pour cette image.</li>';
                }
                ?>
            </ul>
        </div>

        <div class="add-comment">
            <h4>Ajouter un commentaire :</h4>
            <form action="ajouter_commentaire.php" method="post">
                <input type="hidden" name="image_id" value="<?php echo $imageId; ?>">
                <textarea name="commentaire" placeholder="Saisissez votre commentaire" rows="2" cols="40"></textarea><br>
                <input type="submit" value="Ajouter un commentaire">
            </form>
        </div>
    </div>
</div>
</body>
</html>
