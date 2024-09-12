<?php
    session_start(); 
    
    // VERIFC CONNECTE
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



    // VISITE
    $id_utilisateur_connecte = $_SESSION['id_utilisateur']; 

    if(isset($_GET['id_utilisateur'])) {
        //si l'utilisateur visite la page d'un autre user, l'id de ce dernier est dans l'url 
        $id_utilisateur = $_GET['id_utilisateur'];

        // Si on visite le profil d'un mec ban et quon est pas admin on redirige
        $user_id = $_SESSION['id_utilisateur'];
        $sql_check_admin = "SELECT estBanni FROM Utilisateurs WHERE id = $id_utilisateur";
        $result_check_admin = $connexion->query($sql_check_admin);
    
        if ($result_check_admin->num_rows > 0) {
            $row_check_admin = $result_check_admin->fetch_assoc();
            $estBanni = $row_check_admin['estBanni'];
            if (($estBanni == 1) && ($isAdmin == 0)) {
                header('Location: user.php');
            }
        } 


        // Ajouter la visite dans la table Visite
        $idUserVisiteur = $_SESSION['id_utilisateur'];
        $dateVisite = date("Y-m-d"); 

        $sqlInsertVisite = "
        INSERT INTO Visite (idUserVisiteur, idUserProfil, dateVisite) 
        SELECT '$id_utilisateur_connecte', '$id_utilisateur', '$dateVisite' 
        FROM dual
        WHERE NOT EXISTS (
            SELECT 1 
            FROM Visite 
            WHERE idUserVisiteur = '$id_utilisateur_connecte' 
            AND idUserProfil = '$id_utilisateur'
        )
        ";
        
        //echo $sqlInsertVisite;

        $resultInsertVisite = $connexion->query($sqlInsertVisite);
    
        if(!$resultInsertVisite) {
            //echo "Erreur lors de l'insertion de la visite dans la table Visite : " . $connexion->error;
        }
    } else {
        //sinon ça veut dire que l'utilisateur visite sa propre page de profil 
        $id_utilisateur = $_SESSION['id_utilisateur'];
    }



    // COMPTEUR LIKES
    $sql_likes = "SELECT compteur_likes FROM Utilisateurs WHERE id = $id_utilisateur";
    $result_likes = $connexion->query($sql_likes);

    if ($result_likes->num_rows > 0) {
        $row_likes = $result_likes->fetch_assoc();
        $compteur_likes = $row_likes['compteur_likes'];
    } 

    /*
    $sql_a_deja_like = "SELECT * FROM Likes WHERE id_utilisateur_likeur = '$id_utilisateur_connecte' AND id_utilisateur_like = '$id_utilisateur'";
    $result_a_deja_like = $connexion->query($sql_a_deja_like);

    if ($result_a_deja_like->num_rows > 0) {
        echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                document.getElementById("like-btn").classList.add("like-btn-liked");
            });
        </script>';
    } 
    */

?>






<!DOCTYPE html>
<html lang="en">
<head>
    <!-- PAGE SETTINGS -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/profile.css">
    <link rel="shortcut icon" href="assets/icon.png" type="image/x-icon">
    <title>Profil - Smash OR Pass</title>

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
        <?php 
        session_start();

        //verif si user connecté sinon pas accès aux profils 
        if (!isset($_SESSION['id_utilisateur'])) {
            header('Location: index.php');
            exit();
        }

        ini_set('display_errors', 1);
        error_reporting(E_ALL);

        require_once('php/config.php');

        //change l'id selon si c'est le profil de la personne connectée ou s'il visite 
        if(isset($_GET['id_utilisateur'])) {
            //si l'utilisateur visite la page d'un autre user, l'id de ce dernier est dans l'url 
            $user_id = $_GET['id_utilisateur'];
            $id_user = null;
        }else{
            $user_id = $_SESSION['id_utilisateur']; 
            $id_user = $_SESSION['id_utilisateur'];
            $user_id = $id_utilisateur_connecte;
        }
        

        // Requête pour récupérer les informations de base de l'utilisateur
        $sql_info_user = "SELECT *,
                                i.password,
                                a.type_abonnement, a.date_souscription
                          FROM Utilisateurs u
                          LEFT JOIN InfosConnexions as i ON u.id = i.id
                          LEFT JOIN Abonnes as a ON u.id = a.id
                          WHERE u.id= $user_id";

        $result_info_user = $connexion->query($sql_info_user);

        if ($result_info_user->num_rows > 0) {
            $row = $result_info_user->fetch_assoc();

            $prenom = $row["prenom"];
            $nom = $row["nom"];
            $pseudonyme = $row["pseudonyme"];
            $sexe = $row["sexe"];
            $age = $row["age"];
            $profession = $row["profession"];
            $lieu_residence = $row["lieu_residence"];
            $situation_amoureuse = $row["situation_amoureuse"];
            $informations_personnelles = $row["informations_personnelles"];
            $date_inscription_bd = $row["date_inscription"];
            $date_inscription_obj = new DateTime($date_inscription_bd);
            $date_inscription= $date_inscription_obj->format('d/m/Y');
            $description_physique = $row["description_physique"]; 
            $adresse = $row["adresse"]; 
            $img_profil = $row["img_profil"]; 
            $orientation = $row["orientation"]; 
            $img_1 = $row["img_1"];
            $img_2 = $row["img_2"];
            $img_3 = $row["img_3"];
            $img_4 = $row["img_4"];
            $date_de_naissance = $row['date_de_naissance'];




            // Mot de passe
            $mdp = $row["password"];

            // Abonnement
            $type_abonnement = $row["type_abonnement"];
            $date_souscription = $row["date_souscription"];
            $date_souscription_timestamp = ($date_souscription !== null) ? strtotime($date_souscription) : 0;
            $date_souscription_dt = new DateTime();
            $date_souscription_dt->setTimestamp($date_souscription_timestamp);

            // Ajouter la durée de l'abonnement en fonction de son type (gratuit, mensuel, trimestriel, annuel)
            switch ($type_abonnement) {
                case 'gratuit':
                    $date_souscription_dt->add(new DateInterval('P7D')); // Ajoute 7 jours pour un abonnement gratuit
                    break;
                case 'mensuel':
                    $date_souscription_dt->add(new DateInterval('P1M')); // Ajoute 1 mois pour un abonnement mensuel
                    break;
                case 'trimestriel':
                    $date_souscription_dt->add(new DateInterval('P3M')); // Ajoute 3 mois pour un abonnement trimestriel
                    break;
                case 'annuel':
                    $date_souscription_dt->add(new DateInterval('P1Y')); // Ajoute 1 an pour un abonnement annuel
                    break;
                default:
                    // Autre
            }

            // Convertir la date d'expiration en format de date approprié
            $date_expiration = $date_souscription_dt->format('Y-m-d');

            // Calculer les jours restants jusqu'à la date d'expiration
            $date_expiration_timestamp = $date_souscription_dt->getTimestamp();
            $jours_restants = ceil(($date_expiration_timestamp - time()) / (24 * 60 * 60));

            if ($jours_restants < 0) {
                $date_souscription = date('Y-m-d');
                $sql_not_sub = "UPDATE Abonnes SET date_souscription = '$date_souscription', type_abonnement = 'aucun' WHERE id = $user_id";
                $connexion->query($sql_not_sub);
            }
       
        } else {
            //echo "Aucun résultat trouvé";
        }
        ?> 

        <form id="form-profile" name="form-profile" class="profile" enctype="multipart/form-data">
            <div class="profile-menu">
                <div class="upload-picture profile-picture">
                    <label class="label picture-label" for="img_profil">Changer</label>
                    <input id="img_profil" name="img_profil" class="picture-file" type="file" disabled/>
                    <input id="img_profil-path" name="img_profil-path" type="hidden" value="<?php echo $img_profil ?>">
                    <img class="" src="<?php echo 'user-img/' . $img_profil ?>" alt="profile-picture">
                </div>
                <div class="profile-btns">  
                    <?php if ($id_user == $_SESSION['id_utilisateur'] || $isAdmin == 1): ?>
                        <input class="edit-btn" id="edit-btn" type="button" onclick="Edit()" value="Modifier">
                        <?php endif; ?>
                    <?php if ($id_user != $_SESSION['id_utilisateur']): ?>
                            <script>
                                document.getElementById('form-profile').setAttribute('action', 'message.php');
                                document.getElementById('form-profile').setAttribute('method', 'POST');
                            </script>
                            <input class="message-btn" type="submit" value="Message">
                            <button type="button" id="like-btn" name="like-btn" class="like-btn" onclick="liker()"><img src="assets/icon.png" alt="like-icon">
                                <span id="like-count">
                                <?php
                                if ($compteur_likes == 0 || $compteur_likes == 1) {
                                    echo $compteur_likes . " Smash";
                                } else {
                                    echo $compteur_likes . " Smashs";
                                }
                                ?>
                                </span>
                            </button>
                            <input type="hidden" id="id_utilisateur_a_liker" name="id_utilisateur_a_liker" value="<?php echo $user_id ?>">
                            <script>
                                function liker() {
                                    var id_utilisateur = document.getElementById('id_utilisateur_a_liker').value;
                                    var formData = new FormData();
                                    formData.append('id_utilisateur', id_utilisateur);
                                    var xhr = new XMLHttpRequest();
                                    xhr.open('POST', 'php/profile/like.php', true);
                                    xhr.onreadystatechange = function() {
                                        if (xhr.readyState === XMLHttpRequest.DONE) {
                                            if (xhr.status === 200) {
                                                document.getElementById('like-count').innerHTML = xhr.responseText;
                                            } else {
                                                //console.error('Erreur lors de la requête AJAX');
                                            }
                                        }
                                    };
                                    xhr.send(formData);
                                }
                            </script>
                    <?php endif; ?>
                    <input type="hidden" id="interlocuteur_id" name="interlocuteur_id" value="<?php echo $user_id ?>">
                </div>
                <?php if ($id_user == $_SESSION['id_utilisateur'] || $isAdmin == 1): ?>
                <div class="profile-subscription">
                    <p><span id="date-inscription"><?php if (isset($date_inscription)){echo "Inscrit depuis le "."$date_inscription";}?></span></p>
                    <p><span id="niveau-abonnement"><?php if(isset($type_abonnement)){echo "Abonnement "."$type_abonnement";}?></span></p>
                    <p><span id="temps-restant"><?php if (isset($jours_restants) && $type_abonnement!='aucun' && $type_abonnement!='infini'){ echo "$jours_restants"." jours restants"; }?></span></p>
                </div>
                <div class="profile-private-informations">
                    <p class="profile-subtitle">Informations privées</p>
                    <div class="profile-input">
                        <input class="input-editable" readonly id="motdepasse" name="motdepasse" type="password" required="required" maxlength="50" value="<?php if(isset($mdp)) echo $mdp ?>">
                        <label>Mot de passe</label>
                    </div>
                    <div class="profile-input">
                        <input class="input-editable" readonly id="nom" name="nom" type="text" required="" maxlength="50" value="<?php if(isset($nom)) echo $nom ?>">
                        <label>Nom</label>
                    </div>
                    <div class="profile-input">
                        <input class="input-editable" readonly id="prenom" name="prenom" type="text" required="" maxlength="50" value="<?php if(isset($prenom)) echo $prenom ?>">
                        <label>Prenom</label>
                    </div>
                    <div class="profile-input">
                        <input class="input-editable" readonly id="adresse" name="adresse" type="text" required="" maxlength="50" value="<?php if(isset($adresse)) echo $adresse ?>">
                        <label>Adresse</label>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <div class="profile-infos">

                <div class="profile-input pseudo">
                    <input class="input-editable" readonly id="pseudonyme" name="pseudonyme" type="text" required="required" maxlength="30" value="<?php echo "$pseudonyme"?>">
                    <label id="pseudo-label">Pseudonyme</label>
                </div> 

                <div class="profile-row demo-info">
                    <div class="profile-input">
                        <input class="input-editable" readonly id="age" name="age" type="number" required="" value="<?php if(isset($age) && $age>=18) echo $age?>">
                        <label>Age</label>
                    </div>
                    <div class="profile-input">
                        <input class="input-editable" readonly id="sexe" name="sexe" type="text" required="" pattern="^(homme|femme|autre)$" value="<?php if(isset($sexe)) echo $sexe ?>">
                        <label>Genre</label>
                    </div>
                    <div class="profile-input-big profile-input">
                        <input class="input-editable" readonly id="profession" name="profession" type="text" required="" maxlength="50" value="<?php if(isset($profession)) echo $profession ?>">
                        <label>Profession</label>
                    </div>
                </div>

                <div class="profile-row demo-info">
                <div class="profile-input">
                        <input class="input-editable" readonly id="date_de_naissance" name="date_de_naissance" type="text" required="" value="<?php if(isset($date_de_naissance)) echo $date_de_naissance ?>">
                        <label>Date de naissance</label>
                    </div>
                    <div class="profile-input">
                        <input class="input-editable" readonly id="orientation" name="orientation" type="text" required="" pattern="^(hetero|bi|gay)$" value="<?php if(isset($orientation)) echo $orientation ?>">
                        <label>Orientation</label>
                    </div>
                    <div class="profile-input-big profile-input">
                        <input class="input-editable" readonly id="lieu_residence" name="lieu_residence" type="text" maxlength="50" required="" value="<?php if(isset($lieu_residence)) echo $lieu_residence ?>">
                        <label>Ville et Region</label>
                    </div>
                </div>



                <div class="profile-row row-text">
                    <div class="profile-input textarea">
                        <label>Situation amoureuse et familiale</label>
                        <textarea class="input-editable auto-height" readonly id="situation_amoureuse" name="situation_amoureuse"><?php if(isset($situation_amoureuse)) echo $situation_amoureuse ?></textarea>
                    </div>
                    <div class="profile-input textarea">
                        <label>Description physique</label>
                        <textarea class="input-editable auto-height" readonly id="description_physique" name="description_physique" ><?php if(isset($description_physique)) echo $description_physique ?></textarea>
                    </div>
                    <div class="profile-input textarea">
                        <label>Informations personnelles</label>
                        <textarea class="input-editable auto-height" readonly id="informations_personnelles" name="informations_personnelles"><?php if(isset($informations_personnelles)) echo $informations_personnelles ?></textarea>
                    </div>

                    <script>
                        // Fonction pour ajuster dynamiquement la hauteur de la zone de texte en fonction de son contenu
                        function adjustTextareaHeight(textarea) {
                            textarea.style.height = 'auto'; // Réinitialiser la hauteur à auto pour obtenir la hauteur naturelle du contenu
                            textarea.style.height = textarea.scrollHeight + 'px'; // Ajuster la hauteur en fonction de la hauteur de défilement
                        }

                        // Sélectionnez toutes les zones de texte avec la classe .auto-height
                        const textareas = document.querySelectorAll('.auto-height');

                        // Ajoutez un écouteur d'événements pour chaque zone de texte
                        textareas.forEach(textarea => {
                            // Ajuster la hauteur de la zone de texte lorsque le contenu est chargé
                            window.addEventListener('load', () => {
                                adjustTextareaHeight(textarea);
                            });

                            // Ajuster la hauteur de la zone de texte lorsqu'il y a un changement de contenu
                            textarea.addEventListener('input', () => {
                                adjustTextareaHeight(textarea);
                            });
                        });
                    </script>
                </div>

                <div class="profile-row row-pictures">
                    <div class="upload-picture profile-image">
                        <label class="label picture-label" for="img_1">Changer</label>
                        <input class="picture-file" id="img_1" name="img_1" type="file" disabled/>
                        <input id="img_1-path" name="img_1-path" type="hidden" value="<?php echo $img_1 ?>">
                        <img src="<?php echo 'user-img/' . $img_1 ?>" alt="user-picture">
                    </div>
                    <div class="upload-picture profile-image">
                        <label class="label picture-label" for="img_2">Changer</label>
                        <input class="picture-file" id="img_2" name="img_2" type="file" disabled/>
                        <input id="img_2-path" name="img_2-path" type="hidden" value="<?php echo $img_2 ?>">
                        <img src="<?php echo 'user-img/' . $img_2 ?>" alt="user-picture">
                    </div>
                    <div class="upload-picture profile-image">
                        <label class="label picture-label" for="img_3">Changer</label>
                        <input class="picture-file" id="img_3" name="img_3" type="file" disabled/>
                        <input id="img_3-path" name="img_3-path" type="hidden" value="<?php echo $img_3 ?>">
                        <img src="<?php echo 'user-img/' . $img_3 ?>" alt="user-picture">
                    </div>
                    <div class="upload-picture profile-image">
                        <label class="label picture-label" for="img_4">Changer</label>
                        <input class="picture-file" id="img_4" name="img_4" type="file" disabled/>
                        <input id="img_4-path" name="img_4-path" type="hidden" value="<?php echo $img_4 ?>">
                        <img src="<?php echo 'user-img/' . $img_4 ?>" alt="user-picture">
                    </div>
                </div>

                <div id="not-sub-popup" class="popup" onclick="closeInscriptionPopup()">
                    <div class="popup-content">
                        <p>Abonnez vous pour voir qui a consulté votre profil. </p>
                        <div class="popup-btns">
                            <span class="close">Fermer</span>
                        </div>
                    </div>
                </div>
                
                <?php if ($id_user == $_SESSION['id_utilisateur']): ?>
                <p class="profile-subtitle visited">Ils ont vu votre profil :</p>
                <div class="profile-row row-visited">
                    <?php
                        ini_set('display_errors', 1);
                        error_reporting(E_ALL);

                        // Fonction pour rechercher l'ID de l'utilisateur à partir du pseudonyme
                        function rechercherIdUtilisateur($connexion, $username) {
                            $username = $connexion->real_escape_string($username);
                            $sql = "SELECT id FROM Utilisateurs WHERE pseudonyme = '$username'";
                            $result = $connexion->query($sql);
                            if ($result === false) {
                                die('Erreur de requête SQL: ' . $connexion->error);
                            }
                            if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                $id_utilisateur = $row["id"];
                            } else {
                                $id_utilisateur = null;
                            }
                            return $id_utilisateur;
                        }

                        // Fonction pour générer le lien vers le profil de l'utilisateur
                        function genererLienProfil($connexion, $username) {
                            $id_utilisateur = rechercherIdUtilisateur($connexion, $username);
                            if ($id_utilisateur !== null) {
                                if (isset($_SESSION['id_utilisateur']) && $_SESSION['id_utilisateur'] != $id_utilisateur) {
                                    return 'profile.php?id_utilisateur=' . $id_utilisateur;
                                } else {
                                    return 'profile.php';
                                }
                            } else {
                                return '#';
                            }
                        }

                        // Fonction pour obtenir la photo de l'utilisateur
                        function genererImageProfil($connexion, $username) {
                            $id_utilisateur = rechercherIdUtilisateur($connexion, $username);
                            if ($id_utilisateur !== null) {
                                $sql = "SELECT img_profil FROM Utilisateurs WHERE id = $id_utilisateur";
                                $result = $connexion->query($sql);
                                if ($result === false) {
                                    die('Erreur de requête SQL: ' . $connexion->error);
                                }
                                if ($result->num_rows > 0) {
                                    $row = $result->fetch_assoc();
                                    return 'user-img/'.$row['img_profil'];
                                } else {
                                    // Si aucune image de profil n'est trouvée, retournez un chemin par défaut ou une chaîne vide selon votre logique
                                    return 'user-img/default-user.jpg';
                                }
                            } else {
                                // Si l'utilisateur n'existe pas, retournez un chemin par défaut ou une chaîne vide selon votre logique
                                return 'user-img/default-user.jpg';
                            }
                        }

                        // Fonction pour récupérer la liste des utilisateurs depuis la base de données
                        function getUsersList($connexion) {
                            $id_utilisateur = $_SESSION['id_utilisateur'];
    
                            $sql = "SELECT DISTINCT U.pseudonyme 
                            FROM Utilisateurs U 
                            INNER JOIN Visite V ON U.id = V.idUserVisiteur 
                            WHERE V.idUserProfil = $id_utilisateur
                            AND V.idUserVisiteur != $id_utilisateur
                            AND estBanni = 0
                            LIMIT 6";    
                                           
                            $result = $connexion->query($sql);
                            if ($result === false) {
                                die('Erreur de requête SQL: ' . $connexion->error);
                            }
                            $visitors = [];
                            while ($row = $result->fetch_assoc()) {
                                $visitors[] = $row['pseudonyme'];
                            }
                            
                            return $visitors;
                        }

                        
                        // Afficher la liste des utilisateurs qui ont visité le profil
                        $users = getUsersList($connexion);
                        
                        foreach ($users as $username) {
                            $profileLink = genererLienProfil($connexion, $username);
                            $imgLink = genererImageProfil($connexion, $username);
                            echo '<div class="user-profile">';
                            if ($type_abonnement == 'aucun') {
                                echo '<div class="user-profile" onclick="openInscriptionPopup(\'not-sub-popup\')">';
                                echo '<img class="user-picture user-picture-flou" src="'.$imgLink.'" alt="user-picture">';
                                echo '<p class="user-nickname user-nickname-flou">' . $username . '</p>';
                            } else {
                                echo '<a href="' . $profileLink . '" class="user-profile">';
                                echo '<img class="user-picture" src="'.$imgLink.'" alt="user-picture">';
                                echo '<p class="user-nickname">' . $username . '</p>';
                            }
                            if ($type_abonnement == 'aucun') {
                                echo '</div>'; 
                            } else {
                                echo '</a>'; 
                            }
                            echo '</div>';
                        }
                    ?>
                </div>
                <?php endif; ?>
            </div>



            <script>
                const editableInputs = document.querySelectorAll('.input-editable');
                const editButton = document.querySelector('.edit-btn');

                // Fonction pour activer ou désactiver la modification des textes
                function toggleTexte() {
                    editableInputs.forEach(input => {
                        input.readOnly = !input.readOnly;
                    });
                }

                // Fonction pour activer ou désactiver la modification des images
                function toggleImage(enable) {
                    const uploadPictures = document.querySelectorAll('.upload-picture');
                    uploadPictures.forEach(uploadPicture => {
                        const input = uploadPicture.querySelector('.picture-file');
                        const label = uploadPicture.querySelector('.picture-label');

                        if (enable) {
                            input.disabled = false; // Rendre l'élément d'entrée de fichier cliquable
                            label.style.display = 'flex'; // Afficher l'étiquette "Changer"
                        } else {
                            input.disabled = true; // Rendre l'élément d'entrée de fichier non cliquable
                            label.style.display = 'none'; // Cacher l'étiquette "Changer"
                        }
                    });
                }

                // Fonction pour gérer l'action "Modifier"
                function Edit() {
                    event.preventDefault();
                    toggleTexte(); // Activer l'édition des champs
                    toggleImage(true); // Active l'option de changement des images
                    editButton.value = 'Sauvegarder'; // Changer le texte du bouton
                    editButton.onclick = Save; // Définir l'action onclick pour "Sauvegarder" 
                    document.getElementById('motdepasse').type = "text"; // Rend le mot de pass visible
                    document.querySelectorAll('.profile-input').forEach(input => {
                        input.classList.add('editable'); // Ajoute la classe editable à tous les inputs
                    });
                    document.getElementById('pseudo-label').style.display='block';

                    // Ajouter les attributs action et method au formulaire
                    editButton.type="submit";
                    document.getElementById('form-profile').setAttribute('action', 'php/profile/modif.php');
                    document.getElementById('form-profile').setAttribute('method', 'post');
                }

                // Fonction pour gérer l'action "Sauvegarder"
                function Save() {
                    toggleTexte(); // Remettre les champs en lecture seule
                    toggleImage(false); // Desactiver l'option de changement des images
                    editButton.value = 'Modifier'; // Rétablir le texte du bouton
                    editButton.onclick = Edit; // Définir l'action onclick pour "Modifier"
                    document.getElementById('motdepasse').type = "password"; // Rétabli le mot de passe 
                    document.querySelectorAll('.profile-input').forEach(input => {
                        input.classList.remove('editable'); // Supprime la classe editable de tous les inputs
                    });
                    document.getElementById('pseudo-label').style.display='none';
                }
            </script>

            <script>
                // Fonction pour verifier que les infos rentrées sont cohérentes
                function validateForm(event) {
                    var patternSexe = /^(homme|femme|autre)$/;
                    var valeurSexe = document.getElementById("sexe").value.toLowerCase();
                    if (!patternSexe.test(valeurSexe)) {
                        alert("Veuillez saisir une valeur valide pour le sexe :\n'homme', 'femme' ou 'autre'.");
                        event.preventDefault(); 
                    }
                    
                    var patternOrientation = /^(hétéro|hetero|bi|gay)$/;
                    var valeurOrientation = document.getElementById("orientation").value.toLowerCase();
                    if (valeurOrientation !== "" && !patternOrientation.test(valeurOrientation)) {
                        alert("Veuillez saisir une valeur valide pour l'orientation :\n'hetero', 'bi' ou 'gay'.");
                        event.preventDefault(); 
                    }


                    var patternDateOfBirth = new RegExp("^(([1-9]|0[1-9]|[1-2][0-9]|3[0-1])\\s(janvier|février|fevrier|mars|avril|mai|juin|juillet|août|aout|septembre|octobre|novembre|décembre|decembre)\\s\\d{4})$", "i");
                    var dateOfBirthInput = document.getElementById("date_de_naissance").value.trim();
                    
                    if (dateOfBirthInput !== "" && !patternDateOfBirth.test(dateOfBirthInput)) {
                        alert("Veuillez saisir une date de naissance valide\n au format JJ Mois AAAA.");
                        event.preventDefault(); 
                    }                    
                }

                document.addEventListener("DOMContentLoaded", function() {
                    var form = document.getElementById("form-profile");
                    form.addEventListener("submit", validateForm);
                });
            </script>



            <!-- POPUPS APRES EDIT DU PROFIL -->
            <div id="modif-success-popup" class="popup" onclick="closeInscriptionPopup()">
                <div class="popup-content">
                    <p>Modification du profil réussie !</p>
                    <div class="popup-btns">
                        <span class="close">Fermer</span>
                    </div>
                </div>
            </div>

            <div id="pseudo-fail-popup" class="popup" onclick="closeInscriptionPopup()">
                <div class="popup-content">
                    <p>Ce pseudonyme est deja utilisé.<br>Veuillez réessayer.</p>
                    <div class="popup-btns">
                        <span class="close">Fermer</span>
                    </div>
                </div>
            </div>

            <div id="modif-fail-popup" class="popup" onclick="closeInscriptionPopup()">
                <div class="popup-content">
                    <p>Oups, la modification a échouée... <br>Veuillez réessayer.</p>
                    <div class="popup-btns">
                        <span class="close">Fermer</span>
                    </div>
                </div>
            </div>

            <script>
                // Fonction pour ouvrir la popup de succès ou d'échec d'inscription
                function openInscriptionPopup(popupId) {
                    var popup = document.getElementById(popupId);
                    popup.style.display = 'flex';
                }

                // Fonction pour fermer la popup de succès ou d'échec d'inscription
                function closeInscriptionPopup() {
                    var popups = document.querySelectorAll('.popup');
                    popups.forEach(function(popup) {
                        popup.style.display = "none";
                    });
                }

                // Fermer la popup de succès ou d'échec d'inscription si on clique sur la croix
                document.querySelectorAll('.popup-content .close').forEach(function(closeBtn) {
                    closeBtn.addEventListener('click', function() {
                        closeInscriptionPopup();
                    });
                });

                // Fermer la popup de succès ou d'échec d'inscription si on clique en dehors
                window.addEventListener('click', function(event) {
                    var popups = document.querySelectorAll('.popup');
                    popups.forEach(function(popup) {
                        if (event.target == popup) {
                            closeInscriptionPopup();
                        }
                    });
                });
            </script>


            <?php
                // Affiche la popup en fonction du traitement de la modif
                if (isset($_SESSION['profile-modif']) && isset($_SESSION['pseudo-deja-utilise'])) {
                    if ($_SESSION['pseudo-deja-utilise'] == 1) {
                        echo "<script>document.getElementById('pseudo-fail-popup').style.display='flex';</script>";
                    }
                    else if ($_SESSION['profile-modif'] == 1 && $_SESSION['pseudo-deja-utilise'] == 0 ) {
                        echo "<script>document.getElementById('modif-success-popup').style.display='flex';</script>";
                    } else {
                        echo "<script>document.getElementById('modif-fail-popup').style.display='flex';</script>";
                    }
                    unset($_SESSION['profile-modif']);
                }
            ?>

            <script>
                // Fonction pour récuperer les chemins des images
                document.querySelectorAll('input.picture-file').forEach(function(input) {
                    input.addEventListener('change', function() {
                        document.getElementById(this.id + '-path').value = this.files[0].name;
                        console.log(this.id);
                    });
                });
            </script>
        </form>
    </div>



    <div class="footer">
    </div>

</body>
</html>
