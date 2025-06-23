

<?php
require_once "hfc/header.php";
require_once 'hfc/config.php';

if (!isset($_SESSION['id_users'])) {
    header("Location: login.php");
    exit();
}

// Initialisation robuste du tableau de suivi
$_SESSION['read_comments'] = $_SESSION['read_comments'] ?? [];

$users_id = $_SESSION['id_users'];
$user_role = $_SESSION['role'] ?? 'client';
$selected_comment_id = isset($_GET['selected_id']) ? intval($_GET['selected_id']) : null;

// Marquer comme lu lors de la sélection
if ($selected_comment_id) {
    // Solution avec session
    if (!in_array($selected_comment_id, $_SESSION['read_comments'])) {
        $_SESSION['read_comments'][] = $selected_comment_id;
    }
    
    // Solution avec base de données (si vous utilisez la colonne lue_par)
    try {
        $stmt = $requete->prepare("UPDATE commentaire 
                                 SET lue_par = JSON_SET(IFNULL(lue_par, '{}'), CONCAT('$.', :user_id), NOW())
                                 WHERE id = :comment_id");
        $stmt->bindParam(':comment_id', $selected_comment_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $users_id, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        error_log("Erreur marquage comme lu: " . $e->getMessage());
    }
}

// Traitement de la suppression
if (isset($_REQUEST["del"])) {
    $del_id = intval($_GET["del"]);
    
    // Vérification plus permissive
    $check = $requete->prepare("SELECT id_users FROM commentaire WHERE id = :id");
    $check->bindParam(':id', $del_id, PDO::PARAM_INT);
    $check->execute();
    $comment = $check->fetch(PDO::FETCH_ASSOC);
    
    // Autoriser la suppression :
    // 1. L'utilisateur est admin OU
    // 2. C'est l'auteur du commentaire
    if ($comment && ($comment['id_users'] == $users_id 
                    || $user_role === 'admin' 
                    || $user_role === 'conducteur')) {
        $sql = $requete->prepare("DELETE FROM commentaire WHERE id = :id");
        $sql->bindParam(':id', $del_id, PDO::PARAM_INT);
        
        if ($sql->execute()) {
            echo "<script>alert('Commentaire supprimé avec succès')</script>";
            echo "<script>window.location.href='notation.php'</script>";
            exit();
        }
    } else {
        // Message plus explicite
        $message = "Action non autorisée. Seuls les admins, conducteurs ou l'auteur du commentaire peuvent supprimer.";
        echo "<script>alert('$message')</script>";
    }
} 

// Autoriser les réponses pour tous les utilisateurs
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reponse'])) {
    $comment_id = intval($_POST['comment_id']);
    $reponse = trim($_POST['reponse']);
    
    if (!empty($reponse)) {
        // INSERT seulement (pas de vérification d'existence)
        $stmt = $requete->prepare("INSERT INTO reponses_commentaires 
                                 (comment_id, user_id, reponse) 
                                 VALUES (:comment_id, :user_id, :reponse)");
        
        $stmt->bindParam(':comment_id', $comment_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $users_id, PDO::PARAM_INT);
        $stmt->bindParam(':reponse', $reponse, PDO::PARAM_STR);
        
        if ($stmt->execute()) {
            // Mettre à jour la date de modification du commentaire parent
            $requete->prepare("UPDATE commentaire SET updated_at = NOW() WHERE id = ?")
                   ->execute([$comment_id]);
            
            echo "<script>window.location.href='notation.php?selected_id=$comment_id';</script>";
            exit();
        }
    }
}

// Récupération des commentaires avec toutes leurs réponses
$result = $requete->prepare('SELECT c.*, u.prenom, u.nom, 
                           JSON_EXTRACT(lue_par, CONCAT("$.", :user_id)) as date_lecture
                           FROM commentaire c 
                           INNER JOIN users u ON c.id_users = u.id
                           ORDER BY c.created_at DESC');
$result->bindParam(':user_id', $users_id, PDO::PARAM_INT);
$result->execute();
$commentaires = $result->fetchAll(PDO::FETCH_ASSOC);

// Récupération séparée de toutes les réponses
$reponses = $requete->prepare('SELECT r.*, u.prenom, u.nom 
                             FROM reponses_commentaires r
                             JOIN users u ON r.user_id = u.id
                             ORDER BY r.created_at DESC');
$reponses->execute();
$all_reponses = $reponses->fetchAll(PDO::FETCH_ASSOC);

// Organiser les réponses par comment_id pour un accès facile
$reponses_par_comment = [];
foreach ($all_reponses as $reponse) {
    $reponses_par_comment[$reponse['comment_id']][] = $reponse;
}

// Initialisation de la variable
$selected_comment = null;

if ($selected_comment_id) {
    try {
        $stmt = $requete->prepare('SELECT c.*, u.prenom, u.nom, 
                                 ur.prenom as reponse_prenom, ur.nom as reponse_nom
                                 FROM commentaire c 
                                 INNER JOIN users u ON c.id_users = u.id
                                 LEFT JOIN users ur ON c.id_users = ur.id
                                 WHERE c.id = :id');
        $stmt->bindParam(':id', $selected_comment_id, PDO::PARAM_INT);
        $stmt->execute();
        $selected_comment = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erreur lors de la récupération du commentaire: " . $e->getMessage());
    }
}
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
        .clickable-comment {
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
            cursor: pointer;
        }
        .clickable-comment:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .selected-comment {
            border-left: 4px solid #2ecc71 !important;
            background-color: rgba(46, 204, 113, 0.05) !important;
        }
        .comment-details-container {
            position: sticky;
            top: 20px;
            height: calc(100vh - 100px);
            overflow-y: auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        @media (max-width: 991.98px) {
            .comment-details-container {
                position: static;
                height: auto;
                margin-top: 30px;
            }
        }
        .response-card {
            border-left: 3px solid #2ecc71;
            background-color: #f8f9fa;
        }
        .comment-text {
            white-space: pre-wrap;
        }
        #confiance {
            font-size: 1.2rem;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
        }
        .comments-list {
            scroll-behavior: smooth;
            height:300px;
        }
        /* Empêche les liens/buttons d'interférer avec le clic sur la carte */
        .clickable-comment .card-body a,
        .clickable-comment .card-body button {
            position: relative;
            z-index: 2;
        }
        /* Zone cliquable pour la carte entière */
        .clickable-comment::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            height:200px;
            z-index: 1;
        }
        .response-admin {
            border-left: 3px solid #2ecc71;
            background-color: #f8f9fa;
        }
        .response-user {
            border-left: 3px solid #3498db;
            background-color: #f0f8ff;
        }
        .response-author {
            font-weight: bold;
        }
        .response-author.admin:after {
            content: " (Admin)";
            color: #2ecc71;
        }
        .response-author.conducteur:after {
            content: " (Conducteur)";
            color: #e67e22;
        }
        /* Style pour les commentaires non lus */
        .unread-comment {
            border-left: 4px solid #3498db;
            background-color: rgba(52, 152, 219, 0.05);
        }

        /* Badge pour nouveaux commentaires */
        .unread-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 10px;
            height: 10px;
            background-color: #e74c3c;
            border-radius: 50%;
        }

        /* Style pour vos propres commentaires */
        .own-comment {
            border-left: 4px solid #f39c12;
            background-color: rgba(243, 156, 18, 0.05);
        }
    </style>
</head>
<body>
    <img class="img_header" src="images/img_notation.jpg" alt="Bannière commentaires">
    <div class="jumbotro acceuil"><br>
        <div class="container text-center pt-4">
            <h1 class="display-4" data-aos="fade-down" data-aos-duration="800">COMMENTAIRES</h1>
            <p class="lead text-white" id="confiance" data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">Confiance de voyager partout au Sénégal en sécurité avec SéneTransport</p>
        </div>
    </div>

    <div class="text-center my-4" data-aos="zoom-in" data-aos-delay="300">
        <a href="from_commentaire.php" class="px-4 py-2 text-white button_comantaire text-decoration-none">
            <i class="fas fa-plus-circle"></i> Ajouter un commentaire
        </a>
    </div>

    <div class="container-fluid py-4" style="min-height: 80vh;" data-aos="fade" data-aos-delay="400">
        <div class="row mx-0 g-0" style="height: 100%;">
            <!-- Colonne commentaires ... -->
            <div class="col-12 col-lg-5 h-100 d-flex flex-column" data-aos="fade-right" data-aos-delay="500">
                <div class="comments-list  bg-white p-3 rounded shadow-sm flex-grow-1" style="overflow-y: auto; min-height: 500px;">
                    <?php if (empty($commentaires)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-comment-slash fa-3x text-muted mb-3"></i>
                            <h5>Aucun commentaire pour le moment</h5>
                            <p class="text-muted">Soyez le premier à partager votre expérience</p>
                        </div>
                    <?php else: ?>
                        <?php foreach($commentaires as $commentaire): ?>
                            <?php 
                    $is_unread = empty($commentaire['date_lecture']) && $commentaire['id_users'] != $users_id;
                    $is_own = $commentaire['id_users'] == $users_id;
                    ?>
    
    
                    <a href="notation.php?selected_id=<?= $commentaire['id'] ?>" class="text-decoration-none text-dark">
                        <div class="card mb-3 clickable-comment 
                            <?= ($selected_comment_id == $commentaire['id']) ? 'selected-comment' : '' ?>
                            <?= $is_unread ? 'unread-comment' : '' ?>
                            <?= $is_own ? 'own-comment' : '' ?>">
            
                        <?php if ($is_unread): ?>
                            <div class="unread-badge" title="Nouveau commentaire"></div>
                        <?php endif; ?>
                                <div class="row g-0 align-items-center">
                                    <div class="col-3 col-md-2 d-flex justify-content-center align-items-center p-2" style="min-height: 100px;">
                                        <i class="fas fa-user-circle fa-2x text-muted" style="line-height: 1;"></i>
                                    </div>
                                    <div class="col-9 col-md-10">
                                        <div class="card-body py-2">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <h5 class="card-title mb-1">
                                                        <?= htmlspecialchars($commentaire['prenom'] . ' ' . $commentaire['nom']) ?>
                                                    </h5>
                                                    <small class="text-muted">
                                                        <i class="far fa-clock"></i> <?= date('d/m/Y H:i', strtotime($commentaire['created_at'])) ?>
                                                    </small>
                                                </div>
                                                <p class="card-text font-weight-bold text-primary mb-2">
                                                    <?php echo htmlspecialchars($commentaire['conf_conducteur']); ?>
                                                </p>
                                                <p class="card-text text-truncate small text-muted">
                                                    <?php echo htmlspecialchars($commentaire['commentaires']); ?>
                                                </p>
                                                <?php if ($commentaire['id_users'] == $users_id || $user_role === 'admin'): ?>
                                                    <div class="mt-2 d-flex">
                                                        <?php if ($commentaire['id_users'] == $users_id): ?>
                                                            <a href="update_com.php?id=<?php echo $commentaire['id']; ?>" 
                                                               class="btn btn-sm btn-outline-primary mr-2"
                                                               onclick="return confirm('Voulez-vous modifier ce commentaire ?')">
                                                                <i class="fas fa-edit"></i> Modifier
                                                            </a>
                                                            <a href="notation.php?del=<?php echo $commentaire['id']; ?>" 
                                                               class="btn btn-sm btn-outline-danger"
                                                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?')">
                                                                <i class="fas fa-trash-alt"></i> Supprimer
                                                            </a>
                                                        <?php endif; ?>
                                                        <?php if ($user_role === 'admin' && $commentaire['id_users'] != $users_id): ?>
                                                            <a href="notation.php?del=<?php echo $commentaire['id']; ?>" 
                                                               class="btn btn-sm btn-outline-danger"
                                                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?')">
                                                                <i class="fas fa-trash-alt"></i> Supprimer
                                                            </a>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if (!empty($commentaire['reponse_com'])): ?>
                                                    <div class="mt-2">
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-reply"></i> Répondu
                                                        </span>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            
            <!-- Colonne détails -->
            <div class="col-12 col-lg-7 h-100 d-flex flex-column mt-4 mt-lg-0" data-aos="fade-left" data-aos-delay="500">
                <div class="comment-details-container p-4 bg-white rounded shadow-sm flex-grow-1" style="overflow-y: auto; min-height: 500px;">
                    <?php if ($selected_comment): ?>
                        <!-- ... (détails du commentaire) ... -->
                        <div class="d-flex align-items-center mb-4 pb-2 border-bottom">
                            <i class="fas fa-user-circle fa-3x text-muted mr-3"></i>
                            <div>
                                <h4 class="mb-1"><?php echo htmlspecialchars($selected_comment['prenom'] . ' ' . $selected_comment['nom']); ?></h4>
                                <small class="text-muted">
                                    <i class="far fa-clock"></i> Posté le <?php echo date('d/m/Y à H:i', strtotime($selected_comment['created_at'])); ?>
                                </small>
                            </div>
                        </div>
                        
                        <div class="alert alert-info mb-4">
                            <h5 class="alert-heading mb-2">
                                <i class="fas fa-car"></i> Conducteur concerné
                            </h5>
                            <p class="mb-0 font-weight-bold"><?php echo htmlspecialchars($selected_comment['conf_conducteur']); ?></p>
                        </div>
                        
                        <div class="card mb-4 border-0 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title text-primary mb-3">
                                    <i class="fas fa-comment-dots"></i> Commentaire
                                </h5>
                                <div class="comment-text p-3 bg-light rounded">
                                    <?php echo nl2br(htmlspecialchars($selected_comment['commentaires'])); ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="responses-section">
    <h5 class="mb-3 pb-2 border-bottom">
        <i class="fas fa-reply"></i> Réponses
    </h5>
    
    <?php if (!empty($reponses_par_comment[$selected_comment['id']])): ?>
        <?php foreach ($reponses_par_comment[$selected_comment['id']] as $reponse): ?>
            <div class="card mb-4 <?= ($reponse['user_id'] == $users_id) ? 'response-user' : 'response-admin' ?>">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-user-circle fa-2x me-3"></i>
                        <div>
                            <div class="response-author">
                                <?= htmlspecialchars($reponse['prenom'] . ' ' . $reponse['nom']) ?>
                                <?php if ($reponse['user_id'] == $users_id): ?>
                                    <span class="badge bg-primary">Vous</span>
                                <?php endif; ?>
                            </div>
                            <small class="text-muted">
                                <i class="far fa-clock"></i> 
                                <?= date('d/m/Y à H:i', strtotime($reponse['created_at'])) ?>
                            </small>
                        </div>
                    </div>
                    <div class="comment-text p-3 bg-white rounded">
                        <?= nl2br(htmlspecialchars($reponse['reponse'])) ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="alert alert-warning">
            <i class="fas fa-info-circle"></i> Aucune réponse pour le moment.
        </div>
    <?php endif; ?>
    
    <!-- Formulaire de réponse -->
    <form method="POST" class="mt-4" action="notation.php">
        <input type="hidden" name="comment_id" value="<?= $selected_comment['id'] ?>">
        <div class="form-group mb-3">
            <label class="font-weight-bold mb-2">
                <i class="fas fa-pen-alt"></i> Ajouter une réponse
            </label>
            <textarea class="form-control" name="reponse" rows="4" required
                placeholder="Écrivez votre réponse..."></textarea>
        </div>
        <button type="submit" class="btn btn-primary w-100 py-2">
            <i class="fas fa-paper-plane"></i> Envoyer
        </button>
    </form>
</div>
                    <?php else: ?>
                        <!-- ... (section de sélection ) ... -->
                        <div class="d-flex flex-column align-items-center justify-content-center text-center" style="height: 60vh;">
                            <i class="fas fa-comments fa-5x text-muted mb-4"></i>
                            <h4 class="mb-3">Sélectionnez un commentaire</h4>
                            <p class="text-muted">Cliquez sur un commentaire à gauche pour afficher son contenu détaillé ici</p>
                            <?php if (!empty($commentaires)): ?>
                                <small class="text-muted mt-2">
                                    <i class="fas fa-lightbulb"></i> Les commentaires avec <span class="badge bg-success"><i class="fas fa-reply"></i></span> ont déjà reçu une réponse
                                </small>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php require_once "hfc/footer.php"; ?>

<script>
$(document).ready(function() {

  // Scroll vers le commentaire sélectionné
  <?php if ($selected_comment_id): ?>
        setTimeout(function() {
            var selectedComment = $(".clickable-comment.selected-comment");
            if (selectedComment.length) {
                var commentsList = $('.comments-list');
                var scrollPosition = selectedComment.offset().top - commentsList.offset().top + commentsList.scrollTop() - 20;
                
                commentsList.stop().animate({
                    scrollTop: scrollPosition
                }, 500);
            }
        }, 300);
    <?php endif; ?>


    // Ajustement responsive
    function adjustLayout() {
        if ($(window).width() > 992) {
            var windowHeight = $(window).height();
            var headerHeight = $('.jumbotro').outerHeight();
            var availableHeight = windowHeight - headerHeight - 120;
            
            $('.comments-list').css('max-height', availableHeight + 'px');
            $('.comment-details-container').css('max-height', availableHeight + 'px');
        } else {
            $('.comments-list').css('max-height', '');
            $('.comment-details-container').css('max-height', '');
        }
    }

    // Initialisation
    adjustLayout();
    $(window).resize(adjustLayout);

    // Mise à jour du compteur de non-lus
    function updateUnreadCount() {
        var unreadCount = $('.unread-comment').length;
        if (unreadCount > 0) {
            $('#unread-counter').text(unreadCount).show();
        } else {
            $('#unread-counter').hide();
        }
    }

    // Initialiser le compteur
    updateUnreadCount();
});
</script>
</body>
</html>