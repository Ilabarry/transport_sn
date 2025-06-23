<?php

require "hfc/config.php";

// Vérification de la connexion avant tout affichage
// if (!isset($_SESSION["email"])) {  
//     header("Location: log/connexion.php");   
//     exit();
// }

// Traitement du formulaire
if (isset($_POST['submit'])) {
    // Récupération et validation des données
    $conducteur_id = intval($_POST['conducteur']);
    $Confconducteur = htmlspecialchars(trim($_POST['conf-conduc']));
    $commentaire = htmlspecialchars(trim($_POST['commentaire']));
    $email = $_SESSION['email'];

    // Validation des champs
    if (empty($conducteur_id) || empty($Confconducteur) || empty($commentaire)) {
        $_SESSION['error_message'] = "Veuillez remplir tous les champs";
        header("Location: from_commentaire.php");
        exit();
    }

    try {
        // Vérification de l'utilisateur
        $query = $requete->prepare('SELECT id FROM users WHERE email = :email');
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->execute();
        $user = $query->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $userId = $user['id'];
            
            // Insertion du commentaire
            $result = $requete->prepare('INSERT INTO commentaire(nom_conducteur, conf_conducteur, commentaires, id_users) 
                                       VALUES(:cond, :conf, :com, :id_users)');
            $result->bindParam(':cond', $conducteur_id, PDO::PARAM_INT);
            $result->bindParam(':conf', $Confconducteur, PDO::PARAM_STR);
            $result->bindParam(':com', $commentaire, PDO::PARAM_STR);
            $result->bindParam(':id_users', $userId, PDO::PARAM_INT);
            
            if ($result->execute()) {
                $_SESSION['success_message'] = "";
                header("Location: notation.php");
                exit();
            } else {
                $_SESSION['error_message'] = "Erreur lors de l'ajout du commentaire";
                header("Location: from_commentaire.php");
                exit();
            }
        }
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Erreur technique : " . $e->getMessage();
        header("Location: from_commentaire.php");
        exit();
    }
}
 require_once "hfc/header.php";

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un commentaire</title>
    <style>
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
        }
        body {
            background-color: #f8f9fa;
        }
        .header-section {
            background: linear-gradient(135deg,rgba(243, 247, 245, 0.5),rgb(218, 221, 219, 0.5));
            color: white;
            padding: 40px 0;
            margin-bottom: 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .header-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.3;
            z-index: 0;
        }
        .header-content {
            position: relative;
            z-index: 1;
        }
        .header-title {
            font-weight: 700;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.3);
            margin-bottom: 15px;
            font-size: 2.5rem;
        }
        .form-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 20px;
        }
        .form-card {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.1);
            margin-bottom: 40px;
        }
        .form-group {
            margin-bottom: 25px;
        }
        .form-label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
            display: block;
        }
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            transition: all 0.3s;
        }
        .form-control:focus {
            border-color: #2ecc71;
            box-shadow: 0 0 0 3px rgba(46, 204, 113, 0.2);
            outline: none;
        }
        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }
        .form-text {
            color: #6c757d;
            font-size: 14px;
            margin-top: 5px;
        }
        .submit-btn {
            background: #2ecc71;
            color: white;
            border: none;
            padding: 12px 30px;
            font-size: 18px;
            border-radius: 30px;
            cursor: pointer;
            transition: all 0.3s;
            display: block;
            width: 100%;
            max-width: 400px;
            margin: 30px auto 0;
        }
        .submit-btn:hover {
            background: #27ae60;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .warning-note {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin-bottom: 30px;
            border-radius: 4px;
        }
        @media (max-width: 768px) {
            .header-title {
                font-size: 2rem;
            }
            .form-card {
                padding: 20px;
            }
        }
    </style>
    <link rel="icon" type="image" href="senvoyagee.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>


    <div class="header-section">
        <img src="images/img_notation.jpg" alt="Commentaires" class="header-image" data-aos="fade" data-aos-duration="1000">
        <div class="header-content">
            <h1 class="header-title" data-aos="fade-down" data-aos-duration="800">AJOUTER UN COMMENTAIRE</h1>
            <p class="lead text-white" data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">Partagez votre expérience de voyage</p>
        </div>
    </div>

    <div class="container">
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger" data-aos="fade-up" data-aos-duration="500">
                <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
            </div>
        <?php endif; ?>

        <div class="form-container">
            <div class="warning-note" data-aos="fade-right" data-aos-delay="300">
                <strong><i class="fas fa-exclamation-triangle"></i> Important :</strong> 
                Vous êtes invité à commenter avec respect. Tout commentaire inapproprié sera automatiquement supprimé.
            </div>

            <div class="form-card" data-aos="zoom-in" data-aos-delay="400">
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                    <div class="form-group">
                        <label for="conducteur" class="form-label">Conducteur</label>
                        <select class="form-control" id="conducteur" name="conducteur" required>
                            <option value="">Sélectionnez un conducteur</option>
                            <?php
                            $event = $requete->prepare("SELECT * FROM users WHERE role = 'conducteur'");
                            $event->execute();
                            while($rows = $event->fetch(PDO::FETCH_ASSOC)): 
                            ?>
                            <option value="<?php echo htmlspecialchars($rows['id']); ?>">
                                <?php echo htmlspecialchars($rows['prenom']." ".$rows['nom']); ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="conf-conduc" class="form-label">Confirmer le nom du conducteur</label>
                        <input type="text" class="form-control" id="conf-conduc" 
                               placeholder="Entrez le nom du conducteur" name="conf-conduc" required>
                    </div>

                    <div class="form-group">
                        <label for="commentaire" class="form-label">Votre commentaire</label>
                        <textarea class="form-control" id="commentaire" rows="5" 
                                  placeholder="Décrivez votre expérience de voyage..." name="commentaire" required></textarea>
                        <small class="form-text">
                            <i class="fas fa-info-circle"></i> Vous pouvez parler du conducteur, des clients, du moyen de transport et des difficultés ou bons moments rencontrés pendant le voyage.
                        </small>
                    </div>

                    <button type="submit" name="submit" class="submit-btn">
                        <i class="fas fa-paper-plane"></i> Envoyer mon commentaire
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div style="height:100px"></div>
    
    <?php require_once "hfc/footer.php"; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animation pour le formulaire
        document.addEventListener('DOMContentLoaded', function() {
            const formCard = document.querySelector('.form-card');
            formCard.style.opacity = '0';
            formCard.style.transform = 'translateY(20px)';
            formCard.style.transition = 'all 0.5s ease-out';
            
            setTimeout(() => {
                formCard.style.opacity = '1';
                formCard.style.transform = 'translateY(0)';
            }, 200);
        });
    </script>
</body>
</html>