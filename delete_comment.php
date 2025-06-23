<?php
require_once 'hfc/config.php';
session_start();

if (!isset($_SESSION['id_users'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Vérifier si l'utilisateur est admin ou propriétaire du commentaire
    $stmt = $requete->prepare("SELECT id_users FROM commentaire WHERE id = ?");
    $stmt->execute([$id]);
    $comment = $stmt->fetch();
    
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin' || $_SESSION['id_users'] == $comment['id_users']) {
        $delete = $requete->prepare("DELETE FROM commentaire WHERE id = ?");
        $delete->execute([$id]);
        
        header("Location: dashboard.php?delete_success=1");
        exit;
    }
}

header("Location: dashboard.php");
exit;
?>