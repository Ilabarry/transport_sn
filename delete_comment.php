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
        
        try {
            // 1. Supprimer d'abord les réponses liées à ce commentaire
            $delete_replies = $requete->prepare("DELETE FROM reponses_commentaires WHERE comment_id = ?");
            $delete_replies->execute([$id]);
            
            // 2. Ensuite, supprimer le commentaire
            $delete_comment = $requete->prepare("DELETE FROM commentaire WHERE id = ?");
            $delete_comment->execute([$id]);
            
            header("Location: dashboard.php?delete_success=1");
            exit;
            
        } catch (PDOException $e) {
            // Gérer les erreurs de suppression
            $_SESSION['error_message'] = "Erreur lors de la suppression: " . $e->getMessage();
            header("Location: dashboard.php");
            exit;
        }

    }
}

header("Location: dashboard.php");
exit;
?>