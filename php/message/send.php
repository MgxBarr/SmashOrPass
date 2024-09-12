<?php
/*
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['id_utilisateur'])) {
    header('Location: index.php');
    exit();
}

require_once('config.php');

// Récupère l'ID de l'utilisateur et de l'interlocuteur
$user_id = $_SESSION['id_utilisateur'];
$interlocuteur_id = $_POST['interlocuteur_id']; // Assurez-vous d'avoir cette valeur envoyée depuis votre formulaire

// Récupère le message à envoyer
$message = $_POST['message']; // Assurez-vous d'avoir cette valeur envoyée depuis votre formulaire

// Prépare la requête SQL pour insérer le message dans la base de données
$sql_insert_message = "INSERT INTO Messages (id_sender, id_receiver, message) VALUES ($user_id, $interlocuteur_id, '$message')";

// Exécute la requête SQL pour insérer le message
if ($connexion->query($sql_insert_message) === TRUE) {
    // Message envoyé avec succès, redirigez vers message.php
    header('Location: message.php');
    exit();
} else {
    echo "Erreur lors de l'envoi du message : " . $connexion->error;
}

$connexion->close();
*/

session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['id_utilisateur'])) {
    header('Location: ../../index.php');
    exit();
}

require_once('../config.php');

$user_id = $_SESSION['id_utilisateur'];

if (!isset($_POST['interlocuteur_id']) || !isset($_POST['message'])) {
    //echo "Erreur : Données manquantes.";
    exit();
}

$interlocuteur_id = $_POST['interlocuteur_id'];
$message = $_POST['message'];

$sql_insert_message = "INSERT INTO Messages (id_sender, id_receiver, message) VALUES ($user_id, $interlocuteur_id, '$message')";

$_SESSION['arecumessage'] = 1;

if ($connexion->query($sql_insert_message) === TRUE) {
    //echo "Message envoyé avec succès !";

} else {
    //echo "Erreur lors de l'envoi du message : " . $connexion->error;
}


//$connexion->close();
?>
