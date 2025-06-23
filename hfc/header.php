<?php
require "config.php";
session_start();

// if (!isset($_SESSION['id_users'])) {
//     header("Location: log/connexion.php");
//     exit(); 
// }

// Récupérer l'ID de l'utilisateur depuis la session
// $users_id = $_SESSION['id_users'];
?>
<!doctype html>
<html lang="fr">
<head>
    <title>SenVoyage</title>
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
        
        @media (max-width: 992px) {
            .navbar-collapse {
                background-color: #2ecc71;
                padding: 15px;
                border-radius: 5px;
                margin-top: 10px;
            }
            
            .nav-item {
                margin: 5px 0;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top bg-success">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">
                <img src="senvoyagee.png" alt="SenVoyage Logo" height="40" class="mr-2">
                SenVoyage
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

                        if (isset($_SESSION['id_users']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {

                           echo '<li class="nav-item">
                                    <a class="nav-link voyage" href="admin.php"><i class="fas fa-users mr-1"></i> Administration</a>
                                </li>
                             ';
                        }
                    ?>
                </ul>

                <div class="user-profile">
                    <?php if (isset($_SESSION['id_users'])): ?>
                        <a class="nav-link" href="profil.php">
                            <i class="fas fa-user-circle"></i>
                            <span class="user-name">
                                <?php
                                $result = $requete->prepare('SELECT nom FROM users WHERE id = :id');
                                $result->bindParam(':id', $users_id, PDO::PARAM_INT);
                                $result->execute();
                                $profil = $result->fetch(PDO::FETCH_ASSOC);
                                
                                if ($profil) {
                                    echo htmlspecialchars($profil["nom"]);
                                }
                                ?>
                            </span>
                        </a>
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

    <!-- Scripts JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
        
        // Ajoute une classe lorsque l'utilisateur scroll
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                document.querySelector('header').style.height = '60px';
                document.querySelector('header').style.boxShadow = '0 2px 15px rgba(0,0,0,0.2)';
            } else {
                document.querySelector('header').style.height = '70px';
                document.querySelector('header').style.boxShadow = 'none';
            }
        });
    </script>
</body>
</html>