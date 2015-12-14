-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-12-2015 a las 05:01:19
-- Versión del servidor: 5.5.27
-- Versión de PHP: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `lpons_library`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `books`
--

CREATE TABLE IF NOT EXISTS `books` (
  `isbn` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `title` varchar(40) COLLATE utf8_spanish_ci NOT NULL,
  `description` tinytext COLLATE utf8_spanish_ci NOT NULL,
  `summary` text COLLATE utf8_spanish_ci NOT NULL,
  `author` varchar(40) COLLATE utf8_spanish_ci NOT NULL,
  `category` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`isbn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `copybooks`
--

CREATE TABLE IF NOT EXISTS `copybooks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `book` varchar(40) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `status` varchar(10) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_copybooks_books` (`book`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reserves`
--

CREATE TABLE IF NOT EXISTS `reserves` (
  `user` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `copybook` int(11) NOT NULL,
  `date_start` date NOT NULL,
  `date_finish` date NOT NULL,
  `sent` date DEFAULT NULL,
  `received` date DEFAULT NULL,
  PRIMARY KEY (`copybook`,`date_start`),
  KEY `fk_user_reserves_email_users` (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `email` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `pwd` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `name` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `surname` varchar(40) COLLATE utf8_spanish_ci NOT NULL,
  `telephone` varchar(12) COLLATE utf8_spanish_ci NOT NULL,
  `typeUser` varchar(12) COLLATE utf8_spanish_ci NOT NULL,
  `home` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `registered` date NOT NULL,
  PRIMARY KEY (`email`),
  KEY `typeUser` (`typeUser`),
  KEY `typeUser_2` (`typeUser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `copybooks`
--
ALTER TABLE `copybooks`
  ADD CONSTRAINT `fk_copybooks_books` FOREIGN KEY (`book`) REFERENCES `books` (`isbn`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `reserves`
--
ALTER TABLE `reserves`
  ADD CONSTRAINT `fk_copybook_reserves_id_copybooks` FOREIGN KEY (`copybook`) REFERENCES `copybooks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_reserves_email_users` FOREIGN KEY (`user`) REFERENCES `users` (`email`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
