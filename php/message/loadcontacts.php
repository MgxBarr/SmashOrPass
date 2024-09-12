<?php
    session_start(); 

    require_once('../config.php');

    $user_id = $_SESSION['user_id'];

    // Requête SQL pour récupérer la liste des contacts
    $sql_contacts = "SELECT DISTINCT Utilisateurs.id, Utilisateurs.pseudonyme, Utilisateurs.img_profil, MAX(Messages.timestamp) AS last_message_time
                     FROM Utilisateurs 
                     INNER JOIN Messages 
                     ON (Utilisateurs.id = Messages.id_sender AND Messages.id_receiver = $user_id) 
                     OR (Utilisateurs.id = Messages.id_receiver AND Messages.id_sender = $user_id)
                     WHERE Utilisateurs.estBanni = 0
                     GROUP BY Utilisateurs.id, Utilisateurs.pseudonyme
                     ORDER BY last_message_time DESC";
    
    $result_contacts = $connexion->query($sql_contacts);

    if ($result_contacts->num_rows > 0) {
        // Initialiser une variable pour stocker le HTML de la liste des contacts
        $contacts_html = '';

        // Parcourir les résultats de la requête et générer le HTML pour chaque contact
        while ($row = $result_contacts->fetch_assoc()) {
            $contacts_html .= '<div class="contact-history" onclick="loadconversation(' . $row['id'] . ');activeconversation(this)" data-id="' . $row['id'] . '">';
            $contacts_html .= '<img class="contact-history-picture" src="user-img/'. $row['img_profil'] . '" alt="">';
            $contacts_html .= '<p class="contact-history-pseudo">' . $row['pseudonyme'] . '</p>';
            $contacts_html .= '</div>';
        }

        // Envoyer le HTML de la liste des contacts
        echo $contacts_html;
    } else {
        // Si aucun contact n'est trouvé
        //echo 'Aucun contact trouvé.';
    }
?>
