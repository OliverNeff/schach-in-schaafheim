
-- Anpassungen für Joomla / CLM Berechtigungen
UPDATE `#__clm_turniere_grand_prix` SET `published` = '0' WHERE `published` IS NULL; 
ALTER TABLE `#__clm_turniere_grand_prix`
	MODIFY `published` tinyint(4) unsigned NOT NULL DEFAULT '0';
	
UPDATE `#__clm_turniere_grand_prix` SET `checked_out` = '0' WHERE `published` IS NULL; 
ALTER TABLE `#__clm_turniere_grand_prix`
	MODIFY `checked_out` int(10) unsigned NOT NULL DEFAULT '0';

-- Extrapunkte bei regelmäßiger Turnierteilnahme
ALTER TABLE `#__clm_turniere_grand_prix`
	ADD `num_tournaments` mediumint(5) unsigned  DEFAULT '0'
	AFTER `best_of`;

ALTER TABLE `#__clm_turniere_grand_prix`
	ADD `extra_points` decimal(3,1) unsigned  DEFAULT '0'
	AFTER `num_tournaments`;
