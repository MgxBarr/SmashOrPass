<?php

session_start(); 

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once('../config.php');


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $pseudonyme = $_POST['pseudonyme'];
    $mdp = $_POST['password'];

    // Vérifier si l'utilisateur est banni
    $sql = "SELECT u.id, u.estBanni 
            FROM Utilisateurs u
            INNER JOIN InfosConnexions i ON u.id = i.id
            WHERE i.username = '$pseudonyme' AND i.password = '$mdp'";
    
    $result = $connexion->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id_utilisateur = $row['id'];
        $banni = $row['estBanni'];

        if ($banni == 1) {
            // L'utilisateur est banni, afficher un message d'erreur et rester sur la page de connexion
            $_SESSION['login-fail'] = 2;
            header('Location: ../../index.php');
            exit();
        } else {
            // L'utilisateur n'est pas banni, stocker l'ID dans la variable de session et rediriger vers la page utilisateur
            $_SESSION['id_utilisateur'] = $id_utilisateur;
            $_SESSION['login-fail'] = 0;
            header("Location: ../../user.php");
            exit();
        }
    } else {
        // Identifiants incorrects, afficher un message d'erreur et rester sur la page de connexion
        $_SESSION['login-fail'] = 1;
        echo "Pseudonyme ou mot de passe incorrect.";
        header('Location: ../../index.php');
    }

    $connexion->close();
}

?>
