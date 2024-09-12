<?php
session_start(); 
//verif si user connecté 
if (!isset($_SESSION['id_utilisateur'])) {
    header('Location: index.php');
    exit();
}
require_once('../config.php'); 

$idUser = $_SESSION['id_utilisateur'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search = $_POST['search']; 

    $sql = "SELECT * FROM Utilisateurs WHERE id != $idUser";

    if (!empty($search)) {
        $sql .= " AND (pseudonyme LIKE '%$search%')";
    }

    $result = $connexion->query($sql);

    if ($result->num_rows > 0) {
        $results = array();
        while ($row = $result->fetch_assoc()) {
            $results[] = $row;
        }
        // Stocker les résultats dans une variable de session
        $_SESSION['search_results'] = $results;
    } else {
        echo "<p class='no-results'>Aucun utilisateur trouvé.</p>";
        $_SESSION['search_results'] = array(); 
    }
    $_SESSION['aCherche']=1; 

    // Fermeture de la connexion
    $connexion->close();

    header('Location: ../../admin.php');
    exit();
}