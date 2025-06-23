<?php
require_once 'hfc/config.php';
// session_start();
// ob_start();
// Vérification des droits d'admin (décommentez cette section)
// if (!isset($_SESSION['id_users']) || $_SESSION['role'] !== 'admin') {
//     header("Location: login.php");
//     exit();
// }

// Gestion des suppressions
if (isset($_GET['del']) && isset($_GET['type'])) {
    $del_id = intval($_GET['del']);
    $type = $_GET['type'];
    
    try {
        switch($type) {
            case 'user':
                // D'abord supprimer les dépendances (réservations, commentaires)
                $requete->prepare("DELETE FROM reservation WHERE id_users = :id")->execute([':id' => $del_id]);
                $requete->prepare("DELETE FROM commentaire WHERE id_users = :id")->execute([':id' => $del_id]);
                $requete->prepare("DELETE FROM conducteur WHERE id_users = :id")->execute([':id' => $del_id]);
                
                // Puis supprimer l'utilisateur
                $sql = $requete->prepare("DELETE FROM users WHERE id = :id");
                $message = "Utilisateur supprimé avec succès";
                break;
                
            case 'reservation':
                $sql = $requete->prepare("DELETE FROM reservation WHERE id = :id");
                $message = "Réservation supprimée avec succès";
                break;
                
            case 'comment':
                $sql = $requete->prepare("DELETE FROM commentaire WHERE id = :id");
                $message = "Commentaire supprimé avec succès";
                break;
                
            case 'driver':
                $sql = $requete->prepare("DELETE FROM conducteur WHERE id = :id");
                $message = "Conducteur supprimé avec succès";
                break;
                
            default:
                throw new Exception("Type de suppression invalide");
        }
        
        $sql->bindParam(':id', $del_id, PDO::PARAM_INT);
        $sql->execute();
        
        $_SESSION['success'] = $message;
    } catch (Exception $e) {
        $_SESSION['error'] = "Erreur lors de la suppression: " . $e->getMessage();
    }
    
    header("Location: admin.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Transport App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .admin-container {
            margin-top: 80px;
        }
        .stat-card {
            transition: transform 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .nav-pills .nav-link.active {
            background-color: #2ecc71;
        }
        .table-responsive {
            max-height: 500px;
            overflow-y: auto;
        }
        .action-btns .btn {
            margin-right: 5px;
        }
        .search-container {
            margin-bottom: 15px;
        }
        .search-container input {
            padding: 8px 15px;
            border-radius: 20px;
            border: 1px solid #ddd;
            width: 100%;
            max-width: 400px;
        }
        .no-results {
            text-align: center;
            padding: 20px;
            color: #777;
            font-style: italic;
        }
    </style>
</head>
<body>
    <?php include 'hfc/header.php'; ?>

    <!-- messages de success -->
    <?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= $_SESSION['success'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['success']); endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= $_SESSION['error'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['error']); endif; ?>

    <div class="container admin-container">
        <h1 class="mb-4 text-center"><i class="fas fa-cog me-2"></i>Panneau d'Administration</h1>
        
        <!-- Cartes de statistiques -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card stat-card text-white bg-primary">
                    <div class="card-body text-center">
                        <h5><i class="fas fa-users"></i> Utilisateurs</h5>
                        <h3>
                            <?php 
                            $stmt = $requete->query("SELECT COUNT(*) FROM users");
                            echo $stmt->fetchColumn(); 
                            ?>
                        </h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card text-white bg-success">
                    <div class="card-body text-center">
                        <h5><i class="fas fa-car"></i> Conducteurs</h5>
                        <h3>
                            <?php 
                            $stmt = $requete->query("SELECT COUNT(*) FROM users WHERE role = 'conducteur'");
                            echo $stmt->fetchColumn(); 
                            ?>
                        </h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card text-white bg-info">
                    <div class="card-body text-center">
                        <h5><i class="fas fa-calendar-check"></i> Réservations</h5>
                        <h3>
                            <?php 
                            $stmt = $requete->query("SELECT COUNT(*) FROM reservation");
                            echo $stmt->fetchColumn(); 
                            ?>
                        </h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card text-white bg-warning">
                    <div class="card-body text-center">
                        <h5><i class="fas fa-comments"></i> Commentaires</h5>
                        <h3>
                            <?php 
                            $stmt = $requete->query("SELECT COUNT(*) FROM commentaire");
                            echo $stmt->fetchColumn(); 
                            ?>
                        </h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation par onglets -->
        <ul class="nav nav-pills mb-4" id="adminTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="users-tab" data-bs-toggle="pill" data-bs-target="#users" type="button">
                    <i class="fas fa-users me-1"></i>Utilisateurs
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="reservations-tab" data-bs-toggle="pill" data-bs-target="#reservations" type="button">
                    <i class="fas fa-calendar-alt me-1"></i>Réservations
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="comments-tab" data-bs-toggle="pill" data-bs-target="#comments" type="button">
                    <i class="fas fa-comments me-1"></i>Commentaires
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="drivers-tab" data-bs-toggle="pill" data-bs-target="#drivers" type="button">
                    <i class="fas fa-car me-1"></i>Conducteurs
                </button>
            </li>
        </ul>

        <!-- Contenu des onglets -->
        <div class="tab-content" id="adminTabsContent">
            <!-- Onglet Utilisateurs -->
            <div class="tab-pane fade show active" id="users" role="tabpanel">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h5>Gestion des Utilisateurs</h5>
                        <a href="log/inscription.php" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> Ajouter
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="search-container">
                            <input type="text" id="userSearch" placeholder="Rechercher un utilisateur..." class="form-control">
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nom</th>
                                        <th>Email</th>
                                        <th>Rôle</th>
                                        <th>Inscription</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="userTableBody">
                                    <?php
                                    $stmt = $requete->query("SELECT * FROM users ORDER BY created_at DESC");
                                    while ($user = $stmt->fetch(PDO::FETCH_ASSOC)):
                                    ?>
                                    <tr>
                                        <td><?= $user['id'] ?></td>
                                        <td><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></td>
                                        <td><?= htmlspecialchars($user['email']) ?></td>
                                        <td>
                                            <span class="badge bg-<?= $user['role'] === 'admin' ? 'danger' : ($user['role'] === 'conducteur' ? 'success' : 'info') ?>">
                                                <?= ucfirst($user['role']) ?>
                                            </span>
                                        </td>
                                        <td><?= date('d/m/Y H:i', strtotime($user['created_at'])) ?></td>
                                        <td class="action-btns">
                                            <a href="admin.php?del=<?= $user['id'] ?>&type=user" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cet utilisateur?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                            <div id="userNoResults" class="no-results" style="display: none;">
                                Aucun utilisateur trouvé
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Onglet Réservations -->
            <div class="tab-pane fade" id="reservations" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h5>Gestion des Réservations</h5>
                    </div>
                    <div class="card-body">
                        <div class="search-container">
                            <input type="text" id="reservationSearch" placeholder="Rechercher une réservation..." class="form-control">
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped">
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
                                <tbody id="reservationTableBody">
                                    <?php
                                    $stmt = $requete->query("
                                        SELECT r.*, u.prenom, u.nom 
                                        FROM reservation r
                                        JOIN users u ON r.id_users = u.id
                                        ORDER BY r.created_at DESC
                                    ");
                                    while ($res = $stmt->fetch(PDO::FETCH_ASSOC)):
                                    ?>
                                    <tr>
                                        <td><?= $res['id'] ?></td>
                                        <td><?= htmlspecialchars($res['prenom'] . ' ' . $res['nom']) ?></td>
                                        <td><?= htmlspecialchars($res['depart']) ?></td>
                                        <td><?= htmlspecialchars($res['arriver']) ?></td>
                                        <td><?= date('d/m/Y H:i', strtotime($res['date_time'])) ?></td>
                                        <td><?= number_format($res['prix_estime'], 2) ?> €</td>
                                        <td class="action-btns">
                                            <a href="admin.php?del=<?= $res['id'] ?>&type=reservation" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cette réservation?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                            <div id="reservationNoResults" class="no-results" style="display: none;">
                                Aucune réservation trouvée
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Onglet Commentaires -->
            <div class="tab-pane fade" id="comments" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h5>Gestion des Commentaires</h5>
                    </div>
                    <div class="card-body">
                        <div class="search-container">
                            <input type="text" id="commentSearch" placeholder="Rechercher un commentaire..." class="form-control">
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Auteur</th>
                                        <th>Conducteur</th>
                                        <th>Commentaire</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="commentTableBody">
                                    <?php
                                    $stmt = $requete->query("
                                        SELECT c.*, u.prenom, u.nom 
                                        FROM commentaire c
                                        JOIN users u ON c.id_users = u.id
                                        ORDER BY c.created_at DESC
                                    ");
                                    while ($com = $stmt->fetch(PDO::FETCH_ASSOC)):
                                    ?>
                                    <tr>
                                        <td><?= $com['id'] ?></td>
                                        <td><?= htmlspecialchars($com['prenom'] . ' ' . $com['nom']) ?></td>
                                        <td><?= htmlspecialchars($com['nom_conducteur']) ?></td>
                                        <td><?= htmlspecialchars(substr($com['commentaires'], 0, 50)) ?>...</td>
                                        <td><?= date('d/m/Y', strtotime($com['created_at'])) ?></td>
                                        <td class="action-btns">
                                            <a href="admin.php?del=<?= $com['id'] ?>&type=comment" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ce commentaire?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                            <div id="commentNoResults" class="no-results" style="display: none;">
                                Aucun commentaire trouvé
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Onglet Conducteurs -->
            <div class="tab-pane fade" id="drivers" role="tabpanel">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h5>Gestion des Conducteurs</h5>
                        <a href="log/inscription.php" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> Ajouter
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nom</th>
                                        <th>Transport</th>
                                        <th>Permis</th>
                                        <th>Inscription</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $stmt = $requete->query("
                                        SELECT c.*, u.prenom, u.nom 
                                        FROM conducteur c
                                        JOIN users u ON c.id_users = u.id
                                        ORDER BY c.created_at DESC
                                    ");
                                    while ($driver = $stmt->fetch(PDO::FETCH_ASSOC)):
                                    ?>
                                    <tr>
                                        <td><?= $driver['id'] ?></td>
                                        <td><?= htmlspecialchars($driver['prenom'] . ' ' . $driver['nom']) ?></td>
                                        <td><?= htmlspecialchars($driver['nom_transport']) ?></td>
                                        <td><?= htmlspecialchars($driver['type_permi']) ?></td>
                                        <td><?= date('d/m/Y', strtotime($driver['created_at'])) ?></td>
                                        <td class="action-btns">
                                            <a href="admin.php?del=<?= $driver['id'] ?>&type=driver" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ce conducteur?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Activer le premier onglet
            const firstTab = new bootstrap.Tab(document.getElementById('users-tab'));
            firstTab.show();
            
            // Gestion de la suppression avec confirmation
            document.querySelectorAll('.btn-danger').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    if (!confirm('Êtes-vous sûr de vouloir effectuer cette action ?')) {
                        e.preventDefault();
                    }
                });
            });
            
            // Fonction de recherche pour les utilisateurs
            const userSearch = document.getElementById('userSearch');
            const userTableBody = document.getElementById('userTableBody');
            const userNoResults = document.getElementById('userNoResults');
            
            userSearch.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                let hasResults = false;
                
                Array.from(userTableBody.getElementsByTagName('tr')).forEach(row => {
                    const name = row.cells[1].textContent.toLowerCase();
                    const email = row.cells[2].textContent.toLowerCase();
                    const role = row.cells[3].textContent.toLowerCase();
                    
                    if (name.includes(searchTerm) || email.includes(searchTerm) || role.includes(searchTerm)) {
                        row.style.display = '';
                        hasResults = true;
                    } else {
                        row.style.display = 'none';
                    }
                });
                
                userNoResults.style.display = hasResults ? 'none' : 'block';
            });
            
            // Fonction de recherche pour les réservations
            const reservationSearch = document.getElementById('reservationSearch');
            const reservationTableBody = document.getElementById('reservationTableBody');
            const reservationNoResults = document.getElementById('reservationNoResults');
            
            reservationSearch.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                let hasResults = false;
                
                Array.from(reservationTableBody.getElementsByTagName('tr')).forEach(row => {
                    const client = row.cells[1].textContent.toLowerCase();
                    const depart = row.cells[2].textContent.toLowerCase();
                    const arrivee = row.cells[3].textContent.toLowerCase();
                    const date = row.cells[4].textContent.toLowerCase();
                    const prix = row.cells[5].textContent.toLowerCase();
                    
                    if (client.includes(searchTerm) || depart.includes(searchTerm) || 
                        arrivee.includes(searchTerm) || date.includes(searchTerm) || 
                        prix.includes(searchTerm)) {
                        row.style.display = '';
                        hasResults = true;
                    } else {
                        row.style.display = 'none';
                    }
                });
                
                reservationNoResults.style.display = hasResults ? 'none' : 'block';
            });
            
            // Fonction de recherche pour les commentaires
            const commentSearch = document.getElementById('commentSearch');
            const commentTableBody = document.getElementById('commentTableBody');
            const commentNoResults = document.getElementById('commentNoResults');
            
            commentSearch.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                let hasResults = false;
                
                Array.from(commentTableBody.getElementsByTagName('tr')).forEach(row => {
                    const auteur = row.cells[1].textContent.toLowerCase();
                    const conducteur = row.cells[2].textContent.toLowerCase();
                    const commentaire = row.cells[3].textContent.toLowerCase();
                    const date = row.cells[4].textContent.toLowerCase();
                    
                    if (auteur.includes(searchTerm) || conducteur.includes(searchTerm) || 
                        commentaire.includes(searchTerm) || date.includes(searchTerm)) {
                        row.style.display = '';
                        hasResults = true;
                    } else {
                        row.style.display = 'none';
                    }
                });
                
                commentNoResults.style.display = hasResults ? 'none' : 'block';
            });
        });
    </script>
</body>
</html>