<?php
session_start();
require_once('../config.php');

// Récupérer le type d'abonnement depuis le formulaire
$typeAbonnement = $_POST['type-abonnement'];

$idUser = $_SESSION['id_utilisateur'];  
$date_souscription = date('Y-m-d');

//switch selon le type d'abonnement 
switch ($typeAbonnement) {
    case 1:
        $sql = "UPDATE Abonnes SET date_souscription = '$date_souscription', type_abonnement = 'gratuit' WHERE id = $idUser ;";
        //met aGratuit à 0 (peut bénéficier de l'offre 1 semaine gratuite qu'une fois)
        $sql .= "UPDATE Abonnes SET aGratuit = '0' WHERE id = $idUser";
        break;
    case 2:
        // Requête SQL pour le cas 2
        $sql = "UPDATE Abonnes SET date_souscription = '$date_souscription', type_abonnement = 'mensuel' WHERE id = $idUser";
        break;
    case 3:
        // Requête SQL pour le cas 3
        $sql = "UPDATE Abonnes SET date_souscription = '$date_souscription', type_abonnement = 'trimestriel' WHERE id = $idUser";
        break;
    case 4:
        // Requête SQL pour le cas 4
        $sql = "UPDATE Abonnes SET date_souscription = '$date_souscription', type_abonnement = 'annuel' WHERE id = $idUser";
        break;
    default:
        // Action par défaut si $id ne correspond à aucun des cas précédents
        //echo "ID non valide";
}
//echo $sql; 
if ($connexion->multi_query($sql) === TRUE) {
    $_SESSION['achat_reussi'] = 1;
    header('Location: ../../user.php');
    exit();
} else {
    $_SESSION['achat_reussi'] = 0;
    header('Location: ../../user.php');
    exit();
   // echo "Erreur lors de la mise à jour des données : " . $connexion->error;
}
$connexion->close();
?> 
