<?php require_once "hfc/config.php"; ?>

<style>
/* Reset et styles de base */
#sen-transport-footer {
    --primary-color: #2ecc71;
    --dark-green: #27ae60;
    --accent-color: #e74c3c;
    --text-color: #fff;
    --light-bg: rgba(255, 255, 255, 0.1);
    
    font-family: 'Roboto', sans-serif;
    background: linear-gradient(135deg, var(--primary-color), var(--dark-green));
    color: var(--text-color);
    padding: 4rem 0 2rem;
    position: relative;
    overflow: hidden;
}

/* Effet de vague décoratif */
#sen-transport-footer::before {
    content: '';
    position: absolute;
    top: -50px;
    left: 0;
    width: 100%;
    height: 50px;
    background: url('data:image/svg+xml;utf8,<svg viewBox="0 0 1200 120" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none"><path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" fill="%232ecc71" opacity=".25"/><path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" fill="%232ecc71" opacity=".5"/><path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z" fill="%232ecc71"/></svg>');
    background-size: cover;
}

.footer-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
    position: relative;
    z-index: 2;
}

/* Grille responsive */
.footer-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 3rem;
    margin-bottom: 3rem;
}

/* Sections du footer */
.footer-section {
    padding: 1rem;
}

.footer-title {
    font-size: 1.4rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    position: relative;
    display: inline-block;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.footer-title::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 0;
    width: 50px;
    height: 3px;
    background: var(--text-color);
    transition: width 0.3s ease;
}

.footer-section:hover .footer-title::after {
    width: 80px;
}

/* Liens */
.footer-links {
    list-style: none;
}

.footer-links li {
    margin-bottom: 0.8rem;
}

.footer-links a {
    color: var(--text-color);
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-block;
    padding: 0.2rem 0;
    position: relative;
}

.footer-links a::before {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 0;
    height: 1px;
    background: var(--text-color);
    transition: width 0.3s ease;
}

.footer-links a:hover {
    transform: translateX(5px);
}

.footer-links a:hover::before {
    width: 100%;
}

/* Newsletter */
.newsletter-form {
    display: flex;
    margin-top: 1.5rem;
    border-radius: 30px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.newsletter-input {
    flex: 1;
    padding: 0.8rem 1.2rem;
    border: none;
    font-size: 0.9rem;
}

.newsletter-button {
    background: var(--accent-color);
    color: white;
    border: none;
    padding: 0 1.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.newsletter-button:hover {
    background: #c0392b;
    padding: 0 2rem;
}

/* Réseaux sociaux */
.social-links {
    display: flex;
    gap: 1rem;
    margin-top: 1.5rem;
}

.social-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    background: var(--light-bg);
    border-radius: 50%;
    color: white;
    transition: all 0.3s ease;
}

.social-link:hover {
    background: var(--text-color);
    color: var(--primary-color);
    transform: translateY(-3px) scale(1.1);
}

/* Copyright */
.footer-bottom {
    text-align: center;
    padding-top: 2rem;
    margin-top: 2rem;
    border-top: 1px solid rgba(255,255,255,0.1);
    font-size: 0.9rem;
}

/* Responsive */
@media (max-width: 768px) {
    .footer-grid {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .newsletter-form {
        flex-direction: column;
        border-radius: 8px;
    }
    
    .newsletter-button {
        padding: 0.8rem;
        width: 100%;
    }
    
    .newsletter-button:hover {
        padding: 0.8rem;
    }
}
</style>

<!-- Footer Structure -->
<footer id="sen-transport-footer">
    <div class="footer-container">
        <div class="footer-grid">
            <!-- Section A propos -->
            <div class="footer-section" data-aos="fade-up">
                <h3 class="footer-title">SénTransport</h3>
                <p>Leader du transport au Sénégal depuis 2010, nous offrons des solutions de mobilité sécurisées et confortables pour tous vos déplacements.</p>
                <div class="social-links">
                    <a href="#" class="social-link" aria-label="Facebook" data-aos="zoom-in" data-aos-delay="100"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-link" aria-label="Twitter" data-aos="zoom-in" data-aos-delay="200"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-link" aria-label="Instagram" data-aos="zoom-in" data-aos-delay="300"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-link" aria-label="LinkedIn" data-aos="zoom-in" data-aos-delay="400"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            
            <!-- Section Liens rapides -->
            <div class="footer-section" data-aos="fade-up" data-aos-delay="100">
                <h3 class="footer-title">Liens rapides</h3>
                <ul class="footer-links">
                    <li><a href="index.php">Accueil</a></li>
                    <li><a href="services.php">Nos Services</a></li>
                    <li><a href="tarifs.php">Tarifs</a></li>
                    <li><a href="conducteur.php">Conducteurs</a></li>
                </ul>
            </div>
            
            <!-- Section Contact -->
            <div class="footer-section" data-aos="fade-up" data-aos-delay="150">
                <h3 class="footer-title">Contactez-nous</h3>
                <ul class="footer-links">
                    <li><i class="fas fa-map-marker-alt"></i> Dakar, Sénégal</li>
                    <li><i class="fas fa-phone"></i> +221 33 800 00 00</li>
                    <li><i class="fas fa-envelope"></i> contact@sentransport.sn</li>
                </ul>
            </div>
            
            <!-- Section Newsletter -->
            <div class="footer-section" data-aos="fade-up" data-aos-delay="200">
                <h3 class="footer-title">Newsletter</h3>
                <p>Abonnez-vous pour recevoir nos offres spéciales et actualités.</p>
                <form class="newsletter-form" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
                    <input type="email" class="newsletter-input" name="email" placeholder="Votre email" required>
                    <button type="submit" class="newsletter-button" name="submit_newsletter">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
                <?php 
                if(isset($_POST['submit_newsletter'])) {
                    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
                    if($email) {
                        echo '<p style="margin-top:1rem;color:#fff;font-size:0.9rem;">Merci pour votre abonnement!</p>';
                    } else {
                        echo '<p style="margin-top:1rem;color:#ffdddd;font-size:0.9rem;">Veuillez entrer un email valide</p>';
                    }
                }
                ?>
            </div>
        </div>
        
        <!-- Copyright -->
        <div class="footer-bottom" data-aos="fade-in" data-aos-delay="300">
            <p>&copy; <?= date('Y') ?> SénTransport. Tous droits réservés. | <a href="#" style="color:inherit;text-decoration:underline;">Politique de confidentialité</a></p>
        </div>
    </div>
</footer>

<!-- Scripts -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
// Initialisation AOS avec gestion d'erreur
document.addEventListener('DOMContentLoaded', function() {
    try {
        if(typeof AOS !== 'undefined') {
            AOS.init({
                duration: 800,
                easing: 'ease-out-quad',
                once: true,
                offset: 50,
                disable: function() {
                    return window.innerWidth < 768;
                }
            });
            
            // Rafraîchir après le chargement complet
            window.addEventListener('load', AOS.refresh);
        }
    } catch(e) {
        console.error('Erreur AOS:', e);
        document.querySelectorAll('[data-aos]').forEach(el => {
            el.style.opacity = 1;
            el.style.transform = 'none';
        });
    }
});

// Fallback pour mobile
function mobileFallback() {
    if(window.innerWidth < 768) {
        document.querySelectorAll('[data-aos]').forEach(el => {
            el.style.opacity = 1;
            el.style.transform = 'none';
        });
    }
}

window.addEventListener('resize', mobileFallback);
mobileFallback();
</script>