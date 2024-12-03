<?php
require_once "config.php";

session_start();

// Rediriger si déjà connecté
if (isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération et nettoyage des données du formulaire
    $nom = trim(filter_input(INPUT_POST, "nom", FILTER_SANITIZE_STRING));
    $prenom = trim(filter_input(INPUT_POST, "prenom", FILTER_SANITIZE_STRING));
    $email = trim(filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL));
    $motDePasse = $_POST["motDePasse"]; // Pas de nettoyage pour le mot de passe

    // Validation des données
    if (empty($nom) || empty($prenom) || empty($email) || empty($motDePasse)) {
        $message = "Tous les champs sont obligatoires.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Format d'email invalide.";
    } else {
        // Vérification de l'existence de l'email
        $stmt = $mysqli->prepare("SELECT ID FROM utilisateurs WHERE Email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $message = "Cet email est déjà utilisé.";
        } else {
            // Insertion du nouvel utilisateur
            $stmt = $mysqli->prepare("INSERT INTO utilisateurs (Nom, Prenom, Email, MotDePasse) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $nom, $prenom, $email, $motDePasse);
            
            if ($stmt->execute()) {
                $message = "Inscription réussie. Vous pouvez maintenant vous connecter.";
                header("refresh:3;url=connection.php");
            } else {
                $message = "Erreur lors de l'inscription : " . $stmt->error;
            }
        }
        $stmt->close();
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription à VAIKA</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="wrapper">
        <h2>Inscription</h2>
        <?php if (!empty($message)) : ?>
            <p class="message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="input-box">
                <input type="text" name="nom" placeholder="Entrez votre nom" required maxlength="255">
            </div>
            <div class="input-box">
                <input type="text" name="prenom" placeholder="Entrez votre prénom" required maxlength="255">
            </div>
            <div class="input-box">
                <input type="email" name="email" placeholder="Entrez votre email" required maxlength="255">
            </div>
            <div class="input-box">
                <input type="password" name="motDePasse" placeholder="Entrez votre mot de passe" required maxlength="255">
            </div>
            <div class="policy">
                <input type="checkbox" required>
                <h3>J'accepte les termes et conditions</h3>
            </div>
            <div class="input-box button">
                <input type="submit" value="S'inscrire">
            </div>
            <div class="text">
                <h3>Vous avez déjà un compte ? <a href="connection.php">Connectez-vous</a></h3>
            </div>
        </form>
    </div>
</body>
</html>