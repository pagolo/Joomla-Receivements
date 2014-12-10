CREATE TABLE IF NOT EXISTS `#__receivements_ore` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`id_docente` INT(11)  NOT NULL ,
`classi` VARCHAR(255)  NOT NULL ,
`giorno` VARCHAR(255)  NOT NULL ,
`inizio` TIME NOT NULL ,
`fine` TIME NOT NULL ,
`max_app` TINYINT(4)  NOT NULL ,
`cattedra` INT(11)  NOT NULL ,
`sede` INT(11)  NOT NULL ,
`attiva` VARCHAR(255)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__receivements_sedi` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`sede` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

INSERT INTO `#__receivements_sedi` (`id`, `sede`) VALUES
(1, 'Sede'),
(2, 'Succursale');

CREATE TABLE IF NOT EXISTS `#__receivements_cattedre` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `materie` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_unicode_ci;

INSERT INTO `#__receivements_cattedre` (`id`, `materie`) VALUES
(1, 'Religione'),
(2, 'Italiano e Storia'),
(3, 'Italiano, Latino e Storia'),
(4, 'Francese'),
(5, 'Inglese'),
(6, 'Matematica e Fisica'),
(7, 'Chimica'),
(8, 'Scienze'),
(9, 'Scienze motorie');

