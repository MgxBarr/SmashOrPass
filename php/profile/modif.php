<?php
session_start();

//verif si user connecté 
if (!isset($_SESSION['id_utilisateur'])) {
    header('Location: ../../index.php');
    exit();
}


ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once('../config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // On rcupere l'id du profil sur lequel on a cliqué sur modifier (si c le notre, ou si en admin on veut modif le profil de quelqu'un)
    $idUser = $_POST['interlocuteur_id']; 

    // Empecher les ' et "
    function escapeQuotes($str) {
        $escaped_str = str_replace(array("'", '"'), array("\\'", '\\"'), $str);
        return $escaped_str;
    }
    
    $prenom = isset($_POST['prenom']) ? escapeQuotes($_POST['prenom']) : null;
    $nom = isset($_POST['nom']) ? escapeQuotes($_POST['nom']) : null;
    $pseudonyme = isset($_POST['pseudonyme']) ? escapeQuotes($_POST['pseudonyme']) : null;
    $sexe = isset($_POST['sexe']) ? strtolower($_POST['sexe']) : null;
    $orientation = isset($_POST['orientation']) ? $_POST['orientation'] : null;
    if ($orientation == 'hétéro') {
        $orientation = 'hetero';
    }
    $age = isset($_POST['age']) && $_POST['age'] !== '' ? $_POST['age'] : 0;
    $profession = isset($_POST['profession']) ? escapeQuotes($_POST['profession']) : null;
    $lieu_residence = isset($_POST['lieu_residence']) ? escapeQuotes($_POST['lieu_residence']) : null;
    $situation_amoureuse = isset($_POST['situation_amoureuse']) ? escapeQuotes($_POST['situation_amoureuse']) : null;
    $informations_personnelles = isset($_POST['informations_personnelles']) ? escapeQuotes($_POST['informations_personnelles']) : null;
    $mdp = isset($_POST['motdepasse']) ? $_POST['motdepasse'] : null;
    $description_physique = isset($_POST['description_physique']) ? escapeQuotes($_POST['description_physique']) : null;
    $adresse = isset($_POST['adresse']) ? escapeQuotes($_POST['adresse']) : null;
    $img_profil = isset($_POST['img_profil-path']) ? $_POST['img_profil-path'] : null;
    $img_1 = isset($_POST['img_1-path']) ? $_POST['img_1-path'] : null;
    $img_2 = isset($_POST['img_2-path']) ? $_POST['img_2-path'] : null;
    $img_3 = isset($_POST['img_3-path']) ? $_POST['img_3-path'] : null;
    $img_4 = isset($_POST['img_4-path']) ? $_POST['img_4-path'] : null;
    $date_de_naissance = isset($_POST['date_de_naissance']) ? strtolower($_POST['date_de_naissance']) : null;


    // Verif si le pseudo est pas deja pris
    $sql_verifierPseudo = "SELECT id FROM Utilisateurs WHERE pseudonyme = '$pseudonyme' AND id != $idUser";
    $resultat = $connexion->query($sql_verifierPseudo);

    if ($resultat->num_rows > 0) {
        $_SESSION['pseudo-deja-utilise'] = 1;
    }
    else {
        $sql_update = "UPDATE Utilisateurs 
        SET pseudonyme = '$pseudonyme'
        WHERE id = $idUser";
        $connexion->query($sql_update);

        $sql_update_username = "UPDATE InfosConnexions 
                        SET username = '$pseudonyme'
                        WHERE id = $idUser";
        $connexion->query($sql_update_username);

        $_SESSION['pseudo-deja-utilise'] = 0;
    }


    // Verif & update du sexe
    $sql_verif_sexe = "SELECT sexe FROM Utilisateurs WHERE id= $idUser";

    $result_verif_sexe = $connexion->query($sql_verif_sexe);

    if ($result_verif_sexe->num_rows > 0) {
        $row = $result_verif_sexe->fetch_assoc();
        $verif_sexe = $row['sexe'];
    }

    if ($sexe == 'femme') {
        $date_souscription = date('Y-m-d');
        $sql_sexe = "UPDATE Abonnes SET date_souscription = '$date_souscription', type_abonnement = 'infini' WHERE id = $idUser";
        $connexion->query($sql_sexe);
    }
    if ($verif_sexe == 'femme' && $sexe == 'homme') {
        $date_souscription = date('Y-m-d');
        $sql_sexe = "UPDATE Abonnes SET date_souscription = '$date_souscription', type_abonnement = 'aucun' WHERE id = $idUser";
        $connexion->query($sql_sexe);
    }

    
    // Modif des infos
    $sql_update = "UPDATE Utilisateurs 
                   SET prenom = '$prenom', 
                       nom = '$nom', 
                       sexe = '$sexe', 
                       age = '$age', 
                       orientation = '$orientation',
                       profession = '$profession', 
                       lieu_residence = '$lieu_residence', 
                       situation_amoureuse = '$situation_amoureuse', 
                       informations_personnelles = '$informations_personnelles', 
                       description_physique = '$description_physique',
                       adresse = '$adresse', 
                       img_profil = '$img_profil',
                       img_1 = '$img_1',
                       img_2 = '$img_2',
                       img_3 = '$img_3',
                       img_4 = '$img_4',
                       date_de_naissance = '$date_de_naissance'
                   WHERE id = $idUser";


    // Modif du mdp
    $sql_update_mdp = "UPDATE InfosConnexions 
                       SET password = '$mdp'
                       WHERE id = $idUser";


    if ($connexion->query($sql_update_mdp) === TRUE) {
        //echo "Mot de passe mis à jour avec succès.";
    } else {
        //echo "Erreur lors de la mise à jour du mot de passe : " . $connexion->error;
    }



    if ($connexion->query($sql_update) === TRUE) {
        //echo "Données mises à jour avec succès.";
        $_SESSION['profile-modif'] = 1;
        if ($idUser == $_SESSION['id_utilisateur']) {
            header('Location: ../../profile.php');
        }
        else {
            header('Location: ../../profile.php?id_utilisateur=' . $idUser);
        }
    } else {
        //echo "Erreur lors de la mise à jour des données : " . $connexion->error;
        $_SESSION['profile-modif'] = 0;
        header('Location: ../../profile.php');
    }

    
    $connexion->close();
}
?>
