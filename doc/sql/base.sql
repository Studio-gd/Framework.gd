# ************************************************************
# Sequel Pro SQL dump
# Version 3408
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: localhost (MySQL 5.5.9)
# Database: framework
# Generation Time: 2012-03-29 14:53:50 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table avatar
# ------------------------------------------------------------

DROP TABLE IF EXISTS `avatar`;

CREATE TABLE `avatar` (
  `object` varchar(35) NOT NULL,
  `object_id` int(7) unsigned NOT NULL,
  `user_id` int(7) unsigned NOT NULL,
  `version` int(4) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table connect_log
# ------------------------------------------------------------

DROP TABLE IF EXISTS `connect_log`;

CREATE TABLE `connect_log` (
  `username` varchar(20) NOT NULL,
  `logged` datetime NOT NULL,
  `ip` varchar(15) DEFAULT NULL,
  KEY `logged` (`logged`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `connect_log` WRITE;
/*!40000 ALTER TABLE `connect_log` DISABLE KEYS */;

INSERT INTO `connect_log` (`username`, `logged`, `ip`)
VALUES
	('admin','2012-03-15 15:25:23','127.0.0.1'),
	('admin','2012-03-15 15:25:28','127.0.0.1');

/*!40000 ALTER TABLE `connect_log` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table connected
# ------------------------------------------------------------

DROP TABLE IF EXISTS `connected`;

CREATE TABLE `connected` (
  `user_id` int(9) unsigned NOT NULL,
  `inserted` datetime NOT NULL,
  `hash` varchar(15) DEFAULT NULL,
  `ip` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `connected` WRITE;
/*!40000 ALTER TABLE `connected` DISABLE KEYS */;

INSERT INTO `connected` (`user_id`, `inserted`, `hash`, `ip`)
VALUES
	(2,'2012-03-29 13:26:48','1558822948124ad','127.0.0.1');

/*!40000 ALTER TABLE `connected` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table lang
# ------------------------------------------------------------

DROP TABLE IF EXISTS `lang`;

CREATE TABLE `lang` (
  `id` varchar(3) NOT NULL,
  `code` varchar(7) NOT NULL,
  `label` varchar(22) NOT NULL,
  `activate` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `lang` WRITE;
/*!40000 ALTER TABLE `lang` DISABLE KEYS */;

INSERT INTO `lang` (`id`, `code`, `label`, `activate`)
VALUES
	('en','en_en','English',1),
	('fr','fr_fr','FranÃ§ais',1);

/*!40000 ALTER TABLE `lang` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table lang_fr
# ------------------------------------------------------------

DROP TABLE IF EXISTS `lang_fr`;

CREATE TABLE `lang_fr` (
  `ref_id` int(9) unsigned NOT NULL,
  `str` text NOT NULL,
  UNIQUE KEY `ref_id` (`ref_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `lang_fr` WRITE;
/*!40000 ALTER TABLE `lang_fr` DISABLE KEYS */;

INSERT INTO `lang_fr` (`ref_id`, `str`)
VALUES
	(1,'Inscription'),
	(2,'Connexion'),
	(3,'Rechercher'),
	(4,'Fermer'),
	(5,'Chargement'),
	(6,'ou'),
	(7,'DÃ©connexion'),
	(8,'Accueil'),
	(9,'Profil'),
	(10,'Utilisateurs'),
	(11,'Utilisateur'),
	(12,'Liste'),
	(13,'Erreur'),
	(14,'Modifier'),
	(15,'Date de naissance'),
	(16,'Nom'),
	(17,'Adresse'),
	(18,'Code postal'),
	(19,'Pays'),
	(20,'Ville'),
	(21,''),
	(22,'Statistiques'),
	(23,'Site web'),
	(24,'Lundi'),
	(25,'Mardi'),
	(26,'Mercredi'),
	(27,'Jeudi'),
	(28,'Vendredi'),
	(29,'Samedi'),
	(30,'Dimanche'),
	(31,'Janvier'),
	(32,'Fevrier'),
	(33,'Mars'),
	(34,'Avril'),
	(35,'Mai'),
	(36,'Juin'),
	(37,'Juillet'),
	(38,'Aout'),
	(39,'Septembre'),
	(40,'Octobre'),
	(41,'Novembre'),
	(42,'Decembre'),
	(43,'Identifiant'),
	(44,'Mot de passe'),
	(45,'Customiser'),
	(46,'Homme'),
	(47,'Femme'),
	(48,'Sexe'),
	(49,'Modifier le mot de passe'),
	(50,'Nouveau mot de passe'),
	(51,'Nouveau mot de passe (confirmation)'),
	(52,'Annuler'),
	(53,'Mot de passe oubliÃ© ?'),
	(54,'Enregistrer'),
	(55,'Supprimer'),
	(56,'Ajouter'),
	(57,'titre'),
	(58,'Envoyer'),
	(59,'PrÃ©nom'),
	(60,'Nom'),
	(61,'DÃ©placer');

/*!40000 ALTER TABLE `lang_fr` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table lang_ref
# ------------------------------------------------------------

DROP TABLE IF EXISTS `lang_ref`;

CREATE TABLE `lang_ref` (
  `id` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `str` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `lang_ref` WRITE;
/*!40000 ALTER TABLE `lang_ref` DISABLE KEYS */;

INSERT INTO `lang_ref` (`id`, `str`)
VALUES
	(1,'Sign up'),
	(2,'Sign in'),
	(3,'Search'),
	(4,'Close'),
	(5,'Loading'),
	(6,'or'),
	(7,'Sign out'),
	(8,'Home'),
	(9,'Profile'),
	(10,'Users'),
	(11,'User'),
	(12,'List'),
	(13,'Error'),
	(14,'Edit'),
	(15,'Birthdate'),
	(16,'Name'),
	(17,'Address'),
	(18,'Postcode'),
	(19,'Country'),
	(20,'City'),
	(21,'Description'),
	(22,'Statistics'),
	(23,'Website'),
	(24,'Monday'),
	(25,'Tuesday'),
	(26,'Wenesday'),
	(27,'Thursday'),
	(28,'Friday'),
	(29,'Saturday'),
	(30,'Sunday'),
	(31,'January'),
	(32,'February'),
	(33,'March'),
	(34,'April'),
	(35,'May'),
	(36,'June'),
	(37,'July'),
	(38,'August'),
	(39,'September'),
	(40,'October'),
	(41,'November'),
	(42,'December'),
	(43,'Login'),
	(44,'Password'),
	(45,'Customize'),
	(46,'Male'),
	(47,'Female'),
	(48,'Gender'),
	(49,'Change password'),
	(50,'New password'),
	(51,'New password (again)'),
	(52,'Cancel'),
	(53,'Recover password'),
	(54,'Save'),
	(55,'Delete'),
	(56,'create'),
	(57,'title'),
	(58,'Send'),
	(59,'First name'),
	(60,'Last name'),
	(61,'Move');

/*!40000 ALTER TABLE `lang_ref` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table translator
# ------------------------------------------------------------

DROP TABLE IF EXISTS `translator`;

CREATE TABLE `translator` (
  `user_id` int(9) unsigned NOT NULL,
  `lang_id` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(16) DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `sha_pwd` varchar(50) DEFAULT NULL,
  `salt` varchar(32) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `join_date` date DEFAULT NULL,
  `city` varchar(45) DEFAULT NULL,
  `country` varchar(45) DEFAULT NULL,
  `postcode` varchar(45) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `name` varchar(240) DEFAULT NULL,
  `homepage` varchar(255) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `language` varchar(8) DEFAULT NULL,
  `sexe` enum('male','female') DEFAULT NULL,
  `description` text,
  `ip` varchar(15) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `admin` tinyint(1) NOT NULL DEFAULT '1',
  `disabled` tinyint(1) NOT NULL DEFAULT '0',
  `level` int(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;

INSERT INTO `user` (`id`, `username`, `firstname`, `lastname`, `sha_pwd`, `salt`, `email`, `join_date`, `city`, `country`, `postcode`, `address`, `name`, `homepage`, `birthdate`, `language`, `sexe`, `description`, `ip`, `active`, `admin`, `disabled`, `level`)
VALUES
	(2,'admin',NULL,NULL,'1dae58e10b820578a740b8faf640ce281cfa9531','054f115523be782566412baf3dd07e69','sylvain@studio.gd','2012-03-13','','','','','','','1970-01-01','fr','','<ul><li><h1><span>qqqadf asdf asd<b> f<i></i></b></span></h1></li><li><b>asd fsd fasd f</b>asd</li><li>&nbsp;fsadf</li><li>&nbsp;sdf asd</li></ul>','127.0.0.1',1,1,0,0);

/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_online
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_online`;

CREATE TABLE `user_online` (
  `user_id` int(10) unsigned NOT NULL,
  `inserted` datetime NOT NULL,
  KEY `inserted` (`inserted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
