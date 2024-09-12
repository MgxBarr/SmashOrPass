<?php
session_start(); 
require_once('../config.php');

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['id_utilisateur'])) {
    header('Location: ../../index.php');
    exit();
}


// Vérifiez si l'ID de l'utilisateur à bannir est présent dans l'URL
if(isset($_POST['id-aban'])) {
    $id_utilisateur_a_bannir = $_POST['id-aban'];
    
    // Vérifiez si l'utilisateur est déjà banni ou non
    $sql_select = "SELECT estBanni FROM Utilisateurs WHERE id = $id_utilisateur_a_bannir";
    
    $result = $connexion->query($sql_select);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $banni = $row['estBanni'];

        // Si l'utilisateur est déjà banni, le débannir, sinon le bannir
        if ($banni == 1) {
            $sql_update = "UPDATE Utilisateurs SET estBanni = 0 WHERE id = $id_utilisateur_a_bannir";
            $action = 'ban';
        } else {
            $sql_update = "UPDATE Utilisateurs SET estBanni = 1 WHERE id = $id_utilisateur_a_bannir";
            $action = 'deban';
        }
        
        // Exécuter la mise à jour dans la base de données
        if ($connexion->query($sql_update) === TRUE) {
            //echo "Action réalisée avec succès.";
        } else {
            //echo "Erreur lors du $action de l'utilisateur : " . $connexion->error;
        }
    } else {
        //echo "Aucun utilisateur trouvé avec cet identifiant.";
    }
} else {
    //echo "L'identifiant de l'utilisateur à bannir n'a pas été spécifié.";
}

//$connexion->close();
?>
