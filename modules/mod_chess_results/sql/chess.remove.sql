-- phpMyAdmin
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Server-Version: 5.7.16
-- PHP-Version: 5.6.29

--
-- Tabellenstruktur f�r Tabelle `sgw_chess_table`
-- {team, division, date, flag, bp, mp}
-- date: 2004-02-12
DROP TABLE IF EXISTS `sgw_chess_records`;
CREATE TABLE IF NOT EXISTS `sgw_chess_records` (
  `verein`          varchar(30) NOT NULL,
  `division`        varchar(30)  NOT NULL,
  `last_update`     varchar(10) NOT NULL,
  `platz`           int(2) UNSIGNED NOT NULL default 0,
  `flag`            varchar(1)  NOT NULL,
  `summebp`         decimal(5,1) UNSIGNED NOT NULL default 0.0,
  `summemp`         int(10) UNSIGNED NOT NULL default 0,
  PRIMARY KEY (`verein`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `sgw_chess_matches`;
CREATE TABLE IF NOT EXISTS `sgw_chess_matches` (
  `id`          MEDIUMINT NOT NULL AUTO_INCREMENT,
  `division`    varchar(30)  NOT NULL,
  `date`        varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;