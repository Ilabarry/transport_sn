<?php
require "hfc/config.php";
ob_start();
require_once "hfc/header.php";
if(!isset($_SESSION["email"])) {  
    header("location:log/connexion.php");   
}

if (isset($_POST['conducteur'])) {
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['image'];
        $typePermi = $_POST['typePermi'];
        $typeTransport = $_POST['typeTransport'];
        $information = $_POST['information'];
        $email = $_SESSION['email'];
        
        $nomImg = basename($file['name']);
        $stockImg = 'uploads/';

        if (!is_dir($stockImg)) {
            mkdir($stockImg, 0755, true);
        }

        $deplaceImg = $stockImg . $nomImg;
        if (move_uploaded_file($file['tmp_name'], $deplaceImg)) {
            $query = $requete->prepare('SELECT id FROM users WHERE email = :email');
            $query->bindParam(':email', $email, PDO::PARAM_STR);
            $query->execute();
            $user = $query->fetch(PDO::FETCH_ASSOC);
        
            if ($user) {
                $userId = $user['id'];
                $profil = $deplaceImg;
        
                $result = $requete->prepare('INSERT INTO conducteur(type_permi, nom_transport, information, profil, id_users) VALUES(:permis, :transport, :info, :profil, :id_users)');
                $result->bindParam(':permis', $typePermi, PDO::PARAM_STR);
                $result->bindParam(':transport', $typeTransport, PDO::PARAM_STR);
                $result->bindParam(':info', $information, PDO::PARAM_STR);
                $result->bindParam(':profil', $profil, PDO::PARAM_STR);
                $result->bindParam(':id_users', $userId, PDO::PARAM_INT);
                $result->execute();
                
                $_SESSION['success_message'] = "Informations ajoutées avec succès";
                header("Location: conducteur.php");
                exit();
            } else {
                $_SESSION['error_message'] = "Utilisateur non trouvé";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter mes informations</title>
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
            background: linear-gradient(135deg,rgba(243, 247, 245, 0.9),rgb(218, 221, 219, 0.9));
            color: white;
            padding: 80px 0 60px;
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
        .title-section {
            background: #2ecc71;
            padding: 8px 20px;
            border-radius: 0 50% 0 50%;
            color: #fff;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 20px;
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
        <img src="images/img_conduct.jpg" alt="Commentaires" class="header-image" data-aos="fade" data-aos-duration="1000">
        <div class="header-content">
            <h1 class="header-title" data-aos="fade-down" data-aos-duration="800">AJOUTER MES INFORMATIONS</h1>
            <p class="lead text-white" data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">Complétez votre profil professionnel</p>
        </div>
    </div>

    <div class="container">
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger" data-aos="fade-up" data-aos-duration="500">
                <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
            </div>
        <?php endif; ?>

        <div class="form-container">
            <div class="warning-note" data-aos="fade-right" data-aos-delay="300">
                <strong><i class="fas fa-exclamation-triangle"></i> Important :</strong> 
                Soyez précis dans vos informations. Ne déclarez pas des compétences que vous ne possédez pas.
            </div>

            <div class="form-card" data-aos="zoom-in" data-aos-delay="400">
                <h2 class="title-section text-center">Formulaire obligatoire</h2>
                
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="image" class="form-label">Image professionnelle</label>
                        <input type="file" class="form-control" id="image" name="image" required>
                        <small class="form-text">
                            Une photo professionnelle augmente votre crédibilité auprès des clients.
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="typePermi" class="form-label">Type de permis</label>
                        <select class="form-control" id="typePermi" name="typePermi" required>
                            <option value="">Sélectionnez votre permis</option>
                            <option value="Poid légère">Poid légère</option>
                            <option value="Poid moyen">Poid moyen</option>
                            <option value="Poid lourd">Poid lourd</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="typeTransport" class="form-label">Type de transport</label>
                        <select class="form-control" id="typeTransport" name="typeTransport" required>
                            <option value="">Sélectionnez votre véhicule</option>
                            <option value="Bus">Bus</option>
                            <option value="Mini-bus">Mini-bus</option>
                            <option value="7-Places">7-Places</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="information" class="form-label">Vos informations</label>
                        <textarea class="form-control" id="information" rows="5" 
                                  placeholder="Décrivez vos compétences et expériences..." name="information" required></textarea>
                        <small class="form-text">
                            <i class="fas fa-info-circle"></i> Détaillez vos compétences, votre expérience et vos qualités professionnelles. Les clients verront ces informations.
                        </small>
                    </div>

                    <button type="submit" name="conducteur" class="submit-btn">
                        <i class="fas fa-save"></i> Enregistrer mes informations
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