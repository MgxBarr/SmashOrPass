
<?php
    session_start(); 
    //verif si user connecté 
    if (!isset($_SESSION['id_utilisateur'])) {
        header('Location: index.php');
        exit();
    }
    require_once('php/config.php'); 

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
    <link rel="stylesheet" href="css/research.css">
    <link rel="shortcut icon" href="assets/icon.png" type="image/x-icon">
    <title>Recherche - Smash OR Pass</title>

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

        <h1 class="title">Vos Smashs potentiels</h1>
        <div class="research-results">
            <?php
                // Recuperer les résultats de recherche
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $search = $_POST['search']; 
                    $gender = $_POST['gender']; 
                    $orientation = $_POST['orientation']; 
                    $age = $_POST['age']; 

                    $id_utilisateur_connecte = $_SESSION['id_utilisateur']; 

                    $sql = "SELECT * FROM Utilisateurs WHERE estBanni = 0";

                    if (!empty($search)) {
                        $sql .= " AND (pseudonyme LIKE '%$search%' OR informations_personnelles LIKE '%$search%' OR description_physique LIKE '%$search%' OR profession LIKE '%$search%' OR lieu_residence LIKE '%$search%')";
                    }

                    if (!empty($gender)) {
                        $sql .= " AND sexe = '$gender'";
                    }

                    if (!empty($orientation)) {
                        $sql .= " AND orientation = '$orientation'";
                    }

                    if (!empty($age)) {
                        list($minAge, $maxAge) = explode('-', $age);
                        $sql .= " AND age BETWEEN $minAge AND $maxAge";
                    }

                    if (!empty($id_utilisateur_connecte)) {
                        $sql .= " AND id != $id_utilisateur_connecte";
                    }



                    $region = $_POST['region'];
                    $region = str_replace(array('-', ' '), '', $region);
                    if (!empty($region)) {
                        $sql .= " AND LOWER(REPLACE(REPLACE(lieu_residence, '-', ''), ' ', '')) LIKE LOWER('%$region%')";
                    }

                    $photos = $_POST['photos'];
                    if (!empty($photos) && $photos == "photos-oui") {
                        $sql .= " AND (img_1 != 'default-blank.jpg' OR img_2 != 'default-blank.jpg' OR img_3 != 'default-blank.jpg' OR img_4 != 'default-blank.jpg')";
                    }
                    if (!empty($photos) && $photos == "photos-non") {
                        $sql .= " AND (img_1 = 'default-blank.jpg' AND img_2 = 'default-blank.jpg' AND img_3 = 'default-blank.jpg' AND img_4 = 'default-blank.jpg')";
                    }

                    $popularite = $_POST['popularite'];
                    if (!empty($popularite)) {
                        list($minPop, $maxPop) = explode('-', $popularite);
                        $sql .= " AND compteur_likes BETWEEN $minPop AND $maxPop";
                    }

                    $result = $connexion->query($sql);

                    if ($result->num_rows > 0) {
                        $results = array();
                        while ($row = $result->fetch_assoc()) {
                            $results[] = $row;
                        }
                    } else {
                        echo "<p class='no-results'>Aucun utilisateur trouvé.</p>";
                    }

                    $connexion->close();
                    
                }
            ?>

            <?php
                // Afficher les resultats de la recherche
                foreach ($results as $user) {
                    echo '<div class="result">';
                    echo '<img class="profile-picture" src="user-img/' . $user['img_profil'] .'" alt="profile-picture">';
                    echo '<div class="profile-infos">';
                    echo '<p class="pseudonyme" id="pseudonyme">' . $user['pseudonyme'] . '</p>';
                    echo '<div class="profile-infos-2">';
                    echo '<p>';
                    if(isset($user['age']) && $user['age'] >= 18) {
                        echo $user['age'] . ' ans';
                    }
                    echo '</p>';
                    echo '<p>' . $user['sexe'] . '</p>';
                    echo '<p>' . $user['lieu_residence'] . '</p>';
                    echo '<p>' . $user['orientation'] . '</p>';
                    echo '</div>';
                    echo '</div>';
                    echo '<a class="go-to-profile" href="profile.php?id_utilisateur='.$user['id'].'" target="_blank">Accéder au profil</a>';
                    echo '</div>';
                }
            ?>

            <!--<input class="suggest" type="button" value="Voir plus de profils">-->
        </div>
    </div>



    <div class="footer">
    </div>

</body>
</html>
