-- Nachkommastellen bei Modus "prozentual"
ALTER TABLE `#__clm_turniere_grand_prix`
	ADD `precision` mediumint(5) unsigned DEFAULT '0'
	AFTER `extra_points`;
	