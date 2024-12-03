<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classement des Images</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
   <style>
    .card {
        margin-bottom: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
    }

    .card img {
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        height: 200px;
        object-fit: cover;
    }

    .card-body {
        padding: 20px;
    }

    .card-title {
        font-size: 20px;
        margin-bottom: 10px;
    }

    .card-text {
        color: #666;
        font-size: 16px;
        margin-bottom: 15px;
    }

    .card-text.comment-count {
        color: #007BFF;
    }

    /* Ajoutez des styles spécifiques pour les classements or, argent et bronze */
    .gold {
        border-color: #FFD700 !important;
    }

    .silver {
        border-color: #C0C0C0 !important;
    }

    .bronze {
        border-color: #CD7F32 !important;
    }
</style>

    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">GALERIE</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="vaika.php">Publier une photo</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<div class="container">
    <h2>Classement des Images</h2>
    <div class="row">
        <?php
        include "config.php";

        $query = "SELECT images.id, images.title, images.description, images.file_name, COUNT(commentaires.id) AS comment_count
                  FROM images
                  LEFT JOIN commentaires ON images.id = commentaires.id_image
                  GROUP BY images.id
                  ORDER BY comment_count DESC
                  LIMIT 3"; // Sélectionne les 3 images avec le plus de commentaires

        $result = $mysqli->query($query);

        $rankings = array("gold", "silver", "bronze");
        $ranking_index = 0;

        while ($row = $result->fetch_assoc()) {
            $imageId = $row["id"];
            $imageTitle = $row["title"];
            $imageDescription = $row["description"];
            $imageFileName = $row["file_name"];
            $commentCount = $row["comment_count"];

            echo '<div class="col">';
            echo '<div class="card">';
            echo '<img src="uploads/' . $imageFileName . '" class="card-img-top" alt="' . $imageTitle . '">';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">' . $imageTitle . '</h5>';
            echo '<p class="card-text">' . $imageDescription . '</p>';
            echo '<p class="card-text">Nombre de commentaires : ' . $commentCount . '</p>';
            echo '</div>';
            echo '</div>';
            echo '</div>';

            // Ajoutez une classe de rangement si elle est disponible
            if ($ranking_index < count($rankings)) {
                echo '<style>.card:nth-child(' . ($ranking_index + 1) . ') {border: 2px solid ' . $rankings[$ranking_index] . ';}</style>';
            }

            $ranking_index++;
        }

        $mysqli->close();
        ?>
    </div>
</div>
</body>
</html>
