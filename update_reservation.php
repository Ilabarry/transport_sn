<?php
ob_start();
require_once "hfc/config.php";
require_once "hfc/header.php";
require_once "vendor/autoload.php"; // PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


// Vérification session
if (!isset($_SESSION['id_users'])) {
    header("Location: log/connexion.php");
    exit();
}

$id_reservation = $_GET['id'] ?? null;
if (!$id_reservation) {
    die("Réservation introuvable.");
}

$user_id = $_SESSION['id_users'];
$is_admin = $_SESSION['role'] ?? 'user';

// Récupération réservation
if ($is_admin === 'admin') {
    $stmt = $requete->prepare("SELECT * FROM reservation WHERE id = :id");
    $stmt->execute([':id' => $id_reservation]);
} else {
    $stmt = $requete->prepare("SELECT * FROM reservation WHERE id = :id AND id_users = :uid");
    $stmt->execute([':id' => $id_reservation, ':uid' => $user_id]);
}

$reservation = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$reservation) {
    die("Vous n'avez pas accès à cette réservation.");
}

// Traitement du formulaire
if (isset($_POST['update'])) {
    $depart = htmlspecialchars($_POST['depart']);
    $arriver = htmlspecialchars($_POST['arriver']);
    $date_time = htmlspecialchars($_POST['date_time']);
    $passengers = intval($_POST['passengers']);
    $transport_type = htmlspecialchars($_POST['transport_type']);
    $additional_info = htmlspecialchars($_POST['additional_info'] ?? '');

    // Mise à jour
    $requete->prepare("
        UPDATE reservation
        SET depart = :dep, arriver = :arr, date_time = :dt,
            passengers = :pass, transport_type = :trans, additional_info = :info
        WHERE id = :id
    ")->execute([
        ':dep' => $depart,
        ':arr' => $arriver,
        ':dt' => $date_time,
        ':pass' => $passengers,
        ':trans' => $transport_type,
        ':info' => $additional_info,
        ':id' => $id_reservation
    ]);

    // Récupérer les infos de l'utilisateur concerné
    $stmtUser = $requete->prepare("SELECT prenom, nom, email FROM users WHERE id = :id");
    $stmtUser->execute([':id' => $reservation['id_users']]);
    $userData = $stmtUser->fetch(PDO::FETCH_ASSOC);

    // Envoi e-mail
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = "barryila20@gmail.com";
        $mail->Password = "alnd yzka ehpw loziE";
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('barryila20@gmail.com', 'Service de Transport');
        $mail->addAddress($userData['email']);

        $mail->isHTML(true);
        $mail->Subject = "Modification de votre réservation";
        $mail->Body = "
            <div style='font-family: Arial, sans-serif; font-size: 14px; color: #333;'>
                <p>Bonjour <strong>{$userData['prenom']} {$userData['nom']}</strong>,</p>
                <p>Votre réservation a été <strong>modifiée</strong> avec succès. Voici les nouvelles informations :</p>
                <ul>
                    <li><strong>Départ :</strong> {$depart}</li>
                    <li><strong>Arrivée :</strong> {$arriver}</li>
                    <li><strong>Date :</strong> " . date('d/m/Y H:i', strtotime($date_time)) . "</li>
                    <li><strong>Nombre de passagers :</strong> {$passengers}</li>
                    <li><strong>Type de transport :</strong> {$transport_type}</li>
                </ul>
                <p>Informations supplémentaires : {$additional_info}</p>
                <p>Merci d'avoir choisi notre service.</p>
            </div>
        ";

        $mail->send();
    } catch (Exception $e) {
        error_log("Erreur email : " . $mail->ErrorInfo);
    }

    header("Location: afiche_profil_reservation.php?id=" . $user_id);
    exit();
}

?>


<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-success text-white">
            <h4 class="mb-0">Modifier la réservation</h4>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Lieu de départ</label>
                    <input type="text" name="depart" class="form-control" value="<?= htmlspecialchars($reservation['depart']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Lieu d'arrivée</label>
                    <input type="text" name="arriver" class="form-control" value="<?= htmlspecialchars($reservation['arriver']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Date et heure</label>
                    <input type="datetime-local" name="date_time" class="form-control" value="<?= date('Y-m-d\TH:i', strtotime($reservation['date_time'])) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nombre de passagers</label>
                    <input type="number" name="passengers" class="form-control" value="<?= $reservation['passengers'] ?>" min="1" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Type de transport</label>
                    <select name="transport_type" class="form-control" required>
                        <option value="Bus" <?= $reservation['transport_type'] === 'Bus' ? 'selected' : '' ?>>Bus</option>
                        <option value="Mini-bus" <?= $reservation['transport_type'] === 'Mini-bus' ? 'selected' : '' ?>>Mini-bus</option>
                        <option value="7-places" <?= $reservation['transport_type'] === '7-places' ? 'selected' : '' ?>>7-places</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Informations supplémentaires</label>
                    <textarea name="additional_info" class="form-control"><?= htmlspecialchars($reservation['additional_info']) ?></textarea>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="reservation.php" class="btn btn-secondary">Annuler</a>
                    <button type="submit" name="update" class="btn btn-success">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div><br>

<?php require_once "hfc/footer.php"; ?>
