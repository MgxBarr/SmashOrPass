<?php
  session_start(); 

  // VERIF CONNECTE
  if (!isset($_SESSION['id_utilisateur'])) {
      header('Location: index.php');
      exit();
  }
  require_once ('php/config.php'); 


  // VERIF ADMIN
  $user_id = $_SESSION['id_utilisateur'];
  $sql_check_admin = "SELECT estAdmin FROM Utilisateurs WHERE id = $user_id";
  $result_check_admin = $connexion->query($sql_check_admin);

  if ($result_check_admin->num_rows > 0) {
      $row_check_admin = $result_check_admin->fetch_assoc();
      $isAdmin = $row_check_admin['estAdmin'];
  } 
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <!-- PAGE SETTINGS -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/user.css">
    <link rel="shortcut icon" href="assets/icon.png" type="image/x-icon">
    <title>Smash OR Pass - Connecté</title>

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
            
            // ABONNEMENT
            $id_utilisateur = $_SESSION['id_utilisateur']; 

            $sql = "SELECT type_abonnement FROM Abonnes WHERE id = $id_utilisateur";
            $result = $connexion->query($sql);

            if ($result) {
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $type_abonnement = $row['type_abonnement'];

                    switch ($type_abonnement) {
                        case 'gratuit':
                            $abonnement_code = 1;
                            break;
                        case 'mensuel':
                            $abonnement_code = 2;
                            break;
                        case 'trimestriel':
                            $abonnement_code = 3;
                            break;
                        case 'annuel':
                            $abonnement_code = 4;
                            break;
                        default:
                            $abonnement_code = 0; 
                            break;
                    }

                    echo '<input type="hidden" id="abonnement_code" name="abonnement_code" value="' . $abonnement_code . '">';
                } else {
                    //echo "Aucun abonnement trouvé pour cet utilisateur.";
                }
            } else {
                //echo "Erreur lors de l'exécution de la requête : " . $connexion->error;
            }
        ?>



        <!--SUBSCRIPTIONS-->
        <h1 class="title">Pour Smash encore plus fort !</h1>
        <div class="subscription" id="subscription">
            <div class="card">
              <p class="card-title">Découverte</p>
              <p class="price">Gratuit 1 semaine</p>
              <p class="card-text">Profitez d'un avant-goût de l'expérience Smash Or Pass, et cela sans rien débourser !</p>
              <div class="lists">
                <div class="list">
                  <img class="card-point-icon" src="assets/subs-infos-icon.png" alt="">
                  <span class="card-point-text">Créez votre profil</span>
                </div>
                <div class="list">
                  <img class="card-point-icon" src="assets/subs-infos-icon.png" alt="">
                  <span class="card-point-text">Parcourez ceux des autres</span>
                </div>
              </div>
              <input type="button" onclick="openConfirmationPopup(1)" class="action" value="Obtenir">
            </div>
        
            <div class="card">
              <p class="card-title">Mensuel</p>
              <p class="price">8€</p>
              <p class="card-text">Accédez à une expérience complète et sans limites sur Smash Or Pass !</p>
              <div class="lists">
                <div class="list">
                  <img class="card-point-icon" src="assets/subs-infos-icon.png" alt="">
                  <span class="card-point-text">Accès à la messagerie</span>
                </div>
                <div class="list">
                  <img class="card-point-icon" src="assets/subs-infos-icon.png" alt="">
                  <span class="card-point-text">Fonctionnalités avancées</span>
                </div>
              </div>
              <input type="button" onclick="openConfirmationPopup(2)" class="action" value="Obtenir">
            </div>
        
            <div class="card">
              <p class="card-title">Trimestriel</p>
              <p class="price">20€</p>
              <p class="card-text">Une offre parfaite pour une expérience prolongée et économique sur Smash Or Pass !</p>
              <div class="lists">
                <div class="list">
                  <img class="card-point-icon" src="assets/subs-infos-icon.png" alt="">
                  <span class="card-point-text">Même fonctionnalités que le mensuel, moins cher</span>
                </div>
                <div class="list">
                  <img class="card-point-icon" src="assets/subs-infos-icon.png" alt="">
                  <span class="card-point-text">Soit 4€ d'économies</span>
                </div>
              </div>
              <input type="button" onclick="openConfirmationPopup(3)" class="action" value="Obtenir">
            </div>
        
            <div class="card">
              <span class="best"></span>
              <p class="card-title">Annuel</p>
              <p class="price">60€</p>
              <p class="card-text">Maximisez votre expérience sur Smash Or Pass pendant toute une année !</p>
              <div class="lists">
                <div class="list">
                  <img class="card-point-icon" src="assets/subs-infos-icon.png" alt="">
                  <span class="card-point-text">Pour du long terme, c'est la meilleure offre</span>
                </div>
                <div class="list">
                  <img class="card-point-icon" src="assets/subs-infos-icon.png" alt="">
                  <span class="card-point-text">Et en plus, vous économisez plus de 30€ !</span>
                </div>
              </div>
              <input type="button" onclick="openConfirmationPopup(4)" class="action" value="Obtenir">
            </div>

            <div id="infinite-sub-popup" class="popup" onclick="closeInscriptionPopup()">
                <div class="popup-content">
                    <p>L'abonnement est gratuit pour les femmes ! <br>Vous possédez donc déjà l'abonnement.</p>
                    <div class="popup-btns">
                        <span class="close close-sub">Fermer</span>
                    </div>
                </div>
            </div>

            <div id="already-sub-popup" class="popup" onclick="closeInscriptionPopup()">
                <div class="popup-content">
                    <p>Vous êtes déjà abonné !</p>
                    <div class="popup-btns">
                        <span class="close close-sub">Fermer</span>
                    </div>
                </div>
            </div>

            <script>
                var typeAbonnement = '<?php echo $type_abonnement; ?>';

                if (typeAbonnement == 'infini') {
                    var buttons = document.querySelectorAll('.action');
                    buttons.forEach(function(button) {
                        button.onclick = function() {
                            openInscriptionPopup('infinite-sub-popup');
                        };
                    });
                }

                if (typeAbonnement != 'aucun' && typeAbonnement != 'infini') {
                    var buttons = document.querySelectorAll('.action');
                    buttons.forEach(function(button) {
                        button.onclick = function() {
                            openInscriptionPopup('already-sub-popup');
                        };
                    });
                }
            </script>


            <!-- Popup de confirmation -->
            <div id="confirmation-popup" class="popup">
                <div class="popup-content">
                    <form id="updateForm" method="post" action="php/user/updateInfos.php">
                        <p>Êtes-vous sûr de vouloir continuer ?</p>
                        <input type="hidden" id="type-abonnement" name="type-abonnement" value="">
                        <div class="popup-btns">
                            <button type="button" class="cancel-btn" onclick="closeConfirmationPopup()">Annuler</button>
                            <button class="confirm-btn" id="confirm-action-btn" onclick="confirmAction()">Confirmer</button>
                        </div>
                    </form>
                </div>
            </div>
        

            <div id="success-popup" class="popup">
                <div class="popup-content">
                    <p>Achat réussi !</p>
                    <div class="popup-btns">
                        <button class="cancel-btn" onclick="closeConfirmAction()">Fermer</button>
                    </div>
                </div>
            </div>

            <script>
                // Fonction pour ouvrir la popup de confirmation
                function openConfirmationPopup(type) {
                    document.body.classList.add("scrolling");
                    document.getElementById('confirmation-popup').style.display='flex';

                    // Stocker le type d'abonnement dans la variable globale
                    typeAbonnement = type;

                    // Ajouter l'id au bouton confirmAction
                    var boutonConfirmAction = document.getElementById('confirm-action-btn');
                    boutonConfirmAction.setAttribute('data-type-abonnement', type);
                }

                function confirmAction() {
                    document.getElementById('confirmation-popup').style.display='none';
                    //document.getElementById('success-popup').style.display='flex';

                    // Récupérer l'id du bouton confirmAction
                    var typeAbonnement = document.getElementById('confirm-action-btn').getAttribute('data-type-abonnement');
                    document.getElementById('type-abonnement').value = typeAbonnement;
                }

                
                // Fonction pour fermer la popup de confirmation
                function closeConfirmationPopup() {
                    document.getElementById('confirmation-popup').style.display = "none";
                    document.body.classList.remove("scrolling"); 
                }

                function closeConfirmAction() {
                    document.getElementById('success-popup').style.display = "none";
                    document.body.classList.remove("scrolling"); 
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

            <?php   
                // AFFICHE LA POPUP ACHAT REUSSI 
                if (isset($_SESSION['achat_reussi'])) {
                    if ($_SESSION['achat_reussi']) {
                        echo "<script>document.getElementById('success-popup').style.display='flex';</script>";
                    }
                    unset($_SESSION['achat_reussi']);
                }
            ?>

            <div id="not-sub-popup" class="popup" onclick="closeInscriptionPopup()">
                <div class="popup-content">
                    <p>Abonnez vous pour accéder à la messagerie. </p>
                    <div class="popup-btns">
                        <span class="close close-sub">S'abonner</span>
                    </div>
                </div>
            </div>

            <?php   
                // Not sub popup
                if (isset($_SESSION['abonne_toi'])) {
                    if ($_SESSION['abonne_toi']) {
                        echo "<script>document.getElementById('not-sub-popup').style.display='flex';</script>";
                    } 
                    unset($_SESSION['abonne_toi']);
                }
            ?>

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

        </div>

          



        <!--RESEARCH-->
        <div class="research" id="research" name="research">
            <h1 class="title research-title">Enfin prêt à Smash ?</h1>
            <form id="searchForm" name="searchForm" action="research.php" method="POST">
               <div class="inner-form">
                  <div class="basic-search">
                     <div class="input-field">
                        <input class="search-field" id="search" name="search" type="text" placeholder="Rechercher" />
                        <div class="icon-wrap icon-wrap-filter" onclick=" openCloseAdvanced()">
                           <img class="filter-icon"src="assets/filter.png" alt="filter">
                        </div>
                        <div class="icon-wrap" onclick="document.getElementById('searchForm').submit()">
                           <img class="search-icon"src="assets/research.png" alt="research">
                        </div>
                     </div>
                  </div>

                  <script>
                    // Fonction pour ouvrir et fermer la recherche avancée
                    function openCloseAdvanced() {
                        var advancedSearchPanel = document.getElementById('advance-search');
                        if (!advancedSearchPanel.classList.contains('open')) {
                            advancedSearchPanel.classList.add('open');
                        } else {
                            advancedSearchPanel.style.animation ="closeanimation 1s ease";
                            setTimeout(function() {
                                advancedSearchPanel.style.animation = "";
                                advancedSearchPanel.classList.remove('open');
                            }, 850); 
                        }
                    }
                  </script>
   
                  <div class="advance-search" id="advance-search">
                     <span class="desc">Recherche avancée</span>
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
                                       addResetOption(dropdown);
                                   });
                               });
               
                               function addResetOption(dropdown) {
                                   var resetOption = dropdown.querySelector('.reset-option');
                                   if (!resetOption) {
                                       resetOption = document.createElement('li');
                                       resetOption.classList.add('reset-option');
                                       resetOption.textContent = 'Annuler';
                                       menu.appendChild(resetOption);
               
                                       resetOption.addEventListener('click', function () {
                                           var dropdownSpan = this.closest('.dropdown').querySelector('span');
                                           dropdownSpan.textContent = originalTitle;
                                           this.closest('.dropdown').querySelector('input').value='';
                                           this.remove();
                                       });
                                   }
                               }
                           });
                       });
                   </script>
                    <div class="row">
                        <div class="input-field">
                           <div class="input-select">
                               <div class="dropdown">
                                   <div class="select">
                                     <span>Age</span>
                                   </div>
                                   <input type="hidden" id="age" name="age">
                                   <ul class="dropdown-menu">
                                     <li id="18-25">18-25</li>
                                     <li id="25-40">25-40</li>
                                     <li id="40-50">40-50</li>
                                     <li id="50-60">50-60</li>
                                     <li id="60-200">60+</li>
                                   </ul>
                               </div>
                           </div>
                        </div>
                        <div class="input-field">
                           <div class="input-select">
                               <div class="dropdown">
                                   <div class="select">
                                     <span>Genre</span>
                                   </div>
                                   <input type="hidden" id="gender" name="gender">
                                   <ul class="dropdown-menu">
                                     <li id="homme">Homme</li>
                                     <li id="femme">Femme</li>
                                     <li id="autre">Autre</li>
                                   </ul>
                               </div>
                           </div>
                        </div>
                        <div class="input-field">
                           <div class="input-select">
                               <div class="dropdown">
                                   <div class="select">
                                     <span>Orientation</span>
                                   </div>
                                   <input type="hidden" id="orientation" name="orientation">
                                   <ul class="dropdown-menu">
                                     <li id="hetero">Hétéro</li>
                                     <li id="gay">Gay</li>
                                     <li id="bi">Bi</li>
                                   </ul>
                               </div>
                           </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field">
                           <div class="input-select">
                                <div class="dropdown">
                                    <div class="select">
                                        <span>Région</span>
                                    </div>
                                    <input type="hidden" id="region" name="region">
                                    <ul class="dropdown-menu">
                                        <li id="auvergne-rhone-alpes">Auvergne-Rhône-Alpes</li>
                                        <li id="bourgogne-franche-comte">Bourgogne-Franche-Comté</li>
                                        <li id="bretagne">Bretagne</li>
                                        <li id="centre-val-de-loire">Centre-Val de Loire</li>
                                        <li id="corse">Corse</li>
                                        <li id="grand-est">Grand Est</li>
                                        <li id="hauts-de-france">Hauts-de-France</li>
                                        <li id="ile-de-france">Île-de-France</li>
                                        <li id="normandie">Normandie</li>
                                        <li id="nouvelle-aquitaine">Nouvelle-Aquitaine</li>
                                        <li id="occitanie">Occitanie</li>
                                        <li id="pays-de-la-loire">Pays de la Loire</li>
                                        <li id="provence-alpes-cote-dazur">Provence-Alpes-Côte d'Azur</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="input-field">
                           <div class="input-select">
                               <div class="dropdown">
                                   <div class="select">
                                     <span>Photos</span>
                                   </div>
                                   <input type="hidden" id="photos" name="photos">
                                   <ul class="dropdown-menu">
                                     <li id="photos-oui">Oui</li>
                                     <li id="photos-non">Non</li>
                                   </ul>
                               </div>
                           </div>
                        </div>
                        <div class="input-field">
                           <div class="input-select">
                               <div class="dropdown">
                                   <div class="select">
                                     <span>Popularité</span>
                                   </div>
                                   <input type="hidden" id="popularite" name="popularite">
                                   <ul class="dropdown-menu">
                                     <li id="0-5">0-5 Smashs</li>
                                     <li id="5-10">5-10 Smashs</li>
                                     <li id="10-25">10-25 Smashs</li>
                                     <li id="25-50">25-50 Smashs</li>
                                     <li id="50-1000">50+ Smashs</li>
                                   </ul>
                               </div>
                           </div>
                        </div>
                    </div>
                    
                    <div class="row third">
                        <div class="input-field">
                           <div class="group-btn">
                              <button class="reset-btn" id="delete">Réinitialiser</button>
                              <input type="submit" id="" name="" class="apply-btn" value="Appliquer"> 
                           </div>
                        </div>
                    </div>

                    <script>
                        // Reset filtres
                        document.addEventListener('DOMContentLoaded', function() {
                            var resetBtn = document.getElementById('delete');
                            resetBtn.addEventListener('click', function(event) {
                                event.preventDefault();
                                var resetOptions = document.querySelectorAll('.reset-option');
                                resetOptions.forEach(function(option) {
                                    var parent = option.parentElement;
                                    option.click();
                                    parent.click();
                                });

                            });
                        });

                        // Recherche quand on clique sur entrée
                        document.getElementById('search').addEventListener('keypress', function(event) {
                            if (event.key === 'Enter') {
                                event.preventDefault();
                                document.getElementById('searchForm').submit();
                            }
                        });
                    </script>

                  </div>
               </div>
            </form>
         </div>



        <!--LATEST USERS-->
        <div class="latest-users">
            
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
                $id = $_SESSION['id_utilisateur']; 
                $sql = "SELECT pseudonyme FROM Utilisateurs WHERE id!=$id AND estBanni = 0 ORDER BY id DESC LIMIT 10 ";
                $result = $connexion->query($sql);
                if ($result === false) {
                    die('Erreur de requête SQL: ' . $connexion->error);
                }
                $users = [];
                while ($row = $result->fetch_assoc()) {
                    $users[] = $row['pseudonyme'];
                }
                return $users;
            }

            // Récupérer la liste des utilisateurs
            $users = getUsersList($connexion);

            // Afficher les profils des utilisateurs avec des liens générés dynamiquement
            echo '<div class="users-row">';
            $count = 0;
            foreach ($users as $username) {
                $profileLink = genererLienProfil($connexion, $username);
                $imgLink = genererImageProfil($connexion, $username);
                echo '<div class="user-profile">';
                echo '<a href="' . $profileLink . '" class="user-profile">';
                echo '<img class="user-picture" src="'.$imgLink.'" alt="-user-picture">';
                echo '<p class="user-nickname">' . $username . '</p>';
                echo '</a>';
                echo '</div>';
                $count++;
                if ($count % 5 === 0 && $count<10) {
                    echo '</div><div class="users-row">';
                }
            }
            echo '</div>';
            ?>
        </div>


    </div>
    
    <div class="footer">

    </div>

</body>
</html>