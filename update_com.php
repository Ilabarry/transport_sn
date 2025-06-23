<?php
require "hfc/config.php";

// Vérification de la connexion
// if(!isset($_SESSION["email"])) {  
//     header("location:log/connexion.php");   
//     exit();
// }

// Traitement de la mise à jour
if(isset($_POST['update'])) {
    // Récupération de l'ID
    $upid = intval($_GET['id']);
    
    // Validation et sécurisation des données
    $conducteur = htmlspecialchars($_POST['conf-conduc']);
    $commentaire = htmlspecialchars($_POST['commentaire']);

    // Mise à jour dans la base de données
    $sql = $requete->prepare('UPDATE commentaire SET conf_conducteur=:conf, commentaires=:com WHERE id=:uid');
    $sql->bindParam(':conf', $conducteur, PDO::PARAM_STR);
    $sql->bindParam(':com', $commentaire, PDO::PARAM_STR);
    $sql->bindParam(':uid', $upid, PDO::PARAM_INT);

    if($sql->execute()) {
        $_SESSION['success_message'] = "Commentaire modifié avec succès";
        header("Location: notation.php");
        exit();
    } else {
        $error_message = "Une erreur est survenue lors de la modification";
    }
}

require_once "hfc/header.php";

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un commentaire</title>
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
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            color: white;
            padding: 30px 0;
            margin-bottom: 30px;
            text-align: center;
        }
        .header-title {
            font-weight: 600;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.2);
            margin-bottom: 15px;
        }
        .warning-note {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin-bottom: 30px;
            border-radius: 4px;
        }
        .form-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 15px;
        }
        .form-card {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
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
        @media (max-width: 768px) {
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
        <div class="container">
            <h1 class="header-title">Modifier un commentaire</h1>
            <p>Merci de partager votre expérience de voyage</p>
        </div>
    </div>

    <div class="container">
        <div class="warning-note">
            <strong><i class="fas fa-exclamation-triangle"></i> Important :</strong> 
            Vous êtes invité à commenter avec respect. Tout commentaire inapproprié sera automatiquement supprimé.
        </div>

        <div class="form-container">
            <div class="form-card">
                <?php 
                // Récupération du commentaire à modifier
                $upid = intval($_GET['id']);
                $sql = $requete->prepare('SELECT conf_conducteur, commentaires FROM commentaire WHERE id=:uid');
                $sql->bindParam(':uid', $upid, PDO::PARAM_INT);
                $sql->execute();
                $result = $sql->fetch(PDO::FETCH_OBJ);

                if($result) {
                ?>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?id=' . $upid; ?>" method="POST">
                    <div class="form-group">
                        <label for="conducteur" class="form-label">Nom du conducteur</label>
                        <input type="text" class="form-control" id="conducteur" name="conf-conduc" 
                               placeholder="Entrez le nom du conducteur" 
                               value="<?php echo htmlspecialchars($result->conf_conducteur); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="commentaire" class="form-label">Votre commentaire</label>
                        <textarea class="form-control" id="commentaire" name="commentaire" rows="5" 
                                  placeholder="Décrivez votre expérience de voyage..." required><?php echo htmlspecialchars($result->commentaires); ?></textarea>
                        <small class="form-text">
                            <i class="fas fa-info-circle"></i> Vous pouvez parler du conducteur, des clients, du moyen de transport et des difficultés ou bons moments rencontrés pendant le voyage.
                        </small>
                    </div>

                    <button type="submit" name="update" class="submit-btn">
                        <i class="fas fa-save"></i> Enregistrer les modifications
                    </button>
                </form>
                <?php } else { ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i> Commentaire introuvable
                    </div>
                <?php } ?>
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