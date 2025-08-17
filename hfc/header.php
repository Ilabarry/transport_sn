<?php
require "config.php";
session_start();

// Récupérer l'ID de l'utilisateur depuis la session
$users_id = $_SESSION['id_users'] ?? null ;
?>
<!doctype html>
<html lang="fr">
<head>
    <title>SenTransport</title>
    <link rel="icon" type="image" href="senvoyagee.png">
    <!-- Meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSS et polices -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100..900&display=swap" rel="stylesheet">
    
    <style>
        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            height: 70px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .navbar-brand {
            font-family: 'Roboto', sans-serif;
            font-weight: 700;
            font-size: 1.5rem;
            color: white;
        }
        
        .nav-item .nav-link {
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 8px 15px;
            margin: 0 5px;
        }
        
        .nav-link.voyage:hover {
            background-color: #27ae60;
            border-radius: 5px;
            transform: translateY(-2px);
        }
        
        .user-profile {
            display: flex;
            align-items: center;
        }
        
        .user-name {
            margin-left: 8px;
            font-weight: 500;
            color: white;
        }
        
        .fa-user-circle {
            font-size: 1.5rem;
            color: white;
        }
        .Logo {
            width: 140px;
            height: 50px;
            margin-left:-90px;
            object-fit: cover;
        }

        /* Le texte SenTransport à côté du logo */
        .brand-text {
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
        }

        /* RESPONSIVE : max 700px */
        @media (max-width: 700px) {
            .Logo {
                width: 100px;
                height: 45px;
            }

            .brand-text {
                font-size: 1.1rem;
            }

            .navbar-brand {
                margin-left: 0 !important;
            }
        }

        /* @media (max-width: 992px) {
            .navbar-collapse {
                background-color: #2ecc71;
                padding: 15px;
                border-radius: 5px;
                margin-top: 10px;
            }
            
            .nav-item {
                margin: 5px 0;
            }
        } */

    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top bg-success">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="dashboard.php">
                <img src="logoo.png" alt="SenVoyage Logo" class="Logo mr-1">
                <span class="brand-text">SenTransport</span>
            </a>

            
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link voyage" href="index.php"><i class="fas fa-home mr-1"></i> Accueil</a>
                    </li>
                    <?php
                        if (isset($_SESSION['id_users'])) {
                            echo '
                            <li class="nav-item">
                                <a class="nav-link voyage" href="reservation.php"><i class="fas fa-calendar-check mr-1"></i> Réservation</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link voyage" href="notation.php"><i class="fas fa-comments mr-1"></i> Commentaires</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link voyage" href="client.php"><i class="fas fa-tags mr-1"></i> Tarifs</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link voyage" href="conducteur.php"><i class="fas fa-users mr-1"></i> Conducteurs</a>
                            </li>
                        ';
                        }
                    ?>
                </ul>

                
                <div class="user-profile">
                    <?php if (isset($_SESSION['id_users'])): ?>
                        <div class="btn-toolbar mb-2 mb-md-0">
                            <div class="dropdown">
                                <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-user-circle mr-1"></i>
                                    <?php
                                        $result = $requete->prepare('SELECT nom FROM users WHERE id = :id');
                                        $result->bindParam(':id', $users_id, PDO::PARAM_INT);
                                        $result->execute();
                                        $profil = $result->fetch(PDO::FETCH_ASSOC);
                                        
                                        if ($profil) {
                                            echo htmlspecialchars($profil["nom"]);
                                        }
                                        ?>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right bg-success" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="profil.php">Profil</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="logout.php">Déconnexion</a>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <ul class="navbar-nav mr-auto">
                            <li class="nav-item"><a class="nav-link voyage" href="log/connexion.php"><i class="fas fa-sign-in-alt me-2"></i> Connexion</a></li>
                            <li class="nav-item"><a class="nav-link voyage" href="log/inscription.php"><i class="fas fa-user-plus me-2"></i> Inscription</a></li>
                        </ul>
                    <?php endif; ?>
                </div>
                
                
            </div>
        </div>
    </nav>