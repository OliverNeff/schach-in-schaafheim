<?php
/**
 * @ Chess League Manager (CLM) Component 
 * @Copyright (C) 2008-2015 CLM Team.  All rights reserved
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fishpoke.de
 * @author Thomas Schwietert
 * @email fishpoke@fishpoke.de
 * @author Andreas Dorn
 * @email webmaster@sbbl.org
*/

defined('_JEXEC') or die();
jimport('joomla.application.component.model');
jimport( 'joomla.html.parameter' );

class CLMModelTurnier_Teilnehmer extends JModelLegacy {
	
	function __construct() {
		
		parent::__construct();

		$this->turnierid = JRequest::getInt('turnier', 0);

		$this->_getTurnierData();

		$this->_getTurnierPlayers();

	}
	
	function _getTurnierData() {
	
		$query = "SELECT *"
			." FROM #__clm_turniere"
			." WHERE id = ".$this->turnierid
			;
		$this->_db->setQuery( $query );
		$this->turnier = $this->_db->loadObject();

		// turniernamen anpassen?
		$turParams = new clm_class_params($this->turnier->params);
		$addCatToName = $turParams->get('addCatToName', 0);
		if ($addCatToName != 0 AND ($this->turnier->catidAlltime > 0 OR $this->turnier->catidEdition > 0)) {
			$this->turnier->name = CLMText::addCatToName($addCatToName, $this->turnier->name, $this->turnier->catidAlltime, $this->turnier->catidEdition);
		}

	}
	
	
	function _getTurnierPlayers() {
	
		$query = "SELECT *"
			." FROM `#__clm_turniere_tlnr`"
			." WHERE turnier = ".$this->turnierid
			." ORDER BY snr ASC"
			;
		$this->_db->setQuery( $query );
		$this->players = $this->_db->loadObjectList('snr');
	
		if (count($this->players) > 0) {
			foreach($this->players as $player) {
				if ($player->FIDEcco == NULL OR $player->FIDEid == NULL) {
					$query = "SELECT *"
						." FROM #__clm_dwz_spieler"
						." WHERE sid = ".$player->sid
						." AND ZPS = '".$player->zps."' AND Mgl_nr = ".$player->mgl_nr
						;
					$this->_db->setQuery( $query );
					$this->spieler = $this->_db->loadObject();
					if (isset($this->spieler)) {
						$player->FIDEcco = $this->spieler->FIDE_Land;
						$player->FIDEid = $this->spieler->FIDE_ID;
					}
				}
			}
		}
	}

}
?>
