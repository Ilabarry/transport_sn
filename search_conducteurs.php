<?php


// mon code recherche de conducteurs
require_once 'hfc/config.php';

if (isset($_GET['q'])) {
    $search = trim($_GET['q']);

    $sql = $requete->prepare("SELECT conducteur.*, users.prenom, users.nom 
                              FROM conducteur 
                              INNER JOIN users ON conducteur.id_users = users.id 
                              WHERE users.nom LIKE :search OR users.prenom LIKE :search");
    $like = "%$search%";
    $sql->bindParam(':search', $like, PDO::PARAM_STR);
    $sql->execute();
    $conducteurs = $sql->fetchAll(PDO::FETCH_ASSOC);

    if (count($conducteurs) > 0) {
        foreach ($conducteurs as $conduc) {
            include "partials/card_conducteur.php";
        }
    } else {
        echo '<div class="text-center col-12"><p>Aucun conducteur trouvé</p></div>';
    }
}

