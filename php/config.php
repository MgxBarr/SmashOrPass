<?php
// Informations de connexion à la base de données
define('DB_SERVER', 'localhost'); // Adresse du serveur MySQL
define('DB_USERNAME', 'test'); // Nom d'utilisateur MySQL
define('DB_PASSWORD', 'Testmdp@65'); // Mot de passe MySQL
define('DB_NAME', 'site_rencontre'); // Nom de la base de données MySQL

// Connexion à la base de données
$connexion = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Vérification de la connexion
if ($connexion->connect_error) {
    die("Connexion échouée : " . $connexion->connect_error);
}
?>
