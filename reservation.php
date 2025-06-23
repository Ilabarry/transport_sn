<?php require_once "hfc/header.php"; 
require "hfc/config.php";

if (!isset($_SESSION['id_users'])) {
    header("Location: log/connexion.php");
    exit(); 
}

// Récupérer les tarifs depuis la base de données
$tarifs = [];
try {
    $query = $requete->query("SELECT * FROM tarifs");
    $tarifs = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // En cas d'erreur, on utilise des tarifs par défaut
    $tarifs = [
        ['type' => 'Bus', 'prix' => 5000],
        ['type' => 'Mini-bus', 'prix' => 3500],
        ['type' => '7-places', 'prix' => 6000]
    ];
}

// Traitement du formulaire de recherche d'adresse
$adresseParDefaut = "Dakar,+Sénégal";
$adressMap = $adresseParDefaut;

if (isset($_POST['recherche']) && !empty($_POST['adresse'])) {
    $adressMap = urlencode($_POST['adresse']);
}

// Si un type de transport est spécifié dans l'URL, on le pré-sélectionne
$selectedTransport = isset($_GET['type']) ? htmlspecialchars($_GET['type']) : '';
?>

<style>
    * {
        padding: 0;
        margin: 0;
        box-sizing: border-box;
    }
    
    body {
        font-family: 'Roboto', sans-serif;
        background-color: #f8f9fa;
    }
    
    /* Style cohérent avec notation.php */
    .acceuil {
        background: rgba(0, 0, 0, 0.29);
        height: 182px;
        position: relative;
    }
    
    .img_header {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        z-index: -1;
        height: 270px;
        object-fit: cover;
        filter: brightness(0.7);
    }

    .display-4 {
        padding: 5px 20px;
        border-radius: 0 50% 0 50%;
        color: #fff;
        font-weight: 900;
        text-shadow: 1px 1px 3px rgba(0,0,0,0.3);
    }
    
    .title-general {
        background: #2ecc71;
        border-radius: 0 50% 0 50%;
        color: #fff;
        font-weight: 900;
        text-shadow: 1px 1px 3px rgba(0,0,0,0.3);
        padding: 5px 20px;
    }
    
    /* Styles spécifiques à la réservation */
    .reservation-wrapper {
        display: flex;
        min-height: calc(100vh - 282px);
        position: relative;
        overflow: hidden;
    }
    
    .form-container {
        width: 100%;
        max-width: 600px;
        background: rgba(255, 255, 255, 0.95);
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        padding: 30px;
        margin: 30px auto;
        z-index: 2;
        position: relative;
        overflow-y: auto;
        max-height: calc(90vh - 50px);
    }
    
    .map-container {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: calc(150vh - 282px);
        z-index: 1;
        overflow: hidden;
    }
    
    #carte {
        width: 100%;
        height: 100%;
        border: 0;
        filter: grayscale(20%) contrast(90%);
    }
    
    /* Styles des formulaires */
    .form-group label {
        color: #2ecc71;
        font-weight: 500;
    }
    
    .form-control {
        border: none;
        border-bottom: 2px solid #2ecc71;
        border-radius: 0;
        padding-left: 0;
        transition: all 0.3s;
    }
    
    .form-control:focus {
        box-shadow: none;
        border-bottom: 2px solid #27ae60;
    }
    
    .required-field {
        color: #e74c3c;
        font-size: 0.8em;
    }
    
    .btn-submit {
        background-color: #2ecc71;
        border: none;
        border-radius: 8px;
        color: #fff;
        font-size: 18px;
        padding: 12px 30px;
        transition: all 0.3s;
        width: 100%;
    }
    
    .btn-submit:hover {
        background-color: #27ae60;
        color:#fff;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    /* Styles pour les radios et checkboxes */
    .form-check {
        margin-bottom: 10px;
    }
    
    .form-check-input {
        margin-top: 0.3em;
    }
    
    .form-check-label {
        margin-left: 5px;
    }
    
    .transport-options {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin: 15px 0;
    }
    
    .transport-option {
        flex: 1 0 45%;
    }
    
    /* Affichage du prix */
    .price-display {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        margin: 15px 0;
        border-left: 4px solid #2ecc71;
    }
    
    .price-amount {
        font-size: 1.5rem;
        font-weight: bold;
        color: #2ecc71;
    }
    
    /* Responsive */
    @media (max-width: 992px) {
        .reservation-wrapper {
            flex-direction: column;
        }
        
        .map-container {
            position: relative;
            top: auto;
            height: 400px;
        }
        
        .form-container {
            max-height: none;
            margin: 20px auto;
        }
    }
    
    @media (max-width: 768px) {
        .form-container {
            margin: 20px auto;
            padding: 20px;
        }
        
        .transport-option {
            flex: 1 0 100%;
        }
    }
    
    #ma-destination{
        width: 100%;
        max-width: 600px;
        border-radius: 10px;
        padding: 35px;
        margin: 30px auto;
        margin-top: 200px;
        z-index: 2;
        position: absolute;
        max-height: calc(90vh - 50px);
    }
    
    .ma-destination{
        width: 100%;
        max-width: 600px;
        border-radius: 10px;
        padding: 35px;
        margin: 30px auto;
        margin-top: 220px;
        z-index: 2;
        position: absolute;
        max-height: calc(90vh - 50px);
    }
    
    .from_destination{
        width: 100%;
        max-width: 600px;
        border-radius: 10px;
        padding: 35px;
        margin: 30px auto;
        margin-top: -15px;
        margin-left: 340px;
        z-index: 2;
        position: absolute;
        max-height: calc(90vh - 50px);
    }
    
    input[type="search"]{
        padding:10px;
        height:40px;
        border-radius:5px;
    }
</style>

<img class="img_header" src="images/img_reservation.jpg" alt="Bannière réservation">

<div class="jumbotro acceuil">
    <div class="container text-center pt-3"><br><br>
        <h1 class="display-4" data-aos="fade-down" data-aos-duration="800">RÉSERVER UN TRAJET</h1>
        <p class="lead text-white" id="confiance" data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">Voyagez en toute sécurité avec SéneTransport</p>
    </div>
</div>

<div class="from_destination">
    <form class="form-inline my-2 my-lg-0" method="POST" action="reservation.php">
        <input class="form-control mr-sm-2" type="search" placeholder="adress de destination" aria-label="Search" name="adresse">
        <button id="recherche" class="btn btn-success my-2 my-sm-0" type="submit" name="recherche">recherche</button>
    </form>
</div>

<div class="reservation-wrapper">
    <div style="height: 500px;">
        <div class="map-container" data-aos="fade" data-aos-duration="1000">
            <iframe id="carte" src="https://maps.google.com/maps?q=<?= $adressMap ?>&output=embed&zoom=14"></iframe>
        </div>
    </div>
    
    <div class="ma-destination" data-aos="fade-left" data-aos-delay="300">
        <button class="btn btn-success btn-destination">rechercher votre destination</button>
    </div>
    
    <div id="ma-destination" data-aos="fade-right" data-aos-delay="400">
        <button class="btn btn-success btn-voireFrom">voir le formulaire de réservation</button>
    </div>
    
    <div class="form-container" data-aos="zoom-in" data-aos-delay="500">
        <form action="valid_reservation.php" method="POST">
            <h4 class="text-center py-3 title-general" data-aos="fade-down">FAITES VOTRE RÉSERVATION</h4>
            
            <div class="form-group">
                <label for="depart">
                    Départ (adresse de départ) 
                    <span class="required-field">* obligatoire</span>
                </label>
                <input type="text" class="form-control" id="depart" 
                       placeholder="Votre adresse de départ" name="depart" required>
            </div>
            
            <div class="form-group mt-4">
                <label for="arriver">
                    Arrivée (adresse de destination) 
                    <span class="required-field">* obligatoire</span>
                </label>
                <input type="text" class="form-control" id="arriver" 
                       placeholder="Votre adresse d'arrivée" name="arriver" required>
            </div>
            
            <div class="form-group mt-4">
                <label for="dateTime">
                    Date et heure de départ 
                    <span class="required-field">* obligatoire</span>
                </label>
                <input type="datetime-local" class="form-control" 
                       id="dateTime" name="dateTime" required>
            </div>
            
            <div class="form-group mt-4">
                <label for="passengers">
                    Nombre de passagers 
                    <span class="required-field">* obligatoire</span>
                </label>
                <input type="number" class="form-control" id="passengers" 
                       min="1" max="50" value="1" name="passengers" required>
            </div>
            
            <h4 class="text-center py-3 mt-4 title-general">OPTIONS DE TRANSPORT</h4>
            
            <div class="transport-options">
                <?php foreach ($tarifs as $tarif): ?>
                    <div class="transport-option form-check">
                        <input class="form-check-input transport-type" 
                               type="radio" name="transport_type" 
                               id="<?= strtolower(str_replace(' ', '-', $tarif['type'])) ?>" 
                               value="<?= $tarif['type'] ?>"
                               data-prix="<?= $tarif['prix'] ?>"
                               <?= ($selectedTransport === strtolower(str_replace(' ', '-', $tarif['type']))) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="<?= strtolower(str_replace(' ', '-', $tarif['type'])) ?>">
                            <i class="fa <?= 
                                ($tarif['type'] === 'Bus') ? 'fa-bus' : 
                                (($tarif['type'] === 'Mini-bus') ? 'fa-car' : 'fa-taxi') 
                            ?>"></i> 
                            <?= $tarif['type'] ?> (<?= number_format($tarif['prix'], 0, ',', ' ') ?> FCFA)
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Affichage du prix total -->
            <div class="price-display">
                <h5>Prix estimé</h5>
                <div class="price-amount" id="total-price">
                    <?= number_format($tarifs[0]['prix'], 0, ',', ' ') ?> FCFA
                </div>
                <small class="text-muted">Le prix peut varier en fonction de la distance</small>
            </div>
            
            <h5 class="mt-4"><i class="fas fa-info-circle"></i> Informations complémentaires</h5>
            <textarea class="form-control" name="additional_info" 
                      placeholder="Informations supplémentaires (optionnel)"></textarea>
            
            <button type="submit" name="reservation" class="btn btn-submit mt-4">
                <i class="fas fa-paper-plane"></i> Confirmer la réservation
            </button>
        </form>
    </div>
</div>


<?php require_once "hfc/footer.php"; ?>

<script>
    $(document).ready(function() {
        $('.from_destination').hide();
        $('.btn-voireFrom').hide();
        
        // Gestion du bouton destination
        $('.btn-destination').click(function(e) {
            e.preventDefault();
            $(this).hide();
            $('.btn-voireFrom').show();
            $('.form-container').hide();
            $('.from_destination').show();
        });
        
        // Gestion du bouton voir formulaire
        $('.btn-voireFrom').click(function(e) {
            e.preventDefault();
            $(this).hide();
            $('.btn-destination').show();
            $('.form-container').show();
            $('.from_destination').hide();
        });
        
        // Gestion du bouton recherche
        $('#recherche').click(function(e) {
            e.preventDefault();
            $('.btn-destination').hide();
            $('.btn-voireFrom').show();
            $('.form-container').hide();
            $('.from_destination').show();
            
            const adresse = $('input[name="adresse"]').val();
            if(adresse) {
                const adresseEncoded = encodeURIComponent(adresse);
                $('#carte').attr('src', `https://maps.google.com/maps?q=${adresseEncoded}&output=embed&zoom=14`);
            }
        });

        // Calcul du prix en fonction du type de transport et du nombre de passagers
        function calculatePrice() {
            const selectedTransport = $('input[name="transport_type"]:checked');
            const passengers = parseInt($('#passengers').val());
            
            if (selectedTransport.length > 0) {
                const basePrice = parseInt(selectedTransport.data('prix'));
                let totalPrice = basePrice;
                
                // Logique de calcul du prix (peut être ajustée)
                if (selectedTransport.val() === 'Bus') {
                    // Prix fixe pour le bus
                    totalPrice = basePrice;
                } else if (selectedTransport.val() === 'Mini-bus') {
                    // Prix par passager pour mini-bus
                    totalPrice = basePrice * passengers;
                } else if (selectedTransport.val() === '7-places') {
                    // Prix fixe pour 7-places (véhicule complet)
                    totalPrice = basePrice;
                }
                
                // Affichage du prix formaté
                $('#total-price').text(totalPrice.toLocaleString('fr-FR') + ' FCFA');
            }
        }
        
        // Écouteurs d'événements pour le calcul du prix
        $('.transport-type').change(calculatePrice);
        $('#passengers').on('input', calculatePrice);
        
        // Calcul initial
        calculatePrice();
        
        // Mise à jour dynamique de la carte
        function updateMap() {
            const depart = $('#depart').val();
            const arriver = $('#arriver').val();
            
            if(depart && arriver) {
                const query = encodeURIComponent(`${depart} to ${arriver}`);
                $('#carte').attr('src', `https://www.google.com/maps?q=${query}&output=embed`);
                $('#carte').css('opacity', '0.7').animate({ opacity: 1 }, 300);
            }
        }
        
        let typingTimer;
        const doneTypingInterval = 1000;
        
        $('#depart, #arriver').on('input', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(updateMap, doneTypingInterval);
        });
        
        $('#depart, #arriver').on('keydown', function() {
            clearTimeout(typingTimer);
        });
        
        // Focus styles
        $('.form-control').focus(function() {
            $(this).parent().addClass('active');
            $(this).css('border-bottom', '2px solid #27ae60');
        }).blur(function() {
            $(this).css('border-bottom', '2px solid #2ecc71');
            if(!$(this).val()) {
                $(this).parent().removeClass('active');
            }
        });

        // Ajustement responsive
        function adjustLayout() {
            if ($(window).width() <= 992) {
                $('.form-container').css({
                    'margin-top': '20px',
                    'margin-bottom': '40px'
                });
            } else {
                $('.form-container').css({
                    'margin-top': '30px',
                    'margin-bottom': '30px'
                });
            }
        }
        
        $(window).resize(adjustLayout);
        adjustLayout();
    });
</script>