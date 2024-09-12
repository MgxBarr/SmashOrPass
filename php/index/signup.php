<?php
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once('../config.php');


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $pseudonyme = $_POST['pseudonyme'];
    $mdp = $_POST['password'];
    $sexe = $_POST['sexe'];
    $date_inscription = date('Y-m-d');

    // Vérifier si le pseudonyme est déjà pris
    $sql_check_pseudo = "SELECT * FROM InfosConnexions WHERE username = '$pseudonyme'";
    $result = $connexion->query($sql_check_pseudo);
    
    if ($result->num_rows > 0) {
        // Le pseudonyme est déjà pris, afficher un message d'erreur
        $_SESSION['inscription_reussie'] = 0;
        header('Location: ../../index.php');
        exit();
    } else {
        // Le pseudonyme n'est pas pris, procéder à l'inscription

        $sql_infos_connexions = "INSERT INTO InfosConnexions (username, password) VALUES ('$pseudonyme', '$mdp')";
        echo "Requête SQL : " . $sql_infos_connexions . "<br>";

        if ($connexion->query($sql_infos_connexions) === TRUE) {
            $id = $connexion->insert_id;

            $sql = "INSERT INTO Utilisateurs (id, pseudonyme, sexe, date_inscription) 
            VALUES ('$id', '$pseudonyme', '$sexe', '$date_inscription')";

            if ($connexion->query($sql) === TRUE) {
                //echo "Données insérées avec succès.";
                // Ajout de l'abonnement
                if ($sexe =='femme' || $sexe =='Femme') {
                    $sql_abonnement = "INSERT INTO Abonnes (id, type_abonnement) VALUES ('$id', 'infini')";
                }
                else {
                    $sql_abonnement = "INSERT INTO Abonnes (id, type_abonnement) VALUES ('$id', 'aucun')";
                }

                if ($connexion->query($sql_abonnement) === TRUE) {
                    //echo "Abonnement ajouté avec succès.";
                } else {
                    //echo "Erreur lors de l'ajout de l'abonnement : " . $connexion->error;
                }
                $_SESSION['inscription_reussie'] = 1;
                header('Location: ../../index.php');
            } else {
                //echo "Erreur lors de l'insertion des données : " . $connexion->error;
                $_SESSION['inscription_reussie'] = 0;
                header('Location: ../../index.php');
            }
        } else {
            //echo "Erreur lors de l'insertion des données dans la table 'InfosConnexions' : " . $connexion->error;
        }
    }

    // Fermeture de la connexion à la base de données
    $connexion->close();
}
?>
