<?php
require_once '../hfc/config.php';
session_start();

// Validation et sécurisation
$id_reservation = filter_input(INPUT_GET, 'id_reservation', FILTER_VALIDATE_INT);
if ($id_reservation <= 0 || !isset($_SESSION['id_users'])) {
    header("Location: ../log/connexion.php");
    exit();
}

// Récupération réservation
try {
    $stmt = $requete->prepare("
        SELECT r.*, u.prenom, u.nom, u.email, u.telephone 
        FROM reservation r
        INNER JOIN users u ON r.id_users = u.id
        WHERE r.id = :id AND u.id = :uid
    ");
    $stmt->execute([':id' => $id_reservation, ':uid' => $_SESSION['id_users']]);
    $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$reservation) {
        die("Réservation introuvable ou non autorisée.");
    }

    // Formatage du montant
    $montant_total = number_format($reservation['prix_estime'] * $reservation['passengers'], 0, ',', ' ');

} catch (PDOException $e) {
    error_log("Erreur base de données: " . $e->getMessage());
    die("Une erreur est survenue lors de la récupération des informations.");
}

// --- recuperation du numero ---
    $numero = $reservation['numero_telephone'] ?? null;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de paiement | HFC</title>
    <link rel="icon" type="image" href="senvoyagee.png">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2e7d32;
            --secondary-color: #4caf50;
            --light-bg: #f8f9fa;
            --dark-text: #212529;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-bg);
        }
        
        .confirmation-container {
            max-width: 600px;
            margin: 3rem auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .confirmation-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .confirmation-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            color: #fff;
            animation: bounce 1s;
        }
        
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {transform: translateY(0);}
            40% {transform: translateY(-20px);}
            60% {transform: translateY(-10px);}
        }
        
        .confirmation-body {
            padding: 2rem;
        }
        
        .detail-item {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 8px;
            background-color: #f8f9fa;
        }
        
        .btn-dashboard {
            background: var(--primary-color);
            color: white;
            padding: 0.8rem 2rem;
            border-radius: 50px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-dashboard:hover {
            background: var(--secondary-color);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(46, 125, 50, 0.3);
            color: white;
        }
        
        .receipt {
            border: 1px dashed #ddd;
            border-radius: 10px;
            padding: 1.5rem;
            margin-top: 2rem;
        }
        
        .alert-sms {
            border-left: 4px solid var(--primary-color);
        }
    </style>
</head>
<body>
    <div class="confirmation-container">
        <div class="confirmation-header">
            <div class="confirmation-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2>Paiement confirmé !</h2>
            <p class="mb-0">Merci pour votre confiance</p>
        </div>
        
        <div class="confirmation-body">
            <?php if ($numero && isset($result)): ?>
            <div class="alert alert-success alert-sms mb-4">
                <i class="fas fa-sms"></i> Un SMS de confirmation a été envoyé à votre numéro.
            </div>
            <?php endif; ?>
            
            <div class="text-center mb-4">
                <h4 class="fw-bold">Votre réservation est confirmée</h4>
                <p>Nous avons envoyé les détails à <?= htmlspecialchars($reservation['numero_telephone']) ?></p>
            </div>
            
            <div class="receipt">
                <h5 class="text-center mb-4"><i class="fas fa-receipt"></i> Reçu de paiement</h5>
                
                <div class="detail-item">
                    <div class="d-flex justify-content-between">
                        <span>Référence:</span>
                        <strong>#<?= htmlspecialchars($reservation['id']) ?></strong>
                    </div>
                </div>
                
                <div class="detail-item">
                    <div class="d-flex justify-content-between">
                        <span>Client:</span>
                        <strong><?= htmlspecialchars($reservation['prenom'] . ' ' . $reservation['nom']) ?></strong>
                    </div>
                </div>
                
                <div class="detail-item">
                    <div class="d-flex justify-content-between">
                        <span>Trajet:</span>
                        <strong><?= htmlspecialchars($reservation['depart']) ?> → <?= htmlspecialchars($reservation['arriver']) ?></strong>
                    </div>
                </div>
                
                <div class="detail-item">
                    <div class="d-flex justify-content-between">
                        <span>Date:</span>
                        <strong><?= date('d/m/Y H:i', strtotime($reservation['date_time'])) ?></strong>
                    </div>
                </div>
                
                <div class="detail-item">
                    <div class="d-flex justify-content-between">
                        <span>Passagers:</span>
                        <strong><?= htmlspecialchars($reservation['passengers']) ?></strong>
                    </div>
                </div>
                
                <div class="detail-item bg-primary text-white">
                    <div class="d-flex justify-content-between">
                        <span>Montant payé:</span>
                        <strong><?= $montant_total ?> FCFA</strong>
                    </div>
                </div>
                
                <?php if (!empty($reservation['numero_telephone'])): ?>
                <div class="detail-item">
                    <div class="d-flex justify-content-between">
                        <span>Numéro utilisé:</span>
                        <strong><?= htmlspecialchars($reservation['numero_telephone']) ?></strong>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="d-flex flex-column flex-md-row justify-content-center mt-5 gap-3">
                <a href="../profil.php" class="btn btn-dashboard">
                    <i class="fas fa-user"></i> Mon profil
                </a>
                <a href="#" class="btn btn-outline-secondary" onclick="window.print()">
                    <i class="fas fa-print"></i> Imprimer
                </a>
                <a href="whatsapp://send?text=Je viens de confirmer ma réservation HFC #<?= $reservation['id'] ?>" 
                   class="btn btn-success">
                   <i class="fab fa-whatsapp"></i> Partager
                </a>
            </div>
            
            <div class="text-center mt-4 text-muted">
                <small><i class="fas fa-info-circle"></i> Conservez ce reçu comme preuve de paiement.</small>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animation pour les boutons
        document.querySelectorAll('.btn').forEach(button => {
            button.addEventListener('mouseenter', function() {
                const icon = this.querySelector('i');
                if (icon) icon.style.transform = 'translateX(5px)';
            });
            button.addEventListener('mouseleave', function() {
                const icon = this.querySelector('i');
                if (icon) icon.style.transform = 'translateX(0)';
            });
        });
    </script>
</body>
</html>