<?php
require_once 'hfc/config.php';
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Transport</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- AOS Animation CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 250px;
        }
        
        .sidebar {
            position: fixed;
            width: var(--sidebar-width);
            height: 100vh;
            background: #343a40;
            transition: all 0.3s;
            z-index: 1000;
        }
        
        .main-content {
            margin-left: var(--sidebar-width);
            transition: all 0.3s;
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,.5);
            transition: all 0.2s;
        }
        
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: #fff;
            background: rgba(255,255,255,.1);
            transform: translateX(5px);
        }
        
        .card-icon {
            font-size: 2rem;
            opacity: 0.7;
            transition: transform 0.3s;
        }
        
        .card:hover .card-icon {
            transform: scale(1.1);
        }
        
        .table-responsive {
            overflow-x: auto;
        }
        
        /* Animation for notification */
        @keyframes slideIn {
            from { transform: translateX(100%); }
            to { transform: translateX(0); }
        }
        
        .notification {
            animation: slideIn 0.5s forwards;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .sidebar {
                margin-left: -var(--sidebar-width);
            }
            
            .sidebar.active {
                margin-left: 0;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .main-content.active {
                margin-left: var(--sidebar-width);
            }
        }
    </style>
</head>
<body>
    <?php if (!isset($_SESSION['id_users'])): ?>
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-6 text-center">
                    <h3 class="mb-4">Merci de vous connecter pour accéder à vos informations</h3>
                    <a href="log/connexion.php" class="btn btn-primary">Se connecter</a>
                </div>
            </div>
        </div>
    <?php else: ?>
    <!-- Sidebar Toggle Button for Mobile -->
    <button class="btn btn-dark d-md-none fixed-top m-3" id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 sidebar py-3" id="sidebar">
                <div class="text-center mb-4" data-aos="fade-down">
                    <h4 class="text-white">Transport App</h4>
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item" data-aos="fade-right" data-aos-delay="100">
                        <a class="nav-link active" href="dashboard.php">
                            <i class="fas fa-tachometer-alt me-2"></i>Tableau de bord
                        </a>
                    </li>
                    <li class="nav-item" data-aos="fade-right" data-aos-delay="150">
                        <a class="nav-link" href="reservation.php">
                            <i class="fas fa-calendar-alt me-2"></i>Réservations
                        </a>
                    </li>
                    <li class="nav-item" data-aos="fade-right" data-aos-delay="200">
                        <a class="nav-link" href="conducteur.php">
                            <i class="fas fa-car me-2"></i>Conducteurs
                        </a>
                    </li>
                    <li class="nav-item" data-aos="fade-right" data-aos-delay="250">
                        <a class="nav-link" href="client.php">
                            <i class="fas fa-users me-2"></i>Clients
                        </a>
                    </li>
                    <li class="nav-item" data-aos="fade-right" data-aos-delay="300">
                        <a class="nav-link" href="notation.php">
                            <i class="fas fa-comments me-2"></i>Commentaires
                        </a>
                    </li>

                    <?php if (isset($_SESSION['id_users']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') : ?> 
                     
                    <li class="nav-item" data-aos="fade-right" data-aos-delay="350">
                        <a class="nav-link" href="admin.php">
                            <i class="fas fa-cog me-2"></i>Administration
                        </a>
                    </li>
                    <?php endif ?>

                </ul>
            </nav>

            <!-- Main Content -->
            <main class="col-md-10 main-content px-md-4 py-4" id="mainContent">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2" data-aos="fade-down">Tableau de bord</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i>
                                <?php echo htmlspecialchars($_SESSION['prenom'] . ' ' . $_SESSION['nom']); ?>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="profil.php">Profil</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="logout.php">Déconnexion</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Notification Area -->
                <?php if(isset($_GET['delete_success'])): ?>
                <div class="alert alert-success notification" role="alert" data-aos="fade-down">
                    Suppression effectuée avec succès!
                </div>
                <?php endif; ?>

                <!-- Stats Cards -->
                <div class="row mb-4">
                    <div class="col-md-3" data-aos="fade-up" data-aos-delay="100">
                        <div class="card text-white bg-primary mb-3 h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5 class="card-title">Réservations</h5>
                                        <h2 class="card-text">
                                            <?php 
                                            $stmt = $requete->query("SELECT COUNT(*) FROM reservation");
                                            echo $stmt->fetchColumn(); 
                                            ?>
                                        </h2>
                                    </div>
                                    <i class="fas fa-calendar-alt card-icon"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3" data-aos="fade-up" data-aos-delay="200">
                        <div class="card text-white bg-success mb-3 h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5 class="card-title">Conducteurs</h5>
                                        <h2 class="card-text">
                                            <?php 
                                            $stmt = $requete->query("SELECT COUNT(*) FROM users WHERE role = 'conducteur'");
                                            echo $stmt->fetchColumn(); 
                                            ?>
                                        </h2>
                                    </div>
                                    <i class="fas fa-car card-icon"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3" data-aos="fade-up" data-aos-delay="300">
                        <div class="card text-white bg-info mb-3 h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5 class="card-title">Clients</h5>
                                        <h2 class="card-text">
                                            <?php 
                                            $stmt = $requete->query("SELECT COUNT(*) FROM users WHERE role = 'client'");
                                            echo $stmt->fetchColumn(); 
                                            ?>
                                        </h2>
                                    </div>
                                    <i class="fas fa-users card-icon"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3" data-aos="fade-up" data-aos-delay="400">
                        <div class="card text-white bg-warning mb-3 h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5 class="card-title">Revenus éstimé</h5>
                                        <h2 class="card-text">
                                            <?php 
                                            $stmt = $requete->query("SELECT SUM(prix_estime) FROM reservation");
                                            echo number_format($stmt->fetchColumn(), 2) . ' €'; 
                                            ?>
                                        </h2>
                                    </div>
                                    <i class="fas fa-euro-sign card-icon"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dernières Réservations -->
                <div class="card mb-4" data-aos="fade-up">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>Dernières Réservations</h5>
                        <a href="reservation.php" class="btn btn-sm btn-outline-primary">Je reserve</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Client</th>
                                        <th>Départ</th>
                                        <th>Arrivée</th>
                                        <th>Date/Heure</th>
                                        <th>Prix</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $stmt = $requete->query("
                                        SELECT r.*, u.prenom, u.nom 
                                        FROM reservation r
                                        JOIN users u ON r.id_users = u.id
                                        ORDER BY created_at DESC 
                                        LIMIT 5
                                    ");
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
                                    ?>
                                    <tr data-aos="fade-right">
                                        <td><?= $row['id'] ?></td>
                                        <td><?= htmlspecialchars($row['prenom'] . ' ' . $row['nom']) ?></td>
                                        <td><?= htmlspecialchars($row['depart']) ?></td>
                                        <td><?= htmlspecialchars($row['arriver']) ?></td>
                                        <td><?= date('d/m/Y H:i', strtotime($row['date_time'])) ?></td>
                                        <td><?= number_format($row['prix_estime'], 2) ?> €</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <!-- <a  href="afiche_profil_reservation.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a> -->
                                                <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin' || $_SESSION['id_users'] == $row['id_users']): ?>
                                                <a href="delete_reservation.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réservation?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Commentaires Récents -->
                <div class="row">
                    <div class="col-md-6" data-aos="fade-right">
                        <div class="card h-100">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5>Commentaires Récents</h5>
                                <a href="from_commentaire.php" class="btn btn-sm btn-outline-primary">Je commente</a>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <?php
                                    $stmt = $requete->query("
                                        SELECT c.*, u.prenom, u.nom 
                                        FROM commentaire c
                                        JOIN users u ON c.id_users = u.id
                                        ORDER BY created_at DESC 
                                        LIMIT 3
                                    ");
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
                                    ?>
                                    <li class="list-group-item" data-aos="zoom-in">
                                        <div class="d-flex justify-content-between">
                                            <strong><?= htmlspecialchars($row['prenom'] . ' ' . $row['nom']) ?></strong>
                                            <small class="text-muted"><?= date('d/m/Y', strtotime($row['created_at'])) ?></small>
                                        </div>
                                        <p class="mb-1"><?= htmlspecialchars(substr($row['commentaires'], 0, 50)) ?>...</p>
                                        <?php if(!empty($row['reponse_com'])): ?>
                                        <div class="alert alert-light p-2 mt-2">
                                            <strong>Réponse :</strong> <?= htmlspecialchars(substr($row['reponse_com'], 0, 50)) ?>...
                                        </div>
                                        <?php endif; ?>
                                        <div class="mt-2">
                                            <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin' || $_SESSION['id_users'] == $row['id_users']): ?>
                                            <a href="delete_comment.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce commentaire?')">
                                                <i class="fas fa-trash"></i> Supprimer
                                            </a>
                                            <?php endif; ?>
                                        </div>
                                    </li>
                                    <?php endwhile; ?>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Conducteurs Actifs -->
                    <div class="col-md-6" data-aos="fade-left">
                        <div class="card h-100">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5>Conducteurs Actifs</h5>
                                <a href="conducteur.php" class="btn btn-sm btn-outline-primary">Voir tous</a>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <?php
                                    $stmt = $requete->query("
                                        SELECT c.*, u.prenom, u.nom 
                                        FROM conducteur c
                                        JOIN users u ON c.id_users = u.id
                                        ORDER BY created_at DESC 
                                        LIMIT 3
                                    ");
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
                                    ?>
                                    <li class="list-group-item" data-aos="zoom-in">
                                        <div class="d-flex">
                                            <img src="<?= htmlspecialchars($row['profil'] ?? 'default_profile.jpg') ?>" class="rounded-circle me-3" width="50" height="50" alt="Profil" onerror="this.src='default_profile.jpg'">
                                            <div class="flex-grow-1">
                                                <strong><?= htmlspecialchars($row['prenom'] . ' ' . $row['nom']) ?></strong>
                                                <div class="text-muted small"><?= htmlspecialchars($row['nom_transport']) ?></div>
                                                <div class="mt-2">
                                                    <span class="badge bg-primary"><?= htmlspecialchars($row['type_permi']) ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <?php endwhile; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <?php endif; ?>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AOS Animation JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS animations
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });
        
        // Sidebar toggle for mobile
        document.querySelectorAll('.sidebar .nav-link[href^="#"]').forEach(link => {
            link.addEventListener('click', function(e) {
                const targetId = this.getAttribute('href');
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    e.preventDefault();
                    window.scrollTo({
                        top: targetElement.offsetTop - 20,
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
</body>
</html>