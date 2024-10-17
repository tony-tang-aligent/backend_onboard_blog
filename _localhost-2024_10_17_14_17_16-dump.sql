/*!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.6.18-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: 127.0.0.1    Database: mydatabase
-- ------------------------------------------------------
-- Server version	5.7.44

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `Comments`
--

DROP TABLE IF EXISTS `Comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  `message` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(11) DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `Comments_ibfk_1` (`post_id`),
  CONSTRAINT `Comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `Posts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `Comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `Users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Comments`
--

LOCK TABLES `Comments` WRITE;
/*!40000 ALTER TABLE `Comments` DISABLE KEYS */;
INSERT INTO `Comments` VALUES (18,27,'tony_comment','wfqfwqfqwf modified','2024-10-09 03:03:32',2,'rejected'),(19,28,'tony_comment- admin ','wfqwfqwfqw 123 123 admin update','2024-10-09 03:13:29',2,'approved'),(24,28,'Anonymous','Let\'s try an anonymous comment -- admin 123','2024-10-09 22:48:16',2,'approved'),(26,31,'Anonymous- admin modified','Anonymous admin comment here','2024-10-10 00:51:01',5,'approved'),(28,23,'tony_comment','THis is a comment from tony','2024-10-10 01:25:39',2,'approved'),(29,21,'matt_comment-123','This is a comment from matt--123','2024-10-10 01:26:10',3,'approved'),(30,28,'matt_blog --123','This is a matt\'s blog 123','2024-10-10 22:42:22',3,'approved'),(31,28,'matt_comment','This is a matt comment','2024-10-10 22:43:14',3,'approved'),(32,27,'matt_comment','qfqwfqwf','2024-10-10 23:11:27',3,'approved'),(34,28,'matt-1-comment','matt comment','2024-10-11 03:53:25',6,'approved'),(35,27,'TTT','wqfqwfqw','2024-10-11 04:53:39',3,'approved'),(36,28,'matt_comment','oiqjfoqiwfowqij','2024-10-11 09:41:23',3,'approved');
/*!40000 ALTER TABLE `Comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Posts`
--

DROP TABLE IF EXISTS `Posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_id` int(11) DEFAULT NULL,
  `comment_count` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `Posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Posts`
--

LOCK TABLES `Posts` WRITE;
/*!40000 ALTER TABLE `Posts` DISABLE KEYS */;
INSERT INTO `Posts` VALUES (21,'tony_blog_2','wfqfwqwfqwf-I have made some modifications 123','2024-10-08 05:56:09','2024-10-10 01:26:10',2,1),(23,'matt_blog--matt changed it','HAHAHA --matt','2024-10-09 00:00:42','2024-10-10 01:26:27',3,1),(27,'matt_blog_2-123123','This is Matt\'s blog! -123','2024-10-09 02:47:15','2024-10-11 04:53:39',3,3),(28,'tony_blog_3','THis has been modified!','2024-10-09 03:12:56','2024-10-11 09:41:23',2,6),(31,'admin_blog2-123123','This is another admin blog...-123123','2024-10-10 00:44:43','2024-10-11 04:54:17',5,1),(33,'matt-1-blog','wqfwqfwqf-12321','2024-10-11 03:52:52','2024-10-11 03:53:02',6,0),(34,'matt_blog_new','safwqfwqfwq','2024-10-11 09:40:59','2024-10-11 09:40:59',3,0);
/*!40000 ALTER TABLE `Posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Users`
--

DROP TABLE IF EXISTS `Users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Users`
--

LOCK TABLES `Users` WRITE;
/*!40000 ALTER TABLE `Users` DISABLE KEYS */;
INSERT INTO `Users` VALUES (2,'tony','$2y$10$dadO/vVKj6wBxaFVfPbTHu74KP63C2yj34LTr8SShLXZ/rIH31mye','tony@123.com','user','2024-10-08 00:27:41','2024-10-11 09:42:14'),(3,'matt','$2y$10$QHI0oEoqNpfY4fbSIXHw1.A7qr5ftX.Lr.rDpbh.ZITdMUc/gFng6','matt@123.com','user','2024-10-08 23:59:09','2024-10-08 23:59:09'),(4,'adel','$2y$10$Cuw2xpyfeZIL21SbOs3y/eFVKo1fVaWD9rVq2loBdCTV0ASE2Rhla','adel@123.com','user','2024-10-09 00:07:39','2024-10-10 04:32:31'),(5,'admin','$2y$10$UB0Zz3Ic.JjZCDZNbeQZW.h4Vm82o19yaTpQ5BLF521rovIcO2.iK','admin@example.com','admin','2024-10-09 23:23:38','2024-10-09 23:34:47'),(6,'matt-1','$2y$10$bQv1ul4gbU/rITULhH7FY.G3A/J1OMGOufE5xwqid2NMas4iIYOSK','matt-1@123.com','user','2024-10-11 03:52:22','2024-10-11 03:52:22');
/*!40000 ALTER TABLE `Users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-10-17 14:17:16
