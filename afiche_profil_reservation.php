<?php
require_once 'hfc/config.php';
// Vérifier si l'utilisateur est connecté
// if (!isset($_SESSION['id_users'])) {
//     header("Location: log/connexion.php");
//     exit();
// }

require_once "hfc/header.php";

// Récupérer l'ID de l'utilisateur depuis l'URL
if (!isset($_GET['id'])) {
    echo "<div class='container text-center py-5'>
            <i class='fas fa-exclamation-circle fa-3x text-danger mb-3'></i>
            <h3 class='mb-3'>ID utilisateur non spécifié</h3>
            <a href='profil.php' class='btn btn-primary'>Retour au profil</a>
          </div>";
    exit();
}

$users_id = $_GET['id'];

// Suppression d'une réservation
if (isset($_REQUEST["del"])) {
    $del_id = intval($_GET["del"]);
    $sql = $requete->prepare("DELETE FROM reservation WHERE id=:id");
    $sql->bindParam(':id', $del_id, PDO::PARAM_INT);
    
    if ($sql->execute()) {
        echo "<script>alert('Réservation supprimée avec succès')</script>";
        echo "<script>window.location.href='afiche_profil_reservation.php?id=" . $users_id . "'</script>";
        exit();
    }
}

// Récupérer les informations de l'utilisateur
$query = $requete->prepare('SELECT id, prenom, nom FROM users WHERE id = :id');
$query->bindParam(':id', $users_id, PDO::PARAM_INT);
$query->execute();
$user = $query->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "<div class='container text-center py-5'>
            <i class='fas fa-user-slash fa-3x text-warning mb-3'></i>
            <h3 class='mb-3'>Utilisateur non trouvé</h3>
            <a href='profil.php' class='btn btn-primary'>Retour au profil</a>
          </div>";
    exit();
}

$userId = $user['id'];
$prenom = $user['prenom'];
$nom = $user['nom'];
// $photo = $user['photo'] ?? 'images/default-profile.jpg';

// Récupérer toutes les réservations de l'utilisateur
$result = $requete->prepare('SELECT * FROM reservation WHERE id_users = :id_users ORDER BY date_time DESC');
$result->bindParam(':id_users', $userId, PDO::PARAM_INT);
$result->execute();
$reservations = $result->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Réservations de <?php echo htmlspecialchars($prenom); ?></title>
    <link rel="icon" type="image" href="senvoyagee.png">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
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
        .profile-header {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6));
            background-size: cover;
            background-position: center;
            height: 250px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            margin-bottom: 30px;
            position: relative;
        }
        .back-btn {
            position: absolute;
            left: 20px;
            top: 20px;
            color: white;
            font-size: 24px;
            z-index: 10;
        }
        .profile-title {
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }
        .profile-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        .profile-card {
            display: flex;
            align-items: center;
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        .profile-img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 20px;
            border: 3px solid #2ecc71;
        }
        .profile-info h3 {
            margin-bottom: 5px;
            color: #2c3e50;
        }
        .reservation-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            position: relative;
            transition: all 0.3s ease;
        }
        .reservation-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .reservation-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .reservation-title {
            font-weight: 600;
            color: #2c3e50;
            font-size: 18px;
        }
        .reservation-date {
            color: #7f8c8d;
            font-size: 14px;
        }
        .reservation-detail {
            margin: 10px 0;
            display: flex;
        }
        .detail-label {
            font-weight: 600;
            color: #2c3e50;
            min-width: 180px;
        }
        .reservation-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 15px;
        }
        .action-btn {
            border: none;
            background: none;
            cursor: pointer;
            font-size: 16px;
            margin-left: 15px;
            transition: all 0.2s;
        }
        .action-btn:hover {
            transform: scale(1.1);
        }
        .edit-btn {
            color: #f39c12;
        }
        .delete-btn {
            color: #e74c3c;
        }
        .no-reservations {
            text-align: center;
            padding: 50px 0;
        }
        .no-reservations i {
            font-size: 50px;
            color: #bdc3c7;
            margin-bottom: 20px;
        }
        .transport-icon {
            color: #2ecc71;
            margin-right: 8px;
            width: 20px;
            text-align: center;
        }
        @media (max-width: 768px) {
            .profile-header {
                height: 200px;
            }
            .profile-card {
                flex-direction: column;
                text-align: center;
            }
            .profile-img {
                margin-right: 0;
                margin-bottom: 15px;
            }
            .reservation-detail {
                flex-direction: column;
            }
            .detail-label {
                min-width: auto;
                margin-bottom: 5px;
            }
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    
    <div class="profile-header">
        <a href="profil.php" class="back-btn">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="profile-title">MES RÉSERVATIONS</h1>
            <p class="lead text-white">Tous mes voyages programmés</p>
        </div>
    </div>

    <div class="profile-container">
        <div class="profile-card">
            <img src="<?php echo htmlspecialchars($photo); ?>" class="profile-img" alt="Photo de profil">
            <div class="profile-info">
                <h3><?php echo htmlspecialchars($prenom) . ' ' . htmlspecialchars($nom); ?></h3>
                <p class="text-muted">Membre depuis <?php echo date('d/m/Y', strtotime($_SESSION['created_at'] ?? 'now')); ?></p>
            </div>
        </div>

        <?php if (!empty($reservations)): ?>
            <div class="row row-cols-1 row-cols-md-2 g-4">
                <?php foreach ($reservations as $reservation): ?>
                    <div class="col">
                        <div class="reservation-card">
                            <div class="reservation-header">
                                <div class="reservation-title">
                                    <i class="fas fa-map-marked-alt transport-icon"></i>
                                    <?php echo htmlspecialchars($reservation['depart']) . ' → ' . htmlspecialchars($reservation['arriver']); ?>
                                </div>
                                <div class="reservation-date">
                                    <i class="far fa-calendar-alt"></i> 
                                    <?php echo date('d/m/Y H:i', strtotime($reservation['date_time'])); ?>
                                </div>
                            </div>
                            
                            <div class="reservation-detail">
                                <span class="detail-label"><i class="fas fa-user transport-icon"></i> Passager :</span>
                                <span><?php echo htmlspecialchars($prenom) . ' ' . htmlspecialchars($nom); ?></span>
                            </div>
                            
                            <div class="reservation-detail">
                                <span class="detail-label"><i class="fas fa-clock transport-icon"></i> Nbr de chaises :</span>
                                <span><?php echo htmlspecialchars($reservation['passengers']); ?></span>
                            </div>
                            
                            <div class="reservation-detail">
                                <span class="detail-label"><i class="fas fa-bus transport-icon"></i> Transport :</span>
                                <span><?php echo htmlspecialchars($reservation['transport_type']); ?></span>
                            </div>
                            
                            <div class="reservation-detail">
                                <span class="detail-label"><i class="fas fa-calendar-check transport-icon"></i> Réservé le :</span>
                                <span><?php echo date('d/m/Y à H:i', strtotime($reservation['created_at'])); ?></span>
                            </div>
                            
                            <div class="reservation-actions">
                                <a href="update_reservation.php?id=<?php echo $reservation['id']; ?>" 
                                   class="edit-btn action-btn"
                                   title="Modifier"
                                   onclick="return confirm('Voulez-vous modifier cette réservation ?')">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <a href="afiche_profil_reservation.php?del=<?php echo $reservation['id']; ?>&id=<?php echo $users_id; ?>" 
                                   class="delete-btn action-btn"
                                   title="Supprimer"
                                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer définitivement cette réservation ?')">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-reservations">
                <i class="fas fa-calendar-times"></i>
                <h4>Aucune réservation trouvée</h4>
                <p class="text-muted">Vous n'avez pas encore effectué de réservation</p>
                <a href="reservation.php" class="btn btn-primary mt-3">
                    <i class="fas fa-plus-circle"></i> Nouvelle réservation
                </a>
            </div>
        <?php endif; ?>
    </div>

    <?php require_once "hfc/footer.php"; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animation pour faire apparaître les réservations progressivement
        document.addEventListener('DOMContentLoaded', function() {
            const reservationCards = document.querySelectorAll('.reservation-card');
            reservationCards.forEach((card, index) => {
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