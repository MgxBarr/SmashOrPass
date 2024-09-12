
<?php
    session_start(); 
    //verif si user connecté 
    if (!isset($_SESSION['id_utilisateur'])) {
        header('Location: index.php');
        exit();
    }

    require_once('php/config.php');

    $user_id = $_SESSION['id_utilisateur'];
    $_SESSION['user_id'] = $_SESSION['id_utilisateur'];


    // Si c'est l'admin qui look la messagerie d'un user
    $isAdmin = false;
    $sql_check_admin = "SELECT estAdmin FROM Utilisateurs WHERE id = $user_id";
    $result_check_admin = $connexion->query($sql_check_admin);
    if ($result_check_admin->num_rows > 0) {
        $row = $result_check_admin->fetch_assoc();
        $isAdmin = $row['estAdmin'];
    }

    // Si l'utilisateur est un administrateur et qu'un ID utilisateur est spécifié dans l'URL, utilisez cet ID
    if ($isAdmin && isset($_GET['id_utilisateur'])) {
        $user_id = $_GET['id_utilisateur'];
        $_SESSION['user_id'] = $user_id;
    }
    


    // VERIFIE L'ABONNEMENT
    $sql_check_abonnement = "SELECT type_abonnement FROM Abonnes WHERE id = $user_id";
    $result_check_abonnement = $connexion->query($sql_check_abonnement);

    if ($result_check_abonnement->num_rows > 0) {
        $row = $result_check_abonnement->fetch_assoc();
        $type_abonnement = $row['type_abonnement'];

        if ($type_abonnement == 'aucun' && $isAdmin==0) {
            $_SESSION['abonne_toi'] = 1;
            header('Location: user.php');
            exit();
        }
    }



    // RECUPERE LES CONTACTS
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
        $contacts = array();
        while ($row = $result_contacts->fetch_assoc()) {
            $contacts[] = $row;
        }
    }
    

    // RECUPERE L'ID DE L'INTERLOCUTEUR PAR DEFAUT (LA DERNIERE CONVERSATION)
    $sql_default_interlocuteur = "SELECT id_sender, id_receiver FROM Messages WHERE id_sender = $user_id OR id_receiver = $user_id ORDER BY timestamp DESC LIMIT 1";
    $result_default_interlocuteur = $connexion->query($sql_default_interlocuteur);

    if ($result_default_interlocuteur->num_rows > 0) {
        $row_default_interlocuteur = $result_default_interlocuteur->fetch_assoc();
        // Vérifiez si l'utilisateur courant est l'expéditeur ou le destinataire de la dernière conversation
        if ($row_default_interlocuteur['id_sender'] == $user_id) {
            $default_interlocuteur_id = $row_default_interlocuteur['id_receiver'];
        } else {
            $default_interlocuteur_id = $row_default_interlocuteur['id_sender'];
        }
        // Assurez-vous que l'ID de l'interlocuteur par défaut est différent de l'ID de l'utilisateur courant
        if ($default_interlocuteur_id != $user_id) {
            // Récupérez le pseudonyme de l'interlocuteur par défaut
            $sql_default_interlocuteur_pseudo = "SELECT pseudonyme FROM Utilisateurs WHERE id = $default_interlocuteur_id";
            $result_default_interlocuteur_pseudo = $connexion->query($sql_default_interlocuteur_pseudo);
            if ($result_default_interlocuteur_pseudo->num_rows > 0) {
                $row_default_interlocuteur_pseudo = $result_default_interlocuteur_pseudo->fetch_assoc();
                $interlocuteur_pseudonyme = $row_default_interlocuteur_pseudo['pseudonyme'];
            }
        }
    }

    if (isset($_POST['interlocuteur_id'])) {
        $interlocuteur_id = $_POST['interlocuteur_id'];
        // Utilisez l'ID de l'interlocuteur pour récupérer leur identifiant de connexion
        $sql = "SELECT id, pseudonyme FROM Utilisateurs WHERE id = $interlocuteur_id";
        $result_sql = $connexion->query($sql);
        
        if ($result_sql->num_rows > 0) {
            $row = $result_sql->fetch_assoc();
            $interlocuteur_id = $row['id'];
            $interlocuteur_pseudonyme = $row['pseudonyme'];
            $_SESSION['interlocuteur_id'] = $interlocuteur_id;
            $_SESSION['interlocuteur_pseudonyme'] = $interlocuteur_pseudonyme;

            // Vérifiez si une conversation existe entre l'utilisateur actuel et l'utilisateur cible
            $sql_check_conversation = "SELECT id_message FROM Messages WHERE (id_sender = $user_id AND id_receiver = $interlocuteur_id) OR (id_sender = $interlocuteur_id AND id_receiver = $user_id)";
            $result_check_conversation = $connexion->query($sql_check_conversation);

            if ($result_check_conversation->num_rows == 0) {
                // Si aucune conversation n'existe, insérez une nouvelle conversation
                $sql_insert_conversation = "INSERT INTO Messages (id_sender, id_receiver, message, timestamp) VALUES ($user_id, $interlocuteur_id, '[Debut de la conversation]', NOW())";
                $connexion->query($sql_insert_conversation);
                $create_conv = 1;
                $new_interlocuteur_id = $interlocuteur_id;
            }
        }
    } else {
        $interlocuteur_id = $default_interlocuteur_id;
    }


    /*
        //RECUPERE LES MSG
    $sql_messages = "SELECT * FROM Messages WHERE (id_sender = $user_id AND id_receiver = $interlocuteur_id) OR (id_sender = $interlocuteur_id AND id_receiver = $user_id) ORDER BY timestamp";

    $result_messages = $connexion->query($sql_messages);

    if ($result_messages->num_rows > 0) {
        // Initialisez un tableau pour stocker les messages
        $messages = array();

        while ($row = $result_messages->fetch_assoc()) {
            $messages[] = $row;
        }
    } else {
        echo "Aucun message trouvé.";
    }
    */

    $_SESSION['arecumessage'] = 0;

?>






<!DOCTYPE html>
<html lang="en">
<head>
    <!-- PAGE SETTINGS -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/message.css">
    <link rel="shortcut icon" href="assets/icon.png" type="image/x-icon">
    <title>Messagerie - Smash OR Pass</title>

    <!-- FONT -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dosis:wght@200..800&family=Nunito:ital,wght@0,200..1000;1,200..1000" rel="stylesheet">
</head>
<body>

    <!--HEADER-->
    <div class="header">
        <div class="logo"><a href="user.php"><img src="assets/logo.png" alt="logo"></a></div>
        <div class="subscribe">
            <input class="sub-btn" type="button" onclick="location.href = 'user.php'" value="S'abonner">
        </div>
        <div class="menu">
            <?php if ($isAdmin == 1): ?>
                <a class="menu-admin-btn" href="admin.php">Menu Admin</a>
            <?php endif; ?>
            <a class="menu-item" href="user.php#research" ><img class="menu-icon" src="assets/research.png" alt="Rechercher">Rechercher</a>
            <a class="menu-item" href="message.php" ><img class="menu-icon" src="assets/messaging.png" alt="Messagerie">Messagerie</a>
            <a class="menu-item menu-item-profile" href="profile.php" ><img class="menu-icon" src="assets/user.png" alt="Profil">Profil</a>
            <input class="disconnect-btn" type="button" onclick="location.href = 'php/logout.php'" value="Se déconnecter">
        </div>
    </div>

    <div class="main">
        <div class="header-offset">
        </div>

        <div class="message-interface">
            <div class="message-contacts">
                <div class="message-contacts-list">
                    <?php foreach ($contacts as $contact): ?>
                        <div class="contact-history" onclick="loadconversation(<?= $contact['id'] ?>);activeconversation(this);" data-id="<?= $contact['id'] ?>">
                            <img class="contact-history-picture" src="user-img/<?= $contact['img_profil'] ?>" alt="contact-picture">
                            <p class="contact-history-pseudo"><?= $contact['pseudonyme'] ?></p>
                        </div>
                    <?php endforeach; ?>
                    <script>
                        function loadContacts() {
                            var xhr = new XMLHttpRequest();
                            xhr.open('GET', 'php/message/loadcontacts.php', true);
                            xhr.onreadystatechange = function() {
                                if (xhr.readyState === XMLHttpRequest.DONE) {
                                    document.querySelector('.message-contacts-list').innerHTML = xhr.responseText;
                                    var firstContact = document.querySelector('.contact-history');
                                    if (firstContact) {
                                        activeconversation(firstContact);
                                    }
                                }
                            };
                            xhr.send();
                        }
                    </script>

                </div>

            </div>

            <div class="message-messaging">
                <div class="message-conversation-infos">
                    <a id="interlocuteur_pseudo" href="profile.php?id_utilisateur=" class="message-conversation-user"><?php echo $interlocuteur_pseudonyme  ?></a>
                    <div class="message-conversation-btns">
                        <input class="message-conversation-action-btn" type="button" value="" disabled>
                        <?php if (!isset($_GET['id_utilisateur'])): ?>
                            <input class="message-conversation-action-btn" id="block-unblock-btn" type="button" value="Bloquer">
                        <?php endif; ?>
                    </div>
                </div>

                <div id="block-popup" class="popup">
                    <div class="popup-content">
                        <div id="blockForm">
                            <p>Voulez vous vraiment bloquer cet utilisateur ?</p>
                            <div class="popup-btns">
                                <input type="button" class="cancel-btn" onclick="closePopup('block-popup')" value="Annuler">
                                <input type="button" class="confirm-btn" id="confirm-block-btn" onclick="confirmBloquer()" value="Bloquer">
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    // Fonctions pour bloquer un utilisateur
                    function bloquer(id_utilisateur) {
                        openPopup('block-popup');
                        var confirmDeleteBtn = document.getElementById('confirm-block-btn');
                        confirmDeleteBtn.onclick = function() {
                            confirmBloquer(id_utilisateur);
                        };
                    }

                    function confirmBloquer(id_utilisateur) {
                                               
                        var formData = new FormData();
                        formData.append('id_utilisateur_a_bloquer', id_utilisateur);
                        
                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', 'php/message/block.php', true);
                        xhr.onreadystatechange = function() {
                            if (xhr.readyState === XMLHttpRequest.DONE) {
                                loadconversation(document.getElementById('interlocuteur_id').value);
                            }
                        };
                        xhr.send(formData);
                        closePopup('block-popup');
                    }
                </script>

                <div id="message-conversation" class="message-conversation">
                    <?php foreach ($messages as $message): ?>
                        <?php if ($message['id_sender'] == $user_id): ?>
                            <!-- Message envoyé par l'utilisateur -->
                            <div class="message-envoye" data-id="<?= $message['id_message'] ?>" onclick="messageAction('envoye', <?= $message['id_message'] ?>)">
                                <div class="message-envoye-msg">
                                    <?= $message['message'] ?>
                                </div>
                                <span class="message-timing"><?= $message['timestamp'] ?></span>
                            </div>
                        <?php else: ?>
                            <!-- Message reçu par l'utilisateur -->
                            <div class="message-recu" data-id="<?= $message['id_message'] ?>" onclick="messageAction('recu', <?= $message['id_message'] ?>)">
                                <div class="message-recu-msg">
                                    <?= $message['message'] ?>
                                </div>
                                <span class="message-timing"><?= $message['timestamp'] ?></span>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                
                <form class="message-conversation-panel" id="message-form" action="php/message/send.php" method="POST">
                    <input type="hidden" id="interlocuteur_id" name="interlocuteur_id" value="<?= $interlocuteur_id ?>">
                    <input id="message-input" class="message-input" type="text" name="message" placeholder="Ecrire un message ...">
                    <input id="message-send-btn" class="message-send-btn" type="submit" value="Envoyer">
                </form>

                <script>
                    //Fonction pour selectionner par defaut la conversation la plus récente
                    document.addEventListener('DOMContentLoaded', function() {
                        var firstContact = document.querySelector('.contact-history');
                        if (firstContact) {
                            firstContact.click();
                        }
                    });

                    //Fonction pour charger la conversation 
                    function loadconversation(interlocuteur_id) {
                        //messages
                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', 'php/message/loadconversation.php', true);
                        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                        xhr.onreadystatechange = function() {
                            if (xhr.readyState === XMLHttpRequest.DONE) {
                                document.getElementById('message-conversation').innerHTML = xhr.responseText;
                                document.getElementById('message-conversation').scrollTop = document.getElementById('message-conversation').scrollHeight;
                                updateMessageActions();
                            }
                        };
                        xhr.send('interlocuteur_id=' + interlocuteur_id);

                        //pseudo
                        var xhr2 = new XMLHttpRequest();
                        xhr2.open('POST', 'php/message/loadpseudo.php', true);
                        xhr2.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                        xhr2.onreadystatechange = function() {
                            if (xhr2.readyState === XMLHttpRequest.DONE) {
                                document.getElementById('interlocuteur_pseudo').innerHTML = xhr2.responseText;
                                document.getElementById('interlocuteur_pseudo').setAttribute("href", "profile.php?id_utilisateur=" + interlocuteur_id);
                            }
                        };
                        xhr2.send('interlocuteur_id=' + interlocuteur_id);

                        document.getElementById('interlocuteur_id').value = interlocuteur_id;

                        //bouton bloquer debloquer
                        <?php if (!isset($_GET['id_utilisateur'])): ?>
                        var xhr3 = new XMLHttpRequest();
                        xhr3.open('POST', 'php/message/loadbloquer.php', true);
                        xhr3.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                        xhr3.onreadystatechange = function() {
                            if (xhr3.readyState === XMLHttpRequest.DONE) {
                                document.getElementById('block-unblock-btn').value = xhr3.responseText;
                                var btnBloquer = document.getElementById('block-unblock-btn');
                                var blockFormParagraph = document.querySelector('#blockForm p');
                                btnBloquer.setAttribute('onclick', 'bloquer('+interlocuteur_id+')');
                                var messageInput = document.getElementById('message-input');
                                if (btnBloquer.value == 'Bloquer') {
                                    blockFormParagraph.textContent = 'Voulez-vous vraiment bloquer cet utilisateur ?';
                                    document.getElementById('confirm-block-btn').value = 'Bloquer';
                                    messageInput.disabled = false;
                                    messageInput.placeholder = "Ecrire un message ...";
                                    document.getElementById('message-send-btn').disabled = false;
                                } else {
                                    blockFormParagraph.textContent = 'Voulez-vous vraiment débloquer cet utilisateur ?';
                                    document.getElementById('confirm-block-btn').value = 'Débloquer';
                                    messageInput.disabled = true;
                                    messageInput.placeholder = "Vous avez bloqué cet utilisateur";
                                    document.getElementById('message-send-btn').disabled = true;
                                }
                            }
                        };
                        xhr3.send('interlocuteur_id=' + interlocuteur_id);
                        <?php endif; ?>

                        var xhr4 = new XMLHttpRequest();
                        xhr4.open('POST', 'php/message/checkbloque.php', true);
                        xhr4.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                        xhr4.onreadystatechange = function() {
                            if (xhr4.readyState === XMLHttpRequest.DONE) {
                                if (xhr4.responseText === '1') {
                                    document.getElementById('message-input').disabled = true;
                                    document.getElementById('message-input').placeholder = "Cet utilisateur vous a bloqué";
                                    document.getElementById('message-send-btn').disabled = true;
                                }
                            }
                        };
                        xhr4.send('interlocuteur_id=' + interlocuteur_id);
                    }
                    


                    //Fonction pour selectionner la conversation actuelle (contacts)
                    function activeconversation(c) {
                        // Réinitialisation de la couleur de tous les contacts
                        var contacts = document.querySelectorAll('.contact-history');
                        contacts.forEach(function(item) {
                            item.style.backgroundColor = '#fafafa';
                        });
                        
                        // Couleur du contact selectionné
                        c.style.backgroundColor = '#cc3e3e3b';
                    }


                    // Fonction pour gerer les messages si Admin
                    function updateMessageActions() {
                        <?php if ($isAdmin == 1): ?>
                            var messagesRecus = document.querySelectorAll('.message-recu');
                            messagesRecus.forEach(function(message) {
                                var idMessage = message.getAttribute('data-id');
                                message.setAttribute('onclick', 'messageAction("envoye", ' + idMessage + ')');
                            });
                        <?php endif; ?>
                    }
                </script>  

                
                <?php
                    if ($result_check_conversation->num_rows > 0) {
                        // La conversation existe déjà, chargez-la
                        echo "
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    var contacts = document.querySelectorAll('.contact-history');
                                    contacts.forEach(function(contact) {
                                        if (contact.getAttribute('data-id') == $interlocuteur_id) {
                                            contact.click($interlocuteur_id);
                                        }
                                    });
                                });
                            </script>";
                    }
                ?>

                <script>
                    // Fonction pour envoyer un message
                    document.getElementById('message-form').addEventListener('submit', function(event) {
                        event.preventDefault();

                        var formData = new FormData(this);

                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', 'php/message/send.php', true);
                        xhr.onreadystatechange = function() {
                            if (xhr.readyState === XMLHttpRequest.DONE) {
                                document.getElementById('message-input').value = '';
                            }
                        };
                        xhr.send(formData);

                        loadContacts();
                        loadconversation(document.getElementById('interlocuteur_id').value);
                    });
                </script>
                


                <div id="delete-popup" class="popup">
                    <div class="popup-content">
                        <div id="deleteForm">
                            <p>Voulez vous vraiment supprimer ce message ?</p>
                            <div class="popup-btns">
                                <button class="cancel-btn" onclick="closePopup('delete-popup')">Annuler</button>
                                <button class="confirm-btn" id="confirm-delete-btn" onclick="confirmDelete()">Supprimer</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="report-popup" class="popup">
                    <div class="popup-content">
                        <div id="reportForm">
                            <p>Voulez vous vraiment signaler ce message ?</p>
                            <div class="report-reason">
                                <div class="input-select">
                                <div class="dropdown">
                                    <div class="select">
                                        <span>Raison</span>
                                    </div>
                                    <input type="hidden" id="raison_report" name="raison_report" required="required">
                                    <ul class="dropdown-menu">
                                        <li id="contenu-inapproprie">Contenu inapproprié</li>
                                        <li id="spam">Spam</li>
                                        <li id="harcelement">Harcèlement</li>
                                        <li id="contenu-trompeur">Contenu trompeur</li>
                                        <li id="violation-droits-auteur">Violation des droits d'auteur</li>
                                        <li id="contenu-sensible">Contenu sensible</li>
                                        <li id="non-pertinent">Non pertinent</li>
                                        <li id="probleme-technique">Problème technique</li>
                                        <li id="incitation-violence">Incitation à la violence</li>
                                        <li id="autre">Autre</li>
                                    </ul>
                                </div>
                            </div>
                            <script>
                                    document.addEventListener('DOMContentLoaded', function () {
                                        var dropdowns = document.querySelectorAll('.dropdown');
                            
                                        dropdowns.forEach(function (dropdown) {
                                            var originalTitle = dropdown.querySelector('span').textContent;
                                            var menu = dropdown.querySelector('.dropdown-menu');
                            
                                            dropdown.addEventListener('click', function () {
                                                this.setAttribute('tabindex', 1);
                                                this.focus();
                                                this.classList.toggle('active');
                                                if (menu.style.display === 'block') {
                                                    menu.style.display = 'none';
                                                } else {
                                                    menu.style.display = 'block';
                                                }
                                            });
                            
                                            dropdown.addEventListener('focusout', function () {
                                                this.classList.remove('active');
                                                menu.style.display = 'none';
                                            });
                            
                                            var menuItems = dropdown.querySelectorAll('.dropdown-menu li');
                                            menuItems.forEach(function (item) {
                                                item.addEventListener('click', function () {
                                                    var dropdownSpan = this.closest('.dropdown').querySelector('span');
                                                    dropdownSpan.textContent = this.textContent;
                                                    var input = this.closest('.dropdown').querySelector('input');
                                                    input.value = this.getAttribute('id');

                                                    var confirmReportBtn = document.getElementById('confirm-report-btn');
                                                    confirmReportBtn.disabled = false;
                                                });
                                            });
                    
                                        });
                                    });
                                </script>
                            </div>
                            <div class="popup-btns">
                                <button class="cancel-btn" id="cancel-report-btn" onclick="closePopup('report-popup')">Annuler</button>
                                <button class="confirm-btn" id="confirm-report-btn" onclick="confirmReport()">Signaler</button>
                            </div>
                        </div>
                        <script>
                            // Reset le select de la raison
                            var cancelBtn = document.getElementById('cancel-report-btn');
                            var confirmBtn = document.getElementById('confirm-report-btn');

                            cancelBtn.addEventListener('click', function () {
                                var dropdownSpan = document.querySelector('.dropdown .select span');
                                dropdownSpan.textContent = 'Raison'; // Réinitialiser le texte du menu déroulant
                                var dropdownInput = document.getElementById('raison_report');
                                dropdownInput.value = ''; // Réinitialiser la valeur cachée du menu déroulant
                                var confirmReportBtn = document.getElementById('confirm-report-btn');
                                confirmReportBtn.disabled = true; // Désactiver le bouton "Signaler"
                            });

                            confirmBtn.addEventListener('click', function () {
                                var dropdownSpan = document.querySelector('.dropdown .select span');
                                dropdownSpan.textContent = 'Raison'; // Réinitialiser le texte du menu déroulant
                                var dropdownInput = document.getElementById('raison_report');
                                dropdownInput.value = ''; // Réinitialiser la valeur cachée du menu déroulant
                                var confirmReportBtn = document.getElementById('confirm-report-btn');
                                confirmReportBtn.disabled = true; // Désactiver le bouton "Signaler"
                            });

                        </script>
                    </div>
                </div>
                
                <script>
                    // Gestion du menu Signaler / Supprimer
                    document.addEventListener('DOMContentLoaded', function() {
                        const actionBtn = document.querySelector('.message-conversation-action-btn');
                
                        window.messageAction = function(type, id_message) {
                            if (type === 'recu') {
                                actionBtn.value = 'Signaler';
                                actionBtn.onclick = function() { signaler(id_message); }; // Passer l'ID du message à la fonction signaler
                            } else if (type === 'envoye') {
                                actionBtn.value = 'Supprimer';
                                actionBtn.onclick = function() { supprimer(id_message); }; // Passer l'ID du message à la fonction supprimer
                            }
                            actionBtn.disabled = false; // Rendre le bouton cliquable
                        };
                
                        document.querySelector('.message-conversation').addEventListener('click', function(event) {
                            if (!event.target.closest('.message-recu') && !event.target.closest('.message-envoye')) {
                                actionBtn.value = '';
                                actionBtn.disabled = true; // Rendre le bouton non cliquable
                            }
                        });
                    });



                    function supprimer(id_message) {
                        openPopup('delete-popup');
                        var confirmDeleteBtn = document.getElementById('confirm-delete-btn');
                        confirmDeleteBtn.onclick = function() {
                            confirmDelete(id_message);
                        };
                    }

                    function signaler(id_message) {
                        openPopup('report-popup');
                        var confirmReportBtn = document.getElementById('confirm-report-btn');
                        confirmReportBtn.disabled = true;
                        confirmReportBtn.onclick = function() {
                            confirmReport(id_message);
                        };
                    }

                    function confirmDelete(id_message) {
                        
                        var formData = new FormData();
                        formData.append('id_message', id_message);
                        
                        // Envoyer les données au script PHP avec AJAX
                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', 'php/message/delete.php', true);
                        xhr.onreadystatechange = function() {
                            if (xhr.readyState === XMLHttpRequest.DONE) {
                                // Recharger la conversation après la suppression du message
                                loadconversation(document.getElementById('interlocuteur_id').value);
                            }
                        };
                        xhr.send(formData);
                        closePopup('delete-popup');
                    }

                    function confirmReport(id_message) {

                        var formData = new FormData();
                        formData.append('id_message', id_message);
                        var raisonReport = document.getElementById('raison_report').value;
                        formData.append('raison_report', raisonReport);
                        
                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', 'php/message/report.php', true);
                        xhr.onreadystatechange = function() {
                            if (xhr.readyState === XMLHttpRequest.DONE) {
                                loadconversation(document.getElementById('interlocuteur_id').value);
                            }
                        };
                        xhr.send(formData);
                        closePopup('report-popup');
                    }

                    // Fonction pour ouvrir les popup
                    function openPopup(id) {
                        document.body.style.overflow="hidden";
                        document.getElementById(id).style.display='flex';
                    }

                    // Fonction pour fermer les popup
                    function closePopup(id) {
                        document.getElementById(id).style.display = "none";
                        document.body.style.overflow="";
                    }

                    window.addEventListener('click', function(event) {
                        var popups = document.querySelectorAll('.popup');
                        popups.forEach(function(popup) {
                            if (event.target == popup) {
                                closePopup(popup.id);
                            }
                        });
                    });
                </script>
            </div>

            <script>
                // VERIFIE SI UN MESSAGE A ETE RECU POUR ACTUALISER LA CONV
                setInterval(function() {
                    var xhr6 = new XMLHttpRequest();
                    xhr6.open('GET', 'php/message/checkmessages.php', true);
                    xhr6.onreadystatechange = function() {
                        if (xhr6.readyState === XMLHttpRequest.DONE) {
                            console.log(xhr6.responseText);
                            loadconversation(document.getElementById('interlocuteur_id').value);
                            if (xhr6.responseText == '1') {
                                loadconversation(document.getElementById('interlocuteur_id').value);
                                var resetXhr = new XMLHttpRequest();
                                resetXhr.open('GET', 'php/message/resetmessages.php', true);
                                resetXhr.send();
                            }
                        }
                    };
                    xhr6.send();
                }, 1000); 
            </script>
        </div>
    </div>

    <?php if (($isAdmin == 1) && isset($_GET['id_utilisateur'])): ?>
        <script>
            // Si on est en admin désactive la possibilité d'envoyer des messages
            document.addEventListener('DOMContentLoaded', function() {
                var messageInput = document.getElementById('message-input');
                messageInput.disabled = true;
                messageInput.placeholder = "ADMIN : VOUS NE POUVEZ PAS ENVOYER DE MESSAGE";
                document.getElementById('message-send-btn').disabled = true;
                
            });
        </script>
    <?php endif; ?>


    
    <?php if ($create_conv == 1): ?>
        <script>
            window.addEventListener('DOMContentLoaded', function() {
            loadconversation(<?php echo $new_interlocuteur_id ?>); 
            loadContacts();
            });
        </script>
    <?php endif; ?>




    <div class="footer">
    </div>

</body>
</html>
