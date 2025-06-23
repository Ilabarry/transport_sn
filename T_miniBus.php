<?php require "hfc/config.php"; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SénTransport - Nos Mini-Bus</title>
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
        
        .hero-minibus {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('images/mini-bus.jpg');
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
        
        .page-title {
            font-size: 3.5rem;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }
        
        .feature-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        
        .minibus-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .minibus-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        }
        
        .price-tag {
            background: var(--primary-color);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: 600;
            display: inline-block;
        }
        
        .reservation-btn {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 30px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .reservation-btn:hover {
            background: var(--dark-color);
            transform: translateY(-2px);
        }
        .card-img-top{
            height:200px;
            width:100px;
        }
    </style>
</head>
<body>
    <?php require_once "hfc/header.php"; ?>
    
    <!-- Hero Section -->
    <section class="hero-minibus">
        <div class="container">
            <h1 class="page-title" data-aos="fade-up">Nos Mini-Bus</h1>
            <p class="lead fs-4" data-aos="fade-up" data-aos-delay="200">Solution idéale pour les petits groupes</p>
        </div>
    </section>
    
    <!-- Features Section -->
    <section class="container mb-5">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="T_bus.php">Les bus</a></li>
                <li class="breadcrumb-item"><a href="T_miniBus.php">Les minibus</a></li>
                <li class="breadcrumb-item"><a href="T_7place.php">Les 7Places</a></li>
            </ol>
        </nav>

        <div class="row text-center">
            <div class="col-md-4" data-aos="fade-up">
                <div class="feature-icon">
                    <i class="fas fa-tachometer-alt"></i>
                </div>
                <h3>Rapidité</h3>
                <p>Trajets plus directs et temps de voyage réduits</p>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <h3>Économique</h3>
                <p>Excellent rapport qualité-prix pour les petits groupes</p>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-icon">
                    <i class="fas fa-road"></i>
                </div>
                <h3>Accessibilité</h3>
                <p>Accès aux routes secondaires et zones moins desservies</p>
            </div>
        </div>
    </section>
    
    <!-- Minibus List Section -->
    <section class="container mb-5">
        <h2 class="text-center mb-5" data-aos="fade-up">Nos Modèles de Mini-Bus</h2>
        
        <div class="row">
            <!-- Minibus 1 -->
            <div class="col-md-6" data-aos="fade-up">
                <div class="minibus-card">
                    <img src="images/minibus-model1.png" class="card-img-top" alt="Mini-Bus Standard">
                    <div class="card-body">
                        <h3 class="card-title">Mini-Bus Standard</h3>
                        <p class="text-muted">Capacité: 15 passagers</p>
                        <p>Confort de base avec sièges rembourrés et climatisation pour les trajets courts.</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="price-tag">À partir de 3.500 FCFA</span>
                            <?php
                                if (!isset($_SESSION['id_users'])) {
                                    // L'utilisateur n'est pas connecté, ne rien afficher
                                } else {
                                    // Afficher le lien
                                    echo '<a href="reservation.php?type=minibus1" class="reservation-btn">Réserver</a>';
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Minibus 2 -->
            <div class="col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="minibus-card">
                    <img src="images/minibus-model2.png" class="card-img-top" alt="Mini-Bus Confort">
                    <div class="card-body">
                        <h3 class="card-title">Mini-Bus Confort</h3>
                        <p class="text-muted">Capacité: 12 passagers</p>
                        <p>Version améliorée avec sièges plus spacieux et porte-bagages intégré.</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="price-tag">À partir de 4.500 FCFA</span>
                            <?php
                                if (!isset($_SESSION['id_users'])) {
                                    // L'utilisateur n'est pas connecté, ne rien afficher
                                } else {
                                    // Afficher le lien
                                    echo '<a href="reservation.php?type=minibus2" class="reservation-btn">Réserver</a>';
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Testimonials -->
    <section class="container mb-5 py-5 bg-light rounded-3">
        <h2 class="text-center mb-5" data-aos="fade-up">Témoignages</h2>
        <div class="row">
            <div class="col-md-4" data-aos="fade-up">
                <div class="p-4 bg-white rounded shadow-sm">
                    <div class="d-flex align-items-center mb-3">
                        <img src="images/user4.jpg" class="rounded-circle me-3" width="50" alt="Client">
                        <div>
                            <h5 class="mb-0">Ousmane F.</h5>
                            <div class="text-warning">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <p>"Parfait pour nos déplacements professionnels. Service ponctuel et efficace."</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="p-4 bg-white rounded shadow-sm">
                    <div class="d-flex align-items-center mb-3">
                        <img src="images/user5.jpg" class="rounded-circle me-3" width="50" alt="Client">
                        <div>
                            <h5 class="mb-0">Aminata K.</h5>
                            <div class="text-warning">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                        </div>
                    </div>
                    <p>"Idéal pour les voyages entre amis. Prix très raisonnable pour le service offert."</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="p-4 bg-white rounded shadow-sm">
                    <div class="d-flex align-items-center mb-3">
                        <img src="images/user6.jpg" class="rounded-circle me-3" width="50" alt="Client">
                        <div>
                            <h5 class="mb-0">Moussa D.</h5>
                            <div class="text-warning">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <p>"Utilisé pour un mariage, tous les invités étaient ravis du service."</p>
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