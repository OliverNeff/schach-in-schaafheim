
-- minimale Anzahl der Turniere für Gesamtwertung
ALTER TABLE `#__clm_turniere_grand_prix`
	ADD `min_tournaments` mediumint(5) unsigned  DEFAULT '0'
	AFTER `best_of`;
