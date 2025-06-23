<?php require "hfc/config.php"; 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SénTransport - Nos Bus</title>
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
        
        .hero-bus {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('images/bus.jpg');
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
        
        .bus-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .bus-card:hover {
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
    <section class="hero-bus">
        <div class="container">
            <h1 class="page-title" data-aos="fade-up">Nos Bus de Transport</h1>
            <p class="lead fs-4" data-aos="fade-up" data-aos-delay="200">Confort et sécurité pour vos voyages en groupe</p>
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
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3>Sécurité</h3>
                <p>Bus équipés des dernières normes de sécurité et conducteurs expérimentés</p>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-icon">
                    <i class="fas fa-couch"></i>
                </div>
                <h3>Confort</h3>
                <p>Sièges spacieux, climatisation et espaces bagages</p>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-icon">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
                <h3>Réseau étendu</h3>
                <p>Desservant toutes les principales villes du Sénégal</p>
            </div>
        </div>
    </section>
    
    <!-- Bus List Section -->
    <section class="container mb-5">
        <h2 class="text-center mb-5" data-aos="fade-up">Nos Modèles de Bus</h2>
        
        <div class="row">
            <!-- Bus 1 -->
            <div class="col-md-6" data-aos="fade-up">
                <div class="bus-card">
                    <img src="images/bus-model1.png" class="card-img-top" alt="Bus Grand Confort">
                    <div class="card-body">
                        <h3 class="card-title">Bus Grand Confort</h3>
                        <p class="text-muted">Capacité: 50 passagers</p>
                        <p>Equipé de sièges inclinables, climatisation individuelle et wifi à bord.</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="price-tag">À partir de 5.000 FCFA</span>
                            <?php
                                if (!isset($_SESSION['id_users'])) {
                                    // L'utilisateur n'est pas connecté, ne rien afficher
                                } else {
                                    // Afficher le lien
                                    echo '<a href="reservation.php?type=bus1" class="reservation-btn">Réserver</a>';
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Bus 2 -->
            <div class="col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="bus-card">
                    <img src="images/bus-model2.png" class="card-img-top" alt="Bus VIP">
                    <div class="card-body">
                        <h3 class="card-title">Bus VIP</h3>
                        <p class="text-muted">Capacité: 30 passagers</p>
                        <p>Service haut de gamme avec sièges en cuir, espace lounge et service à bord.</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="price-tag">À partir de 8.000 FCFA</span>
                            <?php
                                if (!isset($_SESSION['id_users'])) {
                                    // L'utilisateur n'est pas connecté, ne rien afficher
                                } else {
                                    // Afficher le lien
                                    echo '<a href="reservation.php?type=bus2" class="reservation-btn">Réserver</a>';
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
                        <img src="images/user1.jpg" class="rounded-circle me-3" width="50" alt="Client">
                        <div>
                            <h5 class="mb-0">Mariama D.</h5>
                            <div class="text-warning">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <p>"Voyage très confortable avec des conducteurs professionnels. Je recommande vivement!"</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="p-4 bg-white rounded shadow-sm">
                    <div class="d-flex align-items-center mb-3">
                        <img src="images/user2.jpg" class="rounded-circle me-3" width="50" alt="Client">
                        <div>
                            <h5 class="mb-0">Abdoulaye S.</h5>
                            <div class="text-warning">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                        </div>
                    </div>
                    <p>"Service ponctuel et véhicules bien entretenus. Je voyage toujours avec SénTransport."</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="p-4 bg-white rounded shadow-sm">
                    <div class="d-flex align-items-center mb-3">
                        <img src="images/user3.jpg" class="rounded-circle me-3" width="50" alt="Client">
                        <div>
                            <h5 class="mb-0">Aïssatou B.</h5>
                            <div class="text-warning">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <p>"Parfait pour les voyages en famille. Les enfants ont adoré l'espace et le confort."</p>
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