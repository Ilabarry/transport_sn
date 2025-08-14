<?php
require_once '../hfc/config.php';

$id_reservation = $_GET['id_reservation'] ?? null;
$montant = $_GET['montant'] ?? null;

// Vérification stricte
if ($id_reservation === null || $montant === null || $id_reservation === '' || $montant === '') {
    die("Paramètres manquants !");
}

// Récupération de la réservation
$stmt = $requete->prepare("SELECT * FROM reservation WHERE id = ?");
$stmt->execute([$id_reservation]);
$reservation = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$reservation) {
    die("Réservation introuvable !");
}

// Calcul du montant total
$montant_total = number_format((float)$reservation['prix_estime'] * $reservation['passengers'], 2, ',', ' ');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement de votre réservation | HFC</title>
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
            --accent-color: #8bc34a;
            --light-bg: #f8f9fa;
            --dark-text: #212529;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-bg);
            color: var(--dark-text);
        }
        
        .payment-container {
            max-width: 75%;
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
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            text-align: center;
            margin: 1.5rem 0;
        }
        
        .payment-method {
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }
        
        .payment-method:hover {
            border-color: var(--accent-color);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        .payment-method .icon {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        
        .btn-payment {
            background: var(--primary-color);
            color: white;
            padding: 0.8rem 2rem;
            border-radius: 50px;
            font-weight: 500;
            border: none;
            transition: all 0.3s ease;
            width: 100%;
        }
        
        .btn-payment:hover {
            background: var(--secondary-color);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(46, 125, 50, 0.3);
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
        .payment-card {
        border-radius: 12px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        margin-bottom: 20px;
    }
    
    .payment-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
    }
    
    .card-deck .card {
        min-width: 250px;
    }
    
    @media (max-width: 768px) {
        .card-deck {
            display: flex;
            flex-direction: column;
        }
    }
    </style>
</head>
<body>
    <div class="payment-container">
        <div class="payment-header">
            <a href="../profil.php">retour</a>
            <h1 class="payment-title"><i class="fas fa-check-circle me-2"></i>Finalisez votre paiement</h1>
            <div class="reservation-id">Réservation #<?= htmlspecialchars($reservation['id']); ?></div>
        </div>
        
        <div class="payment-body">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="fw-bold mb-3"><i class="fas fa-receipt me-2"></i>Détails de la réservation</h4>
                    
                    <div class="resume-item">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Trajet :</span>
                            <strong><?= htmlspecialchars($reservation['depart']); ?> → <?= htmlspecialchars($reservation['arriver']); ?></strong>
                        </div>
                    </div>
                    
                    <div class="resume-item">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Date :</span>
                            <strong><?= htmlspecialchars($reservation['date_time']); ?></strong>
                        </div>
                    </div>
                    
                    <div class="resume-item">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Passagers :</span>
                            <strong><?= htmlspecialchars($reservation['passengers']); ?> personne(s)</strong>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 mt-5">
                    <div class="text-center text-muted mb-4 mt-2">Montant total à payer</div>
                    <div class="amount-display">
                        <?= $montant_total; ?> FCFA
                    </div>
                </div>
            </div>
            
            <hr class="my-4">
            
            <h4 class="fw-bold mb-3"><i class="fas fa-wallet me-2"></i>Moyens de paiement</h4>
            
            <div class="row">
    <!-- Wave -->
    <div class="col-lg-4 col-md-4 col-sm-12 mb-4">
        <div class="card h-100 mt-3">
            <img src="img/weve.png" class="card-img-top" alt="Wave" height="250px">
            <div class="card-body">
                <h5 class="card-title">Wave</h5>
                <p class="card-text">Paiement mobile instantané via l'application Wave. Transférez l'argent directement depuis votre compte Wave.</p>               
            </div>
            <div class="card-footer">
                <a href="https://wave.sn/pay" target="_blank" class="btn btn-success btn-sm btn-block">Payer avec Wave</a>             
            </div>
        </div>
    </div>

    <!-- Orange Money -->
    <div class="col-lg-4 col-md-4 col-sm-12 mb-4">
        <div class="card h-100 mt-3">
            <img src="img/orange.webp" class="card-img-top" alt="Orange Money" height="250px">
            <div class="card-body">
                <h5 class="card-title">Orange Money</h5>
                <p class="card-text">Paiement sécurisé via votre compte Orange Money. Confirmation par code USSD.</p>
            </div>
            <div class="card-footer">
                <a href="https://orangemoney.sn" target="_blank" class="btn btn-success btn-sm btn-block">Payer avec Orange</a>                
            </div>
        </div>
    </div>

    <!-- Yassir Pay -->
    <div class="col-lg-4 col-md-4 col-sm-12 mb-4">
        <div class="card h-100 mt-3">
            <img src="img/yass.png" class="card-img-top" alt="Yassir Pay" height="250px">
            <div class="card-body">
                <h5 class="card-title">Yassir Pay</h5>
                <p class="card-text">Solution de paiement numérique via l'application Yassir. Simple et rapide.</p>                
            </div>
            <div class="card-footer">
                <a href="https://yassir.com/pay" target="_blank" class="btn btn-success btn-sm btn-block">Payer avec Yassir</a>
            </div>
        </div>
    </div>
</div>
            
            <div class="security-badge">
                <i class="fas fa-lock me-2"></i>Transactions 100% sécurisées - HFC ne conserve pas vos informations bancaires
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animation pour les boutons de paiement
        document.querySelectorAll('.btn-payment').forEach(button => {
            button.addEventListener('mouseenter', function() {
                this.querySelector('i').style.transform = 'translateX(5px)';
            });
            button.addEventListener('mouseleave', function() {
                this.querySelector('i').style.transform = 'translateX(0)';
            });
        });
    </script>
</body>
</html>