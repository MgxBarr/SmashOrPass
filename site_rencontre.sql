-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 11, 2024 at 08:19 PM
-- Server version: 8.0.36-0ubuntu0.22.04.1
-- PHP Version: 8.1.2-1ubuntu2.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `site_rencontre`
--

-- --------------------------------------------------------

--
-- Table structure for table `Abonnes`
--
DROP DATABASE IF EXISTS site_rencontre;
CREATE DATABASE site_rencontre;

CREATE TABLE `Abonnes` (
  `id` int NOT NULL,
  `type_abonnement` enum('aucun','gratuit','mensuel','trimestriel','annuel','infini') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'aucun',
  `date_souscription` date DEFAULT NULL,
  `aGratuit` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Abonnes`
--

INSERT INTO `Abonnes` (`id`, `type_abonnement`, `date_souscription`, `aGratuit`) VALUES
(1, 'infini', '2024-04-11', 1),
(2, 'infini', '2024-04-11', 1),
(3, 'mensuel', '2024-04-11', 1),
(4, 'aucun', '2024-04-11', 1),
(5, 'aucun', '2024-04-11', 1),
(6, 'aucun', '2024-04-11', 1),
(7, 'infini', '2024-04-11', 1),
(8, 'infini', '2024-04-11', 1),
(9, 'infini', '2024-04-11', 1),
(10, 'aucun', '2024-04-11', 1),
(11, 'aucun', '2024-04-11', 1);

-- --------------------------------------------------------

--
-- Table structure for table `Bloques`
--

CREATE TABLE `Bloques` (
  `id_utilisateur_bloquant` int NOT NULL,
  `id_utilisateur_bloque` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `InfosConnexions`
--

CREATE TABLE `InfosConnexions` (
  `id` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `InfosConnexions`
--

INSERT INTO `InfosConnexions` (`id`, `username`, `password`) VALUES
(1, 'Serena Van Der Woodsen', 'mdp'),
(2, 'Blair Waldorf', 'mdp'),
(3, 'Chuck Bass', 'mdp'),
(4, 'Dan Humphrey', 'mdp'),
(5, 'Nate Archibald', 'mdp'),
(6, 'Rufus Humphrey', 'mdp'),
(7, 'Lily Van Der Woodsen', 'mdp'),
(8, 'Vanessa Abrams ', 'mdp'),
(9, 'Georgina Sparks', 'mdp'),
(10, 'Carter Baizen', 'mdp'),
(11, 'Damien Dalgaard', 'mdp');

-- --------------------------------------------------------

--
-- Table structure for table `Likes`
--

CREATE TABLE `Likes` (
  `id_like` int NOT NULL,
  `id_utilisateur_likeur` int DEFAULT NULL,
  `id_utilisateur_like` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Messages`
--

CREATE TABLE `Messages` (
  `id_message` int NOT NULL,
  `id_sender` int NOT NULL,
  `id_receiver` int NOT NULL,
  `message` text NOT NULL,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `aEteReport` tinyint(1) DEFAULT '0',
  `raison_report` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Messages`
--

INSERT INTO `Messages` (`id_message`, `id_sender`, `id_receiver`, `message`, `timestamp`, `aEteReport`, `raison_report`) VALUES
(1, 2, 3, '[Debut de la conversation]', '2024-04-11 18:16:01', 0, NULL),
(2, 2, 3, 'Message 1', '2024-04-11 18:16:21', 0, NULL),
(3, 2, 3, 'Test message conversation 2', '2024-04-11 18:16:27', 0, NULL),
(4, 2, 3, 'Un plus long message de la conversation 3', '2024-04-11 18:16:38', 0, NULL),
(5, 3, 2, 'Suite de la conversation', '2024-04-11 18:18:00', 0, NULL),
(6, 3, 2, 'Encore la un autre message', '2024-04-11 18:18:18', 0, NULL),
(7, 2, 3, 'ok', '2024-04-11 18:18:37', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `Utilisateurs`
--

CREATE TABLE `Utilisateurs` (
  `id` int NOT NULL,
  `prenom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `nom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `pseudonyme` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `sexe` enum('homme','femme','autre') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `age` int DEFAULT NULL,
  `profession` varchar(255) DEFAULT NULL,
  `lieu_residence` varchar(255) DEFAULT NULL,
  `situation_amoureuse` text,
  `informations_personnelles` text,
  `estAdmin` tinyint(1) DEFAULT '0',
  `date_inscription` date DEFAULT NULL,
  `description_physique` text,
  `adresse` text,
  `img_profil` varchar(255) DEFAULT 'default-user.jpg',
  `orientation` enum('hetero','bi','gay','') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `estBanni` tinyint(1) NOT NULL DEFAULT '0',
  `img_1` varchar(255) DEFAULT 'default-blank.jpg',
  `img_2` varchar(255) DEFAULT 'default-blank.jpg',
  `img_3` varchar(255) DEFAULT 'default-blank.jpg',
  `img_4` varchar(255) DEFAULT 'default-blank.jpg',
  `date_de_naissance` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `compteur_likes` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Utilisateurs`
--

INSERT INTO `Utilisateurs` (`id`, `prenom`, `nom`, `pseudonyme`, `sexe`, `age`, `profession`, `lieu_residence`, `situation_amoureuse`, `informations_personnelles`, `estAdmin`, `date_inscription`, `description_physique`, `adresse`, `img_profil`, `orientation`, `estBanni`, `img_1`, `img_2`, `img_3`, `img_4`, `date_de_naissance`, `compteur_likes`) VALUES
(1, 'Serena', 'Van Der Woodsen', 'Serena Van Der Woodsen', 'femme', 32, 'Journaliste', 'Paris, île de france', 'Je suis à la recherche de quelque chose de sérieux, mais j\'ai eu quelques relations compliquées par le passé. En ce qui concerne ma famille, eh bien, elle est prestigieuse, mais parfois ça peut être un peu étouffant. J\'ai besoin de quelqu\'un qui puisse comprendre ça.', 'Je suis passionnée, spontanée et toujours en quête d\'aventure. Malgré mon statut social, je cherche toujours à rester fidèle à moi-même et à suivre mon propre chemin dans la vie.', 0, '2024-04-10', 'Je suis une femme élégante avec des cheveux blonds ondulés et des yeux bleus perçants. Mon style vestimentaire allie sophistication et décontraction.', '', 'serena.png', 'hetero', 0, 'serena1.png', 'serena2.png', 'serena3.png', 'serena4.png', '14 juillet 1991', 0),
(2, 'Blair', 'Waldorf', 'Blair Waldorf', 'femme', 0, 'étudiante', '', '', '', 0, '2024-04-10', '', '', 'blair.png', 'hetero', 0, 'blair1.png', 'blair2.png', 'blair3.png', 'blair4.png', '', 0),
(3, 'Chuck', 'Bass', 'Chuck Bass', 'homme', 32, 'PDG', 'paris, ile de france', 'Je suis prêt à explorer de nouvelles possibilités, même si mon passé peut être un peu tumultueux. Ma situation familiale, disons qu\'elle est compliquée, mais je cherche quelqu\'un qui puisse m\'accepter tel que je suis.', 'Malgré mon image de playboy, je suis quelqu\'un de profondément loyal envers ceux que j\'aime. J\'ai une passion pour les affaires et j\'aime repousser les limites.', 0, '2024-04-10', 'Je suis souvent décrit comme un jeune homme séduisant avec des cheveux bruns foncés et un regard perçant. Mon style vestimentaire est sophistiqué et souvent associé à des costumes élégants.', '', 'chuck.png', 'hetero', 0, 'chuck1.png', 'chuck2.png', 'chuck3.png', 'chuck4.png', '19 juillet 1991', 0),
(4, 'Dan', 'Humphrey', 'Dan Humphrey', 'homme', 33, 'écrivain', 'toulouse, Occitanie', 'Je suis prêt à rencontrer quelqu\'un de spécial, quelqu\'un qui comprend ma passion pour l\'écriture et qui ne se soucie pas tant de mon statut social. Ma famille ? Elle est simple, mais solide. J\'espère trouver quelqu\'un qui apprécie ça.', 'Je suis un écrivain passionné et un penseur indépendant. J\'aime les discussions profondes et j\'apprécie les petites choses de la vie.', 1, '2024-04-10', 'Je suis un homme brun aux cheveux légèrement désordonnés et aux yeux marron expressifs. Mon style vestimentaire est généralement décontracté.', '112 Plymouth Street', 'dan.png', 'hetero', 0, 'dan1.png', 'dan2.png', 'dan3.png', 'dan4.png', '3 mars 1991', 0),
(5, 'Nate', 'Archibald', 'Nate Archibald', 'homme', 32, 'éditeur en chef', 'nantes, pays de la loire', 'Je suis ouvert à de nouvelles rencontres, mais je cherche quelque chose de sincère. Ma famille est riche, mais ça ne veut pas dire que je recherche quelqu\'un de similaire. Je veux juste quelqu\'un de loyal et de compréhensif.', 'Malgré mon statut social, je suis quelqu\'un de simple et de terre à terre. J\'apprécie les moments simples de la vie et je cherche quelqu\'un avec qui je pourrais partager ces moments.', 0, '2024-04-10', 'Je suis un jeune homme séduisant avec des cheveux blonds dorés et des yeux bleus envoûtants. Mon allure athlétique attire souvent l\'attention.', '', 'nate.png', 'hetero', 0, 'nate2.png', 'nate3.png', 'nate4.png', 'nate1.png', '5 août 1991', 0),
(6, 'Rufus', 'Humphrey', 'Rufus Humphrey', 'homme', 52, 'Musicien', 'bordeaux, Nouvelle aquitaine', 'Je suis prêt à rencontrer quelqu\'un de spécial, quelqu\'un avec qui je pourrais partager ma passion pour la musique et l\'art. Ma famille est simple, mais unie, j’ai un fils Dan et une fille Jenny. ', 'Je suis un artiste dans l\'âme, passionné par la musique et la créativité. J\'apprécie les moments simples de la vie et je cherche quelqu\'un avec qui je pourrais partager ma passion.', 0, '2024-04-10', 'Je suis généralement décrit comme un homme au look décontracté avec des cheveux bruns ébouriffés et une barbe de trois jours. Mon style vestimentaire est souvent plus bohème que celui de mes voisins riches.', '', 'rufus.png', 'hetero', 0, 'rufus1.png', 'rufus2.png', 'rufus3.png', 'rufus4.png', '22 septembre 1973', 0),
(7, 'Lily', 'Van Der Woodsen', 'Lily Van Der Woodsen', 'femme', 58, 'Photographe', 'rennes, bretagne', 'Je suis prête à ouvrir mon cœur à quelqu\'un qui comprendra les défis que ma famille peut parfois apporter. Car elle est prestigieuse, mais parfois ça peut être un peu compliqué. J\'ai besoin de quelqu\'un qui puisse comprendre ça. J’ai une fille Serena et un fils Eric. ', 'Je suis une femme forte et déterminée, mais je cache souvent des secrets douloureux de mon passé. J\'aspire à trouver un équilibre entre mes responsabilités familiales et mes désirs personnels.', 0, '2024-04-10', 'Je suis une femme élégante et sophistiquée avec des cheveux blonds élégants et un style vestimentaire impeccable. Mon apparence reflète souvent mon statut social élevé et mon sens du glamour.', '', 'lily.png', 'hetero', 0, 'lily1.png', 'lily2.png', 'lily3.png', 'lily4.png', '9 mars 1966', 0),
(8, 'Vanessa', 'Abrams', 'Vanessa Abrams ', 'femme', 33, 'réalisatrice de film', 'metz, grand est', 'Je suis ouverte à de nouvelles rencontres, mais je cherche quelqu\'un qui partage ma passion pour l\'art et qui ne se soucie pas tant de l\'argent et du statut social. Ma famille est simple, mais aimante.', 'Je suis une artiste passionnée et une militante sociale. J\'aspire à un monde plus juste et égalitaire, et je cherche quelqu\'un qui partage ces valeurs.', 0, '2024-04-10', 'Je suis souvent décrite comme une jeune femme au look bohème avec des cheveux bruns ondulés et des yeux expressifs. Mon style vestimentaire est souvent décontracté et éclectique.', '', 'vanessa.png', 'hetero', 0, 'vanessa1.png', 'vanessa2.png', 'vanessa3.png', 'vanessa4.png', '19 février 1991', 0),
(9, 'Georgina', 'Sparks', 'Georgina Sparks', 'femme', 34, 'Manager', 'ajaccio, corse', 'Je suis à la recherche de quelqu\'un qui puisse suivre mon rythme, quelqu\'un d\'aussi audacieux que moi. Ma famille est... unique, mais je préfère me concentrer sur l\'avenir plutôt que sur le passé.', 'Je suis passionnée, impulsive et toujours en quête d\'excitation. J\'aime repousser les limites et je suis prête à tout pour obtenir ce que je veux.', 0, '2024-04-10', 'J\'ai une apparence distinctive avec des cheveux blonds platine et un maquillage souvent prononcé. Mon style vestimentaire est souvent audacieux et provocateur.', '', 'georgina.png', 'hetero', 0, 'georgina1.png', 'georgina2.png', 'georgina3.png', 'georgina4.png', '8 novembre 1990', 0),
(10, 'Carter', 'Baizen', 'Carter Baizen', 'homme', 36, 'Entrepreneur', 'Lyon, auvergne rhônes alpes', 'Je suis ouvert à de nouvelles rencontres, mais je cherche quelque chose de léger et sans prise de tête. Ma famille ? Disons qu\'on a nos différends, mais je préfère me concentrer sur l\'instant présent.', 'Je suis quelqu\'un qui aime profiter de la vie au jour le jour. J\'apprécie les plaisirs simples et je suis toujours prêt à relever de nouveaux défis.', 0, '2024-04-10', 'Je suis souvent décrit comme un jeune homme séduisant avec des cheveux bruns et des yeux perçants. Mon allure élégante et mon charisme naturel me permettent souvent de me démarquer.', '', 'carter.png', 'hetero', 0, 'carter1.png', 'carter2.png', 'carter3.png', 'carter4.png', '21 avril 1988', 0),
(11, 'Damien', 'Dalgaard', 'Damien Dalgaard', 'homme', 42, 'trafiquant de drogue', 'dijon, bourgogne franche comté', 'Je suis ouvert à de nouvelles expériences, mais je cherche quelqu\'un qui puisse suivre mon rythme, quelqu\'un qui aime l\'excitation de l\'inattendu. Je ne préfère pas parler de ma famille mais plutôt me concentrer sur ma vie. ', 'Je suis passionné, aventureux et toujours à la recherche de nouvelles sensations. Je suis prêt à tout pour obtenir ce que je veux et je cherche quelqu\'un qui puisse suivre le rythme de ma vie excitante.', 0, '2024-04-10', 'Je suis souvent décrit comme un jeune homme séduisant avec des cheveux bruns désordonnés et un regard intense. Mon allure décontractée et mon charisme me permettent souvent de me fondre dans n\'importe quel environnement.', '', 'damien.png', 'hetero', 0, 'damien1.png', 'damien2.png', 'damien3.png', 'damien4.png', '28 décembre 1982', 0);

-- --------------------------------------------------------

--
-- Table structure for table `Visite`
--

CREATE TABLE `Visite` (
  `idVisite` int NOT NULL,
  `idUserVisiteur` int DEFAULT NULL,
  `idUserProfil` int DEFAULT NULL,
  `dateVisite` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Visite`
--

INSERT INTO `Visite` (`idVisite`, `idUserVisiteur`, `idUserProfil`, `dateVisite`) VALUES
(1, 2, 3, '2024-04-11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Abonnes`
--
ALTER TABLE `Abonnes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Bloques`
--
ALTER TABLE `Bloques`
  ADD PRIMARY KEY (`id_utilisateur_bloquant`,`id_utilisateur_bloque`),
  ADD KEY `id_utilisateur_bloque` (`id_utilisateur_bloque`);

--
-- Indexes for table `InfosConnexions`
--
ALTER TABLE `InfosConnexions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Likes`
--
ALTER TABLE `Likes`
  ADD PRIMARY KEY (`id_like`),
  ADD KEY `id_utilisateur_likeur` (`id_utilisateur_likeur`),
  ADD KEY `id_utilisateur_like` (`id_utilisateur_like`);

--
-- Indexes for table `Messages`
--
ALTER TABLE `Messages`
  ADD PRIMARY KEY (`id_message`),
  ADD KEY `Messages_ibfk_1` (`id_sender`),
  ADD KEY `Messages_ibfk_2` (`id_receiver`);

--
-- Indexes for table `Utilisateurs`
--
ALTER TABLE `Utilisateurs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Visite`
--
ALTER TABLE `Visite`
  ADD PRIMARY KEY (`idVisite`),
  ADD KEY `idUserVisiteur` (`idUserVisiteur`),
  ADD KEY `idUserProfil` (`idUserProfil`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Abonnes`
--
ALTER TABLE `Abonnes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `InfosConnexions`
--
ALTER TABLE `InfosConnexions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `Likes`
--
ALTER TABLE `Likes`
  MODIFY `id_like` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Messages`
--
ALTER TABLE `Messages`
  MODIFY `id_message` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `Utilisateurs`
--
ALTER TABLE `Utilisateurs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `Visite`
--
ALTER TABLE `Visite`
  MODIFY `idVisite` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Bloques`
--
ALTER TABLE `Bloques`
  ADD CONSTRAINT `Bloques_ibfk_1` FOREIGN KEY (`id_utilisateur_bloquant`) REFERENCES `Utilisateurs` (`id`),
  ADD CONSTRAINT `Bloques_ibfk_2` FOREIGN KEY (`id_utilisateur_bloque`) REFERENCES `Utilisateurs` (`id`);

--
-- Constraints for table `Likes`
--
ALTER TABLE `Likes`
  ADD CONSTRAINT `Likes_ibfk_1` FOREIGN KEY (`id_utilisateur_likeur`) REFERENCES `Utilisateurs` (`id`),
  ADD CONSTRAINT `Likes_ibfk_2` FOREIGN KEY (`id_utilisateur_like`) REFERENCES `Utilisateurs` (`id`);

--
-- Constraints for table `Messages`
--
ALTER TABLE `Messages`
  ADD CONSTRAINT `Messages_ibfk_1` FOREIGN KEY (`id_sender`) REFERENCES `Utilisateurs` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `Messages_ibfk_2` FOREIGN KEY (`id_receiver`) REFERENCES `Utilisateurs` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `Visite`
--
ALTER TABLE `Visite`
  ADD CONSTRAINT `Visite_ibfk_1` FOREIGN KEY (`idUserVisiteur`) REFERENCES `Utilisateurs` (`id`),
  ADD CONSTRAINT `Visite_ibfk_2` FOREIGN KEY (`idUserProfil`) REFERENCES `Utilisateurs` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
