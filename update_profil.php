<?php
require "hfc/config.php";

// Vérification de la connexion
// if (!isset($_SESSION["email"])) {
//     header("Location: log/connexion.php");
//     exit();
// }

// Traitement de la mise à jour
if (isset($_POST['update'])) {
    if (!isset($_GET['id'])) {
        $_SESSION['error_message'] = "ID non spécifié";
        header("Location: profil.php");
        exit();
    }

    $upid = intval($_GET['id']);
    if ($upid <= 0) {
        $_SESSION['error_message'] = "ID invalide";
        header("Location: profil.php");
        exit();
    }

    // Validation et sécurisation des données
    $prenom = htmlspecialchars(trim($_POST['prenom']));
    $nom = htmlspecialchars(trim($_POST['nom']));
    $email = htmlspecialchars(trim($_POST['email']));
    $ville_origine = htmlspecialchars(trim($_POST['ville_origine']));
    $ville_domicile = htmlspecialchars(trim($_POST['ville_domicille']));
    $telephone = htmlspecialchars(trim($_POST['telephone']));
    $role = htmlspecialchars($_POST['role']);

    try {
        // Vérification que l'utilisateur existe
        $sql = $requete->prepare('SELECT id FROM users WHERE id = :id');
        $sql->bindParam(':id', $upid, PDO::PARAM_INT);
        $sql->execute();
        
        if ($sql->rowCount() === 0) {
            $_SESSION['error_message'] = "Utilisateur non trouvé";
            header("Location: profil.php");
            exit();
        }

        // Mise à jour du profil
        $sql = $requete->prepare('UPDATE users SET prenom=:pre, nom=:nm, email=:eml, ville_origine=:vio, ville_actuelle=:via, telephone=:tel, role=:rl WHERE id=:uid');
        $sql->bindParam(':pre', $prenom, PDO::PARAM_STR);
        $sql->bindParam(':nm', $nom, PDO::PARAM_STR);
        $sql->bindParam(':eml', $email, PDO::PARAM_STR);
        $sql->bindParam(':vio', $ville_origine, PDO::PARAM_STR);
        $sql->bindParam(':via', $ville_domicile, PDO::PARAM_STR);
        $sql->bindParam(':tel', $telephone, PDO::PARAM_STR);
        $sql->bindParam(':rl', $role, PDO::PARAM_STR);
        $sql->bindParam(':uid', $upid, PDO::PARAM_INT);
        
        if ($sql->execute()) {
            $_SESSION['success_message'] = "Profil modifié avec succès";
            header("Location: profil.php");
            exit();
        } else {
            $_SESSION['error_message'] = "Erreur lors de la mise à jour";
            header("Location: profil.php");
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Erreur technique : " . $e->getMessage();
        header("Location: profil.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier mon profil</title>
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
        .profile-header {
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            color: white;
            padding: 30px 0;
            margin-bottom: 30px;
            text-align: center;
        }
        .profile-title {
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
        .form-row {
            display: flex;
            flex-wrap: wrap;
            margin-right: -15px;
            margin-left: -15px;
        }
        .form-col {
            flex: 0 0 50%;
            max-width: 50%;
            padding-right: 15px;
            padding-left: 15px;
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
            .form-col {
                flex: 0 0 100%;
                max-width: 100%;
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
    <?php require_once "hfc/header.php"; ?>

    <div class="profile-header">
        <div class="container">
            <h1 class="profile-title">Modifier mon profil</h1>
            <p>Mettez à jour vos informations personnelles</p>
        </div>
    </div>

    <div class="container">
        <div class="form-container">
            <div class="form-card">
                <?php
                if (!isset($_GET['id'])) {
                    echo '<div class="alert alert-danger">Aucun ID spécifié</div>';
                    exit();
                }

                $upid = intval($_GET['id']);
                if ($upid <= 0) {
                    echo '<div class="alert alert-danger">ID invalide</div>';
                    exit();
                }

                try {
                    $sql = $requete->prepare('SELECT id, prenom, nom, email, ville_origine, ville_actuelle, telephone, role FROM users WHERE id = :uid');
                    $sql->bindParam(':uid', $upid, PDO::PARAM_INT);
                    $sql->execute();
                    $result = $sql->fetch(PDO::FETCH_OBJ);

                    if (!$result) {
                        echo '<div class="alert alert-danger">Utilisateur non trouvé</div>';
                        exit();
                    }
                ?>
                <form action="update_profil.php?id=<?php echo $result->id; ?>" method="POST">
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="prenom" class="form-label">Prénom</label>
                                <input type="text" class="form-control" id="prenom" placeholder="Votre prénom" 
                                       name="prenom" value="<?php echo htmlspecialchars($result->prenom); ?>" required>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label for="nom" class="form-label">Nom</label>
                                <input type="text" class="form-control" id="nom" placeholder="Votre nom de famille" 
                                       name="nom" value="<?php echo htmlspecialchars($result->nom); ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Adresse e-mail</label>
                        <input type="email" class="form-control" id="email" placeholder="exemple@email.com" 
                               name="email" value="<?php echo htmlspecialchars($result->email); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="ville_origine" class="form-label">Ville d'origine</label>
                        <input type="text" class="form-control" id="ville_origine" placeholder="Votre région natale" 
                               name="ville_origine" value="<?php echo htmlspecialchars($result->ville_origine); ?>">
                    </div>

                    <div class="form-group">
                        <label for="ville_domicille" class="form-label">Ville actuelle</label>
                        <input type="text" class="form-control" id="ville_domicille" placeholder="Votre région de domicile" 
                               name="ville_domicille" value="<?php echo htmlspecialchars($result->ville_actuelle); ?>">
                    </div>

                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="telephone" class="form-label">Numéro de téléphone</label>
                                <input type="text" class="form-control" id="telephone" placeholder="7x xxx xx xx" 
                                       name="telephone" value="<?php echo htmlspecialchars($result->telephone); ?>">
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label for="role" class="form-label">Statut</label>
                                <select id="role" class="form-control" name="role" required>
                                    <option value="client" <?php echo ($result->role == "client") ? "selected" : ""; ?>>Client</option>
                                    <option value="conducteur" <?php echo ($result->role == "conducteur") ? "selected" : ""; ?>>Conducteur</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <button type="submit" name="update" class="submit-btn">
                        <i class="fas fa-save"></i> Enregistrer les modifications
                    </button>
                </form>
                <?php
                } catch (PDOException $e) {
                    echo '<div class="alert alert-danger">Erreur lors de la récupération des données : ' . htmlspecialchars($e->getMessage()) . '</div>';
                }
                ?>
            </div>
        </div>
    </div>

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