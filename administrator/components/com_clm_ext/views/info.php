<?php

/**
 * @ CLM Extern Component
 * @Copyright (C) 2008-2017 CLM Team.  All rights reserved
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.chessleaguemanager.de
 * @author Thomas Schwietert
 * @email fishpoke@fishpoke.de
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

class CLM_EXTViewInfo
{

static function display ($tpl = NULL)
{
	JToolBarHelper::title(   JText::_( 'CLM Extern Komponente' ), 'generic.png' );
	JToolBarHelper::help( 'screen.clm_ext.info' );
?>
<fieldset class="adminform">
	<legend>Informationen</legend>
	<style type="text/css">table { width:90%; }</style>
		<table class="admintable">
		<tbody>
			<tr>
			<td>
			<h2>Eine Komponente zur Darstellung von CLM Ligadaten auf Seiten ohne CLM</h2>
			<br>von Thomas Schwietert [www.sboo.de] - thomas.schwietert@quakenbruecker-schachfreunde.de
			<br><br>
			<b>Projekt Homepage :  </b> http://www.chessleaguemanager.de<br>
			<br>
			<h2>Einstellungen erfolgen über die Parameter des CLM Extern Moduls !</h2>
			<h3>Das Modul kann auch mehrfach benutzt werden ! Dazu einfach im Module Manager eine Kopie anfertigen und andere Parameter wählen.</h3>
			</td>
			</tr>
		</tbody>
		</table>
</fieldset>

<?php }} ?>
