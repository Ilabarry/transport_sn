<?php
require_once '../hfc/config.php';
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id_users'])) {
    header("Location: ../log/connexion.php");
    exit();
}

// Sécurisation de l'ID réservation
$id_reservation = isset($_GET['id_reservation']) ? (int) $_GET['id_reservation'] : 0;
if ($id_reservation <= 0) {
    die("Réservation invalide !");
}

// Récupération de la réservation et de l'utilisateur
$stmt = $requete->prepare("
    SELECT r.*, u.prenom, u.nom 
    FROM reservation r
    INNER JOIN users u ON r.id_users = u.id
    WHERE r.id = :id AND u.id = :uid
");
$stmt->execute([
    ':id' => $id_reservation,
    ':uid' => $_SESSION['id_users']
]);
$reservation = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$reservation) {
    die("Réservation introuvable ou non autorisée !");
}

// Calcul du montant total
$montant_total = number_format($reservation['prix_estime'] * $reservation['passengers'], 2, ',', ' ');

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero = trim($_POST['numero']);
    
    // Validation numéro (Sénégal: commence par 77, 78, 76, 70)
    if (!preg_match('/^(77|78|76|70)[0-9]{7}$/', $numero)) {
        $error = "Numéro de téléphone invalide. Format attendu: 77XXXXXXX";
    } else {

        // Mettre à jour le statut de paiement ET le numéro de téléphone
        $update = $requete->prepare("
            UPDATE reservation 
            SET status_paiement = 'payé',
                numero_telephone = :numero
            WHERE id = :id AND id_users = :uid
        ");
        $update->execute([
            ':numero' => $numero,
            ':id'  => $id_reservation,
            ':uid' => $_SESSION['id_users']
        ]);
        
        // Redirection vers la page de confirmation
        header("Location: confirmation_paiement.php?id_reservation=".$id_reservation);
        exit();
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement Orange Money | HFC</title>
    <link rel="icon" type="image" href="senvoyagee.png">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #ff7900; /* Orange Money */
            --secondary-color: #e66900;
            --accent-color: #ff9e40;
            --light-bg: #f8f9fa;
            --dark-text: #212529;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-bg);
            color: var(--dark-text);
        }
        
        .payment-container {
            max-width: 600px;
            margin: 2rem auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .payment-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .payment-title {
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .reservation-id {
            background: rgba(255, 255, 255, 0.2);
            padding: 0.3rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            display: inline-block;
        }
        
        .payment-body {
            padding: 2rem;
        }
        
        .amount-display {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
            text-align: center;
            margin: 1.5rem 0;
        }
        
        .orange-logo {
            text-align: center;
            margin: 1rem 0;
        }
        
        .orange-logo img {
            max-width: 180px;
        }
        
        .btn-orange {
            background: var(--primary-color);
            color: white;
            padding: 0.8rem 2rem;
            border-radius: 50px;
            font-weight: 500;
            border: none;
            transition: all 0.3s ease;
            width: 100%;
        }
        
        .btn-orange:hover {
            background: var(--secondary-color);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(255, 121, 0, 0.3);
        }
        
        .resume-item {
            padding: 1rem 0;
            border-bottom: 1px solid #eee;
        }
        
        .resume-item:last-child {
            border-bottom: none;
        }
        
        .security-badge {
            text-align: center;
            margin-top: 2rem;
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(255, 121, 0, 0.25);
        }
        
        .orange-badge {
            background-color: #fff3e8;
            color: var(--primary-color);
            padding: 0.5rem;
            border-radius: 5px;
            font-size: 0.8rem;
            text-align: center;
            margin: 1rem 0;
        }
    </style>
</head>
<body>
    <div class="container mt-3">
        <a href="../index.php" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Retour à la plate-forme
        </a>
    </div>

    <div class="payment-container">
        <div class="payment-header">
            <h1 class="payment-title"><i class="fas fa-wallet me-2"></i>Paiement par Orange Money</h1>
            <div class="reservation-id">Réservation #<?= htmlspecialchars($reservation['id']) ?></div>
        </div>
        
        <div class="payment-body">
            <div class="orange-logo">
                <img src="img/orange.webp" alt="Orange Money" class="img-fluid">
            </div>
            
            <div class="orange-badge">
                <i class="fas fa-info-circle"></i> Vous recevrez un code USSD pour confirmer le paiement
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <h4 class="fw-bold mb-3"><i class="fas fa-receipt me-2"></i>Détails de la réservation</h4>
                    
                    <div class="resume-item">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Client :</span>
                            <strong><?= htmlspecialchars($reservation['prenom'].' '.htmlspecialchars($reservation['nom'])) ?></strong>
                        </div>
                    </div>
                    
                    <div class="resume-item">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Trajet :</span>
                            <strong><?= htmlspecialchars($reservation['depart']) ?> → <?= htmlspecialchars($reservation['arriver']) ?></strong>
                        </div>
                    </div>
                    
                    <div class="resume-item">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Passagers :</span>
                            <strong><?= htmlspecialchars($reservation['passengers']) ?> personne(s)</strong>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="text-center text-muted mb-2">Montant total à payer</div>
                    <div class="amount-display">
                        <?= $montant_total ?> FCFA
                    </div>
                </div>
            </div>
            
            <hr class="my-4">
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="mb-3">
                    <label for="numero" class="form-label fw-bold"><i class="fas fa-phone me-2"></i>Numéro Orange Money</label>
                    <input type="tel" class="form-control form-control-lg" id="numero" name="numero" 
                           placeholder="77XXXXXXX" required pattern="(77|78|76|70)[0-9]{7}">
                    <div class="form-text">Entrez votre numéro Orange Money (format: 77XXXXXXX)</div>
                </div>
                
                <button type="submit" class="btn btn-orange mb-3">
                    <i class="fas fa-check-circle me-2"></i> Confirmer le paiement
                </button>
            </form>
            
            <div class="security-badge">
                <i class="fas fa-lock me-2"></i>Transactions 100% sécurisées - HFC ne conserve pas vos informations
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animation pour le bouton de paiement
        document.querySelector('.btn-orange').addEventListener('mouseenter', function() {
            this.querySelector('i').style.transform = 'translateX(5px)';
        });
        document.querySelector('.btn-orange').addEventListener('mouseleave', function() {
            this.querySelector('i').style.transform = 'translateX(0)';
        });
        
        // Formatage automatique du numéro
        document.getElementById('numero').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    </script>
</body>
</html>