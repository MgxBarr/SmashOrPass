<?php
session_start(); 
require_once('../config.php');

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['id_utilisateur'])) {
    header('Location: index.php');
    exit();
}

if(isset($_POST['id_message'])) {
    $message_id = $_POST['id_message'];
    
    $sql = "DELETE FROM Messages WHERE id_message = $message_id";
    
    if ($connexion->query($sql) === TRUE) {
        //echo "Le message a été supprimé avec succès.";
    } else {
        //echo "Erreur lors de la suppression du message : " . $connexion->error;
    }
} else {
    //echo "L'identifiant du message à supprimer n'a pas été spécifié.";
}

$connexion->close();
?>
