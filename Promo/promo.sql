-- phpMyAdmin SQL Dump
-- version 3.3.7deb7
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Temps de generació: 15-05-2013 a les 13:54:54
-- Versió del servidor: 5.1.66
-- Versió de PHP : 5.3.3-7+squeeze15

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de dades: `promo`
--

-- --------------------------------------------------------

--
-- Estructura de la taula `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `imatge` int(11) DEFAULT NULL,
  `pare` int(11) DEFAULT NULL,
  `nom` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_3AF34668389D7490` (`imatge`),
  UNIQUE KEY `UNIQ_3AF3466823BF5034` (`pare`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Bolcant dades de la taula `categories`
--

INSERT INTO `categories` (`id`, `imatge`, `pare`, `nom`) VALUES
(1, 1, NULL, 'Aventura, viaje y automóvil'),
(2, 2, NULL, 'Cocina');

-- --------------------------------------------------------

--
-- Estructura de la taula `imatges`
--

CREATE TABLE IF NOT EXISTS `imatges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `path` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `titol` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Bolcant dades de la taula `imatges`
--

INSERT INTO `imatges` (`id`, `path`, `titol`) VALUES
(1, '1_categoria_aventura.jpeg', 'Categoría aventura, viaje y automóvil'),
(2, '2_categoria_cocina.jpeg', 'Categoría cocina');

-- --------------------------------------------------------

--
-- Estructura de la taula `imatges_productes`
--

CREATE TABLE IF NOT EXISTS `imatges_productes` (
  `producte` int(11) NOT NULL,
  `imatge` int(11) NOT NULL,
  PRIMARY KEY (`producte`,`imatge`),
  KEY `IDX_658D0D0E476EEF0B` (`producte`),
  KEY `IDX_658D0D0E389D7490` (`imatge`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Bolcant dades de la taula `imatges_productes`
--


-- --------------------------------------------------------

--
-- Estructura de la taula `productes`
--

CREATE TABLE IF NOT EXISTS `productes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `imatgeportada` int(11) DEFAULT NULL,
  `categoria` int(11) DEFAULT NULL,
  `nom` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `especificacions` longtext COLLATE utf8_unicode_ci NOT NULL,
  `preus` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8C9B786CFEAA62C0` (`imatgeportada`),
  KEY `IDX_8C9B786C4E10122D` (`categoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Bolcant dades de la taula `productes`
--


-- --------------------------------------------------------

--
-- Estructura de la taula `usuaris`
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
-- Bolcant dades de la taula `usuaris`
--


--
-- Restriccions per taules bolcades
--

--
-- Restriccions per la taula `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `FK_3AF3466823BF5034` FOREIGN KEY (`pare`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `FK_3AF34668389D7490` FOREIGN KEY (`imatge`) REFERENCES `imatges` (`id`);

--
-- Restriccions per la taula `imatges_productes`
--
ALTER TABLE `imatges_productes`
  ADD CONSTRAINT `FK_658D0D0E389D7490` FOREIGN KEY (`imatge`) REFERENCES `imatges` (`id`),
  ADD CONSTRAINT `FK_658D0D0E476EEF0B` FOREIGN KEY (`producte`) REFERENCES `productes` (`id`);

--
-- Restriccions per la taula `productes`
--
ALTER TABLE `productes`
  ADD CONSTRAINT `FK_8C9B786C4E10122D` FOREIGN KEY (`categoria`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `FK_8C9B786CFEAA62C0` FOREIGN KEY (`imatgeportada`) REFERENCES `imatges` (`id`);
