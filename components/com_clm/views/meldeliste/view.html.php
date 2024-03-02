<?php
/**
 * @ Chess League Manager (CLM) Component 
 * @Copyright (C) 2008-2017 CLM Team.  All rights reserved
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.chessleaguemanager.de
 * @author Thomas Schwietert
 * @email fishpoke@fishpoke.de
 * @author Andreas Dorn
 * @email webmaster@sbbl.org
*/

jimport( 'joomla.application.component.view');

class CLMViewMeldeliste extends JViewLegacy
{
	function display($tpl = null)
	{
	$layout = clm_core::$load->request_string('layout');

		$model	= $this->getModel();
		$liga	= $model->getCLMLiga();
		$this->liga = $liga;

		$model	= $this->getModel();
		$spieler= $model->getCLMSpieler();
		$this->spieler = $spieler;

		$model	= $this->getModel();
		$count	= $model->getCLMCount();
		$this->count = $count;

		$model	= $this->getModel();
		$clmuser= $model->getCLMCLMuser();
		$this->clmuser = $clmuser;

	if (!isset($layout) OR $layout == '') {
		$model	= $this->getModel();
		$access	= $model->getCLMAccess();
		$this->access = $access;
			}

		$model	= $this->getModel();
		$abgabe	= $model->getCLMAbgabe();
		$this->abgabe = $abgabe;

		$model	= $this->getModel();
		$mllist	= $model->getCLMML();
		$this->mllist = $mllist;

		parent::display($tpl);
	}	
}
?>
