# Smash Or Pass
## Projet Web ING1 S1 

Site de rencontre innovant qui propose une expérience ludique et interactive pour aider les utilisateurs à trouver leur âme sœur.

# Installation 

### S'assurer que phpMyAdmin est bien installé 

Dans une invite de commande entrer les commandes suivantes : 
$ sudo a2enmod php8.1 
$ sudo systemctl restart apache2 

### Installer la base de données 
Ouvrir un terminal dans le dossier qui contient le projet. 
Entrer la commande suivante : 
```
$ php -S localhost:8080
```
Ouvrir un navigateur et se rendre à l'adresse suivante : localhost/phpmyadmin.
Se connecter avec son login et son mot de passe. 
Cliquer sur <i>import</i> dans le menu supérieur. 
Choisir le fichier site_rencontre.sql. 

### Se connecter à la base de données 
#### Méthode 1 

- si connecté en root sur phpMyAdmin :
Sélectionner la nouvelle base de données, cliquer sur <i>Privilèges</i> dans le menu supérieur puis sur <i>add user account</i>.
Remplir les informations contenue dans le fichier config.php (username : test, password : Testmdp@65).
Cocher <i>grant all privileges on database site_rencontre.sql</i>. 

- via l'invite de commande :
Entrer la commande :
```
$ sudo mysql -u root -p
```
Entrer vos login et mot de passe root puis les commandes suivantes :
```
$ CREATE USER 'test'@'localhost' IDENTIFIED BY 'Testmdp@65);
$ GRANT ALL PRIVILEGES ON site_rencontre.* TO 'test'@'localhost';
$ FLUSH PRIVILEGES;
```

#### Méthode 2
Ouvrir le fichier config.php dans le dossier php et changer le login et le mot de passe par ceux de votre utilisateur existant. 

# Utilisation 
Ouvrir un navigateur (de préférence Chrome) et se rendre à l'adresse suivante : localhost:8080/index.php. 




