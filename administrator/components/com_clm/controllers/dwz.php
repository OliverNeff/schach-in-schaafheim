<?php
/**
 * @ Chess League Manager (CLM) Component 
 * @Copyright (C) 2008-2023 CLM Team.  All rights reserved
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.chessleaguemanager.de
 * @author Thomas Schwietert
 * @email fishpoke@fishpoke.de
 * @author Andreas Dorn
 * @email webmaster@sbbl.org
*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class CLMControllerDWZ extends JControllerLegacy
{
	/**
	 * Constructor
	 */
function __construct( $config = array() )
	{
		parent::__construct( $config );
	}

function display($cachable = false, $urlparams = array())
	{
	$mainframe	= JFactory::getApplication();
	$option 	= clm_core::$load->request_string('option');
	$section	= clm_core::$load->request_string('section');
	$db		=JFactory::getDBO();
	//CLM parameter auslesen
	$config = clm_core::$db->config();
	$countryversion = $config->countryversion;
	
	$filter_vid		= $mainframe->getUserStateFromRequest( "$option.filter_vid",'filter_vid',0,'var' );
	$filter_vid_to 	= "0";
	$filter_vid_from	= $mainframe->getUserStateFromRequest( "$option.filter_vid_from",'filter_vid_from',0,'var' );

	$filter_sort		= $mainframe->getUserStateFromRequest( "$option.filter_sort",'filter_sort',0,'string' );
	if ($countryversion == "de") {
		$filter_mgl		= $mainframe->getUserStateFromRequest( "$option.filter_mgl",'filter_mgl',0,'int' );
		// Wenn Verein und Spieler gewählt wurden dann Daten für Anzeige laden
		if($filter_vid !="0" AND $filter_mgl !="0"){
		$sql = 'SELECT * FROM #__clm_dwz_spieler as a'
			.' LEFT JOIN #__clm_saison AS s ON s.id = a.sid'
			." WHERE s.archiv = 0"
			. " AND ZPS ='$filter_vid'"
			. " AND Mgl_Nr =".$filter_mgl
			;
		$db->setQuery( $sql );
		$spieler=$db->loadObjectList();
		} 
		else $spieler = array();
 	} else {
		$filter_PKZ		= $mainframe->getUserStateFromRequest( "$option.filter_PKZ",'filter_PKZ',0,'string' );
		// Wenn Verein und Spieler gewählt wurden dann Daten für Anzeige laden
		if($filter_vid !="0" AND $filter_PKZ !=""){
		$sql = 'SELECT * FROM #__clm_dwz_spieler as a'
			.' LEFT JOIN #__clm_saison AS s ON s.id = a.sid'
			." WHERE s.archiv = 0"
			. " AND ZPS ='$filter_vid'"
			. " AND PKZ =".$filter_PKZ
			;
		$db->setQuery( $sql );
		$spieler=$db->loadObjectList();
		}
		else $spieler = array();
	}
	// Wenn Verein gewählt wurden dann Daten für Anzeige laden
	if($filter_vid !="0" ){
	$sql = 'SELECT * FROM #__clm_dwz_spieler as a'
		.' LEFT JOIN #__clm_saison AS s ON s.id = a.sid'
		." WHERE s.archiv = 0"
		." AND ZPS ='$filter_vid'";
	if($filter_sort !="0") {
		$sql = $sql. " ORDER BY ".$filter_sort;
		}
	else {
		$sql = $sql. " ORDER BY Spielername ASC ";
		}
	$db->setQuery( $sql );
	$verein=$db->loadObjectList();
	}
	// Wenn FROM-Verein gewählt wurden dann Daten für Anzeige laden
	if($filter_vid_from !="0" ){
	$sql = 'SELECT * FROM #__clm_dwz_spieler as a'
		.' LEFT JOIN #__clm_saison AS s ON s.id = a.sid'
		." WHERE s.archiv = 0"
		." AND ZPS ='$filter_vid_from'";
	if($filter_sort !="0") {
		$sql = $sql. " ORDER BY ".$filter_sort;
		}
	else {
		$sql = $sql. " ORDER BY Spielername ASC ";
		}
	$db->setQuery( $sql );
	$verein_from=$db->loadObjectList();
	}
	// Saison
	$sql = 'SELECT id, name FROM #__clm_saison WHERE published = 1 AND archiv = 0';
	$db->setQuery($sql);
	$lists['saison']=$db->loadObjectList();

	// Vereinefilter laden
	$vlist = CLMFilterVerein::vereine_filter(0);
//	$lists['vid']	= JHTML::_('select.genericlist', $vlist, 'filter_vid', 'class="inputbox" size="1" onchange="document.adminForm.submit();"','zps', 'name', $filter_vid );
	$lists['vid']	= JHTML::_('select.genericlist', $vlist, 'filter_vid', 'class="inputbox" size="1" onchange="change_vid();"','zps', 'name', $filter_vid );
	$lists['vid_to']	= JHTML::_('select.genericlist', $vlist, 'filter_vid_to', 'class="inputbox" size="1" ','zps', 'name', $filter_vid_to );
	$lists['vid_from']	= JHTML::_('select.genericlist', $vlist, 'filter_vid_from', 'class="inputbox" size="1" onchange="document.adminForm.submit();"','zps', 'name', $filter_vid_from );
	

	// Spielerfilter
	//if ($filter_zps !="0" ) {
	if ($filter_vid !="0" ) {
	  if ($countryversion == "de") {
		$sql = 'SELECT Mgl_Nr, Spielername FROM #__clm_dwz_spieler as a'
			.' LEFT JOIN #__clm_saison AS s ON s.id = a.sid'
			." WHERE s.archiv = 0 "
			." AND ZPS ='$filter_vid'"
			." ORDER BY Spielername ASC"
			;
		$db->setQuery($sql);
		$mlist[]	= JHTML::_('select.option',  '0', JText::_( 'DWZ_SPIELER' ), 'Mgl_Nr', 'Spielername' );
		$mlist		= array_merge( $mlist, $db->loadObjectList() );
		$lists['mgl']	= JHTML::_('select.genericlist', $mlist, 'filter_mgl', 'class="inputbox" size="1" onchange="document.adminForm.submit();"','Mgl_Nr', 'Spielername', $filter_mgl );
	  } else {
		$sql = 'SELECT PKZ, Spielername FROM #__clm_dwz_spieler as a'
			.' LEFT JOIN #__clm_saison AS s ON s.id = a.sid'
			." WHERE s.archiv = 0 "
			." AND ZPS ='$filter_vid'"
			." ORDER BY Spielername ASC"
			;
		$db->setQuery($sql);
		$mlist[]	= JHTML::_('select.option',  '0', JText::_( 'DWZ_SPIELER' ), 'PKZ', 'Spielername' );
		$mlist		= array_merge( $mlist, $db->loadObjectList() );
		$lists['PKZ']	= JHTML::_('select.genericlist', $mlist, 'filter_PKZ', 'class="inputbox" size="1" onchange="document.adminForm.submit();"','PKZ', 'Spielername', $filter_PKZ );
	  }	
	}

	if (!isset($verein)) $verein = array();
	if (!isset($verein_from)) $verein_from = array();
	require_once(JPATH_COMPONENT.DS.'views'.DS.'dwz.php');
	CLMViewDWZ::DWZ( $spieler,$verein,$verein_from, $lists, '', $option );
	}


static function cancel()
	{
	$mainframe	= JFactory::getApplication();
	// Check for request forgeries
	defined('clm') or die( 'Invalid Token' );
	
	$option 	= clm_core::$load->request_string('option');
	$section	= clm_core::$load->request_string('section');

	$msg = JText::_( 'DWZ_AKTION');
	$mainframe->enqueueMessage( $msg, 'message' );
	$mainframe->redirect( 'index.php?option='. $option.'&section=vereine' );
	}

static function spieler($zps)
	{
	$mainframe	= JFactory::getApplication();
	// Check for request forgeries
	defined('clm') or die( 'Invalid Token' );
	
	$option 	= clm_core::$load->request_string('option');
	$section	= clm_core::$load->request_string('section');
	$db 		= JFactory::getDBO();

	$query	= "SELECT a.Spielername, a.Mgl_Nr, a.ZPS, a.PKZ FROM #__clm_dwz_spieler as a "
		." LEFT JOIN #__clm_saison as s ON s.id = a.sid "
		." WHERE a.ZPS ='$zps'"
		." AND a.Status = 'N' "
		." AND s.archiv = 0"
		;
	$db->setQuery($query);
	$spieler=$db->loadObjectList();

	return $spieler;
	}

static function nachmeldung_delete()
	{
	$mainframe	= JFactory::getApplication();
	// Check for request forgeries
	defined('clm') or die( 'Invalid Token' );

	$db 		= JFactory::getDBO();
	$option 	= clm_core::$load->request_string('option');
	$section	= clm_core::$load->request_string('section');
	$spieler	= clm_core::$load->request_string('spieler');
	$sid		= clm_core::$load->request_int('sid');
	//CLM parameter auslesen
	$config = clm_core::$db->config();
	$countryversion = $config->countryversion;

	if ( $spieler == 0 OR $spieler == '') {
		$mainframe->enqueueMessage( JText::_( 'DWZ_SPIELER_LOESCH' ), 'warning' );
		$link = 'index.php?option='.$option.'&section='.$section;
		$mainframe->redirect( $link );
	}

	$zps	= $mainframe->getUserStateFromRequest( "$option.filter_vid",'filter_vid',0,'var' );

	$filter_mgl	= $mainframe->getUserStateFromRequest( "$option.filter_mgl",'filter_mgl',0,'int' );
	if ($filter_mgl == $spieler) {
		$mainframe->setUserState( "$option.filter_mgl", 0 );
		$filter_mgl	= $mainframe->getUserState( "$option.filter_mgl",'filter_mgl',0,'int' );
	}

	if ($countryversion =="de") {
		$result = clm_core::$api->db_player_check($sid,$zps,$spieler);
		if (!$result[0]) {
			$mainframe->enqueueMessage( 'Löschen von '.$zps.'-'.$spieler.' nicht möglich, '.$result[1], 'warning' );
			$link = 'index.php?option='.$option.'&section='.$section;
			$mainframe->redirect( $link );
		}
	}

	$query	= "DELETE FROM #__clm_dwz_spieler"
		." WHERE ZPS = '$zps'"
		." AND sid =".$sid;
	if ($countryversion =="de") {
		$query	.= " AND Mgl_Nr = ".$spieler;
	} else {
		$query	.= " AND PKZ = '".$spieler."'";
	}	
	//$db->setQuery($query);
	clm_core::$db->query($query);

	// Log schreiben
	$clmLog = new CLMLog();
	$clmLog->aktion = "Nachmeldung gelöscht";
	$clmLog->params = array('sid' => $sid, 'zps' => $zps, 'mgl_nr' => $spieler, 'cids' => $cid);
	$clmLog->write();
	
	$msg = JText::_( 'DWZ_SPIELER_MITGLIED').' '.$spieler.' '.JText::_('DWZ_LOESCH' );
	$mainframe->enqueueMessage( $msg, 'message' );
	$link = 'index.php?option='.$option.'&section='.$section;
	$mainframe->redirect( $link);
	}

static function nachmeldung()
	{
	$mainframe	= JFactory::getApplication();
	// Check for request forgeries
	defined('clm') or die( 'Invalid Token' );

	$db 		= JFactory::getDBO();
	$option 	= clm_core::$load->request_string('option');
	$section	= clm_core::$load->request_string('section');
	$sid		= clm_core::$load->request_int('sid');

	if ( $sid == 0 ) {
		$mainframe->enqueueMessage( JText::_( 'DWZ_VEREIN' ), 'warning' );
		$link = 'index.php?option='.$option.'&section='.$section;
		$mainframe->redirect( $link);
	}
	//CLM parameter auslesen
	$config = clm_core::$db->config();
	$countryversion = $config->countryversion;

	$name 		= clm_core::$load->request_string('name');
	$mglnr		= clm_core::$load->request_string('mglnr');
	$PKZ		= clm_core::$load->request_string('PKZ');
	$dwz 		= clm_core::$load->request_string('dwz');
	$dwz_index 	= clm_core::$load->request_string('dwz_index', '0');
	if (!isset($dwz_index)) $dwz_index = 0;
	$geschlecht	= clm_core::$load->request_string('geschlecht');
	$geburtsjahr	= clm_core::$load->request_string('geburtsjahr');
	$zps		= clm_core::$load->request_string('zps');
	$status		= clm_core::$load->request_string('status');	
	if (!isset($status) OR $status == "") $status = "N";
	// Prüfen ob Name und Mitgliedsnummer/PKZ angegeben wurden
	if ( $countryversion == "de" AND ($name == "" OR $mglnr =="" OR $mglnr=="0") ) {
		$mainframe->enqueueMessage( JText::_( 'DWZ_NAME_NR' ), 'warning' );
		$link = 'index.php?option='.$option.'&section='.$section;
		$mainframe->redirect( $link );
	}
	if ( $countryversion == "en" AND ($name == "" OR $PKZ =="" OR $PKZ=="0") ) {
		$mainframe->enqueueMessage( JText::_( 'DWZ_NAME_PKZ' ), 'warning' );
		$link = 'index.php?option='.$option.'&section='.$section;
		$mainframe->redirect( $link );
	}

	// Prüfen ob Mitgliedsnummer schon vergeben wurde
	if ( $countryversion == "de") {
		$filter_mgl	= $mainframe->getUserStateFromRequest( "$option.filter_mgl",'filter_mgl',0,'int' );
		$query	= "SELECT Mgl_Nr FROM #__clm_dwz_spieler "
			." WHERE ZPS ='$zps'"
			." AND sid = '$sid'"
			." AND Mgl_Nr = '$mglnr'"
			;
		$db->setQuery($query);
		$mgl_exist = $db->loadObjectList();
		if ($filter_mgl == $mglnr) {
			$mainframe->enqueueMessage( JText::_( 'DWZ_SPIELER_AUSWAHL' ), 'warning' );
			$mainframe->enqueueMessage( JText::_( 'DWZ_DATEN_AENDERN' ), 'notice' );
			$link = 'index.php?option='.$option.'&section='.$section;
			$mainframe->redirect( $link );
		}
		if($mgl_exist[0]->Mgl_Nr !="") {
			$mainframe->enqueueMessage( JText::_( 'DWZ_EXISTIERT' ), 'warning' );
			$mainframe->enqueueMessage( JText::_( 'DWZ_DATEN_AENDERN' ), 'notice' );
			$link = 'index.php?option='.$option.'&section='.$section;
			$mainframe->redirect( $link );
		}
	} else {
		$filter_PKZ	= $mainframe->getUserStateFromRequest( "$option.filter_PKZ",'filter_PKZ','','string' );
		$query	= "SELECT PKZ FROM #__clm_dwz_spieler "
			." WHERE ZPS ='$zps'"
			." AND sid = '$sid'"
			." AND PKZ = '$PKZ'"
			;
		$db->setQuery($query);
		$PKZ_exist = $db->loadObjectList();
		if ($filter_PKZ == $PKZ) {
			$mainframe->enqueueMessage( JText::_( 'DWZ_SPIELER_AUSWAHL' ), 'warning' );
			$mainframe->enqueueMessage( JText::_( 'DWZ_DATEN_AENDERN' ), 'notice' );
			$link = 'index.php?option='.$option.'&section='.$section;
			$mainframe->redirect( $link );
		}
		if($PKZ_exist[0]->PKZ !="") {
			$mainframe->enqueueMessage( JText::_( 'DWZ_EXISTIERT_PKZ' ), 'warning' );
			$mainframe->enqueueMessage( JText::_( 'DWZ_DATEN_AENDERN' ), 'notice' );
			$link = 'index.php?option='.$option.'&section='.$section;
			$mainframe->redirect( $link );
		}
	}
	// Prüfen ob DWZ vorhanden ist
	if (!is_numeric($geburtsjahr))  $geburtsjahr = '0000';
	if ($geschlecht == '0') $geschlecht = 'M';
	if (!$dwz) {
	$query	= "INSERT INTO #__clm_dwz_spieler"
		." ( `sid`,`ZPS`, `Mgl_Nr`, `PKZ`, `Status`, `Spielername`, `Geschlecht`, `Geburtsjahr` ) "
		." VALUES ('".clm_escape($sid)."','".clm_escape($zps)."','".clm_escape($mglnr)."','".clm_escape($PKZ)."','".clm_escape($status)."','".clm_escape($name)."','".clm_escape($geschlecht)."','".clm_escape($geburtsjahr)."')"
		;
		}
	else {
	$query	= "INSERT INTO #__clm_dwz_spieler"
		." ( `sid`,`ZPS`, `Mgl_Nr`, `PKZ`, `Status`, `Spielername`, `Geschlecht`, `Geburtsjahr`, `DWZ`, `DWZ_Index`) "
		." VALUES ('".clm_escape($sid)."', '".clm_escape($zps)."','".clm_escape($mglnr)."','".clm_escape($PKZ)."','".clm_escape($status)."','".clm_escape($name)."','".clm_escape($geschlecht)."',"
		." '".clm_escape($geburtsjahr)."','".clm_escape($dwz)."','".clm_escape($dwz_index)."')"
		;
		}
	//$db->setQuery($query);
	clm_core::$db->query($query);

	// Log schreiben
	$clmLog = new CLMLog();
	$clmLog->aktion = "Nachmeldung";
	//$clmLog->params = array('sid' => $sid, 'zps' => $zps, 'mgl_nr' => $spieler);
	$clmLog->params = array('sid' => $sid, 'zps' => $zps, 'mgl_nr' => $mglnr);
	$clmLog->write();
	
	$msg = JText::_( 'DWZ_SPIELER_SPEICHERN' );
	$mainframe->enqueueMessage( $msg, 'message' );
	$link = 'index.php?option='.$option.'&section='.$section;
	$mainframe->redirect( $link );
	}

static function daten_edit()
	{
	$mainframe	= JFactory::getApplication();
	// Check for request forgeries
	defined('clm') or die( 'Invalid Token' );
	//CLM parameter auslesen
	$config = clm_core::$db->config();
	$countryversion = $config->countryversion;

	$db 		= JFactory::getDBO();
	$option 	= clm_core::$load->request_string('option');
	$section	= clm_core::$load->request_string('section');

	$sid		= clm_core::$load->request_int('sid');
	$name 		= clm_core::$load->request_string('name');
	$mglnr		= clm_core::$load->request_string('mglnr');
	$PKZ		= clm_core::$load->request_string('PKZ');
	$dwz 		= clm_core::$load->request_string('dwz');
	$dwz_index 	= clm_core::$load->request_string('dwz_index','0');
	$geschlecht	= clm_core::$load->request_string('geschlecht');
	$geburtsjahr	= clm_core::$load->request_string('geburtsjahr');
	$zps		= clm_core::$load->request_string('zps');
	$status		= clm_core::$load->request_string('status');	

	// Prüfen ob Name und Mitgliedsnummer/PKZ angegeben wurden
	if ( $countryversion == "de" AND ($name == "" OR $mglnr =="" OR $mglnr=="0") ) {
		$mainframe->enqueueMessage( JText::_( 'DWZ_NAME_NR' ), 'warning' );
		$link = 'index.php?option='.$option.'&section='.$section;
		$mainframe->redirect( $link );
	}
	if ( $countryversion == "en" AND ($name == "" OR $PKZ =="" OR $PKZ=="0") ) {
		$mainframe->enqueueMessage( JText::_( 'DWZ_NAME_PKZ' ), 'warning' );
		$link = 'index.php?option='.$option.'&section='.$section;
		$mainframe->redirect( $link );
	}

	// Prüfen ob PKZ existiert
	if ( $countryversion == "de") {
		$filter_mgl	= $mainframe->getUserStateFromRequest( "$option.filter_mgl",'filter_mgl',0,'int' );
		$query	= "SELECT Mgl_Nr FROM #__clm_dwz_spieler "
			." WHERE ZPS ='$zps'"
			." AND sid = '$sid'"
			." AND Mgl_Nr = '$mglnr'"
			;
		$db->setQuery($query);
		$mgl_exist = $db->loadObjectList();
		if (!$mgl_exist) {
			$mainframe->enqueueMessage( JText::_( 'DWZ_SPIELER_NO' ), 'warning' );
			$mainframe->enqueueMessage( JText::_( 'DWZ_NACHM' ), 'notice' );
			$link = 'index.php?option='.$option.'&section='.$section;
			$mainframe->redirect( $link );
		}
	} else {
		$filter_PKZ	= $mainframe->getUserStateFromRequest( "$option.filter_PKZ",'filter_PKZ',0,'string' );
		$query	= "SELECT PKZ FROM #__clm_dwz_spieler "
			." WHERE ZPS ='$zps'"
			." AND sid = '$sid'"
			." AND PKZ = '$PKZ'"
			;
		$db->setQuery($query);
		$PKZ_exist = $db->loadObjectList();
		if (!$PKZ_exist) {
			$mainframe->enqueueMessage( JText::_( 'DWZ_SPIELER_NO' ), 'warning' );
			$mainframe->enqueueMessage( JText::_( 'DWZ_NACHM' ), 'notice' );
			$link = 'index.php?option='.$option.'&section='.$section;
			$mainframe->redirect( $link );
		}
	}
	// Datensatz updaten
	$query	= "UPDATE #__clm_dwz_spieler "
		." SET Spielername = '".clm_escape($name)."' "
		." , Mgl_Nr = '".clm_escape($mglnr)."' "
		." , PKZ = '".clm_escape($PKZ)."' "
		." , DWZ = '".clm_escape($dwz)."' "
		." , DWZ_Index = '".clm_escape($dwz_index)."' "
		." , Geschlecht = '".clm_escape($geschlecht)."' "
		." , Geburtsjahr = '".clm_escape($geburtsjahr)."' "
		." , Status = '".clm_escape($status)."' "
		." WHERE ZPS = '".clm_escape($zps)."' "
		." AND sid = '".clm_escape($sid)."' ";
	if ( $countryversion == "de") {
		$query .= " AND Mgl_Nr = '".clm_escape($mglnr)."' ";
	} else {
		$query .= " AND PKZ = '".clm_escape($PKZ)."' ";
	}
	//$db->setQuery($query);
	clm_core::$db->query($query);

	// Log schreiben
	$clmLog = new CLMLog();
	$clmLog->aktion = "Spielerdaten geändert";
	$clmLog->params = array('sid' => $sid, 'zps' => $zps, 'mgl_nr' => $mglnr, 'PKZ' => $PKZ);
	$clmLog->write();
	
	$msg = JText::_( 'DWZ_SPIELER_AENDERN' );
	$mainframe->enqueueMessage( $msg, 'message' );
	$link = 'index.php?option='.$option.'&section='.$section;
	$mainframe->redirect( $link );
	}

static function spieler_delete()
	{
	$mainframe	= JFactory::getApplication();
	// Check for request forgeries
	defined('clm') or die( 'Invalid Token' );

	$db 		= JFactory::getDBO();
	$option 	= clm_core::$load->request_string('option');
	$section	= clm_core::$load->request_string('section');
	$spieler	= clm_core::$load->request_string('del_spieler');
	$sid		= clm_core::$load->request_int('sid');
	//CLM parameter auslesen
	$config = clm_core::$db->config();
	$countryversion = $config->countryversion;

	// SL nicht zulassen !
	$clmAccess = clm_core::$access;
	if($clmAccess->access('BE_database_general') === false) {
		$mainframe->enqueueMessage( JText::_( 'DWZ_REFERENT').clm_core::$access->getType(), 'warning' );
		$link = 'index.php?option='.$option.'&section='.$section;
		$mainframe->redirect( $link );
	}

	// Spieler muß ausgewählt sein
	if ( $spieler == 0 OR $spieler == '') {
		$mainframe->enqueueMessage( JText::_( 'DWZ_SPIELER_LOESCH' ), 'warning' );
		$link = 'index.php?option='.$option.'&section='.$section;
		$mainframe->redirect( $link );
	}

	$zps	= $mainframe->getUserStateFromRequest( "$option.filter_vid",'filter_vid',0,'var' );

	if ($countryversion =="de") {
		$result = clm_core::$api->db_player_check($sid,$zps,$spieler);
		if (!$result[0]) {
			$mainframe->enqueueMessage( 'Löschen von '.$zps.'-'.$spieler.' nicht möglich, '.$result[1], 'warning' );
			$link = 'index.php?option='.$option.'&section='.$section;
			$mainframe->redirect( $link );
		}
	}

	$query	= "DELETE FROM #__clm_dwz_spieler"
		." WHERE ZPS = '$zps'"
		." AND sid =".$sid;
	if ($countryversion =="de") {
		$query	.= " AND Mgl_Nr = ".$spieler;
	} else {
		$query	.= " AND PKZ = '".$spieler."'";
	}
	//$db->setQuery($query);
	clm_core::$db->query($query);

	$filter_mgl	= $mainframe->getUserStateFromRequest( "$option.filter_mgl",'filter_mgl',0,'int' );
	if ($filter_mgl == $spieler) {
		$mainframe->setUserState( "$option.filter_mgl", 0 );
		$filter_mgl	= $mainframe->getUserState( "$option.filter_mgl",'filter_mgl',0,'int' );
	}
	// Log schreiben
	$clmLog = new CLMLog();
	$clmLog->aktion = "Spielerdaten gelöscht";
	$clmLog->params = array('sid' => $sid, 'zps' => $zps, 'mgl_nr' => $spieler);
	$clmLog->write();
	
	$msg = JText::_( 'DWZ_SPIELER_MITGLIED').' '.$spieler.' '.JText::_('DWZ_LOESCH' );
	$mainframe->enqueueMessage( $msg, 'message' );
	$link = 'index.php?option='.$option.'&section='.$section;
	$mainframe->redirect( $link );
	}

static function player_move_to()
	{
	$mainframe	= JFactory::getApplication();
	// Check for request forgeries
	defined('clm') or die( 'Invalid Token' );

	$db 		= JFactory::getDBO();
	$option 	= clm_core::$load->request_string('option');
	$section	= clm_core::$load->request_string('section');
	$spieler	= clm_core::$load->request_string('spieler_to');
	$newclub	= clm_core::$load->request_string('filter_vid_to');
	$sid		= clm_core::$load->request_int('sid');
	//CLM parameter auslesen
	$config = clm_core::$db->config();
	$countryversion = $config->countryversion;

	// SL nicht zulassen !
	$clmAccess = clm_core::$access;
	if($clmAccess->access('BE_database_general') === false) {
		$mainframe->enqueueMessage( JText::_( 'DWZ_REFERENT' ).clm_core::$access->getType(), 'warning' );
		$link = 'index.php?option='.$option.'&section='.$section;
		$mainframe->redirect( $link );
	}

	// Spieler muß ausgewählt sein
	if ( $spieler == 0 OR $spieler == '') {
		$mainframe->enqueueMessage( JText::_( 'DWZ_PLAYER_MISSING' ), 'warning' );
		$link = 'index.php?option='.$option.'&section='.$section;
		$mainframe->redirect( $link );
	}
	// neuer Verein muß ausgewählt sein
	if ( $newclub == 0 OR $newclub == '') {
		$mainframe->enqueueMessage( JText::_( 'DWZ_NEWCLUB_MISSING' ), 'warning' );
		$link = 'index.php?option='.$option.'&section='.$section;
		$mainframe->redirect( $link );
	}

	$zps	= $mainframe->getUserStateFromRequest( "$option.filter_vid",'filter_vid',0,'var' );
	// Player auslesen alter Verein
	$query	= "SELECT * FROM #__clm_dwz_spieler "
			." WHERE ZPS ='$zps'"
			." AND sid = '$sid'"
			." AND PKZ = '$spieler'"
			;
	$db->setQuery($query);
	$pl_data = $db->loadObjectList();
	if (!$pl_data) {
			$mainframe->enqueueMessage( JText::_( 'DWZ_PLAYER_CLUB' ), 'warning' );
			$link = 'index.php?option='.$option.'&section='.$section;
			$mainframe->redirect( $link );
	}
	// Player bereits im neuen Verein
	$query	= "SELECT * FROM #__clm_dwz_spieler "
			." WHERE ZPS ='$newclub'"
			." AND sid = '$sid'"
			." AND PKZ = '$spieler'"
			;
	$db->setQuery($query);
	$pl_check = $db->loadObjectList();
	if ($pl_check) {
			$mainframe->enqueueMessage( JText::_( 'DWZ_PLAYER_CLUB_TO' ), 'warning' );
			$link = 'index.php?option='.$option.'&section='.$section;
			$mainframe->redirect( $link );
	}
	// Player check gespielt in alten Verein
	$query	= "SELECT * FROM #__clm_meldeliste_spieler "
			." WHERE ZPS ='$zps'"
			." AND sid = '$sid'"
			." AND PKZ = '$spieler'"
			;
	$db->setQuery($query);
	$pl_check = $db->loadObjectList();
	if ($pl_check) {
			$mainframe->enqueueMessage( JText::_( 'DWZ_PLAYER_CLUB_PLAIED' ), 'warning' );
			$link = 'index.php?option='.$option.'&section='.$section;
			$mainframe->redirect( $link );
	}
	
	// Übernehmen in neuen Verein
	$query	= "INSERT INTO #__clm_dwz_spieler"
		." ( `sid`,`ZPS`, `Mgl_Nr`, `PKZ`, `Status`, `Spielername`, `Geschlecht`, `Geburtsjahr`, `DWZ`) "
		." VALUES ('".$pl_data[0]->sid."', '".$newclub."', 0 ,'".$spieler."','".$pl_data[0]->Status."','".$pl_data[0]->Spielername."','".$pl_data[0]->Geschlecht."',"
		." '".$pl_data[0]->Geburtsjahr."','".$pl_data[0]->DWZ."')"
		;

	//$db->setQuery($query);
	clm_core::$db->query($query);

	$query	= "DELETE FROM #__clm_dwz_spieler"
		." WHERE ZPS = '$zps'"
		." AND sid =".$sid;
	if ($countryversion =="de") {
		$query	.= " AND Mgl_Nr = ".$spieler;
	} else {
		$query	.= " AND PKZ = '".$spieler."'";
	}
	//$db->setQuery($query);
	clm_core::$db->query($query);

	// Log schreiben
	$clmLog = new CLMLog();
	$clmLog->aktion = JText::_( 'DWZ_PLAYER_MOVE_OUT');
	$clmLog->params = array('sid' => $sid, 'zps' => $zps, 'mgl_nr' => $spieler, 'to' => $newclub);
	$clmLog->write();
	
	$msg = JText::_( 'DWZ_PLAYER_MOVE_OUT').' '.$spieler;
	$mainframe->enqueueMessage( $msg, 'message' );
	$link = 'index.php?option='.$option.'&section='.$section;
	$mainframe->redirect( $link );
}

static function player_move_from()
	{
	$mainframe	= JFactory::getApplication();
	// Check for request forgeries
	defined('clm') or die( 'Invalid Token' );

	$db 		= JFactory::getDBO();
	$option 	= clm_core::$load->request_string('option');
	$section	= clm_core::$load->request_string('section');
	$spieler	= clm_core::$load->request_string('spieler_from');
	$oldclub	= clm_core::$load->request_string('filter_vid_from');
	$sid		= clm_core::$load->request_int('sid');
	//CLM parameter auslesen
	$config = clm_core::$db->config();
	$countryversion = $config->countryversion;

	// SL nicht zulassen !
	$clmAccess = clm_core::$access;
	if($clmAccess->access('BE_database_general') === false) {
		$mainframe->enqueueMessage( JText::_( 'DWZ_REFERENT' ).clm_core::$access->getType(), 'warning' );
		$link = 'index.php?option='.$option.'&section='.$section;
		$mainframe->redirect( $link );
	}

	// Spieler muß ausgewählt sein
	if ( $spieler == 0 OR $spieler == '') {
		$mainframe->enqueueMessage( JText::_( 'DWZ_PLAYER_MISSING' ), 'warning' );
		$link = 'index.php?option='.$option.'&section='.$section;
		$mainframe->redirect( $link );
	}
	// alter Verein muß ausgewählt sein
	if ( $oldclub == 0 OR $oldclub == '') {
		$mainframe->enqueueMessage( JText::_( 'DWZ_OLDCLUB_MISSING' ), 'warning' );
		$link = 'index.php?option='.$option.'&section='.$section;
		$mainframe->redirect( $link );
	}

	$zps	= $mainframe->getUserStateFromRequest( "$option.filter_vid",'filter_vid',0,'var' );
	// Player auslesen im alten Verein
	$query	= "SELECT * FROM #__clm_dwz_spieler "
			." WHERE ZPS ='$oldclub'"
			." AND sid = '$sid'"
			." AND PKZ = '$spieler'"
			;
	$db->setQuery($query);
	$pl_data = $db->loadObjectList();
	if (!$pl_data) {
			$mainframe->enqueueMessage( JText::_( 'DWZ_PLAYER_CLUB_FROM' ), 'warning' );
			$link = 'index.php?option='.$option.'&section='.$section;
			$mainframe->redirect( $link );
	}
	// Player bereits im neuen Verein
	$query	= "SELECT * FROM #__clm_dwz_spieler "
			." WHERE ZPS ='$zps'"
			." AND sid = '$sid'"
			." AND PKZ = '$spieler'"
			;
	$db->setQuery($query);
	$pl_check = $db->loadObjectList();
	if ($pl_check) {
			$mainframe->enqueueMessage( JText::_( 'DWZ_PLAYER_CLUB_ALREADY' ), 'warning' );
			$link = 'index.php?option='.$option.'&section='.$section;
			$mainframe->redirect( $link );
	}
	// Player check gespielt in alten Verein
	$query	= "SELECT * FROM #__clm_meldeliste_spieler "
			." WHERE ZPS ='$oldclub'"
			." AND sid = '$sid'"
			." AND PKZ = '$spieler'"
			;
	$db->setQuery($query);
	$pl_check = $db->loadObjectList();
	if ($pl_check) {
			$mainframe->enqueueMessage( JText::_( 'DWZ_PLAYER_CLUB_PLAIED' ), 'warning' );
			$link = 'index.php?option='.$option.'&section='.$section;
			$mainframe->redirect( $link );
	}
	
	// Übernehmen in neuen Verein
	$query	= "INSERT INTO #__clm_dwz_spieler"
		." ( `sid`,`ZPS`, `Mgl_Nr`, `PKZ`, `Status`, `Spielername`, `Geschlecht`, `Geburtsjahr`, `DWZ`) "
		." VALUES ('".$pl_data[0]->sid."', '".$zps."', 0 ,'".$spieler."','".$pl_data[0]->Status."','".$pl_data[0]->Spielername."','".$pl_data[0]->Geschlecht."',"
		." '".$pl_data[0]->Geburtsjahr."','".$pl_data[0]->DWZ."')"
		;

	//$db->setQuery($query);
	clm_core::$db->query($query);

	$query	= "DELETE FROM #__clm_dwz_spieler"
		." WHERE ZPS = '$oldclub'"
		." AND sid =".$sid;
	if ($countryversion =="de") {
		$query	.= " AND Mgl_Nr = ".$spieler;
	} else {
		$query	.= " AND PKZ = '".$spieler."'";
	}
	//$db->setQuery($query);
	clm_core::$db->query($query);

	// Log schreiben
	$clmLog = new CLMLog();
	$clmLog->aktion = JText::_( 'DWZ_PLAYER_MOVE_IN');
	$clmLog->params = array('sid' => $sid, 'zps' => $zps, 'mgl_nr' => $spieler, 'from' => $oldclub);
	$clmLog->write();
	
	$msg = JText::_( 'DWZ_PLAYER_MOVE_IN').' '.$spieler;
	$mainframe->enqueueMessage( $msg, 'message' );
	$link = 'index.php?option='.$option.'&section='.$section;
	$mainframe->redirect( $link );
	}
}
