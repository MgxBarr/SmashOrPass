<?php
    session_start(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- PAGE SETTINGS -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css">
    <link rel="shortcut icon" href="assets/icon.png" type="image/x-icon">
    <title>Smash OR Pass</title>

    <!-- FONT -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dosis:wght@200..800&family=Nunito:ital,wght@0,200..1000;1,200..1000" rel="stylesheet">
</head>
<body>

    <!--HEADER-->
    <div class="header">
        <div class="logo"><a href="index.php"><img src="assets/icon.png" alt="logo"></a></div>
        <div class="compte">
            <?php
                if (isset($_SESSION['id_utilisateur'])) {
                    echo '<a href="php/logout.php"><input class="disconnect-btn" type="button" value="Se déconnecter"></a>';
                } else {
                    echo '<input id="box2" onclick="openmodal(this)" class="login-btn" type="button" value="Se connecter">';
                    echo '<input id="box1" onclick="openmodal(this)" class="signup-btn" type="button" value="S\'inscrire">';
                }
            ?>
        </div>
    </div>

    <!--MAIN-->
    <div class="main">
        <div class="main-content">
            <h1 class="main-title">SMASH</h1>
            <h3 class="main-title2">Or Pass ?</h3>
            <p class="main-text">Découvrez l'amour à coup de coeur, <br>ou brisez les !</p>
            <input class="main-btn" type="button" value="Découvrir" onclick="console.log(window.innerHeight);window.scrollTo({top: window.innerHeight, behavior: 'smooth'});">
        </div>
    </div>

    <div class="infos">
        <div class="story">
            <p>Des rencontres plus simples que jamais : si vous aimez, vous smashez, sinon, vous passez. Pas de prise de tête, juste des connexions authentiques et spontanées. Explorez un univers où les premières impressions sont tout ce qui compte. <br><br>Smash ou pass, à vous de décider.</p>
        </div>
        <div class="stats">
            <div class="stat">
                <p class="stat-number"><img class="stat-icon" src="assets/stats-icon-1.png">9501</p>
                <p class="stat-text">Utilisateurs Inscrits</p>
            </div>
            <div class="stat">
                <p class="stat-number"><img class="stat-icon" src="assets/stats-icon-2.png">1650</p>
                <p class="stat-text">Couples créés</p>
            </div>
            <div class="stat">
                <p class="stat-number"><img class="stat-icon" src="assets/stats-icon-3.png">3214</p>
                <p class="stat-text">Smash-Back</p>
            </div>
            <div class="stat">
                <p class="stat-number"><img class="stat-icon" src="assets/stats-icon-4.png">42%</p>
                <p class="stat-text">Taux de Pass</p>
            </div>
        </div>
    </div>

    <div class="user-sample">
        <div class="users">
            <div class="user">
                <img class="user-icon" src="assets/user-icon-1.png" alt="user-icon">
                <p class="user-name">Sophie</p>
                <p class="user-testimonial">Smash Or Pass m'a aidé à rencontrer des personnes formidables qui partagent mes passions. Je n'aurais jamais pensé trouver l'amour en ligne, mais grâce à ce site, j'ai trouvé mon âme soeur !</p>
            </div>
            <div class="user">
                <img class="user-icon" src="assets/user-icon-2.png" alt="user-icon">
                <p class="user-name">Maxime</p>
                <p class="user-testimonial">Je suis tombé sur Smash Or Pass par hasard, mais je suis resté pour l'expérience. C'est incroyable de pouvoir découvrir autant de personnes intéressantes en un seul endroit. Merci pour cette plateforme fantastique !</p>
            </div>
            <div class="user">
                <img class="user-icon" src="assets/user-icon-3.png" alt="user-icon">
                <p class="user-name">Emma</p>
                <p class="user-testimonial">Je n'étais pas sûre de ce que je cherchais quand je me suis inscrite sur Smash Or Pass, mais j'ai rapidement été impressionnée par la diversité des profils. J'ai enfin pu trouvé quelqu'un qui me correspond vraiment !</p>
            </div>
            <div class="user">
                <img class="user-icon" src="assets/user-icon-4.png" alt="user-icon">
                <p class="user-name">Lucas</p>
                <p class="user-testimonial">En tant que célibataire occupé, je n'avais pas beaucoup de temps pour sortir et rencontrer de nouvelles personnes. Grâce à Smash Or Pass, je suis plus confiant que jamais dans mes rencontres pour trouver l'amour !</p>
            </div>
        </div>
    </div>

    <div class="joinus">
        <p class="joinus-text">Prêt à découvrir des personnes qui vous correspondent vraiment ? <br>Inscrivez-vous dès maintenant et commencez à créer des liens !</p>
        <div class="signup-login">
            <?php
                if (isset($_SESSION['id_utilisateur'])) {
                    echo '<a href="php/logout.php"><input class="disconnect-btn" type="button" value="Se déconnecter"></a>';
                } else {
                    echo '<input id="box2" onclick="openmodal(this)" class="signup-login-btn" type="button" value="Se connecter">';
                    echo '<input id="box1" onclick="openmodal(this)" class="signup-login-btn" type="button" value="S\'inscrire">';
                }
            ?>
                    
            <!--Sign Up-->
            <div id="modal-box1" class="modal">
                <div class="modal-content">
                    <span id="close-box1" class="close">&times;</span>
                    <p class="modal-titre">INSCRIPTION</p>
                    <form id="signup-form" class="modal-form" action="php/index/signup.php" method="post">
                        <div class="user-box">
                            <input id="pseudonyme" name="pseudonyme" type="text" required="required">
                            <label>Pseudonyme</label>
                        </div>
                        <div class="user-box">
                            <input id="sexe" name="sexe" type="text" required="required">
                            <label>Sexe</label>
                        </div>
                        <div class="user-box">
                            <input id="password" name="password" type="password" required="required">
                            <label>Mot de passe</label>
                        </div>
                        <input type="submit" class="form-submit-btn" value="S'inscrire">
                    </form>
                </div>
            </div>

            <!--Login-->
            <div id="modal-box2" class="modal">
                <div class="modal-content">
                    <span id="close-box2" class="close">&times;</span>
                    <p class="modal-titre">CONNEXION</p>
                    <form class="modal-form" action="php/index/login.php" method="post">
                        <div class="user-box">
                            <input id="pseudonyme" name="pseudonyme" type="text" required="required">
                            <label>Pseudonyme</label>
                        </div>
                        <div class="user-box">
                            <input id="password" name="password" type="password" required="required">
                            <label>Mot de passe</label>
                        </div>
                        <input type="submit" class="form-submit-btn" value="Se connecter">
                        <p class="logintosignup">Vous n'avez pas de compte ? <br> <span onclick="signup()">Inscrivez-vous</span></p>
                    </form>
                </div>
            </div>

        </div>


        <script>
            function openmodal(m) {
                //ouvre le modal
                document.body.style.overflow="hidden"
                var modal=document.getElementById("modal-"+m.id);
                modal.style.display="flex";
                
                //ferme le modal si on clique sur sa croix
                document.getElementById("close-"+m.id).onclick = function() {
                    modal.style.animation = "unloading 0.7s";
                    setTimeout(() => {  modal.style.display = "none", modal.style.animation="loading 0.7s", document.body.style.overflow="" }, 650);
                }

                //ferme le modal si on clique en dehors
                window.onclick = function(event) {
                    if (event.target == modal) {
                        modal.style.animation = "unloading 0.7s";
                        setTimeout(() => {  modal.style.display = "none", modal.style.animation="loading 0.7s",  document.body.style.overflow="" }, 650);
                    }
                }
            }

            function signup() {
                document.getElementById('close-box2').click();
                setTimeout(() => { document.body.style.overflow="hidden"; }, 650);
                document.getElementById('box1').click();
            }



            // Verifie la bonne valeur pour le sexe
            function validateForm(event) {
                var pattern = /^(Homme|homme|Femme|femme|Autre|autre)$/;
                var valeur = document.getElementById("sexe").value;
                if (!pattern.test(valeur)) {
                    alert("Veuillez saisir une valeur valide pour le sexe :\n'homme', 'femme' ou 'autre'.");
                    event.preventDefault();
                }
            }

            document.addEventListener("DOMContentLoaded", function() {
                var form = document.getElementById("signup-form");
                form.addEventListener("submit", validateForm);
            });
        </script>
    </div>



     <!-- POPUPS INSCRIPTION -->
    <div id="inscription-success-popup" class="popup" onclick="closeInscriptionPopup()">
        <div class="popup-content">
            <span class="close">&times;</span>
            <p>Inscription réussie !<br>Vous pouvez maintenant vous connecter.</p>
        </div>
    </div>

    <div id="inscription-fail-popup" class="popup" onclick="closeInscriptionPopup()">
        <div class="popup-content">
            <span class="close">&times;</span>
            <p>L'inscription a échouée.<br>Ce pseudonyme est déjà utilisé. <br>Veuillez réessayer avec un autre pseudonyme. </p>
        </div>
    </div>

    <div id="login-fail-popup" class="popup" onclick="closeInscriptionPopup()">
        <div class="popup-content">
            <span class="close">&times;</span>
            <p>Pseudonyme ou Mot de passe incorrect. <br>Veuillez réessayer.</p>
        </div>
    </div>

    <div id="login-ban-popup" class="popup" onclick="closeInscriptionPopup()">
        <div class="popup-content">
            <span class="close">&times;</span>
            <p>Votre compte à été banni. </p>
        </div>
    </div>

    <?php   
        //Signup fail
        if (isset($_SESSION['inscription_reussie'])) {
            if ($_SESSION['inscription_reussie']) {
                echo "<script>document.getElementById('inscription-success-popup').style.display='flex';</script>";
            } else {
                echo "<script>document.getElementById('inscription-fail-popup').style.display='flex';</script>";
            }
            unset($_SESSION['inscription_reussie']);
        }

        //Login fail : pseudo ou mdp incorrect
        if (isset($_SESSION['login-fail'])) {
            if ($_SESSION['login-fail'] == 2) {
                echo "<script>document.getElementById('login-ban-popup').style.display='flex';</script>";
            }
            else if ($_SESSION['login-fail'] == 1) {
                echo "<script>document.getElementById('login-fail-popup').style.display='flex';</script>";
            }
            unset($_SESSION['login-fail']);
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
                popup.style.animation = "unloading 0.5s";
                setTimeout(() => {  popup.style.display = "none" }, 450);
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


</body>
</html>

