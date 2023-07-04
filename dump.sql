-- MySQL dump 10.13  Distrib 5.7.40, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: ecf_back
-- ------------------------------------------------------
-- Server version	5.7.40

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `article_wishlist`
--

DROP TABLE IF EXISTS `article_wishlist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `article_wishlist` (
  `article_id` int(11) NOT NULL,
  `wishlist_id` int(11) NOT NULL,
  PRIMARY KEY (`article_id`,`wishlist_id`),
  KEY `wishlist_id` (`wishlist_id`),
  CONSTRAINT `article_wishlist_ibfk_1` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `article_wishlist_ibfk_2` FOREIGN KEY (`wishlist_id`) REFERENCES `wishlist` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `article_wishlist`
--

LOCK TABLES `article_wishlist` WRITE;
/*!40000 ALTER TABLE `article_wishlist` DISABLE KEYS */;
INSERT INTO `article_wishlist` VALUES (1,1),(3,1);
/*!40000 ALTER TABLE `article_wishlist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `articles`
--

DROP TABLE IF EXISTS `articles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `imgLink` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `articles`
--

LOCK TABLES `articles` WRITE;
/*!40000 ALTER TABLE `articles` DISABLE KEYS */;
INSERT INTO `articles` VALUES (1,'tabouret','c\'est un tabouret',12.00,'null','null'),(3,'table','c\'est une table',3.00,'null','null');
/*!40000 ALTER TABLE `articles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comment`
--

DROP TABLE IF EXISTS `comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` text NOT NULL,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(11) DEFAULT NULL,
  `wishlist_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `wishlist_id` (`wishlist_id`),
  CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`wishlist_id`) REFERENCES `wishlist` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comment`
--

LOCK TABLES `comment` WRITE;
/*!40000 ALTER TABLE `comment` DISABLE KEYS */;
INSERT INTO `comment` VALUES (1,'C\'est nickel','2023-06-29 22:00:00',1,1),(2,'test','2023-07-01 22:00:00',1,1),(3,'hello','2023-07-01 22:00:00',2,1),(4,'bloup','2023-07-01 22:00:00',3,1);
/*!40000 ALTER TABLE `comment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `isActive` tinyint(1) DEFAULT '1',
  `role` varchar(255) DEFAULT 'user',
  `avatarUrl` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'BoB','BoB@mail.BoB','$2y$10$j2H08wmIw9wcDd01YNEZCuz6cmxqcYJ2pjcg5zVgLX4ztvGKZjXYe',1,'admin','https://legends.pokemon.com/fr-ca/pokemon'),(2,'bab','bab@mail.bab','$2y$10$Dy1iDdEXdNss7eXsJwRmW.a4WjDwsWol.J.1ckR0Mnw41rgeClu.G',1,'user',''),(3,'bib','bib@mail.bib','$2y$10$m8dPL5/XTmLwivWFO0pQLevenu8UHNLnRybd79m3JdPVMx796jmEm',1,'user',''),(4,'byb','byb@mail.byb','$2y$10$nLwpveJtrWV27Al437XgRua7FQ7FTC/xu9Bs7zpIx9Z8ofU73J92O',1,'user',''),(5,'beb','beb@mail.beb','$2y$10$m1wayrZw5p2mku/dwJuAj.qCDDVpEtstVNCpyiXcmSrd2zpKp6wdu',1,'user',''),(6,'bvb','bvb@mail.bvb','$2y$10$4xPTqlLn3Wzjo6TbkjNhj.awLlaXpEZ/4dynBnZO66UNaQWAzZMwe',1,'user','https://legends.pokemon.com/fr-ca/pokemon'),(7,'bbb','bbb@mail.bbb','$2y$10$ZkT3OkBileSwaphtzzQoOusk5F1foqWWmxHIAwRjM.6qqfVYtT7x6',1,'user','https://www.pokepedia.fr/images/thumb/7/76/Pikachu-DEPS.png/615px-Pikachu-DEPS.png?20220418180124'),(8,'bcb','bcb@mail.bcb','$2y$10$RUU46AAAAigxYkL88D96UeU25rp663Rlzl14np6mc8AQsydcZMSm.',1,'user','https://www.pokepedia.fr/images/thumb/7/76/Pikachu-DEPS.png/615px-Pikachu-DEPS.png?20220418180124'),(9,'bzb','bzb@mail.com','$2y$10$k8gcqVGzLPwLiyeTaocctuBqjXOelK4aMvCg79auydmV/1ygtcDMa',1,'user','123'),(10,'ibb','ibb@mail.ibb','$2y$10$JVNm9vdgwFBmvEkKowF3Re9AETOpccCDfiNm8icQ2UAXMWnHc5LcS',1,'user','');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wishlist`
--

DROP TABLE IF EXISTS `wishlist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` text,
  `name` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wishlist`
--

LOCK TABLES `wishlist` WRITE;
/*!40000 ALTER TABLE `wishlist` DISABLE KEYS */;
INSERT INTO `wishlist` VALUES (1,'je veux Ã§a pour noel','wishlist de BoB',1),(2,'un truc comme Ã§a','Liste de Bab',NULL),(4,'un truc comme Ã§a','Liste de Bab',2),(5,'a','liste de bob',1),(6,'c\'est un test','Test',2);
/*!40000 ALTER TABLE `wishlist` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-07-04 16:58:05
