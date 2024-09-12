<?php
session_start(); 
require_once('../config.php');

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['id_utilisateur'])) {
    header('Location: index.php');
    exit();
}

if(isset($_POST['id_message'])) {

    $id_message = $_POST['id_message'];
    $raison_report = $_POST['raison_report'];

    $sql = "UPDATE Messages SET raison_report = '$raison_report', aEteReport = TRUE WHERE id_message = $id_message";

    if ($connexion->query($sql) === TRUE) {
        //echo "Le message a été signalé avec succès.";
    } else {
        //echo "Erreur lors de la mise à jour du message : " . $connexion->error;
    }

} else {
    //echo "L'ID du message n'a pas été reçu.";
}
?>
