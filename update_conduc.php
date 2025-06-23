<?php
require "hfc/config.php";
session_start(); 

// Vérification de la connexion
if(!isset($_SESSION["email"])) {  
    header("location:log/connexion.php");   
    exit();
}

// Traitement de la mise à jour
if(isset($_POST['update_conduct'])) {
    // Récupération et validation de l'ID
    $upid = intval($_GET['id']);
    
    // Validation et sécurisation des données
    $typePermi = htmlspecialchars($_POST['typePermi']);
    $typeTransport = htmlspecialchars($_POST['typeTransport']);
    $information = htmlspecialchars($_POST['information']);

    // Mise à jour dans la base de données
    $sql = $requete->prepare('UPDATE conducteur SET type_permi=:permi, nom_transport=:transport, information=:info WHERE id=:uid');
    $sql->bindParam(':permi', $typePermi, PDO::PARAM_STR);
    $sql->bindParam(':transport', $typeTransport, PDO::PARAM_STR);
    $sql->bindParam(':info', $information, PDO::PARAM_STR);
    $sql->bindParam(':uid', $upid, PDO::PARAM_INT);

    if($sql->execute()) {
        $_SESSION['success_message'] = "Informations modifiées avec succès";
        header("Location: conducteur.php");
        exit();
    } else {
        $error_message = "Une erreur est survenue lors de la modification";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier mes informations</title>
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
        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 1em;
        }
        textarea.form-control {
            min-height: 150px;
            resize: vertical;
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
        .current-option {
            font-weight: bold;
            color: #2ecc71;
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
    <?php require_once "hfc/header.php"; ?>

    <div class="header-section">
        <div class="container">
            <h1 class="header-title">Modifier mes informations</h1>
            <p>Mettez à jour vos informations professionnelles</p>
        </div>
    </div>

    <div class="container">
        <div class="form-container">
            <div class="form-card">
                <?php 
                // Récupération des informations actuelles
                $upid = intval($_GET['id']);
                $sql = $requete->prepare('SELECT type_permi, nom_transport, information FROM conducteur WHERE id=:uid');
                $sql->bindParam(':uid', $upid, PDO::PARAM_INT);
                $sql->execute();
                $result = $sql->fetch(PDO::FETCH_OBJ);

                if($result) {
                ?>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?id=' . $upid; ?>" method="POST">
                    <div class="form-group">
                        <label for="typePermi" class="form-label">Type de permis</label>
                        <select class="form-control" id="typePermi" name="typePermi" required>
                            <option value="<?php echo htmlspecialchars($result->type_permi); ?>" class="current-option">
                                Actuel: <?php echo htmlspecialchars($result->type_permi); ?>
                            </option>
                            <option value="Poid légère">Poids léger</option>
                            <option value="Poid moyen">Poids moyen</option>
                            <option value="Poid lourd">Poids lourd</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="typeTransport" class="form-label">Type de transport</label>
                        <select class="form-control" id="typeTransport" name="typeTransport" required>
                            <option value="<?php echo htmlspecialchars($result->nom_transport); ?>" class="current-option">
                                Actuel: <?php echo htmlspecialchars($result->nom_transport); ?>
                            </option>
                            <option value="Bus">Bus</option>
                            <option value="Mini-bus">Mini-bus</option>
                            <option value="7-Places">7-Places</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="information" class="form-label">Informations professionnelles</label>
                        <textarea class="form-control" id="information" name="information" rows="5" 
                                  placeholder="Décrivez votre expérience, vos spécialités..." required><?php echo htmlspecialchars($result->information); ?></textarea>
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle"></i> Ces informations aideront les clients à mieux vous connaître.
                        </small>
                    </div>

                    <button type="submit" name="update_conduct" class="submit-btn">
                        <i class="fas fa-save"></i> Enregistrer les modifications
                    </button>
                </form>
                <?php } else { ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i> Informations introuvables
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