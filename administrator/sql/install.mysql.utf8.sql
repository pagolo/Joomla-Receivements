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
`email` VARCHAR(4) NOT NULL ,
`attiva` VARCHAR(4) NOT NULL ,
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
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `materie` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `codice` varchar(32) COLLATE utf8_unicode_ci NULL,
  `denom_min` varchar(255) COLLATE utf8_unicode_ci NULL,
  
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

CREATE TABLE IF NOT EXISTS `#__receivements_classi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `classe` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `anno` int(11) NULL,
  `sezione` varchar(16) COLLATE utf8_unicode_ci NULL,
  `indirizzo` varchar(255) COLLATE utf8_unicode_ci NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `classe` (`classe`)
) DEFAULT COLLATE=utf8_unicode_ci ;

CREATE TABLE IF NOT EXISTS `#__receivements_calendario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `inizio` date NOT NULL,
  `fine` date NOT NULL,
  `festivo` VARCHAR(4) NOT NULL,
  `finale` VARCHAR(4) NOT NULL,
  `utente` int(11) NOT NULL,
  `descrizione` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__receivements_prenotazioni` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guid` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_agenda` int(11) NOT NULL,
  `id_classe` int(11) NOT NULL,
  `parentela` enum('COM_RECEIVEMENTS_PARENT','COM_RECEIVEMENTS_GRANDPARENT','COM_RECEIVEMENTS_UNCLE','COM_RECEIVEMENTS_BROTHER','COM_RECEIVEMENTS_TUTOR','COM_RECEIVEMENTS_OTHER') COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(90) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nome` varchar(90) COLLATE utf8_unicode_ci NOT NULL,
  `utente` int(11) NOT NULL DEFAULT '0',
  `creato` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__receivements_agenda` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_ore` int(11) NOT NULL,
  `totale_ric` int(11) NOT NULL DEFAULT '0',
  `data` datetime NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__receivements_parenti` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_classe` int(11) NOT NULL,
  `utente` int(11) NOT NULL,
  `id_studente` int(11) NOT NULL DEFAULT '0',
  `studente` varchar(90) COLLATE utf8_unicode_ci NOT NULL,
  `parentela` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_unicode_ci;
