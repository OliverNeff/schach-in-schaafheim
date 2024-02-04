--
-- Tabellenstruktur für Tabelle `#__clm_turniere_grand_prix`
--

CREATE TABLE IF NOT EXISTS `#__clm_turniere_grand_prix` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',  
  `checked_out` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',

  `typ` tinyint(1) unsigned DEFAULT '0',		-- Modus
  `typ_calculation` mediumtext DEFAULT '',		-- Punktevergabe  
  `use_tiebreak` enum('0','1') DEFAULT '0',		-- Feinwertung bei Berechnung berücksichtigen
  `best_of` mediumint(5) unsigned DEFAULT 0,	-- Anzahl der Turniere
  `col_header` enum('0','1') DEFAULT '0',		-- monatlich
  `introduction` text,							-- Einleitungstext
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
