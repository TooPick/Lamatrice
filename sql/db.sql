-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Sam 24 Décembre 2016 à 16:27
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `lamatrice`
--
DROP DATABASE `lamatrice`;
CREATE DATABASE IF NOT EXISTS `lamatrice` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `lamatrice`;

-- --------------------------------------------------------

--
-- Structure de la table `cart`
--

DROP TABLE IF EXISTS `cart`;
CREATE TABLE IF NOT EXISTS `cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `validated` tinyint(1) NOT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_BA388B7A76ED395` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `cart_product`
--

DROP TABLE IF EXISTS `cart_product`;
CREATE TABLE IF NOT EXISTS `cart_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cart_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `processed_quantity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_2890CCAA1AD5CDBF` (`cart_id`),
  KEY `IDX_2890CCAA4584665A` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reference` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  `category` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `quantity` int(11) NOT NULL,
  `quantityAlert` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `expirationDate` datetime NOT NULL,
  `picture` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `visible` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_D34A04ADAEA34913` (`reference`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=31 ;

--
-- Contenu de la table `product`
--

INSERT INTO `product` (`id`, `reference`, `name`, `description`, `category`, `quantity`, `quantityAlert`, `price`, `expirationDate`, `picture`, `visible`) VALUES
(1, 'P100001', 'Article 1', 'Description de l''article : Article 1', 'plastic', 98, 23, 24, '2016-06-09 20:01:40', NULL, 1),
(2, 'P100002', 'Article 2', 'Description de l''article : Article 2', 'plastic', 71, 18, 10, '2016-07-30 20:18:30', NULL, 1),
(3, 'P100003', 'Article 3', 'Description de l''article : Article 3', 'plastic', 91, 13, 8, '2016-06-30 02:37:25', NULL, 1),
(4, 'P100004', 'Article 4', 'Description de l''article : Article 4', 'plastic', 56, 12, 10, '2016-07-14 18:22:51', NULL, 1),
(5, 'P100005', 'Article 5', 'Description de l''article : Article 5', 'plastic', 94, 14, 17, '2016-07-21 16:54:04', NULL, 1),
(6, 'P100006', 'Article 6', 'Description de l''article : Article 6', 'plastic', 76, 12, 25, '2016-05-07 07:43:21', NULL, 1),
(7, 'P100007', 'Article 7', 'Description de l''article : Article 7', 'plastic', 26, 20, 28, '2016-07-24 21:22:55', NULL, 1),
(8, 'P100008', 'Article 8', 'Description de l''article : Article 8', 'plastic', 83, 18, 28, '2016-07-18 15:22:45', NULL, 1),
(9, 'P100009', 'Article 9', 'Description de l''article : Article 9', 'plastic', 72, 10, 26, '2016-03-24 15:32:11', NULL, 1),
(10, 'P1000010', 'Article 10', 'Description de l''article : Article 10', 'plastic', 97, 14, 19, '2016-04-15 17:22:05', NULL, 1),
(11, 'P1000011', 'Article 11', 'Description de l''article : Article 11', 'plastic', 87, 16, 11, '2016-08-01 20:57:47', NULL, 1),
(12, 'P1000012', 'Article 12', 'Description de l''article : Article 12', 'plastic', 66, 18, 13, '2016-09-09 11:19:41', NULL, 1),
(13, 'P1000013', 'Article 13', 'Description de l''article : Article 13', 'plastic', 81, 18, 6, '2016-07-06 08:13:09', NULL, 1),
(14, 'P1000014', 'Article 14', 'Description de l''article : Article 14', 'plastic', 43, 10, 15, '2016-06-22 22:43:57', NULL, 1),
(15, 'P1000015', 'Article 15', 'Description de l''article : Article 15', 'plastic', 77, 22, 10, '2016-08-11 21:11:30', NULL, 1),
(16, 'P1000016', 'Article 16', 'Description de l''article : Article 16', 'stationery', 47, 11, 7, '2016-04-22 04:51:57', NULL, 1),
(17, 'P1000017', 'Article 17', 'Description de l''article : Article 17', 'stationery', 59, 22, 5, '2016-04-03 21:32:43', NULL, 1),
(18, 'P1000018', 'Article 18', 'Description de l''article : Article 18', 'stationery', 86, 10, 25, '2016-06-01 13:25:49', NULL, 1),
(19, 'P1000019', 'Article 19', 'Description de l''article : Article 19', 'stationery', 64, 18, 13, '2016-09-04 19:49:13', NULL, 1),
(20, 'P1000020', 'Article 20', 'Description de l''article : Article 20', 'stationery', 26, 18, 14, '2016-08-13 09:53:05', NULL, 1),
(21, 'P1000021', 'Article 21', 'Description de l''article : Article 21', 'stationery', 54, 16, 29, '2016-03-25 23:40:13', NULL, 1),
(22, 'P1000022', 'Article 22', 'Description de l''article : Article 22', 'stationery', 59, 18, 18, '2016-08-07 08:41:21', NULL, 1),
(23, 'P1000023', 'Article 23', 'Description de l''article : Article 23', 'stationery', 80, 24, 21, '2016-06-29 22:25:23', NULL, 1),
(24, 'P1000024', 'Article 24', 'Description de l''article : Article 24', 'stationery', 45, 23, 26, '2016-08-28 12:53:00', NULL, 1),
(25, 'P1000025', 'Article 25', 'Description de l''article : Article 25', 'stationery', 78, 16, 21, '2016-05-01 09:42:24', NULL, 1),
(26, 'P1000026', 'Article 26', 'Description de l''article : Article 26', 'stationery', 59, 12, 7, '2016-07-03 22:37:58', NULL, 1),
(27, 'P1000027', 'Article 27', 'Description de l''article : Article 27', 'stationery', 40, 21, 21, '2016-04-13 11:26:40', NULL, 1),
(28, 'P1000028', 'Article 28', 'Description de l''article : Article 28', 'stationery', 30, 16, 20, '2016-08-19 13:22:14', NULL, 1),
(29, 'P1000029', 'Article 29', 'Description de l''article : Article 29', 'stationery', 30, 14, 13, '2016-06-20 19:21:42', NULL, 1),
(30, 'P1000030', 'Article 30', 'Description de l''article : Article 30', 'stationery', 68, 10, 14, '2016-04-17 09:03:37', NULL, 1);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `username_canonical` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `email_canonical` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `confirmation_token` varchar(180) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D64992FC23A8` (`username_canonical`),
  UNIQUE KEY `UNIQ_8D93D649A0D96FBF` (`email_canonical`),
  UNIQUE KEY `UNIQ_8D93D649C05FB297` (`confirmation_token`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Contenu de la table `user`
--

INSERT INTO `user` (`id`, `username`, `username_canonical`, `email`, `email_canonical`, `enabled`, `salt`, `password`, `last_login`, `confirmation_token`, `password_requested_at`, `roles`) VALUES
(1, 'user', 'user', 'user@user.com', 'user@user.com', 1, 'jQVqMu8015kSDpIJ2lFREbGTsWBFowOETX8Zv9o6hCY', 'ERFqy/Z5h7dlPn/f4RWI58FhxZC8FwDWwZzI0cbkx1DGdqtYOBjuhTRKKp6wExzhkqotj74tNlFRPGg2e6hpjA==', NULL, NULL, NULL, 'a:0:{}'),
(2, 'admin', 'admin', 'admin@admin.com', 'admin@admin.com', 1, 'wat1rgl2mGc17JtcDyY7BP2fqk54OtvjgixCoIntkEQ', 'K6b/HxSMCysX7nE6ggusVh/DZSLJtZVTRkVvnWaZQfvnBhzIX3pI1DufFUkc4OrCsumZHoT7T/nRRMS94rMkWw==', NULL, NULL, NULL, 'a:1:{i:0;s:10:"ROLE_ADMIN";}');

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `FK_BA388B7A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `cart_product`
--
ALTER TABLE `cart_product`
  ADD CONSTRAINT `FK_2890CCAA4584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `FK_2890CCAA1AD5CDBF` FOREIGN KEY (`cart_id`) REFERENCES `cart` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
