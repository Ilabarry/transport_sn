<?php
require "hfc/config.php";
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

// Vérification de la connexion
if (!isset($_SESSION["email"])) {
    header("Location: log/connexion.php");
    exit();
}

// Vérification de l'ID de réservation
if (!isset($_GET['id'])) {
    $_SESSION['error_message'] = "ID de réservation manquant";
    header("Location: reservation.php");
    exit();
}

$upid = intval($_GET['id']);
if ($upid <= 0) {
    $_SESSION['error_message'] = "ID de réservation invalide";
    header("Location: reservation.php");
    exit();
}

// Traitement de la mise à jour
if (isset($_POST['update'])) {
    // Validation et sécurisation des données
    $depart = htmlspecialchars(trim($_POST['depart']));
    $arriver = htmlspecialchars(trim($_POST['arriver']));
    $date_time = htmlspecialchars(trim($_POST['dateTime']));
    $rithme = htmlspecialchars($_POST['rithme'] ?? '');
    $moinsPransport = isset($_POST['moinsPransport']) && is_array($_POST['moinsPransport']) 
                    ? implode(', ', $_POST['moinsPransport']) 
                    : '';

    // Récupération des infos utilisateur pour l'email
    $email = $_SESSION['email'];
    $query = $requete->prepare('SELECT id, prenom, nom FROM users WHERE email = :email');
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->execute();
    $user = $query->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        try {
            // Mise à jour de la réservation
            $sql = $requete->prepare('UPDATE reservation SET depart=:dep, arriver=:arv, date_time=:dt, rithme=:rit, moins_transport=:trans WHERE id=:uid');
            $sql->bindParam(':dep', $depart, PDO::PARAM_STR);
            $sql->bindParam(':arv', $arriver, PDO::PARAM_STR);
            $sql->bindParam(':dt', $date_time, PDO::PARAM_STR);
            $sql->bindParam(':rit', $rithme, PDO::PARAM_STR);
            $sql->bindParam(':trans', $moinsPransport, PDO::PARAM_STR);
            $sql->bindParam(':uid', $upid, PDO::PARAM_INT);
            $sql->execute();

            // Envoi de l'email de confirmation
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = "barryila20@gmail.com";
                $mail->Password = "alnd yzka ehpw lozi";
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('barryila20@gmail.com', 'Service de Transport');
                $mail->addAddress($email);

                $mail->isHTML(false);
                $mail->Subject = 'Mise à jour de votre réservation';
                $mail->Body = "Bonjour {$user['prenom']} {$user['nom']},\n\n";
                $mail->Body .= "Votre réservation a été mise à jour avec succès.\n\n";
                $mail->Body .= "Détails de la réservation :\n";
                $mail->Body .= "- Départ : $depart\n";
                $mail->Body .= "- Arrivée : $arriver\n";
                $mail->Body .= "- Date et heure : $date_time\n";
                $mail->Body .= "- Rythme : $rithme\n";
                $mail->Body .= "- Modes de transport : $moinsPransport\n\n";
                $mail->Body .= "Merci d'avoir utilisé notre service.";

                $mail->send();
                $_SESSION['success_message'] = "Réservation mise à jour avec succès. Un email de confirmation a été envoyé.";
            } catch (Exception $e) {
                $_SESSION['warning_message'] = "Réservation mise à jour mais l'envoi de l'email a échoué : " . $e->getMessage();
            }

            header("Location: reservation.php");
            exit();
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Erreur lors de la mise à jour : " . $e->getMessage();
            header("Location: update_reservation.php?id=$upid");
            exit();
        }
    }
}

// Récupération des données actuelles de la réservation
try {
    $sql = $requete->prepare('SELECT depart, arriver, date_time, rithme, moins_transport FROM reservation WHERE id = :uid');
    $sql->bindParam(':uid', $upid, PDO::PARAM_INT);
    $sql->execute();
    $reservation = $sql->fetch(PDO::FETCH_OBJ);

    if (!$reservation) {
        $_SESSION['error_message'] = "Réservation introuvable";
        header("Location: reservation.php");
        exit();
    }
} catch (PDOException $e) {
    $_SESSION['error_message'] = "Erreur lors de la récupération des données : " . $e->getMessage();
    header("Location: reservation.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier ma réservation</title>
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
        .section-title {
            color: #2ecc71;
            text-align: center;
            margin: 25px 0;
            font-weight: 600;
        }
        .transport-options {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 25px;
        }
        .transport-option {
            flex: 1 1 200px;
        }
        .transport-icon {
            margin-right: 8px;
            color: #2ecc71;
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
    <?php require_once "hfc/header.php"; ?>

    <div class="header-section">
        <div class="container">
            <h1 class="header-title">Modifier ma réservation</h1>
            <p>Mettez à jour les détails de votre voyage</p>
        </div>
    </div>

    <div class="container">
        <div class="form-container">
            <div class="form-card">
                <form action="update_reservation.php?id=<?php echo $upid; ?>" method="POST">
                    <div class="form-group">
                        <label for="depart" class="form-label">Lieu de départ</label>
                        <input type="text" class="form-control" id="depart" name="depart" 
                               placeholder="Adresse de départ" value="<?php echo htmlspecialchars($reservation->depart); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="arriver" class="form-label">Lieu d'arrivée</label>
                        <input type="text" class="form-control" id="arriver" name="arriver" 
                               placeholder="Adresse d'arrivée" value="<?php echo htmlspecialchars($reservation->arriver); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="dateTime" class="form-label">Date et heure de départ</label>
                        <input type="datetime-local" class="form-control" id="dateTime" name="dateTime" 
                               value="<?php echo htmlspecialchars($reservation->date_time); ?>" required>
                    </div>

                    <h4 class="section-title">Mon profil voyageur</h4>

                    <div class="form-group">
                        <label class="form-label">Rythme de marche</label>
                        <div class="transport-options">
                            <div class="transport-option">
                                <input type="radio" id="lentMarche" name="rithme" value="Lent-marche" 
                                       <?php echo ($reservation->rithme == "Lent-marche") ? "checked" : ""; ?>>
                                <label for="lentMarche">Lent (3 km/h)</label>
                            </div>
                            <div class="transport-option">
                                <input type="radio" id="moyenMarche" name="rithme" value="Moyen-marche" 
                                       <?php echo ($reservation->rithme == "Moyen-marche") ? "checked" : ""; ?>>
                                <label for="moyenMarche">Moyen (4 km/h)</label>
                            </div>
                            <div class="transport-option">
                                <input type="radio" id="rapideMarche" name="rithme" value="Rapide-marche" 
                                       <?php echo ($reservation->rithme == "Rapide-marche") ? "checked" : ""; ?>>
                                <label for="rapideMarche">Rapide (5 km/h)</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Rythme de cyclisme</label>
                        <div class="transport-options">
                            <div class="transport-option">
                                <input type="radio" id="lentCyclisme" name="rithme" value="Lent-cyclisme" 
                                       <?php echo ($reservation->rithme == "Lent-cyclisme") ? "checked" : ""; ?>>
                                <label for="lentCyclisme">Lent (10 km/h)</label>
                            </div>
                            <div class="transport-option">
                                <input type="radio" id="moyenCyclisme" name="rithme" value="Moyen-cyclisme" 
                                       <?php echo ($reservation->rithme == "Moyen-cyclisme") ? "checked" : ""; ?>>
                                <label for="moyenCyclisme">Moyen (15 km/h)</label>
                            </div>
                            <div class="transport-option">
                                <input type="radio" id="rapideCyclisme" name="rithme" value="Rapide-cyclisme" 
                                       <?php echo ($reservation->rithme == "Rapide-cyclisme") ? "checked" : ""; ?>>
                                <label for="rapideCyclisme">Rapide (20 km/h)</label>
                            </div>
                        </div>
                    </div>

                    <h4 class="section-title">Modes de transport souhaités</h4>

                    <div class="form-group">
                        <div class="transport-options">
                            <?php
                            $currentTransports = explode(', ', $reservation->moins_transport);
                            $transportOptions = [
                                'Bus' => ['icon' => 'bus', 'label' => 'Bus'],
                                'Mini-bus' => ['icon' => 'car', 'label' => 'Mini-bus'],
                                '7-places' => ['icon' => 'taxi', 'label' => '7-places']
                            ];
                            
                            foreach ($transportOptions as $value => $option) {
                                $checked = in_array($value, $currentTransports) ? 'checked' : '';
                                echo "
                                <div class='form-check transport-option'>
                                    <input class='form-check-input' type='checkbox' name='moinsPransport[]' id='transport$value' value='$value' $checked>
                                    <label class='form-check-label' for='transport$value'>
                                        <i class='fas fa-{$option['icon']} transport-icon'></i> {$option['label']}
                                    </label>
                                </div>";
                            }
                            ?>
                        </div>
                    </div>

                    <button type="submit" name="update" class="submit-btn">
                        <i class="fas fa-save"></i> Enregistrer les modifications
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