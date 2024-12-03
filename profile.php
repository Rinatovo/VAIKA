<!-- profile.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil de l'utilisateur</title>
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

        .user-info {
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            margin-top: 20px;
        }

        .user-info h2 {
            margin: 0;
        }

        .user-info p {
            margin-top: 10px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Profil de l'utilisateur</a>
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="index.php">Retour à la galerie</a>
            </li>
        </ul>
    </div>
</nav>
<div class="container">
    <div class="user-info">
        <h1>Informations de l'utilisateur</h1>
        <?php
        // Inclure le code de connexion à la base de données
        include "config.php";

        // Démarrer la session
        session_start();

        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            // Requête pour récupérer les informations de l'utilisateur
            $query = "SELECT nom_utilisateur, email_utilisateur FROM Utilisateurs WHERE id = $user_id";
            $result = $mysqli->query($query);

            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $nom_utilisateur = $row['nom_utilisateur'];
                $email_utilisateur = $row['email_utilisateur'];
                echo '<h2>Nom d\'utilisateur : ' . $nom_utilisateur . '</h2>';
                echo '<p>Email : ' . $email_utilisateur . '</p>';
            }
        } else {
            echo '<p>Vous devez être connecté pour accéder à votre profil.</p>';
        }

        // Fermer la connexion à la base de données
        $mysqli->close();
        ?>
    </div>
</div>
</body>
</html>
