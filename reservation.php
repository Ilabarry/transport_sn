<?php require_once "hfc/header.php"; 
require "hfc/config.php";

if (!isset($_SESSION['id_users'])) {
    header("Location: log/connexion.php");
    exit(); 
}

// Récupérer les tarifs depuis la base de données
try {
    $query = $requete->query("SELECT * FROM tarifs");
    $tarifs = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $tarifs = [
        ['type' => 'Bus', 'prix' => 5000],
        ['type' => 'Mini-bus', 'prix' => 3500],
        ['type' => '7-places', 'prix' => 6000]
    ];
}

// Recherche d'adresse
$adressMap = "Dakar,+Sénégal";
if (isset($_POST['recherche']) && !empty($_POST['adresse'])) {
    $adressMap = urlencode($_POST['adresse']);
}

$selectedTransport = isset($_GET['type']) ? htmlspecialchars($_GET['type']) : '';
?>

<style>
body {
    background-color: #f8f9fa;
    font-family: 'Roboto', sans-serif;
}
.jumbotron {
    /* background: url('images/img_reservation.jpg') center/cover no-repeat; */
    height: 270px;
    position: relative;
    margin-bottom: 0;
}
.jumbotron::before {
    content: '';
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.4);
}
.jumbotron .container {
    position: relative;
    z-index: 1;
}
.display-4 {
    padding: 5px 20px;
    border-radius: 0 50% 0 50%;
    color: #fff;
    font-weight: 900;
    text-shadow: 1px 1px 3px rgba(0,0,0,0.3);
}
.acceuil {
    background: rgba(0, 0, 0, 0.19);
    height: 182px;
    position: relative;
}
.img_header {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    z-index: -1;
    height: 257px;
    object-fit: cover;
    filter: brightness(0.7);
}
.title-general {
    background: #2ecc71;
    border-radius: 0 50% 0 50%;
    color: #fff;
    padding: 5px 20px;
    font-weight: 900;
}
.price-amount {
    font-size: 1.5rem;
    font-weight: bold;
    color: #2ecc71;
}
    .form-container, .ma_carte {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        padding: 30px;
        margin: 30px;
        overflow-y: auto;
        max-height: calc(100vh - 200px);
    }
.transport-option label {
    cursor: pointer;
}
iframe {
    border: none;
    width: 100%;
    height: 400px;
}
</style>

<img class="img_header" src="images/img_reservation.jpg" alt="Bannière conducteurs">
<div class="jumbotron d-flex align-items-center justify-content-center text-white text-center acceuil">
    <div class="container">
        <h1 class="display-4">RÉSERVER UN TRAJET</h1>
        <p class="lead">Voyagez en toute sécurité avec SéneTransport</p>
    </div>
</div>

<div class="container py-5">
    <div class="row">
        <!-- Formulaire -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-body form-container">
                    <h4 class="text-center py-3 title-general">FAITES VOTRE RÉSERVATION</h4>
                    <form action="valid_reservation.php" method="POST">
                        <div class="form-group">
                            <label for="depart">Départ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="depart" name="depart" required>
                        </div>

                        <div class="form-group">
                            <label for="arriver">Arrivée <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="arriver" name="arriver" required>
                        </div>

                        <div class="form-group">
                            <label for="dateTime">Date et heure <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control" id="dateTime" name="dateTime" required>
                        </div>

                        <div class="form-group">
                            <label for="passengers">Nombre de passagers <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="passengers" name="passengers" min="1" max="50" value="1" required>
                        </div>

                        <h5 class="mt-4 title-general text-center">OPTIONS DE TRANSPORT</h5>
                        <div class="row mt-3">
                            <?php foreach ($tarifs as $tarif): ?>
                            <div class="col-6 transport-option">
                                <div class="form-check">
                                    <input class="form-check-input transport-type" type="radio" name="transport_type"
                                           id="<?= strtolower(str_replace(' ', '-', $tarif['type'])) ?>"
                                           value="<?= $tarif['type'] ?>" data-prix="<?= $tarif['prix'] ?>"
                                           <?= ($selectedTransport === strtolower(str_replace(' ', '-', $tarif['type']))) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="<?= strtolower(str_replace(' ', '-', $tarif['type'])) ?>">
                                        <i class="fa <?= ($tarif['type'] === 'Bus') ? 'fa-bus' : (($tarif['type'] === 'Mini-bus') ? 'fa-car-side' : 'fa-taxi') ?>"></i>
                                        <?= $tarif['type'] ?> (<?= number_format($tarif['prix'], 0, ',', ' ') ?> FCFA)
                                    </label>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="mt-4 p-3 bg-light border-left border-success">
                            <h6>Prix estimé</h6>
                            <div id="total-price" class="price-amount">
                                <?= number_format($tarifs[0]['prix'], 0, ',', ' ') ?> FCFA
                            </div>
                            <small class="text-muted">Le prix peut varier selon la distance</small>
                        </div>

                        <div class="form-group mt-3">
                            <label>Informations complémentaires</label>
                            <textarea class="form-control" name="additional_info" placeholder="Optionnel"></textarea>
                        </div>

                        <button type="submit" name="reservation" class="btn btn-success btn-block">
                            <i class="fas fa-paper-plane"></i> Confirmer la réservation
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Carte -->
        <div class="col-lg-6">
            <div class="card shadow mb-3">
                <div class="card-body ma_carte">
                    <form class="form-inline mb-3" method="POST" action="reservation.php">
                        <input class="form-control mr-2 flex-fill" type="search" placeholder="Adresse de destination" name="adresse">
                        <button class="btn btn-success" type="submit" name="recherche">Recherche</button>
                    </form>
                    <iframe id="carte" src="https://maps.google.com/maps?q=<?= $adressMap ?>&output=embed&zoom=14"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once "hfc/footer.php"; ?>

<script>
$(document).ready(function() {
    calculatePrice();

    function calculatePrice() {
        const selectedTransport = $('input[name="transport_type"]:checked');
        let passengers = parseInt($('#passengers').val()) || 1;
        let totalPrice = 0;

        if (selectedTransport.length > 0) {
            const basePrice = parseFloat(selectedTransport.data('prix'));
            const type = selectedTransport.val();
            if (type === 'Mini-bus') {
                totalPrice = basePrice * Math.min(passengers, 15);
            } else if (type === 'Bus') {
                totalPrice = basePrice * Math.min(passengers, 50);
            } else if (type === '7-places') {
                totalPrice = basePrice * Math.min(passengers, 7);
            } else {
                totalPrice = basePrice;
            }
        }
        $('#total-price').text(totalPrice.toLocaleString('fr-FR') + ' FCFA');
    }

    $('.transport-type, #passengers').on('change input', calculatePrice);

    function updateMap() {
        const depart = $('#depart').val().trim();
        const arriver = $('#arriver').val().trim();
        let query = "Dakar,+Sénégal";

        if (depart && arriver) query = `${depart} to ${arriver}`;
        else if (arriver) query = arriver;
        else if (depart) query = depart;

        $('#carte').attr('src', `https://maps.google.com/maps?q=${encodeURIComponent(query)}&output=embed&zoom=14`);
    }

    $('#depart, #arriver').on('input', function() {
        clearTimeout(window.typingTimer);
        if ($(this).val().length > 2) {
            window.typingTimer = setTimeout(updateMap, 1000);
        }
    });
});
</script>
