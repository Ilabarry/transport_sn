<?php require "hfc/config.php"; ?>
    <?php require_once "hfc/header.php"; ?>
    <!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SénTransport - Nos Tarifs</title>
    <link rel="icon" type="image" href="senvoyagee.png">
    
    <!-- CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #2ecc71;
            --secondary-color: #f1c40f;
            --accent-color: #e74c3c;
            --dark-color: #2c3e50;
            --light-color: #ecf0f1;
        }
        
        .hero-tarifs {
            /* background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('images/img_tarif.jpg'); */
            height: 60vh;
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            margin-bottom: 3rem;
        }

        .img_header {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        z-index: -1;
        height: 70vh;
        object-fit: cover;
        filter: brightness(0.7);
        }
        
        .page-title {
            font-size: 3.5rem;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }
        
        .pricing-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            background: white;
        }
        
        .pricing-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        }
        
        .pricing-header {
            background: var(--primary-color);
            color: white;
            padding: 1.5rem;
            text-align: center;
        }
        
        .pricing-title {
            font-size: 1.8rem;
            margin-bottom: 0;
        }
        
        .price-tag {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 1rem 0;
            color: var(--dark-color);
        }
        
        .price-period {
            font-size: 1rem;
            color: #777;
        }
        
        .pricing-features {
            padding: 2rem;
        }
        
        .pricing-features ul {
            list-style: none;
            padding: 0;
        }
        
        .pricing-features li {
            padding: 0.5rem 0;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
        }
        
        .pricing-features li:last-child {
            border-bottom: none;
        }
        
        .pricing-features i {
            color: var(--primary-color);
            margin-right: 0.5rem;
        }
        
        .reservation-btn {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 30px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: block;
            width: 80%;
            margin: 1.5rem auto;
        }
        
        .reservation-btn:hover {
            background: var(--dark-color);
            transform: translateY(-2px);
        }
        
        .table-tarifs {
            width: 100%;
            border-collapse: collapse;
        }
        
        .table-tarifs th {
            background: var(--primary-color);
            color: white;
            padding: 1rem;
            text-align: left;
        }
        
        .table-tarifs td {
            padding: 1rem;
            border-bottom: 1px solid #eee;
        }
        
        .table-tarifs tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .table-tarifs tr:hover {
            background-color: #f1f1f1;
        }
        
        .badge-promo {
            background: var(--accent-color);
            color: white;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            margin-left: 0.5rem;
        }
        
        @media (max-width: 768px) {
            .page-title {
                font-size: 2.5rem;
            }
            
            .pricing-card {
                margin-bottom: 2rem;
            }
        }
    </style>
</head>
<body>
    <?php require_once "hfc/header.php"; ?>
    
<img class="img_header" src="images/ima_tarif.jpg" alt="Bannière réservation">
<!-- Hero Section -->
    <section class="hero-tarifs">
        <div class="container">
            <h1 class="page-title" data-aos="fade-up">Nos Tarifs</h1>
            <p class="lead fs-4" data-aos="fade-up" data-aos-delay="200">Des prix transparents et compétitifs pour tous vos déplacements</p>
        </div>
    </section>
    
    <!-- Tarifs Section -->
    <section class="container mb-5">
        <div class="row">
            <!-- Bus Card -->
            <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up">
                <div class="pricing-card">
                    <div class="pricing-header">
                        <h3 class="pricing-title">Bus</h3>
                    </div>
                    <div class="pricing-features">
                        <div class="price-tag">À partir de <br>5.000 FCFA</div>
                        <span class="price-period">par trajet</span>
                        <ul>
                            <li><i class="fas fa-check"></i> Capacité: 50 passagers</li>
                            <li><i class="fas fa-check"></i> Climatisation</li>
                            <li><i class="fas fa-check"></i> Wi-Fi à bord</li>
                            <li><i class="fas fa-check"></i> Espace bagages</li>
                            <li><i class="fas fa-check"></i> Trajets directs</li>
                        </ul>
                        <a href="reservation.php?type=bus" class="reservation-btn">Réserver</a>
                    </div>
                </div>
            </div>
            
            <!-- Mini Bus Card -->
            <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                <div class="pricing-card">
                    <div class="pricing-header">
                        <h3 class="pricing-title">Mini Bus</h3>
                    </div>
                    <div class="pricing-features">
                        <div class="price-tag">À partir de <br>3.500 FCFA</div>
                        <span class="price-period">par trajet</span>
                        <ul>
                            <li><i class="fas fa-check"></i> Capacité: 15 passagers</li>
                            <li><i class="fas fa-check"></i> Climatisation</li>
                            <li><i class="fas fa-check"></i> Sièges rembourrés</li>
                            <li><i class="fas fa-check"></i> Accès routes secondaires</li>
                            <li><i class="fas fa-check"></i> Trajets plus rapides</li>
                        </ul>
                        <a href="reservation.php?type=minibus" class="reservation-btn">Réserver</a>
                    </div>
                </div>
            </div>
            
            <!-- 7 Places Card -->
            <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                <div class="pricing-card">
                    <div class="pricing-header">
                        <h3 class="pricing-title">7 Places</h3>
                    </div>
                    <div class="pricing-features">
                        <div class="price-tag">À partir de <br>6.000 FCFA</div>
                        <span class="price-period">par trajet</span>
                        <ul>
                            <li><i class="fas fa-check"></i> Capacité: 7 passagers</li>
                            <li><i class="fas fa-check"></i> Véhicule privé</li>
                            <li><i class="fas fa-check"></i> Flexibilité horaire</li>
                            <li><i class="fas fa-check"></i> Arrêts possibles</li>
                            <li><i class="fas fa-check"></i> Idéal pour familles</li>
                        </ul>
                        <a href="reservation.php?type=7places" class="reservation-btn">Réserver</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Detailed Tarifs Table -->
    <section class="container mb-5">
        <h2 class="text-center mb-5" data-aos="fade-up">Tarifs détaillés par destination</h2>
        
        <div class="table-responsive" data-aos="fade-up">
            <table class="table-tarifs">
                <thead>
                    <tr>
                        <th>Destination</th>
                        <th>Bus</th>
                        <th>Mini Bus</th>
                        <th>7 Places</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Dakar - Thiès</td>
                        <td>5.000 FCFA</td>
                        <td>3.500 FCFA</td>
                        <td>6.000 FCFA</td>
                    </tr>
                    <tr>
                        <td>Dakar - Mbour</td>
                        <td>6.500 FCFA</td>
                        <td>4.500 FCFA</td>
                        <td>8.000 FCFA</td>
                    </tr>
                    <tr>
                        <td>Dakar - Saint-Louis</td>
                        <td>8.000 FCFA</td>
                        <td>6.000 FCFA</td>
                        <td>12.000 FCFA</td>
                    </tr>
                    <tr>
                        <td>Dakar - Kaolack</td>
                        <td>7.500 FCFA</td>
                        <td>5.500 FCFA</td>
                        <td>10.000 FCFA</td>
                    </tr>
                    <tr>
                        <td>Dakar - Touba</td>
                        <td>7.000 FCFA <span class="badge-promo">Promo</span></td>
                        <td>5.000 FCFA <span class="badge-promo">Promo</span></td>
                        <td>9.500 FCFA</td>
                    </tr>
                    <tr>
                        <td>Dakar - Ziguinchor</td>
                        <td>15.000 FCFA</td>
                        <td>12.000 FCFA</td>
                        <td>25.000 FCFA</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
    
    <!-- Additional Info -->
    <section class="container mb-5 py-5 bg-light rounded-3">
        <div class="row">
            <div class="col-md-6" data-aos="fade-right">
                <h3 class="mb-4">Informations complémentaires</h3>
                <div class="accordion" id="tarifsAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                                Politique tarifaire
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#tarifsAccordion">
                            <div class="accordion-body">
                                <p>Nos tarifs sont calculés en fonction de la distance, du type de véhicule et des services inclus. Les prix sont fixes et sans surprise.</p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                                Réductions disponibles
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#tarifsAccordion">
                            <div class="accordion-body">
                                <p>Nous offrons des réductions pour :</p>
                                <ul>
                                    <li>Groupes de plus de 10 personnes (-15%)</li>
                                    <li>Enfants de moins de 12 ans (-30%)</li>
                                    <li>Réservations aller-retour (-10%)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">
                                Options supplémentaires
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#tarifsAccordion">
                            <div class="accordion-body">
                                <p>Services optionnels disponibles :</p>
                                <ul>
                                    <li>Assurance voyage: 1.000 FCFA</li>
                                    <li>Prise en charge à domicile: 2.000 FCFA</li>
                                    <li>Service VIP: 5.000 FCFA</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6" data-aos="fade-left">
                <h3 class="mb-4">Pourquoi choisir SénTransport ?</h3>
                <div class="d-flex mb-3">
                    <div class="me-3 text-primary">
                        <i class="fas fa-shield-alt fa-2x"></i>
                    </div>
                    <div>
                        <h5>Sécurité garantie</h5>
                        <p>Véhicules régulièrement contrôlés et conducteurs professionnels</p>
                    </div>
                </div>
                <div class="d-flex mb-3">
                    <div class="me-3 text-primary">
                        <i class="fas fa-hand-holding-usd fa-2x"></i>
                    </div>
                    <div>
                        <h5>Prix transparents</h5>
                        <p>Aucun frais caché, vous payez uniquement le prix affiché</p>
                    </div>
                </div>
                <div class="d-flex mb-3">
                    <div class="me-3 text-primary">
                        <i class="fas fa-headset fa-2x"></i>
                    </div>
                    <div>
                        <h5>Service client 24/7</h5>
                        <p>Assistance disponible à tout moment pour vos questions</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php require_once "hfc/footer.php"; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });
    </script>
</body>
</html>