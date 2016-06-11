ALTER TABLE `#__receivements_ore`  ADD `una_tantum` INT(11) NOT NULL DEFAULT '0' AFTER `id_docente`;
CREATE TABLE IF NOT EXISTS `#__receivements_generali` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titolo` varchar(64) NOT NULL,
  `descrizione` text,
  `inizio` time NOT NULL,
  `fine` time NOT NULL,
  `data` date DEFAULT NULL,
  `sede` int(11) NOT NULL DEFAULT '0',
  `attiva` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;
