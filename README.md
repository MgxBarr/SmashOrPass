# Smash Or Pass
## Project Web ING1 S1 

Innovative dating website offering a fun, interactive experience to help users find their soulmate.

# Installation 

### Make sure phpMyAdmin is installed correctly 

Enter the following commands in a command prompt: 
```
sudo a2enmod php8.1 
sudo systemctl restart apache2
```

### Setting up the database
Open a command prompt in the folder containing the project. 
Enter the following command: 
```
php -S localhost:8080
```
Open a browser and go to the following address  : localhost/phpmyadmin.
Log yourself in. 
Click on <i>import</i> in the top menu. 
Choose the file site_rencontre.sql. 

### Connect to the database 
#### Method 1 

- if logged in as root on phpMyAdmin :
Select the new database, click on <i>Privileges</i> in the top menu then on <i>add user account</i>.
Fill in the information in config.php (username : test, password : Testmdp@65).
Tick <i>grant all privileges on database site_rencontre.sql</i>. 

- via command prompt :
Enter the command :
```
sudo mysql -u root -p
```
Enter your root login and password, then the following commands :
```
CREATE USER 'test'@'localhost' IDENTIFIED BY 'Testmdp@65);
GRANT ALL PRIVILEGES ON site_rencontre.* TO 'test'@'localhost';
FLUSH PRIVILEGES;
```

#### Method 2
Open the config.php file in the php folder and change the login and password to those of your existing user. 

# Use 
Open a browser (preferably Chrome) and go to the following address : localhost:8080/index.php. 




