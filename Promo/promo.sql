-- phpMyAdmin SQL Dump
-- version 3.3.7deb7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 31, 2013 at 02:54 PM
-- Server version: 5.1.66
-- PHP Version: 5.3.3-7+squeeze15

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `promo`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `imatge` int(11) DEFAULT NULL,
  `pare` int(11) DEFAULT NULL,
  `nom` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_3AF34668389D7490` (`imatge`),
  KEY `pare` (`pare`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=29 ;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `imatge`, `pare`, `nom`) VALUES
(1, 1, NULL, 'Aventura, viaje y automóvil'),
(2, 2, NULL, 'Cocina'),
(16, 16, NULL, 'aaa'),
(17, 17, NULL, 'bbbbb'),
(18, 18, NULL, 'Informática'),
(19, 19, 2, 'Tazas y mug'),
(21, 21, 2, 'Pequeños aparatos'),
(24, 27, 17, 'dddddddd');

-- --------------------------------------------------------

--
-- Table structure for table `imatges`
--

CREATE TABLE IF NOT EXISTS `imatges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `path` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `titol` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=36 ;

--
-- Dumping data for table `imatges`
--

INSERT INTO `imatges` (`id`, `path`, `titol`) VALUES
(1, '1_categoria_aventura.jpeg', 'Categoría aventura, viaje y automóvil'),
(2, '2_categoria_cocina.jpeg', 'Categoría cocina'),
(16, '1368737329_aaa.jpeg', NULL),
(17, '1368739305_bbbbb.jpeg', NULL),
(18, '1368744830_Informatica.jpeg', 'Categoría Informática'),
(19, '1368776434_Tazas_y_mug.jpeg', 'Categoría tazas y mug'),
(21, '1368779899_Pequenos_aparatos.jpeg', 'Categoría Pequeños aparatos'),
(22, '1368782667_Lector_tarjetas_SD_MS_Mini_SD_Micr.jpeg', 'Lector tarjetas SD/MS/Mini SD/Micro SD'),
(23, '1368824898_Carpeta_porta_papeles.jpeg', 'Carpeta porta papeles'),
(24, '1369996393_asdasdasd.jpeg', 'Categoría asdasdasd'),
(25, '1369997876_asdasdasd.jpeg', 'Categoría asdasdasd'),
(26, '1369998028_asdasdasd.jpeg', 'Categoría asdasdasd'),
(27, '1369998095_dddddddd.jpeg', 'Categoría dddddddd'),
(28, '1369998219_ffffff.jpeg', 'Categoría ffffff'),
(29, '1369998338_asdasdasd.jpeg', 'Categoría asdasdasd'),
(30, '1369998388_asdasdasd.jpeg', 'Categoría asdasdasd'),
(31, '1369998452_asdasdasd.jpeg', 'Categoría asdasdasd'),
(32, '1369998539_asdasdasd.jpeg', 'Categoría asdasdasd'),
(33, '1370000260_asdasdasd.jpeg', 'Categoría asdasdasd'),
(34, '1370000320_asdasdasd.jpeg', 'Categoría asdasdasd'),
(35, '1370001236_rrrrr.jpeg', 'Categoría rrrrr');

-- --------------------------------------------------------

--
-- Table structure for table `imatges_productes`
--

CREATE TABLE IF NOT EXISTS `imatges_productes` (
  `producte` int(11) NOT NULL,
  `imatge` int(11) NOT NULL,
  PRIMARY KEY (`producte`,`imatge`),
  KEY `IDX_658D0D0E476EEF0B` (`producte`),
  KEY `IDX_658D0D0E389D7490` (`imatge`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `imatges_productes`
--


-- --------------------------------------------------------

--
-- Table structure for table `productes`
--

CREATE TABLE IF NOT EXISTS `productes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `imatgeportada` int(11) DEFAULT NULL,
  `categoria` int(11) DEFAULT NULL,
  `nom` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `especificacions` longtext COLLATE utf8_unicode_ci NOT NULL,
  `preus` longtext COLLATE utf8_unicode_ci NOT NULL,
  `casexit` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8C9B786CFEAA62C0` (`imatgeportada`),
  KEY `IDX_8C9B786C4E10122D` (`categoria`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `productes`
--

INSERT INTO `productes` (`id`, `imatgeportada`, `categoria`, `nom`, `especificacions`, `preus`, `casexit`) VALUES
(1, 22, 18, 'Lector tarjetas SD/MS/Mini SD/Micro SD', 'Ref.: CTL 53993', 'Unidad: 2.09 €\r\n+100: 1.57 €\r\n+400: 1.48 €\r\n+1000: 1.39 €\r\n+3000: 1.31 €', 1),
(2, 23, 18, 'Carpeta porta papeles', 'Ref: ASS10010\r\n\r\n100 gr', '>100   2€\r\n>200   1.5€', 0);

-- --------------------------------------------------------

--
-- Table structure for table `usuaris`
--

CREATE TABLE IF NOT EXISTS `usuaris` (
  `usuari` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `mail` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `pwd` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `forceupdate` tinyint(1) NOT NULL,
  `recoverytoken` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `recoveryexpiration` datetime DEFAULT NULL,
  `lastaccess` datetime DEFAULT NULL,
  PRIMARY KEY (`usuari`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `usuaris`
--


--
-- Constraints for dumped tables
--

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `FK_3AF34668389D7490` FOREIGN KEY (`imatge`) REFERENCES `imatges` (`id`);

--
-- Constraints for table `imatges_productes`
--
ALTER TABLE `imatges_productes`
  ADD CONSTRAINT `FK_658D0D0E389D7490` FOREIGN KEY (`imatge`) REFERENCES `imatges` (`id`),
  ADD CONSTRAINT `FK_658D0D0E476EEF0B` FOREIGN KEY (`producte`) REFERENCES `productes` (`id`);

--
-- Constraints for table `productes`
--
ALTER TABLE `productes`
  ADD CONSTRAINT `FK_8C9B786C4E10122D` FOREIGN KEY (`categoria`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `FK_8C9B786CFEAA62C0` FOREIGN KEY (`imatgeportada`) REFERENCES `imatges` (`id`);
