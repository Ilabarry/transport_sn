<?php require "hfc/config.php";
require_once "hfc/header.php";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transport Aérien | SénTransport</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/animations.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    
    <!-- Hero Section -->
    <section class="hero-section air-travel-hero">
        <div class="container" data-aos="fade-up">
            <h1 class="hero-title">
                <span class="title-part-1">Sén</span>
                <span class="title-part-2">Trans</span>
                <span class="title-part-3">port Aérien</span>
            </h1>
            <p class="hero-subtitle">Voyagez à travers le Sénégal et l'Afrique de l'Ouest en toute sérénité</p>
        </div>
    </section>
    
    <!-- Destinations Section -->
    <section class="destinations-section">
        <div class="container">
            <h2 class="section-title text-center">Nos destinations</h2>
            
            <div class="row">
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="destination-card">
                        <img src="images/dakar.png" alt="Dakar" class="destination-img" height="170px">
                        <div class="destination-overlay">
                            <h3>Dakar</h3>
                            <p>Aéroport International Blaise Diagne</p>
                            <a href="#" class="btn btn-outline-light">Voir les vols</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="destination-card">
                        <img src="images/ziguinchor.jpg" alt="Ziguinchor" class="destination-img" height="170px">
                        <div class="destination-overlay">
                            <h3>Ziguinchor</h3>
                            <p>Aéroport de Ziguinchor</p>
                            <a href="#" class="btn btn-outline-light">Voir les vols</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="destination-card">
                        <img src="images/saint-louis.jpg" alt="Saint-Louis" class="destination-img" height="170px">
                        <div class="destination-overlay">
                            <h3>Saint-Louis</h3>
                            <p>Aéroport International de Saint-Louis</p>
                            <a href="#" class="btn btn-outline-light">Voir les vols</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Services Section -->
    <section class="services-section">
        <div class="container">
            <h2 class="section-title text-center">Nos services aériens</h2>
            
            <div class="row">
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="service-card text-center">
                        <div class="service-icon">
                            <i class="fas fa-plane-departure"></i>
                        </div>
                        <h3>Vols intérieurs</h3>
                        <p>Réseau complet couvrant toutes les régions du Sénégal avec des tarifs compétitifs.</p>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="service-card text-center">
                        <div class="service-icon">
                            <i class="fas fa-globe-africa"></i>
                        </div>
                        <h3>Vols régionaux</h3>
                        <p>Connexions vers les principales capitales d'Afrique de l'Ouest.</p>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="service-card text-center">
                        <div class="service-icon">
                            <i class="fas fa-concierge-bell"></i>
                        </div>
                        <h3>Services VIP</h3>
                        <p>Accueil personnalisé, lounge VIP et services sur mesure.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Booking Section -->
    <section class="booking-section">
        <div class="container">
            <div class="booking-card" data-aos="fade-up">
                <div class="row">
                    <div class="col-lg-6 booking-form-col">
                        <h2 class="section-title">Réservez votre vol</h2>
                        <form id="flight-booking-form">
                            <div class="form-group">
                                <label for="departure">Départ</label>
                                <select class="form-control" id="departure" required>
                                    <option value="">Choisissez une ville</option>
                                    <option value="Dakar">Dakar</option>
                                    <option value="Ziguinchor">Ziguinchor</option>
                                    <option value="Saint-Louis">Saint-Louis</option>
                                    <option value="Tambacounda">Tambacounda</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="arrival">Destination</label>
                                <select class="form-control" id="arrival" required>
                                    <option value="">Choisissez une ville</option>
                                    <option value="Dakar">Dakar</option>
                                    <option value="Ziguinchor">Ziguinchor</option>
                                    <option value="Saint-Louis">Saint-Louis</option>
                                    <option value="Tambacounda">Tambacounda</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="departure-date">Date de départ</label>
                                <input type="date" class="form-control" id="departure-date" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="passengers">Passagers</label>
                                <select class="form-control" id="passengers">
                                    <option value="1">1 adulte</option>
                                    <option value="2">2 adultes</option>
                                    <option value="3">3 adultes</option>
                                    <option value="4">4 adultes</option>
                                </select>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-block">Rechercher des vols</button>
                        </form>
                    </div>
                    
                    <div class="col-lg-6 booking-info-col">
                        <div class="booking-info">
                            <h3>Pourquoi choisir SénTransport Aérien ?</h3>
                            <ul>
                                <li><i class="fas fa-check-circle"></i> Sécurité maximale avec des compagnies partenaires certifiées</li>
                                <li><i class="fas fa-check-circle"></i> Meilleurs tarifs garantis</li>
                                <li><i class="fas fa-check-circle"></i> Assistance 24h/24</li>
                                <li><i class="fas fa-check-circle"></i> Flexibilité des billets</li>
                                <li><i class="fas fa-check-circle"></i> Programme de fidélité avantageux</li>
                            </ul>
                            
                            <div class="contact-info">
                                <h4>Service client</h4>
                                <p><i class="fas fa-phone-alt"></i> +221 33 800 00 00</p>
                                <p><i class="fas fa-envelope"></i> aerien@sentransport.sn</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Partners Section -->
    <section class="partners-section">
        <div class="container">
            <h2 class="section-title text-center">Nos compagnies partenaires</h2>
            
            <div class="partners-slider" data-aos="fade-up">
                <div class="partner-item">
                    <img src="images/partners/air-senegal.png" alt="Air Sénégal">
                </div>
                <div class="partner-item">
                    <img src="images/partners/asky-airlines.png" alt="ASKY Airlines">
                </div>
                <div class="partner-item">
                    <img src="images/partners/air-cote-ivoire.png" alt="Air Côte d'Ivoire">
                </div>
                <div class="partner-item">
                    <img src="images/partners/emirates.png" alt="Emirates">
                </div>
                <div class="partner-item">
                    <img src="images/partners/ethiopian-airlines.png" alt="Ethiopian Airlines">
                </div>
            </div>
        </div>
    </section>
    
    <!-- Testimonials Section -->
    <section class="testimonials-section">
        <div class="container">
            <h2 class="section-title text-center">Témoignages</h2>
            
            <div class="row">
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="testimonial-card">
                        <div class="testimonial-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="testimonial-text">"Service impeccable ! J'ai pu changer mon vol sans frais lorsque mes plans ont changé. Je recommande vivement."</p>
                        <div class="testimonial-author">
                            <img src="images/testimonials/client1.jpg" alt="Aïda Ndiaye">
                            <h5>Aïda Ndiaye</h5>
                            <p>Dakar - Ziguinchor</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="testimonial-card">
                        <div class="testimonial-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <p class="testimonial-text">"Ponctualité parfaite et personnel très attentionné. Le service VIP en vaut vraiment la peine."</p>
                        <div class="testimonial-author">
                            <img src="images/testimonials/client2.jpg" alt="Moussa Diallo">
                            <h5>Moussa Diallo</h5>
                            <p>Saint-Louis - Dakar</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="testimonial-card">
                        <div class="testimonial-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="testimonial-text">"Première fois que je voyage en avion au Sénégal et tout s'est parfaitement déroulé grâce à SénTransport."</p>
                        <div class="testimonial-author">
                            <img src="images/testimonials/client3.jpg" alt="Fatou Bâ">
                            <h5>Fatou Bâ</h5>
                            <p>Tambacounda - Dakar</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- FAQ Section -->
    <section class="faq-section">
        <div class="container">
            <h2 class="section-title text-center">Questions fréquentes</h2>
            
            <div class="accordion" id="faqAccordion">
                <div class="accordion-item" data-aos="fade-up" data-aos-delay="100">
                    <h3 class="accordion-header" id="headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Quelles sont les conditions d'annulation ?
                        </button>
                    </h3>
                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <p>Vous pouvez annuler votre vol jusqu'à 24 heures avant le départ avec un remboursement complet. Pour les annulations dans les 24 heures, des frais peuvent s'appliquer selon le type de billet.</p>
                        </div>
                    </div>
                </div>
                
                <div class="accordion-item" data-aos="fade-up" data-aos-delay="200">
                    <h3 class="accordion-header" id="headingTwo">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Quel est le poids autorisé pour les bagages ?
                        </button>
                    </h3>
                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <p>Pour les vols intérieurs, vous avez droit à :</p>
                            <ul>
                                <li>1 bagage en soute de 20kg maximum</li>
                                <li>1 bagage à main de 7kg (dimensions 55x35x25cm)</li>
                            </ul>
                            <p>Des suppléments peuvent s'appliquer pour les excédents.</p>
                        </div>
                    </div>
                </div>
                
                <div class="accordion-item" data-aos="fade-up" data-aos-delay="300">
                    <h3 class="accordion-header" id="headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Comment modifier une réservation ?
                        </button>
                    </h3>
                    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <p>Vous pouvez modifier votre réservation :</p>
                            <ul>
                                <li>En ligne via "Mon compte" sur notre site</li>
                                <li>Par téléphone au +221 33 800 00 00</li>
                                <li>Dans nos agences physiques</li>
                            </ul>
                            <p>Des frais de modification peuvent s'appliquer selon le type de billet.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="js/scripts.js"></script>
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