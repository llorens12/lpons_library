-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-12-2015 a las 06:57:40
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

--
-- Volcado de datos para la tabla `books`
--

INSERT INTO `books` (`isbn`, `title`, `description`, `summary`, `author`, `category`) VALUES
('234234sasd', 'The Mark of Athena', 'ANNABETH IS SORT OF CONTENT. She finally has Percy back (with all of his memories, no less), but there is word going around about a prophecy given by a... harpy? And what''s this about the augur, Octavian? Is there a reason he''s acting so oddly? And w', 'ANNABETH IS SORT OF CONTENT. She finally has Percy back (with all of his memories, no less), but there is word going around about a prophecy given by a... harpy? And what''s this about the augur, Octavian? Is there a reason he''s acting so oddly? And why did someone show up-someone who is supposed to be dead?\r\n\r\nPERCY IS NERVOUS. Juno warned him that Annabeth would have a difficult job ahead of her and that she might not be up for it. Percy wants to believe that Annabeth won''t cause trouble for the quest, but when she gets cursed, Percy just isn''t sure any more. \r\n\r\nLEO WANTS RESPECT. He''s sick of Annabeth and pretty much everyone else poking fun of him. So sick of it, that he will combust at a moment''s notice. But can he control it long enough to keep Frank Zhang alive?\r\n\r\nIn the third book of The Heroes of Olympus series, the Argo II finally lands in Camp Jupiter. Soon after, the seven demigods go out on their quest to Rome and then to Greece. But, when you''re a demigod, something bad is bound to happen.', 'Bradie Davis', ' Humor'),
('2343da3', 'Leave Me Breathless', '\r\nJason Keely â€“ Incubus, strong, powerful and about to be glorified by accepting a mission none would take. Itâ€™s simple; build a life in the human world and protect what is to be sent without the Underworldâ€™s knowledge.\r\nBut when a tr', '\r\nJason Keely â€“ Incubus, strong, powerful and about to be glorified by accepting a mission none would take. Itâ€™s simple; build a life in the human world and protect what is to be sent without the Underworldâ€™s knowledge.\r\nBut when a troubled girl with a complicate past crashes into his life everything changes. She threatens to blow his cover and he must decide which to safeguard â€“ his mission or the girl.\r\nSuddenly heâ€™s not that strong or powerful or even capable of saving the person he loves from the very world he belongs to. After all what good is power if it canâ€™t be used to keep the people you love alive?\r\nWicked Desires â€“ a biker and a baby was never so tempting. . .', 'Jemma Grey', ' Fantasy'),
('234dasd3', 'With Eyes Closed', '\r\nA short fictional story of two friends held in the claws of a ravaging earthquake.', '\r\nA short fictional story of two friends held in the claws of a ravaging earthquake.', 'Aditi Das Bhowmik', ' Short Story'),
('234ed45', 'Dragon Girl', '\r\nEver since birth, I have been different. \r\nI have the ability to transform myself, at will, into a dragon. \r\nI realize this sounds like a blessing - After all who wouldn''t mind ', '\r\nEver since birth, I have been different. \r\nI have the ability to transform myself, at will, into a dragon. \r\nI realize this sounds like a blessing - After all who wouldn''t mind transforming into a three-story tall, fire-breathing, flying lizard? - But the reality is the exact opposite. Itâ€™s a curse. Everyone who knows about ''the dragon'' rejects and hates me, dooming me to a lifetime of distrust and solitude. \r\nNo, this is no blessing. \r\nFor all of my seventeen years, I have thought I was the only one of my kind and the only ones who truly understood me were the moon and the night sky.\r\nAt least, that''s what I thought until I met the man who would change my life forever.', 'Melissa Nichols', ' Fantasy'),
('349203d9f23', 'Hush', 'Secrets are a dangerous thing, especially when an entire relationship is based off one, big, destructive secret.\r\n\r\nThe young, successful, forward-thinking, Olivia Reinbeck, falls for an older man, the alluring Callem Tate. Together, they grow into a', 'Secrets are a dangerous thing, especially when an entire relationship is based off one, big, destructive secret.\r\n\r\nThe young, successful, forward-thinking, Olivia Reinbeck, falls for an older man, the alluring Callem Tate. Together, they grow into a luxurious lifestyle thanks to Callem''s business. \r\n\r\nEverything between the two of them is moving in the right direction, until Olivia discovers a ghastly secret that crumbles the ground beneath her and the foundation of her relationship.\r\n\r\nThe secret drives Olivia out, but Callem won''t stand for it. His love is too deep to lose her over his transgressions. He fights to win her back. When all attempts fail, he resorts to other, more abrupt measures.\r\n\r\nFollow Olivia and Callem between their past and their present, discover Callem''s secret, and witness Olivia hold true to herself at her most trying hour in ''Hush''.\r\n', 'Jess Wygle', ' Thriller'),
('4234da4', 'Kingdom of a thousand', '\r\nInvention of sci-fi. Guaranteed voodoo-free!\r\nThe adventures of Prince Henley to Westerburg, Patchara Petch-a-boon and Svinenysh Galactic.', '\r\nInvention of sci-fi. Guaranteed voodoo-free!\r\nThe adventures of Prince Henley to Westerburg, Patchara Petch-a-boon and Svinenysh Galactic.', 'Eftos Ent', ' Science Fictio'),
('433dd3', 'A cute love story', 'Aakriti is in love with Neeraj.Neeraj is also mad for Aakriti.but she found out him not to be a good boy. will she be able to change him ?will their love win over the weaknesses of Neeraj? will they have happy life together?', 'Aakriti is in love with Neeraj.Neeraj is also mad for Aakriti.but she found out him not to be a good boy. will she be able to change him ?will their love win over the weaknesses of Neeraj? will they have happy life together?', 'Nidhi Agrawal', 'Romance'),
('453hj9', 'Ultimate Pleasure', 'A girl who hits the clubs every other day and sleeps with 1 diffrent guy every other day until she finds the perfect guy. Her own personal sex god.', 'A girl who hits the clubs every other day and sleeps with 1 diffrent guy every other day until she finds the perfect guy. Her own personal sex god.', 'Rachel G', 'Erotic'),
('567bgf', 'English Speaking and Grammar through Hin', 'Learn English speaking and grammar through Hindi language. Full grammar, a guideline for spoken English. It teaches you the rules of English from basic to advance level with full guarantee.', 'Learn English speaking and grammar through Hindi language. Full grammar, a guideline for spoken English. It teaches you the rules of English from basic to advance level with full guarantee.', 'Niranjan Jha', 'Education'),
('asd0q34', 'Destined Love', '\r\nPrincess Cleopatra has to work together with the arrogant but extremely handsome Prince Durwald. Will she be able to complete her job successfully without Prince Durwald stealing her heart? Or will she fall for his charms?\r\n', '\r\nPrincess Cleopatra has to work together with the arrogant but extremely handsome Prince Durwald. Will she be able to complete her job successfully without Prince Durwald stealing her heart? Or will she fall for his charms?\r\n', 'Marline', ' Romance'),
('asd3234o', 'Esperanza Rising', '\r\nEsperanza Ortega possesses all the treasures a young girl could want: fancy dresses; a beautiful home filled with servants in the bountiful region of Aguascalientes, Mexico; and the promise of one day rising to Mamaâ€™s position and presiding over ', '\r\nEsperanza Ortega possesses all the treasures a young girl could want: fancy dresses; a beautiful home filled with servants in the bountiful region of Aguascalientes, Mexico; and the promise of one day rising to Mamaâ€™s position and presiding over all of Rancho de las Rosas. But a sudden tragedy shatters that dream, forcing Esperanza and Mama to flee to California and settle in a Mexican farm labor camp. There they confront the challenges of hard work, acceptance by their own people, and economic difficulties brought on by the Great Depression. When Mama falls ill from Valley Fever and a strike for better working conditions threatens to uproot their new life, Esperanza must relinquish her hold on the past and learn to embrace a future ripe with the riches of family and community.', 'Pam MuÃ±oz Ryan', 'Fiction'),
('asds993', 'Kidnapped the Wrong Sister', 'To stop his brother from an unsuitable marriage, Nikias Dranias holds the woman he believes to be Daryle prisoner on his island. However, it is her sister Diona that Nikias has mistakenly held, not believing her story that she too had come to stop th', 'To stop his brother from an unsuitable marriage, Nikias Dranias holds the woman he believes to be Daryle prisoner on his island. However, it is her sister Diona that Nikias has mistakenly held, not believing her story that she too had come to stop the marriage of the two siblings. \r\n\r\nSoon however, sparks are flying between the two, as they find themselves fiercely attracted to each other Less\r\nTo stop his brother from an unsuitable marriage, Nikias Dranias holds the woman he believes to be Daryle prisoner on his island. However, it is her sister Diona that Nikias has mistakenly held, not believing her story that she too had come to stop the marriage of the two siblings. \r\n\r\nSoon however, sparks are flying between the two, as they find themselves fiercely attracted to each other.', 'Marie Kelly', ' Drama'),
('jadoas445', 'Little Brother', ' years ago a person named Jerry got dared to sleep in a house that was believed haunted. The next day his friends waited for him outside the house...... They had to go inside and search for him. They went through every room exept the attic. He wasn''t', ' years ago a person named Jerry got dared to sleep in a house that was believed haunted. The next day his friends waited for him outside the house...... They had to go inside and search for him. They went through every room exept the attic. He wasn''t supposed to sleep there. He was supposed to sleep in the living room they went to the attic\r\n. They saw Jerry''s corpse and they just left because they were scared. But that night they all died because of their friend. He killed them for making him sleep in that house if you don''t send this to 11 comments you will die tonight by Jerry. Example 1: A man named Stewart read this and didn''t believe it. He shut off his computer and went through his day. That night while he was in bed he heard something outside of his door. He got up to look. ', 'Cory Doctorow', ' Fiction'),
('sq34a4', 'Forbidden Fantasy', 'It all began with a stolen kiss.\r\nLayla Bungah had a normal life before the school system threw a new teacher into the mix. She had a boyfriend that loved her, a best friend that was more of a sister than anything, and a father that trusted her, even', 'It all began with a stolen kiss.\r\nLayla Bungah had a normal life before the school system threw a new teacher into the mix. She had a boyfriend that loved her, a best friend that was more of a sister than anything, and a father that trusted her, even though she kept secrets from him daily. Devin Simmons has decided to give being an English teacher a chance since that was his actual major in college. He just wanted to have the thrill of teaching young people what he knew and what he was taught. He never expected to be teaching one of his students other things. When Layla comes to class one day sheâ€™s disappointed that one of her favorite teacher was fired and replaced so easily. She soon finds out the new teacher isnâ€™t so bad, not to mention hunkalisious. But Layla isn''t the only one guilty of fantasizing about her sexy new teacher. Devin often stares at Layla with affection, and Layla canâ€™t help but think he feels the same way. Soon Devin and Layla have a secret. A secret that really shouldnâ€™t get out or it could cause trouble for the both of them.\r\n\r\nSome material may not be suitable for readers under 18: MATURE YA: Read with caution', 'Nina Kari', ' Fiction');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=50 ;

--
-- Volcado de datos para la tabla `copybooks`
--

INSERT INTO `copybooks` (`id`, `book`, `status`) VALUES
(13, 'asds993', 'New'),
(14, '234234sasd', 'New'),
(15, '2343da3', 'New'),
(16, '234dasd3', 'New'),
(17, '234ed45', 'New'),
(18, '349203d9f23', 'New'),
(19, '234ed45', 'New'),
(20, '349203d9f23', 'New'),
(21, '4234da4', 'New'),
(22, '433dd3', 'New'),
(23, '453hj9', 'New'),
(24, '567bgf', 'New'),
(25, 'asd0q34', 'New'),
(26, 'asd3234o', 'New'),
(27, 'asds993', 'New'),
(28, 'jadoas445', 'New'),
(29, 'sq34a4', 'New'),
(30, '234234sasd', 'Good'),
(31, '2343da3', 'Good'),
(32, '234ed45', 'Bad'),
(33, '349203d9f23', 'Good'),
(34, '234ed45', 'Bad'),
(35, '4234da4', 'Good'),
(36, '4234da4', 'Bad'),
(37, '453hj9', 'Good'),
(38, '567bgf', 'Bad'),
(39, 'asd0q34', 'Good'),
(40, 'asd0q34', 'Bad'),
(41, 'asd3234o', 'Bad'),
(42, 'asds993', 'Good'),
(43, '234ed45', 'Good'),
(44, 'sq34a4', 'Good'),
(45, '234234sasd', 'Bad'),
(46, '2343da3', 'Good'),
(47, '234ed45', 'Bad'),
(48, 'asd3234o', 'Good'),
(49, 'asd0q34', 'Bad');

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

--
-- Volcado de datos para la tabla `reserves`
--

INSERT INTO `reserves` (`user`, `copybook`, `date_start`, `date_finish`, `sent`, `received`) VALUES
('goirlanda@hotmail.com', 13, '2015-12-02', '2015-12-10', '2015-12-14', NULL),
('lorenzoponsbarber@gmail.com', 13, '2015-12-26', '2016-01-16', '2015-12-14', NULL),
('daw2@iesjoanramis.org', 14, '2015-12-14', '2015-12-17', NULL, NULL),
('paco@delmonte.com', 14, '2015-12-18', '2016-01-11', NULL, NULL),
('goirlanda@hotmail.com', 15, '2015-12-14', '2016-01-06', NULL, NULL),
('isaac@gmail.com', 17, '2015-12-14', '2015-12-24', '2015-12-14', NULL),
('isaac@gmail.com', 18, '2015-12-14', '2015-12-24', '2015-12-14', NULL),
('isaac@gmail.com', 21, '2015-12-14', '2016-01-05', NULL, NULL),
('daw2@iesjoanramis.org', 22, '2015-12-14', '2016-01-14', '2015-12-14', NULL),
('juanjo@gmail.com', 23, '2015-12-14', '2016-01-15', NULL, NULL),
('isaac@gmail.com', 24, '2015-12-14', '2016-01-25', '2015-12-14', NULL),
('goirlanda@hotmail.com', 25, '2015-12-14', '2015-12-25', '2015-12-14', NULL),
('lorenzoponsbarber@gmail.com', 26, '2015-12-14', '2015-12-25', '2015-12-14', NULL),
('juanjo@gmail.com', 27, '2015-12-14', '2015-12-25', '2015-12-14', NULL),
('daw2@iesjoanramis.org', 27, '2015-12-26', '2016-02-18', NULL, NULL),
('lorenzoponsbarber@gmail.com', 28, '2015-12-14', '2015-12-29', NULL, NULL),
('julio@gmail.com', 28, '2015-12-30', '2016-01-30', '2015-12-14', NULL),
('juanjo@gmail.com', 28, '2016-01-31', '2016-02-12', '2015-12-14', NULL),
('paco@delmonte.com', 28, '2017-04-30', '2017-05-10', '2015-12-14', NULL),
('daw2@iesjoanramis.org', 29, '2015-12-14', '2015-12-18', NULL, NULL),
('julio@gmail.com', 29, '2015-12-19', '2016-01-31', '2015-12-14', NULL),
('juanjo@gmail.com', 30, '2015-12-14', '2016-01-25', NULL, NULL),
('julio@gmail.com', 39, '2015-12-14', '2016-01-14', '2015-12-14', NULL),
('daw2@iesjoanramis.org', 40, '2015-12-14', '2016-01-16', NULL, NULL),
('paco@delmonte.com', 42, '2015-12-14', '2015-12-25', '2015-12-14', NULL),
('julio@gmail.com', 42, '2015-12-26', '2016-01-27', NULL, NULL),
('lorenzoponsbarber@gmail.com', 44, '2015-12-14', '2016-01-16', '2015-12-14', NULL),
('goirlanda@hotmail.com', 44, '2016-01-17', '2016-03-11', NULL, NULL),
('julio@gmail.com', 45, '2015-12-04', '2016-01-10', '2015-12-04', NULL),
('isaac@gmail.com', 45, '2016-01-05', '2016-02-07', NULL, NULL),
('paco@delmonte.com', 48, '2015-12-14', '2016-01-25', '2015-12-14', NULL),
('lorenzoponsbarber@gmail.com', 49, '2015-12-14', '2016-01-03', NULL, NULL);

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
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`email`, `pwd`, `name`, `surname`, `telephone`, `typeUser`, `home`, `registered`) VALUES
('daw2@iesjoanramis.org', '6512bd43d9caa6e02c990b0a82652dca', 'Borja', 'Benejam Montesinos', '987-25-63-78', 'Librarian', 'showTableUsers', '2015-12-14'),
('goirlanda@hotmail.com', '6512bd43d9caa6e02c990b0a82652dca', 'Ferran', 'Sintes Perez', '960-21-12-23', 'User', 'showTableUsers', '2015-12-14'),
('isaac@gmail.com', '6512bd43d9caa6e02c990b0a82652dca', 'Isaac', 'Ainsa Olives', '971-65-63-89', 'User', 'showTableUsers', '2015-12-14'),
('juanjo@gmail.com', '6512bd43d9caa6e02c990b0a82652dca', 'Juanjo', 'Juan Jose', '960-21-12-23', 'User', 'showTableUsers', '2015-12-14'),
('julio@gmail.com', '6512bd43d9caa6e02c990b0a82652dca', 'Julio', 'Diaz Juanico', '987-25-63-78', 'User', 'showTableUsers', '2015-12-14'),
('lorenzoponsbarber@gmail.com', '6512bd43d9caa6e02c990b0a82652dca', 'Lorenzo', 'Pons Barber', '960-21-12-23', 'Admin', 'showTableUsers', '2015-12-14'),
('paco@delmonte.com', '6512bd43d9caa6e02c990b0a82652dca', 'Paco', 'Del Bosque', '971-65-63-89', 'User', 'showTableUsers', '2015-12-14');

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
