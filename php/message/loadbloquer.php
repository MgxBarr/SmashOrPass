<?php
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['id_utilisateur'])) {
    exit('Utilisateur non connecté.');
}

require_once('../config.php');

if (isset($_POST['interlocuteur_id'])) {
    $id_utilisateur_a_modifier = $_POST['interlocuteur_id'];
    $id_utilisateur_bloquant = $_SESSION['id_utilisateur'];

    $sql = "SELECT * FROM Bloques WHERE id_utilisateur_bloquant = $id_utilisateur_bloquant AND id_utilisateur_bloque = $id_utilisateur_a_modifier";
    $result_sql = $connexion->query($sql);

    if ($result_sql->num_rows > 0) {
        $texte_bloquer = "Débloquer";
    }
    else {
        $texte_bloquer = "Bloquer";
    }

    echo $texte_bloquer;

}

?>
