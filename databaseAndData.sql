-- --------------------------------------------------------
-- Хост:                         127.0.0.1
-- Версия сервера:               10.5.9-MariaDB-1:10.5.9+maria~focal - mariadb.org binary distribution
-- Операционная система:         debian-linux-gnu
-- HeidiSQL Версия:              11.2.0.6213
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Дамп структуры базы данных burgers
CREATE DATABASE IF NOT EXISTS `burgers` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `burgers`;

-- Дамп структуры для таблица burgers.order_total
CREATE TABLE IF NOT EXISTS `order_total` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date_time` datetime NOT NULL,
  `id_user` int(10) unsigned NOT NULL,
  `id_payment` int(10) unsigned NOT NULL,
  `comment` varchar(200) DEFAULT NULL,
  `callback` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `address` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4;

-- Дамп данных таблицы burgers.order_total: ~2 rows (приблизительно)
/*!40000 ALTER TABLE `order_total` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_total` ENABLE KEYS */;

-- Дамп структуры для таблица burgers.payment
CREATE TABLE IF NOT EXISTS `payment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

-- Дамп данных таблицы burgers.payment: ~2 rows (приблизительно)
/*!40000 ALTER TABLE `payment` DISABLE KEYS */;
INSERT INTO `payment` (`id`, `name`) VALUES
	(1, 'Наличными'),
	(2, 'Оплата по карте');
/*!40000 ALTER TABLE `payment` ENABLE KEYS */;

-- Дамп структуры для таблица burgers.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(45) NOT NULL,
  `name` varchar(45) NOT NULL,
  `phone` varchar(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4;

-- Дамп данных таблицы burgers.users: ~7 rows (приблизительно)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `email`, `name`, `phone`) VALUES
	(1, 'user1@gmail.com', 'Иван', '89236501177'),
	(2, 'user2@gmail.com', 'Андрей', '89237771122'),
	(5, 'user3@gmail.com', 'Юля', '89137771020'),
	(6, 'user777@gnail.com', 'Васяня', '79236501111');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
