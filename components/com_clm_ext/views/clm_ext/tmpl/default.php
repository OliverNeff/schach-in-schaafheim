<?php
/**
 * @ CLM Extern Component
 * @Copyright (C) 2008-2023 CLM Team.  All rights reserved
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.chessleaguemanager.de
 * @author Thomas Schwietert
 * @email fishpoke@fishpoke.de
*/
defined('_JEXEC') or die('Restricted access');

function unicode_umlaute($string = '') {
	$search = array("\u00c4", "\u00d6", "\u00dc", "\u00e4", "\u00f6", "\u00fc", "\00df", "\00e9");
	$replace = array("Ä", "Ö", "Ü", "ä", "ö", "ü", "ß", "é");
	return str_replace($search, $replace, $string);		
}

$ext_view	= clm_ext_request_string('ext_view');
$saison		= clm_ext_request_int('saison');
$liga		= clm_ext_request_int('liga');
$runde		= clm_ext_request_int('runde');
$dg		= clm_ext_request_int('dg');
$tlnr		= clm_ext_request_int('tlnr');
$zps		= clm_ext_request_string('zps');
$mglnr		= clm_ext_request_int('mglnr');
$source_id  = clm_ext_request_string('source');
$itemid		= clm_ext_request_string('Itemid');
$detail		= clm_ext_request_string('detail');
$pgn		= clm_ext_request_string('pgn');

if (!is_numeric($source_id)) {	// Aufruf über Menüeintrag
	$url = $source_id;
	$source = $url;
//	$ext_view ='rangliste';

	$keyword	= '';
	$keyword2	= '';
	$keyword3	= '';
	$keyword4	= '';
	$keyword5	= '';
	$keyword6	= '';
	$mcolor		= '0';
	$mcolor2	= '0';
	$mcolor3	= '0';
	$mcolor4	= '0';
	$mcolor5	= '0';
	$mcolor6	= '0';

} else { 						// Aufruf über Modul
// URL aus Module-Parametern holen
	$db	= JFactory::getDBO();
	$query = "SELECT  * FROM #__modules"
		." WHERE id = ".$source_id;
	$db->setQuery( $query );
	$mod_data = $db->loadObjectList();
// Parameter string to array
	$mod_data[0]->params = substr($mod_data[0]->params,1,strlen($mod_data[0]->params)-2);
	$paramsStringArray = explode(",", $mod_data[0]->params);
	$mod_data[0]->params = array();
	foreach ($paramsStringArray as $value) {
		$ipos = strpos ($value, ':');
		if ($ipos !==false) {
			$key = substr($value,1,$ipos-2);
			$mod_data[0]->params[$key] = substr($value,$ipos+2,-1);
		}
	}
	
	$url	= str_replace ( '\/', '/', $mod_data[0]->params["URL"]);
	$source = $url;
	
	$keyword	= unicode_umlaute($mod_data[0]->params["keyword"]);
	$keyword2	= unicode_umlaute($mod_data[0]->params["keyword2"]);
	$keyword3	= unicode_umlaute($mod_data[0]->params["keyword3"]);
	$keyword4	= unicode_umlaute($mod_data[0]->params["keyword4"]);
	$keyword5	= unicode_umlaute($mod_data[0]->params["keyword5"]);
	$keyword6	= unicode_umlaute($mod_data[0]->params["keyword6"]);
	$mcolor		= $mod_data[0]->params["mcolor"];
	$mcolor2	= $mod_data[0]->params["mcolor2"];
	$mcolor3	= $mod_data[0]->params["mcolor3"];
	$mcolor4	= $mod_data[0]->params["mcolor4"];
	$mcolor5	= $mod_data[0]->params["mcolor5"];
	$mcolor6	= $mod_data[0]->params["mcolor6"];
	if ($mod_data[0]->params["marke"] == '0') {
		$mcolor = '0'; 
		$mcolor2 = '0'; 
		$mcolor3 = '0'; 	
	}
	if ($mod_data[0]->params["smarke"] == '0') {
		$mcolor4 = '0'; 
		$mcolor5 = '0'; 
		$mcolor6 = '0'; 	
	}
	if ($mcolor == '0') { $keyword = ''; }
	if ($mcolor2 == '0') { $keyword2 = ''; }
	if ($mcolor3 == '0') { $keyword3 = ''; }
	if ($mcolor4 == '0') { $keyword4 = ''; }
	if ($mcolor5 == '0') { $keyword5 = ''; }
	if ($mcolor6 == '0') { $keyword6 = ''; }

}
	$url	= unicode_umlaute($url);
	$url_sj		= $url;

// delete backslashs if exist
if (substr($url,0,1) == chr(92) ) { $url = substr($url,1,strlen($url)-1); }
if (substr($url,strlen($url)-1,1) == chr(92) ) { $url = substr($url,0,strlen($url)-1); }
 
// Include the class
	if (!class_exists('idna_convert')) {
		include_once('idna_convert.class.php');
	}
	if (!class_exists('Net_IDNA_php4')) {
		require_once('Net_IDNA_php4.class.php'); 
	}
// Instantiate it (depending on the version you are using) with
	$IDN = new idna_convert();
// The work string
	$url1 = $url;
// Encode it to its punycode presentation
	$url = $IDN->encode($url1);

$ext_url 	= "http://".$url;
$ext_url1 	= "http://".$url1;

//echo "<br>url:".$ext_url;
//echo "<br>url1:".$ext_url1;
//echo "<br>ext_view:".$ext_view;
//echo "<br>saison:".$saison;
 
// if($ext_view=="" ) {  
 if( $ext_view=="" OR $saison=="" ) {  ?>
<h1><?php echo JText::_('PAR_ERROR01') ?></h1><h2><?php echo JText::_('PAR_ERROR02') ?></h2>
<?php } else {

	$document	= JFactory::getDocument();
	$cssDir		= JURI::base().'components'.DS.'com_clm_ext'.DS;
	$document->addStyleSheet( $cssDir.DS.'clm_content.css', 'text/css', null, array() );
	$document->addStyleSheet( $cssDir.DS.'submenu.css', 'text/css', null, array() );
	$document->addScript( $cssDir.DS.'submenu.js');

 	$document->addScript( $cssDir.'jsPgnViewer.js');
	$document->addScript( $cssDir.'showPgnViewer.js');

	// Zufallszahl
	$now = time()+mt_rand();
	$document->addScriptDeclaration("var randomid = $now;");
	// pgn-params (Standardwerte der Hauptkomponente - hier keine Parameter
	$document->addScriptDeclaration("var param = new Array();");
	$document->addScriptDeclaration("param['fe_pgn_moveFont'] = '666666';");
	$document->addScriptDeclaration("param['fe_pgn_commentFont'] = '888888';");
	$document->addScriptDeclaration("param['fe_pgn_style'] = 'png';");
	// Tooltip-Texte
	$document->addScriptDeclaration("var text = new Array();");
	$document->addScriptDeclaration("text['altRewind'] = '".JText::_('PGN_ALT_REWIND')."';");
	$document->addScriptDeclaration("text['altBack'] = '".JText::_('PGN_ALT_BACK')."'");
	$document->addScriptDeclaration("text['altFlip'] = '".JText::_('PGN_ALT_FLIP')."';");
	$document->addScriptDeclaration("text['altShowMoves'] = '".JText::_('PGN_ALT_SHOWMOVES')."';");
	$document->addScriptDeclaration("text['altComments'] = '".JText::_('PGN_ALT_COMMENTS')."';");
	$document->addScriptDeclaration("text['altPlayMove'] = '".JText::_('PGN_ALT_PLAYMOVE')."';");
	$document->addScriptDeclaration("text['altFastForward'] = '".JText::_('PGN_ALT_FASTFORWARD')."';");
	$document->addScriptDeclaration("text['pgnClose'] = '".JText::_('PGN_CLOSE')."';");
	// Pfad
	$document->addScriptDeclaration("var imagepath = '".JURI::base()."components/com_clm_ext/images/pgnviewer/'");


/////////////////////
// Aufrufen mit z.B.:
// http://localhost/install/index.php?option=com_clm_ext&view=clm_ext&ext_view=rangliste&saison=1&liga=1
/////////////////////

// Views der Hauptauswahl des CLM Moduls

	$part		= explode("/", $url);
	$count 		= count($part);
	$url_org    = '';
	for($x=1; $x < $count; $x++) {
		if ($part[$x] !="" ){
			$url_org .= DS.$part[$x];	
		}
	}
	$urlaa = ""; // bis 1.1.4 "'"
	$urla  = $urlaa.$url.$urlaa;
	
if ($ext_view =="rangliste" OR $ext_view =="tabelle" OR $ext_view =="paarungsliste" OR $ext_view =="dwz_liga" OR $ext_view =="statistik" OR $ext_view =="teilnehmer" OR $ext_view =="liga_info"){
	$link = $ext_url.DS.'index.php?option=com_clm&view='.$ext_view.'&format=raw&html=0&saison='.$saison.'&liga='.$liga;
//	if ($ext_view =="rangliste" AND $pgn == '1') $link .= '&pgn=1';
	}

else if ($ext_view =="runde") {
	$link = $ext_url.DS.'index.php?option=com_clm&view='.$ext_view.'&format=raw&html=0&saison='.$saison.'&liga='.$liga.'&runde='.$runde.'&dg='.$dg;
	if ($detail == '1') $link .= '&detail=1';
	$path = "option=com_clm_ext&amp;view=clm_ext&amp;url=$urla&amp;ext_view=";
	}

else if ($ext_view =="aktuell_runde") {
	$url = $ext_url.'/components/com_clm/clm/xml_round.php';
	$url .='?lid=' . $liga;
//echo "<br>zrl $url ";

	if (!$html = file_get_contents($url)) {
		$url0 = $source.'/';
		if ($this->url_exists ( $url0 ) )
			echo "<br>".JText::_("PLG_CLM_SHOW_ERR_VERSION");
		else
			echo "<br>".JText::_("PLG_CLM_SHOW_ERR_CONNECTION");
	}
	if (!$xml = new SimpleXMLElement($html)) {
		foreach (libxml_get_errors() as $error) {
			echo "<br>errror:"; var_dump($error);
		}
		libxml_clear_errors();
	}
	if (isset($xml->error)) {
//			$error_text = 'PLG_CLM_SHOW_ERR_NO_TOURNAMENT';
			echo "<br>".JText::_($xml->error);
	}
// Aufbereitung der Ergebnisse
		if (isset($xml->lid) AND $xml->lid != "") {
			$lid = htmlentities($xml->lid);
		} else {
			$lid = "";
		}
		if (isset($xml->runde) AND $xml->runde != "") {
			$runde = htmlentities($xml->runde);
		} else {
			$runde = "";
		}
		if (isset($xml->dg) AND $xml->dg != "") {
			$dg = htmlentities($xml->dg);
		} else {
			$dg = "";
		}
//echo "<br>lid $lid  runde $runde  dg $dg ";
	$ext_view = "runde";
//die();	
	$link = $ext_url.DS.'index.php?option=com_clm&view='.$ext_view.'&format=raw&html=0&saison='.$saison.'&liga='.$liga.'&runde='.$runde.'&dg='.$dg;
	$path = "option=com_clm_ext&amp;view=clm_ext&amp;url=$urla&amp;ext_view=";
	}
		
// sekundäre Views durch Links	
else if ($ext_view =="mannschaft") {
	$link = $ext_url.DS.'index.php?option=com_clm&view='.$ext_view.'&format=raw&html=0&saison='.$saison.'&liga='.$liga.'&tlnr='.$tlnr;
	$path = "option=com_clm_ext&amp;view=clm_ext&amp;url=$urla&amp;ext_view=";
	}
else if ($ext_view =="verein") {
	$link = $ext_url.DS.'index.php?option=com_clm&view='.$ext_view.'&format=raw&html=0&saison='.$saison.'&zps='.$zps;
	$path = "option=com_clm_ext&amp;view=clm_ext&amp;url=$urla&amp;ext_view=";
	}
else if ($ext_view =="dwz") {
	$link = $ext_url.DS.'index.php?option=com_clm&view='.$ext_view.'&format=raw&html=0&saison='.$saison.'&zps='.$zps;
	}
else if ($ext_view =="spieler") {
	$link = $ext_url.DS.'index.php?option=com_clm&view='.$ext_view.'&format=raw&html=0&saison='.$saison.'&zps='.$zps.'&mglnr='.$mglnr;
	$path = "option=com_clm_ext&amp;view=clm_ext&amp;url=$urla&amp;ext_view=";
	}
else if ($ext_view =="vereinsliste") {
	$link = $ext_url.DS.'index.php?option=com_clm&view='.$ext_view.'&format=raw&html=0&saison='.$saison;
	$path = "option=com_clm_ext&amp;view=clm_ext&amp;url=$urla&amp;ext_view=";
	}
else if ($ext_view =="termine") {
	$link = $ext_url.DS.'index.php?option=com_clm&view='.$ext_view.'&format=raw&html=0&saison='.$saison;
	$path = "option=com_clm_ext&amp;view=clm_ext&amp;url=$urla&amp;ext_view=";
	}
else if ($ext_view =="info") {
	$link = $ext_url.DS.'index.php?option=com_clm&view='.$ext_view.'&format=raw&html=0&saison='.$saison;
	$path = "option=com_clm_ext&amp;view=clm_ext&amp;url=$urla&amp;ext_view=";
} else {
	$link = $ext_url.DS.'index.php?option=com_clm&view='.$ext_view.'&format=raw&html=0&saison='.$saison;
	$path = "option=com_clm_ext&amp;view=clm_ext&amp;url=$urla&amp;ext_view=";
	}

$ctx = stream_context_create(array('http'=> array( 'timeout' => 10 ) ));
$msg = '';
if (($data = @file_get_contents($link,false,$ctx)) === false) {
    $error_http = error_get_last();
    $msg = "HTTP request failed. Error was: " . $error_http['message'];
	$link = str_replace('http','https',$link);
	if (($data = @file_get_contents($link,false,$ctx)) === false) {
      $error_https = error_get_last();
      $msg = "HTTPS request failed. Error was: " . $error_https['message'];
	}
}
if ($msg != '') {
	echo '<br>'.$msg;
	$db	= JFactory::getDBO();
	$query = 'SELECT * FROM #__clm_logging LIMIT 1';
	$db->setQuery($query);
	$log_data = $db->loadObjectList();
// Log
	if (isset($log_data[0])) {  
		$aktion = "Extern Fehler";
		$callid = uniqid ( "", false );
		$userid = -1;
		$parray = array('source_id' => $source_id, 'sid' => $saison, 'liga' => $liga, 'msg' => $msg);
		$query	= "INSERT INTO #__clm_logging "
			." ( `callid`, `userid`, `timestamp` , `type` ,`name`, `content`) "
			." VALUES ('".$callid."','".$userid."',".time().",5,'".$aktion."','".json_encode($parray)."') "
			;
		$db->setQuery($query);
		$db->execute();
	}
}
	
/*	$data		= file_get_contents ($link);
if (is_string($data)) echo "<br>String: ".strlen($data);
else { echo "<br>kein String: "; var_dump($data); }
*/
	// Umlenkung bei KO-System: Rangliste wird zu Paarungsliste
	if ($ext_view =="rangliste") {
		$pos = strpos($data, 'GO_TO_PAARUNGSLISTE');
		if ($pos !== false) {
			$ext_view = 'paarungsliste';
			$link = $ext_url.DS.'index.php?option=com_clm&view='.$ext_view.'&format=raw&html=0&saison='.$saison.'&liga='.$liga;
			
			$data		= file_get_contents ($link);
		}
	}

	// Umsetzen &amp; --> &   zur Vereinfachung
	$url_org0 = '#&amp;#';
	$url_trans	= '&';
	$data		= preg_replace ( $url_org0, $url_trans, $data, -1, $anz );

	// Änderungen für CLM 3.2 
	$url_org0 	= array('class="clm"','.clm');
	$url_trans	= array('id="clm"','');
	$data 		= str_replace($url_org0, $url_trans, $data);
	// Ende Änderungen
	
	// Sonderfall www.schachjugend-nrw.de
	if ($url_sj == 'www.schachjugend-nrw.de') {
		$url_org00 = '#/ergebnisdienst/#';
		$url_trans	= '/index.php/component/clm/';
		$data		= preg_replace ( $url_org00, $url_trans, $data, -1, $anz );
		$url_org00 = '#schachbund.de/dwz/db/#';
		$url_trans	= 'schachbund.de/';
		$data		= preg_replace ( $url_org00, $url_trans, $data, -1, $anz );
		// Sonderfall www.schachjugend-nrw.de - views
		$url_org01 = '#/mannschaft/#';
		$url_trans	= '/?view=mannschaft/';
		$data		= preg_replace ( $url_org01, $url_trans, $data, -1, $anz );
		$url_org02 = '#/spieler/#';
		$url_trans	= '/?view=spieler/';
		$data		= preg_replace ( $url_org02, $url_trans, $data, -1, $anz );
		$url_org03 = '#/verein/#';
		$url_trans	= '/?view=verein/';
		$data		= preg_replace ( $url_org03, $url_trans, $data, -1, $anz );
		// Sonderfall www.schachjugend-nrw.de - parameters
		$url_org11 = '#/saison-#';
		$url_trans	= '&saison=';
		$data		= preg_replace ( $url_org11, $url_trans, $data, -1, $anz );
		$url_org12 = '#/liga-#';
		$url_trans	= '&liga=';
		$data		= preg_replace ( $url_org12, $url_trans, $data, -1, $anz );
		$url_org13 = '#/tlnr-#';
		$url_trans	= '&tlnr=';
		$data		= preg_replace ( $url_org13, $url_trans, $data, -1, $anz );
		$url_org14 = '#/zps-#';
		$url_trans	= '&zps=';
		$data		= preg_replace ( $url_org14, $url_trans, $data, -1, $anz );
		$url_org15 = '#/mglnr-#';
		$url_trans	= '&mglnr=';
		$data		= preg_replace ( $url_org15, $url_trans, $data, -1, $anz );
		// Sonderfall www.schachjugend-nrw.de - end
		$replace_html = '&Itemid='.$itemid.'"';
		$url_org91 = '#.html"#';
		$url_trans	= $replace_html;  //'&Itemid=101"';
		$data		= preg_replace ( $url_org91, $url_trans, $data, -1, $anz );
	}

	// pgn-Icon ausblenden
	if ($ext_view =="rangliste") {
		$pos_pgn = strpos($data, 'pgn.gif"');
		if ($pos_pgn > 1) {
//			echo "<br>pos_pgn:"; var_dump($pos_pgn);
			$data		= preg_replace ( '#pgn.gif"#', 'pgn.gif" style="display:none;"', $data, -1, $anz );
//			echo "<br>anz:"; var_dump($anz);
		}
	}
	
	// Suche ersten und letzten pdf-Links
	$first_pos_pdf = strpos ($data, 'format=pdf');
	$last_pos_pdf = strrpos ($data, 'format=pdf');
	if (!is_numeric($first_pos_pdf) OR $first_pos_pdf < 0) $first_pos_pdf = 0;
	if ($first_pos_pdf > 200) $first_pos_pdf -= 200;
	if (!is_numeric($last_pos_pdf) OR $last_pos_pdf < 0) $last_pos_pdf = 0;
	if ($last_pos_pdf > 0) $last_pos_pdf += 10;
	$data1 = substr($data, 0, $first_pos_pdf);
	$data2 = substr($data, $first_pos_pdf, ($last_pos_pdf-$first_pos_pdf));
	$data3 = substr($data, $last_pos_pdf);

	// URL anfügen !! WICHTIG !!!
	$url_org1 = 'href="'.$url_org.DS.'index.php';
	$url_trans	= 'href="'.JURI::base().'index.php';
	$data1		= preg_replace ( '#'.$url_org1.'#', $url_trans, $data1, -1, $anz1 );
	if ($anz1 == 0) {
	$url_org1 = $url_org.DS.'component/clm/';
	$url_trans	= JURI::base().'component/clm/';
	$data1		= preg_replace ( '#'.$url_org1.'#', $url_trans, $data1, -1, $anz2 );
	}
	// URL anfügen !! WICHTIG !!! für pdf-Links
	$url_org1 = 'href="'.$url_org.DS.'index.php';
	$url_trans	= 'href="'.$ext_url1.DS.'index.php';
	$data2		= preg_replace ( '#'.$url_org1.'#', $url_trans, $data2, -1, $anz3 );
	if ($anz3 == 0) {
	$url_org1 = $url_org.DS.'component/clm/';
	$url_trans	= $ext_url1.DS.'component/clm/';
	$data2		= preg_replace ( '#'.$url_org1.'#', $url_trans, $data2, -1, $anz4 );
	}
	// URL anfügen !! WICHTIG !!!
	$url_org1 = 'href="'.$url_org.DS.'index.php';
	$url_trans	= 'href="'.JURI::base().'index.php';
	$data3		= preg_replace ( '#'.$url_org1.'#', $url_trans, $data3, -1, $anz5 );
	if ($anz5 == 0) {
	$url_org1 = $url_org.DS.'component/clm/';
	$url_trans	= JURI::base().'component/clm/';
	$data3		= preg_replace ( '#'.$url_org1.'#', $url_trans, $data3, -1, $anz6 );
	}

	$data = $data1.$data2.$data3;
 
	// Suche letzten pdf-Links
	$first_pos_pdf = strpos ($data, 'format=pdf');
	$last_pos_pdf = strrpos ($data, 'format=pdf');
	if (!is_numeric($first_pos_pdf) OR $first_pos_pdf < 0) $first_pos_pdf = 0;
	if ($first_pos_pdf > 200) $first_pos_pdf -= 200;
	if (!is_numeric($last_pos_pdf) OR $last_pos_pdf < 0) $last_pos_pdf = 0;
	if ($last_pos_pdf > 0) $last_pos_pdf += 10;
	$data1 = substr($data, 0, $first_pos_pdf);
	$data2 = substr($data, $first_pos_pdf, ($last_pos_pdf-$first_pos_pdf));
	$data3 = substr($data, $last_pos_pdf);
 
	// Alle anderen ersetzen - Suchmaschinenfreundliche URLs auf gerufener Seite: Nein
	$url_org	= JURI::base().'index.php\?option=com_clm&view=';
	$url_trans	= JURI::base()."index.php?option=com_clm_ext&view=clm_ext&source=$source_id&amp;ext_view=";
	$data1		= preg_replace ( '#'.$url_org.'#', $url_trans, $data1, -1, $anz11 );
	// Alle anderen ersetzen - Suchmaschinenfreundliche URLs auf gerufener Seite: Nein
	$url_org	= JURI::base().'index.php?option=com_clm&view=';
	$url_trans	= JURI::base()."index.php?option=com_clm_ext&view=clm_ext&source=$source_id&ext_view=";
	$data1		= preg_replace ( '#'.$url_org.'#', $url_trans, $data1, -1, $anz12 );
	// Alle anderen ersetzen - Suchmaschinenfreundliche URLs auf gerufener Seite: Ja und mod_rewrite Nein  (Landesseite)
	$url_org	= '#'.JURI::base().'index.php/component/clm/\?view=#';
	$url_trans	= JURI::base()."index.php?option=com_clm_ext&view=clm_ext&source=$source_id&ext_view=";
	$data1		= preg_replace ( $url_org, $url_trans, $data1, -1, $anz13 );
	// Alle anderen ersetzen - Suchmaschinenfreundliche URLs auf gerufener Seite: Ja und mod_rewrite Nein  (sbrp.de)
	$url_org	= '#'.JURI::base().'index.php/de/component/clm/\?view=#';             // und Mehrsprachigkeit mit Standard de              
	$url_trans	= JURI::base()."index.php?option=com_clm_ext&view=clm_ext&source=$source_id&ext_view=";
	$data1		= preg_replace ( $url_org, $url_trans, $data1, -1, $anz14 );
	// Alle anderen ersetzen - Suchmaschinenfreundliche URLs auf gerufener Seite: Ja und mod_rewrite Ja     (Dessau)
	$url_org	= '#'.JURI::base().'component/clm/\?view=#';
	$url_trans	= JURI::base()."index.php?option=com_clm_ext&view=clm_ext&source=$source_id&ext_view=";
	$data1		= preg_replace ( $url_org, $url_trans, $data1, -1, $anz15 );

	// Alle anderen ersetzen - Suchmaschinenfreundliche URLs auf gerufener Seite: Nein
	$url_org	= JURI::base().'index.php\?option=com_clm&view=';
	//$url_trans	= JURI::base()."index.php?option=com_clm_ext&view=clm_ext&url=$urla&amp;ext_view=";
	$url_trans	= JURI::base()."index.php?option=com_clm_ext&view=clm_ext&source=$source_id&amp;ext_view=";
	$data3		= preg_replace ( '#'.$url_org.'#', $url_trans, $data3, -1, $anz11 );
	// Alle anderen ersetzen - Suchmaschinenfreundliche URLs auf gerufener Seite: Nein
	$url_org	= JURI::base().'index.php?option=com_clm&view=';
	$url_trans	= JURI::base()."index.php?option=com_clm_ext&view=clm_ext&source=$source_id&ext_view=";
	$data3		= preg_replace ( '#'.$url_org.'#', $url_trans, $data3, -1, $anz12 );
	// Alle anderen ersetzen - Suchmaschinenfreundliche URLs auf gerufener Seite: Ja und mod_rewrite Nein  (Landesseite)
	$url_org	= '#'.JURI::base().'index.php/component/clm/\?view=#';
	$url_trans	= JURI::base()."index.php?option=com_clm_ext&view=clm_ext&source=$source_id&ext_view=";
	$data3		= preg_replace ( $url_org, $url_trans, $data3, -1, $anz13 );
	// Alle anderen ersetzen - Suchmaschinenfreundliche URLs auf gerufener Seite: Ja und mod_rewrite Nein  (sbrp.de)
	$url_org	= '#'.JURI::base().'index.php/de/component/clm/\?view=#';             // und Mehrsprachigkeit mit Standard de              
	$url_trans	= JURI::base()."index.php?option=com_clm_ext&view=clm_ext&source=$source_id&ext_view=";
	$data3		= preg_replace ( $url_org, $url_trans, $data3, -1, $anz14 );
	// Alle anderen ersetzen - Suchmaschinenfreundliche URLs auf gerufener Seite: Ja und mod_rewrite Ja     (Dessau)
	$url_org	= '#'.JURI::base().'component/clm/\?view=#';
	$url_trans	= JURI::base()."index.php?option=com_clm_ext&view=clm_ext&source=$source_id&ext_view=";
	$data3		= preg_replace ( $url_org, $url_trans, $data3, -1, $anz15 );
 	
	$data = $data1.$data2.$data3;

	// Bilderpfad ändern
	$url_org	= '#'.$ext_url.DS.'components'.DS.'com_clm'.DS.'images#';
	$url_trans	= JURI::base().'components'.DS.'com_clm_ext'.DS.'images';
	$data		= preg_replace ( $url_org, $url_trans, $data );

	// Hervorheben von ausgewählten Verein
	if ($keyword != '') {
	    $iwhile = 0; 
	    $pstart = 0;
		$span11  = '<span style="background-color:';
		$span12  = ' ! important;">';
		$span2   = '</span>';
	    while ($iwhile < 20 AND $pstart < strlen($data)) {
			$iwhile++;
		    $treffer = strpos ( $data, $keyword , $pstart );
			if ($treffer === false) break;
			$imax = 30; $i1 = 1; $i2 = 1;
			while ($i1 < $imax AND substr($data,$treffer-$i1,1) != '>') { //echo "<br>Rückwärts: ".substr($data,$treffer-$i1,1); 
				$i1++; }
			if ($i1 < $imax) $sstart = $treffer-$i1+1; else $sstart = false;
			while ($i2 < $imax AND substr($data,$treffer+$i2,1) != '<') { //echo "<br>Vorwärts: ".substr($data,$treffer+$i2,1); 
				$i2++; }
			if ($i2 < $imax) $sende = $treffer+$i2; else $sende = false;
			if ($sstart === false OR $sende === false) {
				$pstart = $treffer + strlen($keyword);
			} else {
				$data = substr($data,0,$sstart).$span11.$mcolor.$span12.substr($data,$sstart,$sende-$sstart).$span2.substr($data,$sende,strlen($data)-$sende);
				$pstart = $sende + strlen($span11) + strlen($span12) + strlen($mcolor) + strlen($span2) ;
			}
		}
	}

	// Hervorheben 2 von ausgewählten Verein
	if ($keyword2 != '') {
	    $iwhile = 0; 
	    $pstart = 0;
		$span11  = '<span style="background-color:';
		$span12  = ' ! important;">';
		$span2   = '</span>';
	    while ($iwhile < 20 AND $pstart < strlen($data)) {
			$iwhile++;
		    $treffer = strpos ( $data, $keyword2, $pstart );
			if ($treffer === false) break;
			$imax = 30; $i1 = 1; $i2 = 1;
			while ($i1 < $imax AND substr($data,$treffer-$i1,1) != '>') { //echo "<br>Rückwärts: ".substr($data,$treffer-$i1,1); 
				$i1++; }
			if ($i1 < $imax) $sstart = $treffer-$i1+1; else $sstart = false;
			while ($i2 < $imax AND substr($data,$treffer+$i2,1) != '<') { //echo "<br>Vorwärts: ".substr($data,$treffer+$i2,1); 
				$i2++; }
			if ($i2 < $imax) $sende = $treffer+$i2; else $sende = false;
			if ($sstart === false OR $sende === false) {
				$pstart = $treffer + strlen($keyword2);
			} else {
				$data = substr($data,0,$sstart).$span11.$mcolor2.$span12.substr($data,$sstart,$sende-$sstart).$span2.substr($data,$sende,strlen($data)-$sende);
				$pstart = $sende + strlen($span11) + strlen($span12) + strlen($mcolor2) + strlen($span2) ;
			}
		}
	}

	// Hervorheben 3 von ausgewählten Verein
	if ($keyword3 != '') {
	    $iwhile = 0; 
	    $pstart = 0;
		$span11  = '<span style="background-color:';
		$span12  = ' ! important;">';
		$span2   = '</span>';
	    while ($iwhile < 20 AND $pstart < strlen($data)) {
			$iwhile++;
		    $treffer = strpos ( $data, $keyword3, $pstart );
			if ($treffer === false) break;
			$imax = 30; $i1 = 1; $i2 = 1;
			while ($i1 < $imax AND substr($data,$treffer-$i1,1) != '>') { //echo "<br>Rückwärts: ".substr($data,$treffer-$i1,1); 
				$i1++; }
			if ($i1 < $imax) $sstart = $treffer-$i1+1; else $sstart = false;
			while ($i2 < $imax AND substr($data,$treffer+$i2,1) != '<') { //echo "<br>Vorwärts: ".substr($data,$treffer+$i2,1); 
				$i2++; }
			if ($i2 < $imax) $sende = $treffer+$i2; else $sende = false;
			if ($sstart === false OR $sende === false) {
				$pstart = $treffer + strlen($keyword3);
			} else {
				$data = substr($data,0,$sstart).$span11.$mcolor3.$span12.substr($data,$sstart,$sende-$sstart).$span2.substr($data,$sende,strlen($data)-$sende);
				$pstart = $sende + strlen($span11) + strlen($span12) + strlen($mcolor3) + strlen($span2) ;
			}
		}
	}

	// Hervorheben 4 von ausgewählten Verein
	if ($keyword4 != '') {
	    $iwhile = 0; 
	    $pstart = 0;
		$span11  = '<span style="color:';
		$span12  = ' ! important;">';
		$span2   = '</span>';
	    while ($iwhile < 20 AND $pstart < strlen($data)) {
			$iwhile++;
		    $treffer = strpos ( $data, $keyword4, $pstart );
			if ($treffer === false) break;
			$imax = 30; $i1 = 1; $i2 = 1;
			while ($i1 < $imax AND substr($data,$treffer-$i1,1) != '>') { //echo "<br>Rückwärts: ".substr($data,$treffer-$i1,1); 
				$i1++; }
			if ($i1 < $imax) $sstart = $treffer-$i1+1; else $sstart = false;
			while ($i2 < $imax AND substr($data,$treffer+$i2,1) != '<') { //echo "<br>Vorwärts: ".substr($data,$treffer+$i2,1); 
				$i2++; }
			if ($i2 < $imax) $sende = $treffer+$i2; else $sende = false;
			if ($sstart === false OR $sende === false) {
				$pstart = $treffer + strlen($keyword4);
			} else {
				$data = substr($data,0,$sstart).$span11.$mcolor4.$span12.substr($data,$sstart,$sende-$sstart).$span2.substr($data,$sende,strlen($data)-$sende);
				$pstart = $sende + strlen($span11) + strlen($span12) + strlen($mcolor4) + strlen($span2) ;
			}
		}
	}

	// Hervorheben 5 von ausgewählten Verein
	if ($keyword5 != '') {
	    $iwhile = 0; 
	    $pstart = 0;
		$span11  = '<span style="color:';
		$span12  = ' ! important;">';
		$span2   = '</span>';
	    while ($iwhile < 20 AND $pstart < strlen($data)) {
			$iwhile++;
		    $treffer = strpos ( $data, $keyword5, $pstart );
			if ($treffer === false) break;
			$imax = 30; $i1 = 1; $i2 = 1;
			while ($i1 < $imax AND substr($data,$treffer-$i1,1) != '>') { //echo "<br>Rückwärts: ".substr($data,$treffer-$i1,1); 
				$i1++; }
			if ($i1 < $imax) $sstart = $treffer-$i1+1; else $sstart = false;
			while ($i2 < $imax AND substr($data,$treffer+$i2,1) != '<') { //echo "<br>Vorwärts: ".substr($data,$treffer+$i2,1); 
				$i2++; }
			if ($i2 < $imax) $sende = $treffer+$i2; else $sende = false;
			if ($sstart === false OR $sende === false) {
				$pstart = $treffer + strlen($keyword5);
			} else {
				$data = substr($data,0,$sstart).$span11.$mcolor5.$span12.substr($data,$sstart,$sende-$sstart).$span2.substr($data,$sende,strlen($data)-$sende);
				$pstart = $sende + strlen($span11) + strlen($span12) + strlen($mcolor5) + strlen($span2) ;
			}
		}
	}

	// Hervorheben 6 von ausgewählten Verein
	if ($keyword6 != '') {
	    $iwhile = 0; 
	    $pstart = 0;
		$span11  = '<span style="color:';
		$span12  = ' ! important;">';
		$span2   = '</span>';
	    while ($iwhile < 20 AND $pstart < strlen($data)) {
			$iwhile++;
		    $treffer = strpos ( $data, $keyword6, $pstart );
			if ($treffer === false) break;
			$imax = 30; $i1 = 1; $i2 = 1;
			while ($i1 < $imax AND substr($data,$treffer-$i1,1) != '>') { //echo "<br>Rückwärts: ".substr($data,$treffer-$i1,1); 
				$i1++; }
			if ($i1 < $imax) $sstart = $treffer-$i1+1; else $sstart = false;
			while ($i2 < $imax AND substr($data,$treffer+$i2,1) != '<') { //echo "<br>Vorwärts: ".substr($data,$treffer+$i2,1); 
				$i2++; }
			if ($i2 < $imax) $sende = $treffer+$i2; else $sende = false;
			if ($sstart === false OR $sende === false) {
				$pstart = $treffer + strlen($keyword6);
			} else {
				$data = substr($data,0,$sstart).$span11.$mcolor6.$span12.substr($data,$sstart,$sende-$sstart).$span2.substr($data,$sende,strlen($data)-$sende);
				$pstart = $sende + strlen($span11) + strlen($span12) + strlen($mcolor6) + strlen($span2) ;
			}
		}
	}

// Daten anzeigen
echo $data;

?>
<br>

<hr>
<?php echo JText::_('END_NOTICE') ?><a href="<?php echo $ext_url;?>"><?php echo $ext_url1;?></a>
<?php } ?>
 