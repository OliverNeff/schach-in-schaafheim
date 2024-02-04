--
-- Tabellenstruktur für Tabelle `#__clm_turniere_grand_prix`
--

CREATE TABLE IF NOT EXISTS `#__clm_turniere_grand_prix` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `published` tinyint(4) NOT NULL DEFAULT '0',  
  `checked_out` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',

  `typ` tinyint(1) unsigned DEFAULT '0',		-- Modus
  `typ_calculation` mediumtext DEFAULT '',		-- Punktevergabe  
  `use_tiebreak` enum('0','1') DEFAULT '0',		-- Feinwertung bei Berechnung berücksichtigen
  `best_of` mediumint(5) unsigned DEFAULT 0,	-- Anzahl der Turniere für Gesamtwertung
  `min_tournaments` mediumint(5) unsigned  DEFAULT '0', -- minimale Anzahl der Turniere für Gesamtwertung
  `num_tournaments` mediumint(5) unsigned  DEFAULT '0',	-- Anzahl Turniere für Extrapunkte
  `extra_points` decimal(3,1) unsigned  DEFAULT '0',	-- Extrapunkte pro Turnier
  `precision` mediumint(5) unsigned DEFAULT '0',		-- Nachkommastellen Modus "prozentual"
  `col_header` enum('0','1') DEFAULT '0',		-- monatlich Turniere
  `introduction` text,							-- Einleitungstext
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

