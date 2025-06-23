<?php
require_once 'hfc/config.php';
require_once "hfc/header.php";

if (!isset($_SESSION['id_users'])) {
    header("Location: log/connexion.php");
    exit(); 
}
$users_id = $_SESSION['id_users'];

if (isset($_REQUEST["del"])) {
    $del_id = intval($_GET["del"]);
    $sql = $requete->prepare("DELETE FROM conducteur where id=:id");
    $sql->bindParam(':id', $del_id, PDO::PARAM_STR);
    $sql->execute();
    echo "<script>alert('Information supprimée avec succès')</script>";
    echo "<script>window.location.href='conducteur.php'</script>";
}

$result = $requete->prepare('SELECT conducteur.*, users.prenom, users.nom, users.role FROM conducteur INNER JOIN users ON conducteur.id_users = users.id ORDER BY created_at DESC ;');
$result->execute();
$conducteur = $result->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <style>
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
        }
        .acceuil {
            background: rgba(0, 0, 0, 0.29);
            height: 182px;
            position: relative;
        }
        .jumbotro .display-4 {
            padding: 5px 20px;
            border-radius: 0 50% 0 50%;
            color: #fff;
            font-weight: 900;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.3);
        }
        .img_header {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            z-index: -1;
            height: 247px;
            object-fit: cover;
            filter: brightness(0.7);
        }
        .button_comantaire {
            background: #2ecc71;
            font-size: 18px;
            border-radius: 15px;
            margin-top: 20px;
            transition: all 0.3s;
            padding: 10px 25px;
        }
        .button_comantaire:hover {
            background: #27ae60;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .card-conducteur {
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .card-conducteur:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .conducteur-img {
            height: 180px;
            object-fit: cover;
            width: 100%;
        }
        .conducteur-info {
            padding: 15px;
        }
        .conducteur-name {
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        .conducteur-details {
            color: #7f8c8d;
            font-size: 14px;
            margin-bottom: 5px;
        }
        .conducteur-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
        }
        .action-btn {
            border: none;
            background: none;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.2s;
        }
        .action-btn:hover {
            transform: scale(1.1);
        }
        .view-btn {
            color: #3498db;
        }
        .edit-btn {
            color: #f39c12;
        }
        .delete-btn {
            color: #e74c3c;
        }
        #confiance {
            font-size: 1.2rem;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
        }
        .section-title {
            position: relative;
            margin-bottom: 30px;
            text-align: center;
        }
        .section-title:after {
            content: "";
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: #2ecc71;
        }
        .no-conducteurs {
            text-align: center;
            padding: 50px 0;
        }
        .no-conducteurs i {
            font-size: 50px;
            color: #bdc3c7;
            margin-bottom: 20px;
        }
    </style>
    <link rel="icon" type="image" href="senvoyagee.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <img class="img_header" src="images/img_conduct.jpg" alt="Bannière conducteurs">
    <div class="jumbotro acceuil"><br>
        <div class="container text-center pt-4">
            <h1 class="display-4" data-aos="fade-down" data-aos-duration="800">LES CONDUCTEURS</h1>
            <p class="lead text-white" id="confiance" data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">Confiance de voyager partout au Sénégal en sécurité avec SéneTransport</p>
        </div>
    </div>

    <div class="container my-5">
        <h3 class="section-title" data-aos="fade-up">Nos conducteurs professionnels</h3>
        <p class="text-center mb-4" data-aos="fade-up" data-aos-delay="200">Cette section permet aux conducteurs d'ajouter leurs informations pour faciliter le choix des clients.</p>
    
        <div class="text-center mb-5" data-aos="zoom-in" data-aos-delay="300">
            <?php if (isset($_SESSION['id_users']) && isset($_SESSION['role']) && $_SESSION['role'] === 'conducteur'): ?>
                <a href="from_conduct.php" class="px-4 py-2 text-white button_comantaire text-decoration-none">
                    <i class="fas fa-plus-circle"></i> Ajouter mes informations
                </a>
            <?php endif; ?>
            <!-- input de recherche automatique -->
            <input type="text" id="searchInput" class="form-control mt-4 w-50 mx-auto" placeholder="Rechercher un conducteur par prénom ou nom...">
            
        </div>
        <!-- Résultats de la recherche affichés ici -->
        <div id="resultats" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php foreach($conducteur as $conduc): ?>
                <!-- carte conducteur normale ici -->

                <?php include "partials/card_conducteur.php"; ?>
            <?php endforeach; ?>
        </div>

    </div>

    <?php require_once "hfc/footer.php"; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animation au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.card-conducteur');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });

        // pour mes recherches de conducteurs
        document.getElementById('searchInput').addEventListener('input', function () {
            const query = this.value.trim();

            fetch('search_conducteurs.php?q=' + encodeURIComponent(query))
                .then(response => response.text())
                .then(data => {
                    document.getElementById('resultats').innerHTML = data;
                })
                .catch(error => {
                    console.error('Erreur AJAX :', error);
                });
        });

    </script>
</body>
</html>