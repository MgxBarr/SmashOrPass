<?php
session_start();

// Vérification de l'authentification de l'utilisateur
if (!isset($_SESSION['id_utilisateur'])) {
    header('Location: ../../index.php');
    exit();
}

require_once('../config.php');

// Vérification de la méthode de requête
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_message = $_POST['id_message'];

    $sql = "UPDATE Messages SET aEteReport = 0 WHERE id_message = $id_message";

    if ($connexion->query($sql) === TRUE) {
        //echo "Le signalement du message a été retiré avec succès.";
    } else {
        //echo "Erreur lors du retrait du signalement du message : " . $connexion->error;
    }
} else {
    //echo "Erreur : méthode de requête non autorisée.";
}

// Fermeture de la connexion à la base de données
$connexion->close();
?>
