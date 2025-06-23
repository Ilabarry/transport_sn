<?php
require_once 'hfc/config.php';


if(isset($_GET['id'])) {
    $id = $_GET['id'];

    $result = $requete->prepare('SELECT conducteur.*, users.prenom, users.nom 
                               FROM conducteur 
                               INNER JOIN users ON conducteur.id_users = users.id 
                               WHERE conducteur.id = :id');
    $result->execute(['id' => $id]);
    $conducteur = $result->fetch(PDO::FETCH_ASSOC);

    if ($conducteur) { 
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
            color: #333;
        }
        .conducteur-header {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('images/img_conduct.jpg');
            background-size: cover;
            background-position: center;
            height: 250px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            margin-bottom: 40px;
        }
        .conducteur-title {
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }
        .conducteur-subtitle {
            font-weight: 300;
            opacity: 0.9;
        }
        .conducteur-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        .conducteur-card {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            background: white;
        }
        .conducteur-img {
            width: 100%;
            height: 300px;
            object-fit: cover;
        }
        .conducteur-body {
            padding: 30px;
        }
        .conducteur-name {
            font-size: 28px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 15px;
        }
        .conducteur-detail {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            font-size: 16px;
        }
        .conducteur-detail i {
            width: 30px;
            color: #2ecc71;
            font-size: 18px;
        }
        .conducteur-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 25px;
            margin-top: 20px;
        }
        .info-title {
            font-weight: 600;
            color: #2ecc71;
            margin-bottom: 15px;
            font-size: 20px;
        }
        .info-content {
            line-height: 1.8;
            text-align: justify;
        }
        .back-btn {
            display: inline-block;
            margin-top: 30px;
            padding: 10px 25px;
            background: #2ecc71;
            color: white;
            border-radius: 30px;
            text-decoration: none;
            transition: all 0.3s;
        }
        .back-btn:hover {
            background: #27ae60;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        @media (max-width: 768px) {
            .conducteur-header {
                height: 200px;
            }
            .conducteur-img {
                height: 250px;
            }
        }
    </style>
    <link rel="icon" type="image" href="senvoyagee.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php require_once "hfc/header.php"; ?>
    
    <div class="conducteur-header">
        <div>
            <h1 class="conducteur-title">PROFIL CONDUCTEUR</h1>
            <p class="conducteur-subtitle">Découvrez le profil complet de notre conducteur professionnel</p>
        </div>
    </div>

    <div class="conducteur-container">
        <div class="conducteur-card">
            <img src="<?php echo htmlspecialchars($conducteur['profil']); ?>" class="conducteur-img" alt="Photo du conducteur">
            
            <div class="conducteur-body">
                <h2 class="conducteur-name"><?php echo htmlspecialchars($conducteur['prenom']) .' '. htmlspecialchars($conducteur['nom']); ?></h2>
                
                <div class="conducteur-detail">
                    <i class="fas fa-id-card"></i>
                    <span><strong>Permis :</strong> <?php echo htmlspecialchars($conducteur['type_permi']); ?></span>
                </div>
                
                <div class="conducteur-detail">
                    <i class="fas fa-bus"></i>
                    <span><strong>Transport :</strong> <?php echo htmlspecialchars($conducteur['nom_transport']); ?></span>
                </div>
                
                <div class="conducteur-detail">
                    <i class="fas fa-calendar-alt"></i>
                    <span><strong>Membre depuis :</strong> <?php echo date('d/m/Y', strtotime($conducteur['created_at'])); ?></span>
                </div>
                
                <div class="conducteur-info">
                    <h3 class="info-title"><i class="fas fa-user-tie"></i> À PROPOS DE MOI</h3>
                    <p class="info-content"><?php echo nl2br(htmlspecialchars($conducteur['information'])); ?></p>
                </div>
                
                <a href="conducteur.php" class="back-btn">
                    <i class="fas fa-arrow-left"></i> Retour à la liste
                </a>
            </div>
        </div>
    </div>

    <div style="height:100px"></div>
    
    <?php require_once "hfc/footer.php"; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animation pour faire apparaître progressivement la carte
        document.addEventListener('DOMContentLoaded', function() {
            const card = document.querySelector('.conducteur-card');
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'all 0.5s ease-out';
            
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100);
        });
    </script>
</body>
</html>
<?php
    } else {
        echo "<div class='container text-center py-5'>
                <i class='fas fa-exclamation-triangle fa-3x text-warning mb-3'></i>
                <h3 class='mb-3'>Ce conducteur n'est pas enregistré</h3>
                <a href='conducteur.php' class='btn btn-primary'>Retour à la liste</a>
              </div>";
    }
} else {
    echo "<div class='container text-center py-5'>
            <i class='fas fa-exclamation-circle fa-3x text-danger mb-3'></i>
            <h3 class='mb-3'>Identifiant non reconnu</h3>
            <a href='conducteur.php' class='btn btn-primary'>Retour à la liste</a>
          </div>";
}
?>