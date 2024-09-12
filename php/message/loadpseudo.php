<?php
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['id_utilisateur'])) {
    exit('Utilisateur non connecté.');
}

// Vérifie si l'identifiant de l'interlocuteur est défini dans la requête POST
if (!isset($_POST['interlocuteur_id'])) {
    exit('Identifiant de l\'interlocuteur non fourni.');
}

require_once('../config.php');

$user_id = $_SESSION['user_id'];
$interlocuteur_id = $_POST['interlocuteur_id'];



$sql = "SELECT pseudonyme FROM Utilisateurs WHERE id = $interlocuteur_id";
$result_sql = $connexion->query($sql);

if ($result_sql->num_rows > 0) {
    $row = $result_sql->fetch_assoc();
    $interlocuteur_pseudonyme = $row['pseudonyme'];
}

echo $interlocuteur_pseudonyme;

?>
