<?php
require_once 'hfc/config.php';
session_start();

if (!isset($_SESSION['id_users'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Vérifier si l'utilisateur est admin ou propriétaire de la réservation
    $stmt = $requete->prepare("SELECT id_users FROM reservation WHERE id = ?");
    $stmt->execute([$id]);
    $reservation = $stmt->fetch();
    
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin' || $_SESSION['id_users'] == $reservation['id_users']) {
        $delete = $requete->prepare("DELETE FROM reservation WHERE id = ?");
        $delete->execute([$id]);
        
        header("Location: dashboard.php?delete_success=1");
        exit;
    }
}

header("Location: dashboard.php");
exit;
?>