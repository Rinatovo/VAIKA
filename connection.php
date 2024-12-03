<?php
        include "config.php";
        session_start();


if ($mysqli->connect_error) {
    die("Erreur de connexion à la base de données : " . $mysqli->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $motDePasse = $_POST["motDePasse"];

    $selectQuery = "SELECT * FROM Utilisateurs WHERE Email = ?";
    $stmt = $mysqli->prepare($selectQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $motDePasseStocke = $row["MotDePasse"];
        if ($motDePasse ===  $motDePasseStocke) {
            // Connexion réussie
            $_SESSION['id'] = $row['ID'];
            header("Location: index.php");
            exit;
        } else {
            $message = "Mot de passe incorrect.";
        }
    }   

    $stmt->close();
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<title>Connexion à VAIKA</title>
    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion à VAIKA</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="wrapper">
        <h2>Connexion</h2>
        <form method="post" action="connection.php">
            <div class="input-box">
                <input type="email" name="email" placeholder="Adresse e-mail" required>
            </div>
            <div class="input-box">
                <input type="password" name="motDePasse" placeholder="Mot de passe" required>
            </div>
            <div class="input-box button">
                <input type="submit" value="Se connecter">
            </div>
            <div class="text">
                <h3>Vous n'avez pas de compte ? <a href="inscription.php">Inscrivez-vous</a></h3>
            </div>
        </form>
    </div>

    <?php
    // Ajoutez cette ligne pour vérifier l'ID de session
    if(isset($_SESSION['id'])) {
        var_dump($_SESSION['id']);
    }
    ?>
</body>
</html>
