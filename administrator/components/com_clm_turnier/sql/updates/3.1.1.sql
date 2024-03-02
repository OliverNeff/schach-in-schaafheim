-- DEFAULT bei 'typ_calculation' entfernt.
-- Kompatibilitätsprobleme mit MySQL Datenbank
ALTER TABLE `#__clm_turniere_grand_prix`
	CHANGE `typ_calculation` `typ_calculation` MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL;
