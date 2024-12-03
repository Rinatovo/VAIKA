<?php
date_default_timezone_set('Indian/Antananarivo');

include "config.php";
session_start();

// Étape 1 : Recuperation des dates uniques pour la liste déroulante
$query_dates = "SELECT DISTINCT DATE(date_publication) as pub_date FROM images ORDER BY pub_date DESC";
$result_dates = $mysqli->query($query_dates);

// Vérification de la date sélectionnée
$selected_date = isset($_GET['date']) ? $_GET['date'] : '';

// Étape 3 : requête SQL pour les images en fonction de la date sélectionnée
if (!empty($selected_date)) {
    // Requete pour une date spécifique
    $query_images = "SELECT i.*, u.Nom as nom_utilisateur FROM images i 
                     LEFT JOIN utilisateurs u ON i.user_id = u.ID 
                     WHERE DATE(i.date_publication) = ? 
                     ORDER BY i.date_publication DESC";
    $stmt = $mysqli->prepare($query_images);
    $stmt->bind_param("s", $selected_date);
    $stmt->execute();
    $result_images = $stmt->get_result();
} else {
 
    $query_images = "SELECT i.*, u.Nom as nom_utilisateur FROM images i 
                     LEFT JOIN utilisateurs u ON i.user_id = u.ID 
                     ORDER BY i.date_publication DESC";
    $result_images = $mysqli->query($query_images);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galerie d'images - VAIKA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        
        .image-container {
            position: relative;
            overflow: hidden;
        }
        .image-container img {
            transition: transform 0.3s ease;
        }
        .image-container:hover img {
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">VAIKA</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="vaika.php">Publier une photo</a>
                    </li>
                    <?php if (!isset($_SESSION['id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="inscription.php">Inscription</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="connection.php">Connexion</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="deconnexion.php">Déconnexion</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1 class="mb-4">Galerie d'images</h1>
        
        <!-- Etape 2 : Liste deroulante pour le filtrage par date -->
        <form action="" method="GET" class="mb-4">
            <select name="date" class="form-select" onchange="this.form.submit()">
                <option value="">Toutes les dates</option>
                <?php while ($row_date = $result_dates->fetch_assoc()) : ?>
                    <option value="<?php echo $row_date['pub_date']; ?>" <?php echo ($selected_date == $row_date['pub_date']) ? 'selected' : ''; ?>>
                        <?php echo date('d/m/Y', strtotime($row_date['pub_date'])); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </form>

        <!-- Affichage des images -->
        <div class="row">
            <?php while ($row = $result_images->fetch_assoc()) : ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="image-container">
                            <img src="uploads/<?php echo htmlspecialchars($row['file_name']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['title']); ?>">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars(substr($row['description'], 0, 100)) . '...'; ?></p>
                            <p class="card-text"><small class="text-muted">Publié par <?php echo htmlspecialchars($row['nom_utilisateur']); ?> le <?php echo date('d/m/Y', strtotime($row['date_publication'])); ?></small></p>
                            <a href="image_detail.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">Voir détails</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    
    <div class="container mt-3 text-center">
        <a href="statut.php" class="btn btn-secondary">Voir les statuts</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>