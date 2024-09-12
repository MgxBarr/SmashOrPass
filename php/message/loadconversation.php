<?php
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['id_utilisateur'])) {
    exit('Utilisateur non connecté.');
}

// Vérifie si l'identifiant de l'interlocuteur est défini dans la requête POST
if (!isset($_POST['interlocuteur_id'])) {
    exit('Identifiant de l\'interlocuteur non fourni.');
}

require_once('../config.php');

$user_id = $_SESSION['user_id'];
$interlocuteur_id = $_POST['interlocuteur_id'];

// Récupère les messages échangés entre l'utilisateur et l'interlocuteur
$sql_messages = "SELECT * FROM Messages WHERE (id_sender = $user_id AND id_receiver = $interlocuteur_id) OR (id_sender = $interlocuteur_id AND id_receiver = $user_id) ORDER BY timestamp";
$result_messages = $connexion->query($sql_messages);

if ($result_messages->num_rows > 0) {
    // Initialise un tableau pour stocker les messages HTML
    $messages_html = array();
    while ($row = $result_messages->fetch_assoc()) {
        // Génère le HTML pour chaque message
        $message_html = '<div class="' . ($row['id_sender'] == $user_id ? 'message-envoye' : 'message-recu') . '"';
        $message_html .= ' data-id="' . $row['id_message'] . '" onclick="messageAction(\'' . ($row['id_sender'] == $user_id ? 'envoye' : 'recu') . '\', ' . $row['id_message'] . ')">';
        $message_html .= '<div class="' . ($row['id_sender'] == $user_id ? 'message-envoye-msg' : 'message-recu-msg') . '">';
        $message_html .= $row['message'];
        $message_html .= '</div>';
        $timestamp = strtotime($row['timestamp']);
        $date_format = date('d/m/Y - H:i', $timestamp);
        $message_html .= '<span class="message-timing">' . $date_format . '</span>';
        $message_html .= '</div>';
        // Ajoute le HTML du message au tableau
        $messages_html[] = $message_html;
    }
    // Imprime tous les messages HTML
    echo implode('', $messages_html);

} else {
    echo "Aucun message trouvé.";
}
?>
