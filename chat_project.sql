-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.28-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.4.0.6670
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for chat_project
CREATE DATABASE IF NOT EXISTS `chat_project` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_turkish_ci */;
USE `chat_project`;

-- Dumping structure for table chat_project.chat_group
CREATE TABLE IF NOT EXISTS `chat_group` (
  `CHAT_GROUP_ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` varchar(10) NOT NULL,
  `CREATED_BY` int(11) NOT NULL,
  `STATUS` tinyint(4) NOT NULL,
  PRIMARY KEY (`CHAT_GROUP_ID`) USING BTREE,
  UNIQUE KEY `NAME` (`NAME`),
  KEY `FK_chat_group_user` (`CREATED_BY`),
  CONSTRAINT `FK_chat_group_user` FOREIGN KEY (`CREATED_BY`) REFERENCES `user` (`USER_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

-- Data exporting was unselected.

-- Dumping structure for table chat_project.chat_group_member
CREATE TABLE IF NOT EXISTS `chat_group_member` (
  `CHAT_GMEMBER_ID` int(11) NOT NULL AUTO_INCREMENT,
  `CHAT_GROUP_ID` int(11) NOT NULL,
  `USER_ID` int(11) NOT NULL,
  `JOIN_DATETIME` datetime NOT NULL,
  `LEAVE_DATETIME` datetime DEFAULT NULL,
  `IS_BLOCKED` tinyint(4) NOT NULL,
  `IS_ADMIN` tinyint(4) NOT NULL,
  PRIMARY KEY (`CHAT_GMEMBER_ID`) USING BTREE,
  UNIQUE KEY `CHAT_GROUP_ID_USER_ID` (`CHAT_GROUP_ID`,`USER_ID`),
  KEY `FK_chat_group_member_user` (`USER_ID`),
  CONSTRAINT `FK_chat_group_member_chat_group` FOREIGN KEY (`CHAT_GROUP_ID`) REFERENCES `chat_group` (`CHAT_GROUP_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_chat_group_member_user` FOREIGN KEY (`USER_ID`) REFERENCES `user` (`USER_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

-- Data exporting was unselected.

-- Dumping structure for table chat_project.message
CREATE TABLE IF NOT EXISTS `message` (
  `CHAT_GMEMBER_ID` int(11) NOT NULL,
  `CHAT_GROUP_ID` int(11) NOT NULL,
  `MESSAGE` text NOT NULL,
  `SENT_DATETIME` datetime NOT NULL,
  KEY `FK__chat_group_member` (`CHAT_GMEMBER_ID`),
  KEY `FK_message_chat_group` (`CHAT_GROUP_ID`),
  CONSTRAINT `FK__chat_group_member` FOREIGN KEY (`CHAT_GMEMBER_ID`) REFERENCES `chat_group_member` (`CHAT_GMEMBER_ID`),
  CONSTRAINT `FK_message_chat_group` FOREIGN KEY (`CHAT_GROUP_ID`) REFERENCES `chat_group` (`CHAT_GROUP_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

-- Data exporting was unselected.

-- Dumping structure for table chat_project.user
CREATE TABLE IF NOT EXISTS `user` (
  `USER_ID` int(11) NOT NULL AUTO_INCREMENT,
  `USERNAME` varchar(20) NOT NULL,
  `EMAIL` text NOT NULL,
  `PASSWORD` varchar(16) NOT NULL,
  `CREATED_DATETIME` datetime NOT NULL,
  `BANNED_DATETIME` datetime DEFAULT NULL,
  `LAST_ONLINE_DATETIME` datetime DEFAULT NULL,
  `IS_ONLINE` tinyint(4) NOT NULL,
  `IS_BANNED` tinyint(4) NOT NULL,
  `STATUS` tinyint(4) NOT NULL,
  PRIMARY KEY (`USER_ID`) USING BTREE,
  UNIQUE KEY `USERNAME` (`USERNAME`),
  UNIQUE KEY `EMAIL` (`EMAIL`) USING HASH
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

-- Data exporting was unselected.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
