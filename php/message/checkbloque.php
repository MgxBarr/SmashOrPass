<?php
session_start();
require_once('../config.php');

if (isset($_POST['interlocuteur_id'])) {
    $id_utilisateur_actuel = $_SESSION['id_utilisateur'];
    $id_interlocuteur = $_POST['interlocuteur_id'];

    // Requête pour vérifier si l'utilisateur actuel a bloqué l'interlocuteur
    $sql = "SELECT COUNT(*) AS count FROM Bloques WHERE id_utilisateur_bloquant = $id_interlocuteur AND id_utilisateur_bloque = $id_utilisateur_actuel";
    $result = $connexion->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $count = $row['count'];
        // Si l'utilisateur actuel a bloqué l'interlocuteur, renvoyer 1, sinon renvoyer 0
        if ($count > 0) {
            echo "1";
        } else {
            echo "0";
        }
    } else {
        // Erreur lors de l'exécution de la requête
        //echo "Erreur : Impossible de vérifier le blocage.";
    }
}

$connexion->close();
?>
