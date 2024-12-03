<?php
include "config.php";

session_start();

// Vérifiez si la méthode de requête est POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifiez si les données du formulaire sont définies
    if (isset($_POST["commentaire"]) && isset($_POST["image_id"])) {
        // Récupérez les données du formulaire
        $commentaire = $_POST["commentaire"];
        $image_id = $_POST["image_id"];
        
        // Vérifiez si l'ID utilisateur est stocké dans la session
        if (isset($_SESSION['id'])) {
            // Récupérez l'ID utilisateur à partir de la session
            $user_id = $_SESSION['id'];
        } else {
            // Redirigez vers une page appropriée si l'utilisateur n'est pas connecté
            header("Location: connection.php");
            exit();
        }

        // Établissez une connexion à la base de données
        $mysqli = new mysqli("localhost", "root", "root", "vaika");

        // Vérifiez la connexion à la base de données
        if ($mysqli->connect_error) {
            die("Erreur de connexion à la base de données : " . $mysqli->connect_error);
        }

        // Préparez la requête SQL pour insérer le commentaire
        $requete = "INSERT INTO commentaires (id_image, commentaire, id_utilisateur) VALUES ('$image_id', '$commentaire', '$user_id')";

        // Exécutez la requête
        if ($mysqli->query($requete) === TRUE) {
            // Affichez un message de succès
            echo "Commentaire ajoute avec succes.";
            header("Location: /vaika/image_detail.php?id=$image_id");
            exit();
        } else {
            // Affichez un message d'erreur en cas d'échec de l'insertion du commentaire
            echo "Erreur lors de l'ajout du commentaire : " . $mysqli->error;
        }

        // Fermez la connexion à la base de données
        $mysqli->close();
    } else {
        // Redirigez vers une page appropriée si les données du formulaire ne sont pas définies
        header("Location: index.php");
        exit();
    }
} else {
    // Redirigez vers une page appropriée si la méthode de requête n'est pas POST
    header("Location: index.php");
    exit();
}
?>
