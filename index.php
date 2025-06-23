<?php require "hfc/config.php";
require_once "hfc/header.php";
?>

    <style>
        :root {
            --primary-color: #2ecc71;
            --secondary-color: #f1c40f;
            --accent-color: #e74c3c;
            --dark-color: #2c3e50;
            --light-color: #ecf0f1;
        }
        
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('images/image_acceuil.jpg');
            height: 80vh;
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            position: relative;
        }
        
        .hero-title {
            font-size: 4rem;
            font-weight: 900;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }
        
        .title-part-1 { color: var(--primary-color); }
        .title-part-2 { color: var(--secondary-color); }
        .title-part-3 { color: var(--accent-color); }
        
        .hero-subtitle {
            font-size: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .about-section {
            background-color: white;
            padding: 5rem 0;
            margin-top: -100px;
            position: relative;
            z-index: 2;
            box-shadow: 0 -10px 30px rgba(0,0,0,0.1);
            border-radius: 20px 20px 0 0;
        }
        
        .section-title {
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 3rem;
            position: relative;
            display: inline-block;
        }
        
        .section-title:after {
            content: '';
            position: absolute;
            width: 50%;
            height: 4px;
            background: var(--primary-color);
            bottom: -10px;
            left: 25%;
            border-radius: 2px;
        }
        
        .transport-section {
            background-color: var(--light-color);
            padding: 5rem 0;
        }
        
        .transport-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
            height: 100%;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .transport-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        }
        
        .transport-card img {
            height: 250px;
            object-fit: cover;
            transition: all 0.3s ease;
        }
        
        .transport-card:hover img {
            transform: scale(1.05);
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        .card-title {
            color: var(--dark-color);
            font-weight: 600;
            margin-bottom: 1rem;
        }
        
        .card-text {
            color: #666;
            margin-bottom: 1.5rem;
        }
        
        .learn-more-btn {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 30px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .learn-more-btn:hover {
            background: var(--dark-color);
            color: white;
            transform: translateY(-2px);
        }
        
        .air-travel-section {
            padding: 5rem 0;
            background: white;
        }
        .about-section ul li {
            margin-bottom: 0.5rem;
        }

        .about-section .lead {
            font-weight: 600;
            color: var(--dark-color);
        }

        .about-section h5 {
            color: var(--dark-color);
            font-weight: 600;
            margin-top: 1.5rem;
            margin-bottom: 1rem;
        }
        
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.2rem;
            }
            
            .about-section {
                margin-top: -50px;
                padding: 3rem 0;
            }
        }
    </style>
</head>
<body>
    
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container" data-aos="fade-up">
            <h1 class="hero-title">
                <span class="title-part-1">Sén</span>
                <span class="title-part-2">Trans</span>
                <span class="title-part-3">port</span>
            </h1>
            <p class="hero-subtitle">Confiance de voyager partout au Sénégal dans la sécurité avec SénTransport</p>
        </div>
    </section>
    
    <!-- About Section -->
    <section class="about-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0" data-aos="fade-right">
                <img src="images/img_propos.jpg" alt="À propos de SénTransport" height="800px" class="img-fluid rounded shadow">
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <h2 class="section-title text-center text-lg-start">Qui sommes-nous ?</h2>
                <p class="lead">SénTransport - Leader du transport terrestre et aérien au Sénégal depuis 2010</p>
                
                <p>Fondée en 2010, SénTransport s'est rapidement imposée comme la référence en matière de transport de personnes au Sénégal. Notre mission est de révolutionner l'expérience du voyage en offrant des solutions de mobilité sécurisées, confortables et accessibles à tous.</p>
                
                <!-- <p>Avec une flotte moderne de plus de 150 véhicules (bus, minibus et voitures 7 places) et des partenariats avec les principales compagnies aériennes régionales, nous couvrons l'ensemble du territoire sénégalais et les principales destinations en Afrique de l'Ouest.</p> -->
                
                <h5 class="mt-4">Nos engagements :</h5>
                <ul class="list-unstyled">
                    <li><i class="fas fa-check-circle text-primary me-2"></i> Sécurité renforcée avec des véhicules contrôlés quotidiennement</li>
                    <li><i class="fas fa-check-circle text-primary me-2"></i> Confort optimal avec des équipements haut de gamme</li>
                    <li><i class="fas fa-check-circle text-primary me-2"></i> Ponctualité garantie avec 98% de nos trajets à l'heure</li>
                    <li><i class="fas fa-check-circle text-primary me-2"></i> Prix transparents sans surprise</li>
                </ul>
                
                <p>Chaque année, ce sont plus de 500 000 voyageurs qui nous font confiance pour leurs déplacements professionnels, familiaux ou touristiques. Notre équipe de 200 collaborateurs passionnés est à votre service 24h/24 et 7j/7.</p>
                
                <div class="d-flex align-items-center mt-4">
                    <div class="me-3">
                        <i class="fas fa-phone-alt fa-2x text-primary"></i>
                    </div>
                    <div>
                        <h6 class="mb-0">Service client</h6>
                        <p class="mb-0">+221 33 800 00 00 (24h/24)</p>
                    </div>
                </div>
                
                <p class="text-muted mt-3">Dernière mise à jour : <?php echo date('d/m/Y'); ?></p>
            </div>
        </div>
    </div>
</section>
    
    <!-- Transport Section -->
    <section class="transport-section">
        <div class="container">
            <h2 class="section-title text-center">Nos solutions de transport</h2>
            
            <div class="row">
                <!-- Bus Card -->
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <a href="T_bus.php" class="text-decoration-none">
                        <div class="transport-card">
                            <img src="images/bus.jpg" class="card-img-top" alt="Bus">
                            <div class="card-body text-center">
                                <h5 class="card-title">Transport en Bus</h5>
                                <p class="card-text">Découvrez nos bus confortables pour vos voyages en groupe ou longues distances.</p>
                                <button class="learn-more-btn"><a href="T_bus.php" class="text-decoration-none">Voir les options</a></button>
                            </div>
                        </div>
                    </a>
                </div>
                
                <!-- Mini Bus Card -->
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <a href="T_miniBus.php" class="text-decoration-none">
                        <div class="transport-card">
                            <img src="images/mini-bus.jpg" class="card-img-top" alt="Mini Bus">
                            <div class="card-body text-center">
                                <h5 class="card-title">Mini Bus</h5>
                                <p class="card-text">Solution idéale pour les petits groupes avec un excellent rapport qualité-prix.</p>
                                <button class="learn-more-btn"><a href="T_miniBus.php" class="text-decoration-none">Découvrir</a></button>
                            </div>
                        </div>
                    </a>
                </div>
                
                <!-- 7 Places Card -->
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <a href="T_7place.php" class="text-decoration-none">
                        <div class="transport-card">
                            <img src="images/7place.jpg" class="card-img-top" alt="7 Places">
                            <div class="card-body text-center">
                                <h5 class="card-title">Voitures 7 Places</h5>
                                <p class="card-text">Parfait pour les familles ou petits groupes souhaitant un transport privé.</p>
                                <button class="learn-more-btn"><a href="T_7place.php" class="text-decoration-none">En savoir plus</a></button>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Air Travel Section -->
    <section class="air-travel-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8" data-aos="fade-up">
                    <div class="transport-card">
                        <img src="images/avion.jpg" class="card-img-top" alt="Voyage aérien">
                        <div class="card-body text-center">
                            <h5 class="card-title">Solutions de voyage aérien</h5>
                            <p class="card-text">Nous proposons également des solutions complètes pour vos déplacements aériens à travers le Sénégal et l'Afrique de l'Ouest.</p>
                            <a href="client.php" class="learn-more-btn">Consulter les tarifs</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });
    </script>
    
    <?php require_once "hfc/footer.php"; ?>
</body>
</html>