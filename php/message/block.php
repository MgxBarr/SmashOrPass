<?php
session_start();
require_once('../config.php');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id_utilisateur'])) {
    echo "Erreur : Vous devez être connecté pour bloquer ou débloquer un utilisateur.";
    exit();
}

// Vérifier si l'ID de l'utilisateur à bloquer/débloquer a été envoyé en tant que paramètre POST
if (isset($_POST['id_utilisateur_a_bloquer'])) {

    // Récupérer l'ID de l'utilisateur à bloquer/débloquer et l'ID de l'utilisateur bloquant depuis la session
    $id_utilisateur_a_modifier = $_POST['id_utilisateur_a_bloquer'];
    $id_utilisateur_bloquant = $_SESSION['id_utilisateur'];

    // Vérifier si l'utilisateur est déjà bloqué
    $sql_check_blocked = "SELECT * FROM Bloques WHERE id_utilisateur_bloquant = $id_utilisateur_bloquant AND id_utilisateur_bloque = $id_utilisateur_a_modifier";
    $result_check_blocked = $connexion->query($sql_check_blocked);

    if ($result_check_blocked->num_rows > 0) {
        // L'utilisateur est déjà bloqué, donc le débloquer
        $sql = "DELETE FROM Bloques WHERE id_utilisateur_bloquant = $id_utilisateur_bloquant AND id_utilisateur_bloque = $id_utilisateur_a_modifier";
        if ($connexion->query($sql) === TRUE) {
            //echo "L'utilisateur a été débloqué avec succès.";
        } else {
            //echo "Erreur : " . $sql . "<br>" . $connexion->error;
        }
    } else {
        // L'utilisateur n'est pas bloqué, donc le bloquer
        $sql = "INSERT INTO Bloques (id_utilisateur_bloquant, id_utilisateur_bloque) VALUES ($id_utilisateur_bloquant, $id_utilisateur_a_modifier)";
        if ($connexion->query($sql) === TRUE) {
            //echo "L'utilisateur a été bloqué avec succès.";
        } else {
            //echo "Erreur : " . $sql . "<br>" . $connexion->error;
        }
    }
}
$connexion->close();
?>
