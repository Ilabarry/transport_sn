
<?php
require "hfc/config.php";
require_once "hfc/header.php";
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_SESSION['id_users'])) {
    header("Location: log/connexion.php");
    exit(); 
}

if (isset($_POST['reservation'])) {
    // Récupération et sécurisation des données
    $depart = htmlspecialchars($_POST['depart']);
    $arriver = htmlspecialchars($_POST['arriver']);
    $date_time = htmlspecialchars($_POST['dateTime']);
    // Conversion des dates
        $now = new DateTime();
        $date_reservation = new DateTime($date_time);

        // Calcul de la différence
        $interval = $now->diff($date_reservation);
        $hoursDiff = ($interval->days * 24) + $interval->h;

        // Vérification des règles
        if ($date_reservation <= $now) {
            die("<div class='alert alert-danger text-center mt-4' role='alert'>🚫 La date du voyage doit être dans le futur.</div>");
        }

        if ($hoursDiff < 12) {
            die("<div class='alert alert-warning text-center mt-4' role='alert'>⏳ La réservation doit être faite au moins 12 heures avant le départ.</div>");
        }

        if ($hoursDiff > (4 * 24)) {
            die("<div class='alert alert-info text-center mt-4' role='alert'>📅 La réservation ne peut pas être faite plus de 4 jours à l'avance.</div>");
        }

    $passengers = intval($_POST['passengers']);
    $transport_type = htmlspecialchars($_POST['transport_type']);
    $additional_info = htmlspecialchars($_POST['additional_info'] ?? '');
    $email = htmlspecialchars($_SESSION['email'] ?? '');
    
    // Calcul du prix estimé (vous pouvez adapter cette logique selon vos besoins)
    $prix_estime = 0;
    switch($transport_type) {
        case 'Bus':
            $prix_estime = 5000 * ceil($passengers / 20); // Exemple: 5000 FCFA pour 20 passagers
            break;
        case 'Mini-bus':
            $prix_estime = 3500 * ceil($passengers / 10);
            break;
        case '7-places':
            $prix_estime = 6000 * ceil($passengers / 7);
            break;
    }

    // Initialisation de PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Vérification si l'utilisateur est connecté
        if(empty($email)) {
            header("Location: log/connexion.php?error=Merci de vous connecter avant de faire votre réservation");
            exit();
        }

        $query = $requete->prepare('SELECT id, prenom, nom FROM users WHERE email = :email');
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->execute();
        $user = $query->fetch(PDO::FETCH_ASSOC);
    
        if ($user) {
            $userId = $user['id'];
            $prenom = $user['prenom'];
            $nom = $user['nom'];
    
            // Insertion de la réservation
            $result = $requete->prepare('INSERT INTO reservation(depart, arriver, date_time, passengers, transport_type, additional_info, prix_estime, id_users) 
                                      VALUES(:dep, :arv, :dt, :pass, :trans, :info, :prix, :id_users)');
            $result->bindParam(':dep', $depart, PDO::PARAM_STR);
            $result->bindParam(':arv', $arriver, PDO::PARAM_STR);
            $result->bindParam(':dt', $date_time, PDO::PARAM_STR);
            $result->bindParam(':pass', $passengers, PDO::PARAM_INT);
            $result->bindParam(':trans', $transport_type, PDO::PARAM_STR);
            $result->bindParam(':info', $additional_info, PDO::PARAM_STR);
            $result->bindParam(':prix', $prix_estime, PDO::PARAM_STR);
            $result->bindParam(':id_users', $userId, PDO::PARAM_INT);
            $result->execute();
            
            $reservationId = $requete->lastInsertId();
            
            // Récupération des détails de la réservation
            $result = $requete->prepare('SELECT reservation.*, users.prenom, users.nom 
                                       FROM reservation 
                                       INNER JOIN users ON reservation.id_users = users.id 
                                       WHERE reservation.id = :reservationId');
            $result->bindParam(':reservationId', $reservationId, PDO::PARAM_INT);
            $result->execute();
            $monReservation = $result->fetch(PDO::FETCH_ASSOC);  

            if ($monReservation) { 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Confirmation de réservation</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image" href="senvoyagee.png">
    
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
        .confirmation-header {
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            margin-bottom: 40px;
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
        .confirmation-title {
            font-weight: 700;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.2);
        }
        .confirmation-container {
            max-width: 800px;
            margin: 0 auto 50px;
            padding: 0 20px;
        }
        .confirmation-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .confirmation-icon {
            font-size: 60px;
            color: #2ecc71;
            margin-bottom: 20px;
            text-align: center;
        }
        .confirmation-message {
            text-align: center;
            margin-bottom: 30px;
            font-size: 18px;
        }
        .reservation-details {
            margin: 25px 0;
        }
        .detail-item {
            display: flex;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        .detail-label {
            font-weight: 600;
            color: #2c3e50;
            min-width: 180px;
        }
        .detail-icon {
            color: #2ecc71;
            margin-right: 10px;
            width: 20px;
        }
        .action-buttons {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }
        .btn-modify {
            background: #f39c12;
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 30px;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
        }
        .btn-modify:hover {
            background: #e67e22;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            color: white;
        }
        .email-notice {
            text-align: center;
            margin-top: 20px;
            color: #7f8c8d;
            font-size: 14px;
        }
        @media (max-width: 768px) {
            .confirmation-header {
                height: 180px;
            }
            .detail-item {
                flex-direction: column;
            }
            .detail-label {
                min-width: auto;
                margin-bottom: 5px;
            }
        }
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
        .confirmation-header {
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            margin-bottom: 40px;
            position: relative;
        }
        /* ... (gardez le reste de votre CSS existant) ... */
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    
    <div class="confirmation-header">
        <a href="index.php" class="back-btn">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="confirmation-title">RÉSERVATION CONFIRMÉE</h1>
            <p class="lead">Merci d'avoir choisi notre service</p>
        </div>
    </div>

    <div class="confirmation-container">
        <div class="confirmation-card">
            <div class="confirmation-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            
            <div class="confirmation-message">
                <h3>Votre réservation a été enregistrée avec succès</h3>
                <p>Veuillez vérifier les détails ci-dessous</p>
            </div>
            
            <div class="reservation-details">
                <div class="detail-item">
                    <span class="detail-icon"><i class="fas fa-user"></i></span>
                    <span class="detail-label">Passager :</span>
                    <span><?php echo htmlspecialchars($monReservation['prenom']) . ' ' . htmlspecialchars($monReservation['nom']); ?></span>
                </div>
                
                <div class="detail-item">
                    <span class="detail-icon"><i class="fas fa-map-marker-alt"></i></span>
                    <span class="detail-label">Départ :</span>
                    <span><?php echo htmlspecialchars($monReservation['depart']); ?></span>
                </div>
                
                <div class="detail-item">
                    <span class="detail-icon"><i class="fas fa-map-marker-alt"></i></span>
                    <span class="detail-label">Destination :</span>
                    <span><?php echo htmlspecialchars($monReservation['arriver']); ?></span>
                </div>
                
                <div class="detail-item">
                    <span class="detail-icon"><i class="fas fa-calendar-alt"></i></span>
                    <span class="detail-label">Date et heure :</span>
                    <span><?php echo date('d/m/Y H:i', strtotime($monReservation['date_time'])); ?></span>
                </div>
                
                <div class="detail-item">
                    <span class="detail-icon"><i class="fas fa-users"></i></span>
                    <span class="detail-label">Nombre de passagers :</span>
                    <span><?php echo htmlspecialchars($monReservation['passengers']); ?></span>
                </div>
                
                <div class="detail-item">
                    <span class="detail-icon"><i class="fas fa-bus"></i></span>
                    <span class="detail-label">Type de transport :</span>
                    <span><?php echo htmlspecialchars($monReservation['transport_type']); ?></span>
                </div>
                
                <div class="detail-item">
                    <span class="detail-icon"><i class="fas fa-money-bill-wave"></i></span>
                    <span class="detail-label">Prix estimé :</span>
                    <span><?php echo number_format($monReservation['prix_estime'] * $monReservation['passengers'], 0, ',', ' '); ?> FCFA</span>
                </div>

                <?php
                    $id_reservation = (int)$monReservation['id'];
                    $prix_estime = (int)($monReservation['prix_estime'] ?? 0);

                    $payUrl = 'paiement/paiement.php?' . http_build_query([
                        'id_reservation' => $id_reservation,
                        'montant' => $prix_estime,
                    ]);

                    echo '<a href="'.$payUrl.'" class="btn btn-success">Procéder au paiement</a>';
                ?>

                
                <?php if(!empty($monReservation['additional_info'])): ?>
                <div class="detail-item">
                    <span class="detail-icon"><i class="fas fa-info-circle"></i></span>
                    <span class="detail-label">Informations supplémentaires :</span>
                    <span><?php echo htmlspecialchars($monReservation['additional_info']); ?></span>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="action-buttons">
                <a href="update_reservation.php?id=<?php echo $monReservation['id']; ?>" class="btn-modify">
                    <i class="fas fa-edit mr-2"></i> Modifier la réservation
                </a>
            </div>
            
            <div class="email-notice">
                <i class="fas fa-envelope"></i> Un email de confirmation vous a été envoyé à <?php echo htmlspecialchars($email); ?>
            </div>
        </div>
    </div>

    <?php require_once "hfc/footer.php"; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animation pour l'apparition de la carte
        document.addEventListener('DOMContentLoaded', function() {
            const card = document.querySelector('.confirmation-card');
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'all 0.5s ease-out';
            
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 200);
        });
    </script>
</body>
</html>
<?php
                // Envoi de l'email de confirmation
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

                    $mail->isHTML(true);
                    $mail->Subject = 'Confirmation de réservation - Service de Transport';
                    $mail->Body = "
                    <!DOCTYPE html>
                    <html>
                    <head>
                    <meta charset='UTF-8'>
                    <style>
                        body { font-family: Arial, sans-serif; background-color: #f8f9fa; color: #333; margin: 0; padding: 0; }
                        .container { max-width: 600px; margin: auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
                        .header { background: linear-gradient(135deg, #2ecc71, #27ae60); padding: 20px; color: white; text-align: center; }
                        .header h2 { margin: 0; }
                        .content { padding: 20px; }
                        .content h3 { color: #2c3e50; }
                        .details { background: #f4f6f7; padding: 15px; border-radius: 5px; margin-top: 15px; }
                        .details p { margin: 8px 0; }
                        .footer { text-align: center; font-size: 12px; color: #7f8c8d; padding: 15px; background: #ecf0f1; }
                    </style>
                    </head>
                    <body>
                        <div class='container'>
                            <div class='header'>
                                <h2>Confirmation de votre réservation</h2>
                            </div>
                            <div class='content'>
                                <p>Bonjour <strong>$prenom $nom</strong>,</p>
                                <p>Votre réservation a été enregistrée avec succès.</p>
                                <div class='details'>
                                    <p><strong>Départ :</strong> $depart</p>
                                    <p><strong>Destination :</strong> $arriver</p>
                                    <p><strong>Date et heure :</strong> ".date('d/m/Y H:i', strtotime($date_time))."</p>
                                    <p><strong>Passagers :</strong> $passengers</p>
                                    <p><strong>Type de transport :</strong> $transport_type</p>
                                    <p><strong>Prix estimé :</strong> ".number_format($prix_estime * $passengers, 0, ',', ' ')." FCFA</p>"
                                    .(!empty($additional_info) ? "<p><strong>Infos supplémentaires :</strong> $additional_info</p>" : "")."
                                </div>
                                <p style='margin-top:15px;'>Merci d'avoir choisi notre service de transport.</p>
                            </div>
                            <div class='footer'>
                                &copy; ".date('Y')." Service de Transport - Tous droits réservés
                            </div>
                        </div>
                    </body>
                    </html>
                    ";
                    $mail->send();
                } catch (Exception $e) {
                    echo "<script>console.error('Erreur d'envoi d\'email: {$mail->ErrorInfo}')</script>";
                }
            }
        } else {
            header("Location: log/connexion.php?error=Merci de vous connecter avant de faire votre réservation");
            exit();
        }
    } catch (PDOException $e) {
        echo "<div class='container text-center py-5'>
                <i class='fas fa-exclamation-circle fa-3x text-danger mb-3'></i>
                <h3 class='mb-3'>Une erreur est survenue lors de la réservation</h3>
                <p class='text-muted'>".htmlspecialchars($e->getMessage())."</p>
                <a href='reservation.php' class='btn btn-primary'>Retour</a>
              </div>";
    }
} else {
    header("Location: reservation.php");
    exit();
}
?>