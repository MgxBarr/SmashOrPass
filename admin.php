
<?php
    session_start(); 
    
    // VERIF CONNECTE
    if (!isset($_SESSION['id_utilisateur'])) {
        header('Location: index.php');
        exit();
    }

    require_once('php/config.php');

    // VERIF ADMIN
    $user_id = $_SESSION['id_utilisateur'];
    $sql_check_admin = "SELECT estAdmin FROM Utilisateurs WHERE id = $user_id";
    $result_check_admin = $connexion->query($sql_check_admin);

    if ($result_check_admin->num_rows > 0) {
        $row_check_admin = $result_check_admin->fetch_assoc();
        $isAdmin = $row_check_admin['estAdmin'];
    } 
    if ($isAdmin == 0) {
        header('Location: user.php');
        exit();
    }
?>






<!DOCTYPE html>
<html lang="en">
<head>
    <!-- PAGE SETTINGS -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="shortcut icon" href="assets/icon.png" type="image/x-icon">
    <title>Menu Admin - Smash OR Pass</title>

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
        
        <div class="admin-menu">
            <?php
                // RECUPERE LA LISTE DES USERS
                $users = array();

                $idUser = $_SESSION['id_utilisateur'];
                $sql = "SELECT * FROM Utilisateurs WHERE id != $idUser";
                $result = $connexion->query($sql);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $users[] = $row;
                    }
                } else {
                    //echo "Aucun utilisateur trouvé.";
                }
            ?>

            <div class="users-list">
                <h1 class="title">Liste des utilisateurs</h1>

                    <!-- barre de recherche --> 
                    <form id="searchForm" name="searchForm" action="php/admin/researchAdmin.php" method="POST">
                        <div class="inner-form">
                            <div class="basic-search">
                                <div class="input-field">
                                    <input class="search-field" id="search" name="search" type="text" placeholder="Rechercher" />
                                    <div class="icon-wrap" onclick="document.getElementById('searchForm').submit()">
                                    <img class="search-icon"src="assets/research.png" alt="research">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form> 

                <div class="users-list-list">

                    <?php 
                    if (isset($_SESSION['aCherche']) && $_SESSION['aCherche'] == 1) {
                        // Affichage des résultats de la recherche
                        if (isset($_SESSION['search_results'])) {
                            $users = $_SESSION['search_results'];
                            foreach ($users as $user) :
                    ?>
                    <div class="line-item" id="user-<?php echo $user['id']?>" data-id="<?php echo $user['id']?>">
                        <img class="profile-picture" src="user-img/<?php echo $user['img_profil']; ?>" alt="profile-picture">
                        <?php if ($user['estBanni'] == 1) : ?>
                            <p class="profile-pseudonyme pseudo-banni"><?php echo $user['pseudonyme']; ?></p>
                        <?php else : ?>
                            <p class="profile-pseudonyme"><?php echo $user['pseudonyme']; ?></p>
                        <?php endif; ?>
                        <div class="profile-buttons">
                            <input class="profile-btn" type="button" onclick="location.href='profile.php?id_utilisateur=<?php echo $user['id']; ?>'" value="Profil">
                            <input class="profile-btn" type="button" onclick="location.href='message.php?id_utilisateur=<?php echo $user['id']; ?>'" value="Messagerie">
                            <?php if ($user['estBanni'] == 1) : ?>
                                <input class="profile-btn ban" type="button" onclick="openConfirmationPopup(<?php echo $user['id']; ?>)" value="Débannir">
                            <?php else : ?>
                                <input class="profile-btn ban" type="button" onclick="openConfirmationPopup(<?php echo $user['id']; ?>)" value="Bannir">
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php
                        endforeach;
                        } else {
                            echo "<p class='no-results'>Aucun résultat trouvé.</p>";
                        }
                    } else {
                        // Affichage de tous les utilisateurs 
                        foreach ($users as $user) :
                    ?>
                        <div class="line-item" id="user-<?php echo $user['id']?>" data-id="<?php echo $user['id']?>">
                            <img class="profile-picture" src="user-img/<?php echo $user['img_profil']; ?>" alt="profile-picture">
                            <?php if ($user['estBanni'] == 1) : ?>
                                <p class="profile-pseudonyme pseudo-banni"><?php echo $user['pseudonyme']; ?></p>
                            <?php else : ?>
                                <p class="profile-pseudonyme"><?php echo $user['pseudonyme']; ?></p>
                            <?php endif; ?>
                            <div class="profile-buttons">
                                <input class="profile-btn" type="button" onclick="location.href='profile.php?id_utilisateur=<?php echo $user['id']; ?>'" value="Profil">
                                <input class="profile-btn" type="button" onclick="location.href='message.php?id_utilisateur=<?php echo $user['id']; ?>'" value="Messagerie">
                                <?php if ($user['estBanni'] == 1) : ?>
                                    <input class="profile-btn ban" type="button" onclick="openConfirmationPopup(<?php echo $user['id']; ?>)" value="Débannir">
                                <?php else : ?>
                                    <input class="profile-btn ban" type="button" onclick="openConfirmationPopup(<?php echo $user['id']; ?>)" value="Bannir">
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php
                        endforeach;
                    }
                    ?>
                </div>
            </div>
    
            <?php
                // RECUPERE LA LISTE DES MSG REPORT
                $messages_report = array();

                $sql_messages_reported = "SELECT * FROM Messages                 
                INNER JOIN Utilisateurs ON Messages.id_sender = Utilisateurs.id 
                WHERE Utilisateurs.estBanni = 0 AND aEteReport = TRUE";
                
                $result_messages_reported = $connexion->query($sql_messages_reported);
                
                if ($result_messages_reported->num_rows > 0) {
                    while ($row = $result_messages_reported->fetch_assoc()) {
                        $messages_report[] = $row;
                    }
                } else {
                    //echo "Aucun message signalé trouvé.";
                }
            ?>
            <div class="reported-messages">
                <h1 class="title">Messages signalés</h1>
                <form  id="reported-messages-form" class="reported-messages-messages">
                    <?php foreach ($messages_report as $message) : ?>
                        <div class="line-item">
                            <p class="message-report"><?php echo $message['raison_report'] . ' : ' . $message['message']; ?></p>
                            <div class="profile-buttons profile-buttons-report">
                                <input class="profile-btn remove_report" type="button" value="Ignorer" data-id="<?php echo $message['id_message']; ?>">
                                <input class="profile-btn" type="button" onclick="location.href='profile.php?id_utilisateur=<?php echo $message['id_sender']; ?>'" value="Profil">
                                <input class="profile-btn" type="button" onclick="location.href='message.php?id_utilisateur=<?php echo $message['id_sender']; ?>'" value="Messagerie">
                                <input class="profile-btn ban" type="button" onclick="openConfirmationPopup()" value="Bannir">
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <input type="hidden" id="id_message_a_ignorer" name="id_message_a_ignorer" value="">
                </form>
            </div>

            <script>
                // IGNORE UN REPORT
                var removeButtons = document.querySelectorAll('.remove_report');

                removeButtons.forEach(function(button) {
                    button.addEventListener('click', function() {
                        var messageId = this.getAttribute('data-id');
                        var formData = new FormData();
                        formData.append('id_message', messageId);

                        var lineItem = this.parentNode.parentNode;
                        var parent = this.parentNode.parentNode.parentNode;

                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', 'php/admin/remove_report.php', true);
                        xhr.onload = function() {
                            if (xhr.status === 200) {
                                // Remove le message (sinon il faut f5)
                                parent.removeChild(lineItem);
                            }
                        };
                        xhr.send(formData);
                    });
                });

            </script>

            <div id="confirmation-popup" class="popup">
                <div class="popup-content">
                    <p>Confirmer l'action sur cet utilisateur ?</p>
                    <input type="hidden" id="id-aban" name="id-aban" value="">
                    <div class="popup-btns">
                        <button class="cancel-btn" onclick="closeConfirmationPopup()">Annuler</button>
                        <button class="confirm-btn" id="confirm-action-btn" onclick="confirmAction()">Confirmer</button>
                    </div>
                </div>
            </div>

            <script>
                // BAN / DEBAN
                function confirmAction() {
                    var xhr = new XMLHttpRequest();
                    var idaban = document.getElementById('id-aban').value;
                    xhr.open('POST', 'php/admin/ban.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === XMLHttpRequest.DONE) {
                            if (xhr.status >= 200 && xhr.status < 300) {

                            } else {
                                //console.error("Erreur lors de la requête : " + xhr.status);
                            }
                        }
                    };
                    xhr.send('id-aban=' + idaban);
                    
                    document.getElementById('confirmation-popup').style.display='none';     
                          
                    var userDiv = document.getElementById('user-' + idaban);
                    if  (userDiv.querySelector('.ban').value=="Bannir") {
                        userDiv.querySelector('.ban').value = "Debannir";
                    }
                    else {
                        userDiv.querySelector('.ban').value = "Bannir";
                    }
                    userDiv.querySelector('.profile-pseudonyme').classList.toggle('pseudo-banni');
                     
                }
            </script>

            <script>
                // Fonction pour ouvrir la popup de confirmation
                function openConfirmationPopup(id) {
                    document.body.style.overflow="hidden";
                    document.getElementById('confirmation-popup').style.display='flex';
                    document.getElementById('id-aban').value = id;
                }
                
                // Fonction pour fermer la popup de confirmation
                function closeConfirmationPopup() {
                    document.getElementById('confirmation-popup').style.display = "none";
                    document.body.style.overflow="";
                }

                function closeConfirmAction() {
                    document.getElementById('success-popup').style.display = "none";
                    document.body.style.overflow="";
                }

                window.addEventListener('click', function(event) {
                    var popups = document.querySelectorAll('.popup');
                    popups.forEach(function(popup) {
                        if (event.target == popup) {
                            closeConfirmationPopup();
                        }
                    });
                });
            </script>

        </div>

    </div>


    <?php
        $connexion->close();
    ?>





    <div class="footer">
    </div>

</body>
</html>
