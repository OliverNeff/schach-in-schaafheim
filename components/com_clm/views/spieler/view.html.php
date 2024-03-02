<?php
/**
 * @ Chess League Manager (CLM) Component 
 * @Copyright (C) 2008-2020 CLM Team.  All rights reserved
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.chessleaguemanager.de
 * @author Thomas Schwietert
 * @email fishpoke@fishpoke.de
 * @author Andreas Dorn
 * @email webmaster@sbbl.org
*/

jimport( 'joomla.application.component.view');

class CLMViewSpieler extends JViewLegacy
{
	function display($tpl = null)
	{
		$model	  = $this->getModel();
  		$spieler     = $model->getCLMSpieler();
		$this->spieler = $spieler;

		$model	  = $this->getModel();
  		$runden     = $model->getCLMRunden();
		$this->runden = $runden;
		
		$model	  = $this->getModel();
		$spielerliste     = $model->getCLMSpielerliste();
		$this->spielerliste = $spielerliste;
		
		$model	  = $this->getModel();
		$vereinsliste     = $model->getCLMVereinsliste();
		$this->vereinsliste = $vereinsliste;
		
		$model	  = $this->getModel();
		$saisons     = $model->getCLMSaisons();
		$this->saisons = $saisons;
		
		$document =JFactory::getDocument();
		
		parent::display($tpl);
	}	
}
?>
