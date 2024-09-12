<?php
session_start();

// Assurez-vous que l'utilisateur est connecté avant d'effectuer toute action
if (!isset($_SESSION['id_utilisateur'])) {
    echo "Erreur : Utilisateur non connecté";
    exit();
}

// Connexion à la base de données
require_once('../config.php');

// Récupération de l'ID de l'utilisateur actuel
$id_utilisateur = $_SESSION['id_utilisateur'];
$id_profil_visite = $_POST['id_utilisateur'];

// Vérification si le like existe déjà
$sql_check_like = "SELECT * FROM Likes WHERE id_utilisateur_likeur = $id_utilisateur AND id_utilisateur_like = $id_profil_visite";
$result_check_like = $connexion->query($sql_check_like);

if ($result_check_like->num_rows > 0) {
    // Le like existe, donc on le supprime et on décrémente le compteur de likes
    $sql_remove_like = "DELETE FROM Likes WHERE id_utilisateur_likeur = $id_utilisateur AND id_utilisateur_like = $id_profil_visite";
    if ($connexion->query($sql_remove_like) === TRUE) {
        // Mise à jour du nombre de likes dans la table Utilisateurs (décrémentation)
        $sql_update_likes = "UPDATE Utilisateurs SET compteur_likes = compteur_likes - 1 WHERE id = $id_profil_visite";
        if ($connexion->query($sql_update_likes) === TRUE) {
            // Récupération et affichage du nouveau nombre de likes
            $sql_count = "SELECT compteur_likes FROM Utilisateurs WHERE id = $id_profil_visite";
            $result_count = $connexion->query($sql_count);
            $row_count = $result_count->fetch_assoc();
            $nombre_likes = $row_count['compteur_likes'];
            echo ($nombre_likes == 0 || $nombre_likes == 1) ? $nombre_likes . " Smash" : $nombre_likes . " Smashs";
        } else {
            //echo "Erreur lors de la mise à jour du nombre de likes dans la table Utilisateurs : " . $connexion->error;
        }
    } else {
        //echo "Erreur lors de la suppression du like dans la table Likes : " . $connexion->error;
    }
} else {
    // Le like n'existe pas, donc on l'ajoute et on incrémente le compteur de likes
    $sql_insert_like = "INSERT INTO Likes (id_utilisateur_likeur, id_utilisateur_like) VALUES ($id_utilisateur, $id_profil_visite)";
    if ($connexion->query($sql_insert_like) === TRUE) {
        // Mise à jour du nombre de likes dans la table Utilisateurs (incrémentation)
        $sql_update_likes = "UPDATE Utilisateurs SET compteur_likes = compteur_likes + 1 WHERE id = $id_profil_visite";
        if ($connexion->query($sql_update_likes) === TRUE) {
            // Récupération et affichage du nouveau nombre de likes
            $sql_count = "SELECT compteur_likes FROM Utilisateurs WHERE id = $id_profil_visite";
            $result_count = $connexion->query($sql_count);
            $row_count = $result_count->fetch_assoc();
            $nombre_likes = $row_count['compteur_likes'];
            echo ($nombre_likes == 0 || $nombre_likes == 1) ? $nombre_likes . " Smash" : $nombre_likes . " Smashs";
        } else {
            //echo "Erreur lors de la mise à jour du nombre de likes dans la table Utilisateurs : " . $connexion->error;
        }
    } else {
        //echo "Erreur lors de l'ajout du like dans la table Likes : " . $connexion->error;
    }
}

$connexion->close();
?>
