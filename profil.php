<?php
require_once 'hfc/config.php';
require_once "hfc/header.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id_users'])) {
    header("Location: log/connexion.php");
    exit();
}

$users_id = $_SESSION['id_users'];

// Récupérer les informations de l'utilisateur
$result = $requete->prepare('SELECT * FROM users WHERE id = :id');
$result->bindParam(':id', $users_id, PDO::PARAM_INT);
$result->execute();
$user = $result->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header("Location: log/connexion.php");
    exit();
}

// Compter les commentaires et réservations
$comment_count = $requete->prepare("SELECT COUNT(*) FROM commentaire WHERE id_users = :id_users");
$comment_count->bindParam(':id_users', $users_id, PDO::PARAM_INT);
$comment_count->execute();
$comment_count = $comment_count->fetchColumn();

$reservation_count = $requete->prepare("SELECT COUNT(*) FROM reservation WHERE id_users = :id_users");
$reservation_count->bindParam(':id_users', $users_id, PDO::PARAM_INT);
$reservation_count->execute();
$reservation_count = $reservation_count->fetchColumn();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil</title>
    <style>
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
        }
        body {
            background-color: #f8f9fa;
            color: #333;
        }
        .profile-header {
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            color: white;
            padding: 30px 0;
            margin-bottom: 30px;
            position: relative;
        }
        .profile-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid white;
            box-shadow: 0 3px 10px rgba(0,0,0,0.2);
        }
        .profile-name {
            font-weight: 700;
            margin-top: 15px;
        }
        .edit-profile-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(255,255,255,0.2);
            border: none;
            border-radius: 30px;
            padding: 8px 20px;
            color: white;
            transition: all 0.3s;
        }
        .edit-profile-btn:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-2px);
        }
        .stats-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            transition: all 0.3s;
            height: 100%;
        }
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .stats-number {
            font-size: 36px;
            font-weight: 700;
            color: #2ecc71;
            margin: 10px 0;
        }
        .info-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .info-item {
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        .info-label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        .logout-btn {
            background: #e74c3c;
            color: white;
            border: none;
            border-radius: 30px;
            padding: 12px 30px;
            font-size: 18px;
            transition: all 0.3s;
            display: block;
            width: 100%;
            max-width: 300px;
            margin: 0 auto;
        }
        .logout-btn:hover {
            background: #c0392b;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .section-title {
            position: relative;
            margin-bottom: 25px;
            padding-bottom: 10px;
        }
        .section-title:after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background: #2ecc71;
        }
        @media (max-width: 768px) {
            .profile-header {
                text-align: center;
            }
            .edit-profile-btn {
                position: static;
                margin-top: 15px;
            }
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <br><br>
    <div class="profile-header text-center">
        <div class="container">
            <a href="update_profil.php?id=<?php echo $user['id']; ?>" class="edit-profile-btn">
                <i class="fas fa-edit"></i> Modifier
            </a>
            <img src="<?php echo htmlspecialchars($user['photo'] ?? 'images/default-profile.jpg'); ?>" class="profile-avatar" alt="Photo de profil">
            <h2 class="profile-name"><?php echo htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?></h2>
            <p class="text-white">Membre depuis <?php echo date('d/m/Y', strtotime($user['created_at'])); ?></p>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <!-- Colonne Statistiques -->
            <div class="col-md-6 mb-4">
                <h3 class="section-title">Mon Activité</h3>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="stats-card text-center">
                            <i class="fas fa-comments fa-2x text-primary"></i>
                            <h4>Commentaires</h4>
                            <div class="stats-number"><?php echo $comment_count; ?></div>
                            <a href="afiche_profil_commentaire.php?id=<?php echo $user['id']; ?>" class="btn btn-outline-primary">
                                Voir mes commentaires
                            </a>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="stats-card text-center">
                            <i class="fas fa-calendar-check fa-2x text-success"></i>
                            <h4>Réservations</h4>
                            <div class="stats-number"><?php echo $reservation_count; ?></div>
                            <a href="afiche_profil_reservation.php?id=<?php echo $user['id']; ?>" class="btn btn-outline-success">
                                Voir mes réservations
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Colonne Informations -->
            <div class="col-md-6 mb-4">
                <h3 class="section-title">Mes Informations</h3>
                
                <div class="info-card">
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-user mr-2"></i> Prénom</div>
                        <div><?php echo htmlspecialchars($user['prenom']); ?></div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-user mr-2"></i> Nom</div>
                        <div><?php echo htmlspecialchars($user['nom']); ?></div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-envelope mr-2"></i> Email</div>
                        <div><?php echo htmlspecialchars($user['email']); ?></div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-map-marker-alt mr-2"></i> Ville d'origine</div>
                        <div><?php echo htmlspecialchars($user['ville_origine']); ?></div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-home mr-2"></i> Adresse actuelle</div>
                        <div><?php echo htmlspecialchars($user['ville_actuelle']); ?></div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-phone mr-2"></i> Téléphone</div>
                        <div><?php echo htmlspecialchars($user['telephone']); ?></div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-user-tag mr-2"></i> Rôle</div>
                        <div><?php echo htmlspecialchars($user['role']); ?></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Bouton Déconnexion -->
        <div class="text-center py-4">
            <button onclick="confirmLogout()" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Se déconnecter
            </button>
        </div>
    </div>

    <?php require_once "hfc/footer.php"; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmLogout() {
            if (confirm("Vous serez déconnecté et devrez vous reconnecter pour accéder à vos informations. Continuer ?")) {
                window.location.href = "logout.php";
            }
        }
        
        // Animation pour les cartes
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.stats-card, .info-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'all 0.5s ease-out';
                
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
</body>
</html>