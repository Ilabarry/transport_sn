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

// Suppression d'un commentaire
if (isset($_REQUEST["del"])) {
    $del_id = intval($_GET["del"]);
    $sql = $requete->prepare("DELETE FROM commentaire WHERE id=:id");
    $sql->bindParam(':id', $del_id, PDO::PARAM_INT);
    
    if ($sql->execute()) {
        echo "<script>alert('Commentaire supprimé avec succès')</script>";
        echo "<script>window.location.href='afiche_profil_commentaire.php?id=" . $users_id . "'</script>";
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

// Récupérer tous les commentaires de l'utilisateur
$result = $requete->prepare('SELECT commentaire.*, users.prenom, users.nom 
                           FROM commentaire 
                           JOIN users ON commentaire.id_users = users.id
                           WHERE commentaire.id_users = :id_users
                           ORDER BY commentaire.created_at DESC');
$result->bindParam(':id_users', $userId, PDO::PARAM_INT);
$result->execute();
$commentaires = $result->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Commentaires de <?php echo htmlspecialchars($prenom); ?></title>
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
            max-width: 800px;
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
        .comment-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            position: relative;
            transition: all 0.3s ease;
        }
        .comment-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .comment-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        .comment-user {
            font-weight: 600;
            color: #2c3e50;
        }
        .comment-date {
            color: #7f8c8d;
            font-size: 14px;
        }
        .comment-conducteur {
            color: #2ecc71;
            font-weight: 600;
            margin: 10px 0;
        }
        .comment-text {
            line-height: 1.6;
            margin-bottom: 15px;
        }
        .comment-actions {
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
        .no-comments {
            text-align: center;
            padding: 50px 0;
        }
        .no-comments i {
            font-size: 50px;
            color: #bdc3c7;
            margin-bottom: 20px;
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
            <h1 class="profile-title">MES COMMENTAIRES</h1>
            <p class="lead text-white">Tous les commentaires que j'ai postés</p>
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

        <?php if (!empty($commentaires)): ?>
            <?php foreach ($commentaires as $commentaire): ?>
                <div class="comment-card">
                    <div class="comment-header">
                        <i class="fas fa-user-circle fa-2x mr-3 text-muted"></i>
                        <div>
                            <div class="comment-user"><?php echo htmlspecialchars($commentaire['prenom']) . ' ' . htmlspecialchars($commentaire['nom']); ?></div>
                            <div class="comment-date">
                                <i class="far fa-clock"></i> <?php echo date('d/m/Y à H:i', strtotime($commentaire['created_at'])); ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="comment-conducteur">
                        <i class="fas fa-car"></i> <?php echo htmlspecialchars($commentaire['conf_conducteur']); ?>
                    </div>
                    
                    <div class="comment-text">
                        <?php echo nl2br(htmlspecialchars($commentaire['commentaires'])); ?>
                    </div>
                    
                    <div class="comment-actions">
                        <a href="update_com.php?id=<?php echo $commentaire['id']; ?>" 
                           class="edit-btn action-btn"
                           title="Modifier"
                           onclick="return confirm('Voulez-vous modifier ce commentaire ?')">
                            <i class="fas fa-edit"></i>
                        </a>
                        
                        <a href="afiche_profil_commentaire.php?del=<?php echo $commentaire['id']; ?>&id=<?php echo $users_id; ?>" 
                           class="delete-btn action-btn"
                           title="Supprimer"
                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer définitivement ce commentaire ?')">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-comments">
                <i class="far fa-comment-dots"></i>
                <h4>Aucun commentaire trouvé</h4>
                <p class="text-muted">Vous n'avez pas encore posté de commentaires</p>
                <a href="notation.php" class="btn btn-primary mt-3">
                    <i class="fas fa-plus-circle"></i> Ajouter un commentaire
                </a>
            </div>
        <?php endif; ?>
    </div>

    <?php require_once "hfc/footer.php"; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animation pour faire apparaître les commentaires progressivement
        document.addEventListener('DOMContentLoaded', function() {
            const commentCards = document.querySelectorAll('.comment-card');
            commentCards.forEach((card, index) => {
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