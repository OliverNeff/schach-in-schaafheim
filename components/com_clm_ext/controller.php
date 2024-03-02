<?php
/**
 * @ CLM Extern Component
 * @Copyright (C) 2008-2021 CLM Team.  All rights reserved
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.chessleaguemanager.de
 * @author Thomas Schwietert
 * @email fishpoke@fishpoke.de
*/
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
jimport('joomla.application.component.controller');

class CLM_EXTController extends JControllerLegacy
{
	function display($cachable = false, $urlparams = array())
	{
		// Setzt einen Standard view
		if ( clm_ext_request_string( 'view') == '' ) {
			$_REQUEST['view'] = 'categories';
		}
		parent::display();
	}
}
