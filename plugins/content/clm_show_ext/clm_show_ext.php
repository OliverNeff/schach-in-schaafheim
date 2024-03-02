<?php
/**
 * @ Chess League Manager (CLM) Component 
 * @Copyright (C) 2008-2019 CLM Team.  All rights reserved
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.chessleaguemanager.de
*/
// kein direkter Zugriff über eine Url sondern nur über's Joomla-Framework
defined('_JEXEC') or die('Unerlaubter Zugriff');
// lade die JPlugin-Klasse, von der unsere eigene Plugin-Klasse abgeleitet wird
jimport('joomla.plugin.plugin');
// Rumpf unserer Plugin-Klasse
class PlgContentClm_show_ext extends JPlugin {
	function __construct(&$subject, $my_config) {
		if(!defined("DS")){define('DS', DIRECTORY_SEPARATOR);} // fix for Joomla 3.2
		$lang = JFactory::getLanguage();
		//$lang->load('plg_content_clm_show_ext', JPATH_ADMINISTRATOR);
		$lang->load('plg_content_clm_show_ext', JPATH_SITE . DS . 'plugins/content/clm_show_ext');
		parent::__construct($subject, $my_config);
	}

    function url_exists($url) {
        $a_url = parse_url($url);
        if (!isset($a_url['port'])) $a_url['port'] = 80;
        $errno = 0;
        $errstr = '';
        $timeout = 30;
        if(isset($a_url['host']) && $a_url['host']!=gethostbyname($a_url['host'])){
            $fid = fsockopen($a_url['host'], $a_url['port'], $errno, $errstr, $timeout);
            if (!$fid) return false;
            $page = isset($a_url['path'])  ?$a_url['path']:'';
            $page .= isset($a_url['query'])?'?'.$a_url['query']:'';
            fputs($fid, 'HEAD '.$page.' HTTP/1.0'."\r\n".'Host: '.$a_url['host']."\r\n\r\n");
            $head = fread($fid, 4096);
            fclose($fid);
            return preg_match('#^HTTP/.*\s+[200|302]+\s#i', $head);
        } else {
            return false;
        }
    }

	protected function get_css_style($style) {
		switch ($style) {
			case 1:
				return $style_css = "width:100% !important;";
			case 2:
				return $style_css = "width:auto; margin:0 auto 0 0;";
			case 3:
				return $style_css = "width:auto; margin:0 auto;";
			case 4:
				return $style_css = "width:auto; margin:0 0 0 auto;";
			case 5:
				return $style_css = "width:auto; float:left; margin-right: 8px;";
			case 6:
				return $style_css = "width:auto; float:right; margin-left: 8px;";
			default:
				return $style_css = "width:auto;";
		}
	}
	protected function check_highlighting($string) {
		if ($string == "") {
			return array(true, array());
		}
		$what = explode("!", $string);
		for ($i = 0;$i < count($what);$i++) {
			$what[$i] = explode("?", $what[$i]);
			if ((count($what[$i]) < 2) || (count($what[$i]) > 4) || $what[$i][0] == "" || !is_numeric($what[$i][1]) || $what[$i][1] < 0 || $what[$i][1] > 2) {
				return array(false);
			}
		}
		return array(true, $what);
	}
	protected function get_old_config($in) {
		$new = array();
		$old = explode(" ", $in);
		if (count($old) > 1) {
			$new[0] = $old[1]; // ID ist nun vorne
			$new[1] = $old[0]; // Jahr ist nun weiter hinten
			$new[2] = 0; // Style immer mit width:auto
			if (count($old) > 2) {
				$new[3] = $old[2]; // max_Aufsteiger
				if (count($old) > 3) {
					$new[4] = $old[3]; // min Aufsteiger
					
				}
			}
		} else {
			return "";
		}
		$out = "";
		for ($i = 0;$i < count($new);$i++) {
			if ($i != 0) {
				$out.= ":";
			}
			$out.= $new[$i];
		}
		return $out;
	}
	protected function mm_findItemWithArg($zeile, $tag) {
		$number = 0;
		$in = "";
		$before = strpos($zeile, '[' . $tag);
		if ($before === false) {
			return $zeile;
		}
		$tag_length = strlen($tag);
		$stop = false;
		do {
			$next = strpos($zeile, '[' . $tag, $before + $tag_length + 2);
			if ($next === false) {
				$stop = true;
			}
			$after = strpos($zeile, ']', $before + $tag_length + 2);
			if ($after !== false && ($stop || $after < $next)) {
				$my_config = substr($zeile, ($before + $tag_length + 2), $after - ($before + $tag_length + 2));
				$first = substr($zeile, 0, $before);
				$config_char = $zeile[$before + $tag_length + 1];
				if ($config_char == '_') {
					$zeile[$before + $tag_length + 1] = ' ';
				} else if ($config_char == '-') {
					$zeile[$before + $tag_length + 1] = ':';
				} else if (($config_char == ':') || ($config_char == ' ')) {
					$out = $this->mm_clmligen_print($my_config, $config_char == ' ', $number);
					$number++;
					if ($out[0]) { // Falls durch den Tag größere Abschnitte eingeschlossen sind werden diese wieder eingebunden
						$in.= $first . $out[1];
					} else {
//						$in.= $first . $out[1] . $my_config;
						// Fehlertextaufbereitung
						if (isset($out[2])) $out[1] .= ' - '.$out[2];
						$error_text = '
							<br>
							<table>
							<div class="title" style="border-style: solid; border-width: thin; width: 60%">Beim Plugin-Aufruf ist ein Fehler aufgetreten, ggf. informieren Sie den Seitenadministrator.</div>
							<div style="border-style: solid; border-width: thin; width: 60%; background-color: rgb(255, 255, 153);">'.$out[1].'</div>
							<div style="border-style: solid; border-width: thin; width: 60%; background-color:">Parameter: '.$my_config.'</div>
							</table>
							<br>';
							$in.= $first . $error_text;
					}
					$zeile = substr($zeile, $after + 1);
					if (!$stop) {
						$next = $next - ($after + 1);
					} // Position auf neuen String übertragen.
					
				}
			}
			$before = $next;
		}
		while (!$stop);
		return $in . $zeile;
	}
	protected function mm_clmligen_print($my_config, $old, $number) {
		if (!ini_get('allow_url_fopen')) {
			return array(false, JText::_("PLG_CLM_SHOW_ERR_FOPEN"));
		}
		if (!is_numeric($my_config)) {
			$my_config = explode(":", $my_config);
		} else {
			$my_config = array($my_config);
		}
		// Test Anzahl Parameter
		if (count($my_config) > 7 || count($my_config) < 2) {
			return array(false, JText::_("PLG_CLM_SHOW_ERR_TAG"));
		}
		// Test Letzter Parameter ist alphanumerisch -> letzten Parameter ignorieren
		if ((isset($my_config[3]) AND $my_config[3] == 14 AND count($my_config) > 5)
			OR !isset($my_config[3]) OR $my_config[3] != 14) {
			if (!is_numeric($my_config[count($my_config)-1])) {
				unset($my_config[count($my_config)-1]);
			}
		}
		// Test Source-ID - Pflichtangabe
		if (!is_numeric($my_config[0]) OR !ctype_digit($my_config[0]) OR $my_config[0] < 1 OR $my_config[0] > 3) {
			return array(false, JText::_("PLG_CLM_SHOW_ERR_SOURCE"));
		}
		$source_id = $my_config[0];
		// Test Liga-ID - Pflichtangabe
		if (!is_numeric($my_config[1]) OR !ctype_digit($my_config[1])) {
			return array(false, JText::_("PLG_CLM_SHOW_ERR_ID"));
		}
		$liga_id = $my_config[1];
		// Test Style-Parameter - Standard ist 0
		if (count($my_config) > 2) {
			if (!is_numeric($my_config[2]) || ($my_config[2] < 0) || ($my_config[2] > 6)) {
				return array(false, JText::_("PLG_CLM_SHOW_ERR_STYLE"));
			}
			$style = $my_config[2];
		} else {
			$style = 0;
		}
		// Test View - Standard ist 0 = Kreuztabelle
		if (count($my_config) > 3) {
			if (!is_numeric($my_config[3]) OR !ctype_digit($my_config[3]) OR ($my_config[3] > 4 AND $my_config[3] != 14)) {
				return array(false, JText::_("PLG_CLM_SHOW_ERR_VIEW"));
			}
			$view = $my_config[3];
		} else {
			$view = 0;
		}
		// Test Runde - Standard ist 0 (alle Runden z.B. bei Kreuztabelle) - Pflichtangabe > 0 bei Paarung
		if ($view < 5) {
			if (count($my_config) > 4) {
				if (!is_numeric($my_config[4]) OR !ctype_digit($my_config[4])) {
					return array(false, JText::_("PLG_CLM_SHOW_ERR_RUNDE"));
				}
				$runde = $my_config[4];
			} else {
				$runde = 0;
			}
		} else {						// view = 14
			if (count($my_config) < 5) {
					return array(false, JText::_("PLG_CLM_SHOW_ERR_CLUB"));
			}
			$runde = $my_config[4];
		}
		// Test Paar - Standard ist 0 - Pflichtangabe > 0 bei Paarung
		if (count($my_config) > 5 AND $view != 0) {
			if (!is_numeric($my_config[5]) OR !ctype_digit($my_config[5])) {
				return array(false, JText::_("PLG_CLM_SHOW_ERR_PAAR"));
			}
			$paar = $my_config[5];
		} else {
			$paar = 0;
		}
		// Test Durchgang - Standard ist 1
		if (count($my_config) > 6) {
			if (!is_numeric($my_config[6]) OR !ctype_digit($my_config[6])) {
				return array(false, JText::_("PLG_CLM_SHOW_ERR_DG"));
			}
			$dg = $my_config[6];
		} else {
			$dg = 1;
		}
		
		// Kombi-Tests der Parameter
		// Test Paarung
		if ($view == 3 AND ($runde == 0 OR $paar == 0)) {
				return array(false, JText::_("PLG_CLM_SHOW_ERR_LOG_PAAR"));
		}
		if (($view == 0 OR $view == 1 OR $view == 2 OR $view == 14) AND $paar != 0) {
				$paar = 0; 									// keine Fehlermeldung; Angabe wird nur ignoriert
		}
		if (($view == 0 OR $view == 1 OR $view == 2) AND $runde > 0 AND $dg == 0) {
				return array(false, JText::_("PLG_CLM_SHOW_ERR_LOG_DG"));
		}
		if ($view == 4 AND $runde == 0) {					// View Spielplan: parameter runde muss teilnehmer enthalten
				return array(false, JText::_("PLG_CLM_SHOW_ERR_LOG_PLAN"));
		}
		$highlighting[0] = false;

		$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		$domain = $_SERVER['HTTP_HOST'];
		$request = $_SERVER["REQUEST_URI"];
		$pos = strpos($request, '?');
		if ($pos !== false) $request = substr($request, 0, $pos);
		$request = str_replace ( "/index.php", "", $request);
		$source = '';
		if ($source_id == 0) $source = $protocol.$domain.$request;			// neu!!!!!!!!!!!!!!																					
		//if ($source_id == 0) $source = JPATH_SITE;			// neu!!!!!!!!!!!!!!																					
		else {
			if ($source_id == 1) $source = $this->params->get('source1', '');
			if ($source_id == 2) $source = $this->params->get('source2', '');
			if ($source_id == 3) $source = $this->params->get('source3', '');
		}		
		if (substr($source, (strlen($source) - 1), 1) == '/') $source = substr($source, 0, (strlen($source) - 1));
		$url = $source.'/components/com_clm/clm/xml_output.php';
		
		$url .='?lid=' . $liga_id.'&plgview='.$view;
		$url .= '&runde='.$runde.'&paar='.$paar.'&dg='.$dg;

		if (strlen($url) > 150 OR strlen($url) < 10 OR substr_count($url, '?') != 1 OR $this->url_exists($url) === false)
				return array(false, JText::_("PLG_CLM_SHOW_ERR_URL"), $url);
		if (!$html = file_get_contents($url)) {
			$url0 = $source.'/';
			if ($this->url_exists ( $url0 ) )
				return array(false, JText::_("PLG_CLM_SHOW_ERR_VERSION"));
			else
				return array(false, JText::_("PLG_CLM_SHOW_ERR_CONNECTION"));
		}

		if (!$xml = new SimpleXMLElement($html)) {
			foreach (libxml_get_errors() as $error) {
				echo "<br>errror:"; var_dump($error);
			}
			libxml_clear_errors();
		}
		
		if (isset($xml->error)) {
//				$error_text = 'PLG_CLM_SHOW_ERR_NO_TOURNAMENT';
				return array(false, JText::_($xml->error));
		}

// Aufbereitung einzelner Liga-Details
		if (isset($xml->sname) AND $xml->sname != "") {
			$season = htmlentities($xml->sname);
		} else {
			$season = "";
		}
		if (!isset($xml->sid)) $sid = 0;
		else {
			$var_xml = htmlentities($xml->sid);
			if (is_numeric($var_xml) AND ctype_digit($var_xml)) $sid = $var_xml;
			else $sid = 0;
		}
		if (!isset($xml->ab)) $ab = 0;
		else {
			$var_xml = htmlentities($xml->ab);
			if (is_numeric($var_xml) AND ctype_digit($var_xml)) $ab = $var_xml;
			else $ab = 0;
		}
		if (!isset($xml->ab_evtl)) $ab_evtl = 0;
		else {
			$var_xml = htmlentities($xml->ab_evtl);
			if (is_numeric($var_xml) AND ctype_digit($var_xml)) $ab_evtl = $var_xml;
			else $ab_evtl = 0;
		}
		if (!isset($xml->auf)) $auf = 0;
		else {
			$var_xml = htmlentities($xml->auf);
			if (is_numeric($var_xml) AND ctype_digit($var_xml)) $auf = $var_xml;
			else $auf = 0;
		}
		if (!isset($xml->auf_evtl)) $auf_evtl = 0;
		else {
			$var_xml = htmlentities($xml->auf_evtl);
			if (is_numeric($var_xml) AND ctype_digit($var_xml)) $auf_evtl = $var_xml;
			else $auf_evtl = 0;
		}

		$min_auf = $auf;
		$max_auf = $auf + $auf_evtl;
		$min_ab = $ab;
		$max_ab = $ab + $ab_evtl;

		require_once (JPATH_SITE . DS . 'plugins/content/clm_show_ext' . DS . 'css_path.php');
		//require_once (JPATH_SITE . DS . 'components/com_clm/includes' . DS . 'css_path.php');
		$html = '<div id="clm">';
		
// View ranngliste -----------------------------------------------------------------------------------------------------------------
		if ($view == 0 OR $view == 1) { 
		$html .= '
<div class="clm"><div id="rangliste"><div class="plg_clm_show_ext">
<table cellpadding="0" cellspacing="0" class="rangliste" style="' . $this->get_css_style($style) . '">
<tr>
	<th class="rang"><div>' . JText::_('RANG') . '</div></th>
	';

		// einfache Runde oder doppelrundig
		$d = 0;
		foreach ($xml->rangliste->teams as $oneTeam) {
			foreach ($oneTeam->kreuzBody->e as $e) {
				if ($e == "**") {
					$d = 1;
				} 
				if ($e->eHR == "**") {
					$d = 2;
				}
				if ($d > 0) break;
			}
			if ($d > 0) break;
		}
		if ($season != "") {
			$html.= '<th class="team"><div><a target="_blank" href="'.$source.'/index.php/component/clm/?view=rangliste&saison='.$sid.'&liga='.$liga_id.'">' . $xml->tname . " - " . $season . '</a></div></th>';
		} else {
			$html.= '<th class="team"><div><a target="_blank" href="'.$source.'/index.php/component/clm/?view=rangliste&liga='.$liga_id.'">' . $xml->tname  . '</a></div></th>';
		}
		$team = 0;
		if ($view == 0) { 
			foreach ($xml->kreuzHeader->eH as $eH) {
				$html.= '<th class="rnd"><div>' . $eH . '</div></th>';
				$team++;
			}
			if ($d == 2) {
				foreach ($xml->kreuzHeader->eH as $eH) {
					$html.= '<th class="rnd"><div>' . $eH . '</div></th>';
				}
			}
		}
		if ($view == 1) { 
			foreach ($xml->kreuzHeader->eH as $eH) {
				$team++;
			}
			$html.= '<th class="rnd"><div>' . JText::_('TABELLE_GAMES_PLAYED') . '</div></th>';
			$html.= '<th class="rnd"><div>' . JText::_('TABELLE_WINS') . '</div></th>';
			$html.= '<th class="rnd"><div>' . JText::_('TABELLE_DRAW') . '</div></th>';
			$html.= '<th class="rnd"><div>' . JText::_('TABELLE_LOST') . '</div></th>';
		}
		// check if there are enough teams
		if ($team == 0) {
			return array(false, JText::_("PLG_CLM_SHOW_ERR_BAD"));
		}
		if ($team < $max_ab + $max_auf) {
			return array(false, JText::_("PLG_CLM_SHOW_ERR_COUNT"));
		}
		$html.= '			
			<th class="mp"><div>' . JText::_('MP') . '</div></th>
			<th class="bp"><div>' . JText::_('BP') . '</div></th>
					</tr>';

		$where = 0;
		$min_ab = $team - $min_ab;
		$max_ab = $team - $max_ab;
		foreach ($xml->rangliste->teams as $oneTeam) {
		$oneTeam->team = str_replace("  ", " ", $oneTeam->team); // fix, nrw use bad strings
			if ($where % 2 == 0) {
				$html.= '<tr class="zeile1">';
			} else {
				$html.= '<tr class="zeile2">';
			}
			if ($where < $min_auf) {
				$html.= '<td class="rang_auf"> ';
			} else if ($where < $max_auf) {
				$html.= '<td class="rang_auf_evtl"> ';
			} else if ($where >= $min_ab) {
				$html.= '<td class="rang_ab"> ';
			} else if ($where >= $max_ab) {
				$html.= '<td class="rang_ab_evtl"> ';
			} else {
				$html.= '<td class="rang"> ';
			}
			$html.= $oneTeam->platz . '</td>
			';
			$exist = false;
			if ($highlighting[0]) {
				for ($i = 0;$i < count($highlighting[1]);$i++) {
					if ($highlighting[1][$i][0] == $oneTeam->team) {
						if (count($highlighting[1][$i]) == 3) {
							$html.= '<td style="color:' . htmlentities($highlighting[1][$i][2], ENT_QUOTES, "UTF-8") . ';" class="team">';
						} else {
							$html.= '<td class="team">';
						}
						if ($highlighting[1][$i][1] == 1) {
							$html.= '<b>' . $oneTeam->team . '</b></td>
';
						} else {
							$html.= $oneTeam->team . '</td>
';
						}
						$exist = true;
						break;
					}
				}
			}
			if (!$exist) {
				$html.= '<td class="team">' . $oneTeam->team . '</td>';
			}
			if ($view == 0) {
				if ($d == 1) {
					foreach ($oneTeam->kreuzBody->e as $e) {
						if ($e == "**") {
							$html.= '<td class="trenner">X</td>';
						} else {
							$html.= '<td>' . $e . '</td>';
						}
					}
				}
				if ($d ==2) {
					foreach ($oneTeam->kreuzBody->e as $e) {
						if ($e->eHR == "**") {
							$html.= '<td class="trenner">X</td>';
						} else {
							$html.= '<td>' . $e->eHR . '</td>';
						}
					}
					foreach ($oneTeam->kreuzBody->e as $e) {
						if ($e->eHR == "**") {
							$html.= '<td class="trenner">X</td>';
						} else {
							$html.= '<td>' . $e->eRR . '</td>';
						}
					}
				}
			}
			if ($view == 1) {
				$html .= '<td>' . $oneTeam->count_G . '</td>';
				$html .= '<td>' . $oneTeam->count_S . '</td>';
				$html .= '<td>' . $oneTeam->count_R . '</td>';
				$html .= '<td>' . $oneTeam->count_V . '</td>';
			}
			$html.= '<td class="mp">' . $oneTeam->mp . '</td>
<td class="bp">' . $oneTeam->bp . '</td>
</tr>';
			$where++;
		}
		
		$html.= '
</table>';
		if ($this->params->get('ajax', 1) == 1) {
			$html.= '<div style="' . $this->get_css_style($style) . '" id="plg_clm_show_' . $number . '"></div>';
		}
		$html.= '
</div></div></div>
';
		}
		
// View paarungsliste -----------------------------------------------------------------------------------------------------------------
		if ($view == 2) { 
		$spzahl = 8;
		$html .= '
<div class="clm"><div id="paarungsliste"><div class="plg_clm_show_ext">
<table cellpadding="0" cellspacing="0" class="paarungsliste" style="' . $this->get_css_style($style) . '">
<tr>
	';

		if ($season != "") {
			$html.= '<th class="team" colspan="'.$spzahl.'"><div><a target="_blank" href="'.$source.'/index.php/component/clm/?view=paarungsliste&saison='.$sid.'&liga='.$liga_id.'">' . $xml->tname . " - " . $season . '</a></div></th>';
		} else {
			$html.= '<th class="team" colspan="'.$spzahl.'"><div><a target="_blank" href="'.$source.'/index.php/component/clm/?view=paarungsliste&liga='.$liga_id.'">' . $xml->tname  . '</a></div></th>';
		}
		$html.= '</tr>';
		$where = 0;
		$z1 = 0;
		foreach ($xml->paarungsliste->paarung as $onePaar) {
			if ($z1 == 0) {
				$v = $onePaar;
				//$v->dg = $onePaar->dg;
				//$v->runde = $onePaar->runde;
			}
				  
			if ($z1 == 0 OR (string)$v->dg != (string)$onePaar->dg OR (string)$v->runde != (string)$onePaar->runde) {
			//} else {
				$v->dg = $onePaar->dg;
				$v->runde = $onePaar->runde;
				if ($onePaar->rdatum > '1970-01-01') {
					$rdatum = JHTML::_('date',  $onePaar->rdatum, JText::_('DATE_FORMAT_CLM_F')); 
					if ($onePaar->startzeit != '00:00:00') $rdatum .= '  '.substr($onePaar->startzeit,0,5);
				} else {
					$rdatum = '';
				}
				$html.= '<tr>';
				$html.= '<td class="heim" colspan="5"><div>' . $rdatum . '</div></td>';
				$html.= '<td class="erg" colspan="3"><div>' . $onePaar->rname . '</div></td>';
				$html.= '</tr>';
				$html.= '<tr>';
				$html.= '<th class="rnd"><div>' . JText::_('PAAR') . '</div></th>';
				$html.= '<th class="rnd"><div>' . JText::_('TLN') . '</div></th>';
				$html.= '<th class="rnd"><div>' . JText::_('HOME') . '</div></th>';
				$html.= '<th class="rnd"><div>' . JText::_('DWZ') . '</div></th>';
				$html.= '<th class="erg"><div>' . JText::_('RESULT') . '</div></th>';
				$html.= '<th class="rnd"><div>' . JText::_('TLN') . '</div></th>';
				$html.= '<th class="rnd"><div>' . JText::_('GUEST') . '</div></th>';
				$html.= '<th class="rnd"><div>' . JText::_('DWZ') . '</div></th>';
				$html.= '</tr>';
				$where = 0;
			}
			if ($where % 2 == 0) {
				$html.= '<tr class="zeile1">';
			} else {
				$html.= '<tr class="zeile2">';
			}
			$html.= '<td class="paar"> '. $onePaar->paar . '</td>';
			$html.= '<td class="tln"> '. $onePaar->tln_nr . '</td>';
			$html.= '<td class="heim"> '. $onePaar->hname . '</td>';
			$html.= '<td class="dwz"> '. $onePaar->dwz . '</td>';
			$html.= '<td class="erg"> '. $onePaar->brettpunkte.':'.$onePaar->gbrettpunkte . '</td>';
			$html.= '<td class="tln"> '. $onePaar->gtln . '</td>';
			$html.= '<td class="gast"> '. $onePaar->gname . '</td>';
			$html.= '<td class="dwz"> '. $onePaar->gdwz . '</td>';
			$html.= '</tr>';
			if ($onePaar->comment != "") { 
				if ($where % 2 == 0) {
					$html.= '<tr class="zeile1">';
				} else {
					$html.= '<tr class="zeile2">';
				}
				$html.= '<td class="paar"> '. $onePaar->paar . '</td>';
				$html.= '<td colspan="7"> '. $onePaar->comment . '</td>';
				$html.= '</tr>';
			}
			$where++;
			$z1++;
		}
		
		$html.= '
</table>';
		if ($this->params->get('ajax', 1) == 1) {
			$html.= '<div style="' . $this->get_css_style($style) . '" id="plg_clm_show_' . $number . '"></div>';
		}

		$html.= '
</div></div></div>
';
		}
		
// View paarung -----------------------------------------------------------------------------------------------------------------
		if ($view == 3) { 
		$html .= '
<div class="clm"><div id="paarung"><div class="plg_clm_show_ext">
<table cellpadding="0" cellspacing="0" class="runde" style="' . $this->get_css_style($style) . '">
<tr>
	';
		$html .= '
	    <tr>
		<td colspan="4">
        <div class="run_titel" style="text-align: left">
			<a target="_blank" href="'.$source.'/index.php/component/clm/?view=rangliste&saison='.$sid.'&liga='.$liga_id.'">' . $xml->tname . " - " . $season . '</a>
        </div>
		</td>
		<td colspan="2">
        <div class="run_titel">
            <a target="_blank" href="'.$source.'/index.php/component/clm/?view=runde&amp;liga='.$liga_id.'&amp;saison='.$sid.'&amp;runde='.$runde.'&amp;dg='.$dg.'&amp;Itemid=101">'.$xml->rname.'</a>
        </div>
		</td></tr>
';
	
		$html.= '
    <tr>
        <th class="paarung2" colspan="2">
         <div class=paarung></div>
                '.$xml->hname.'
                </th>
        <th class="paarung">'.$xml->hdwz.'</th>
        <th class="paarung">'.$xml->hbp.' : '.$xml->gbp.'</th>
        <th class="paarung" colspan="1">
                '.$xml->gname.'
                </th>
        <th class="paarung">'.$xml->gdwz.'</th>
    </tr>
';

		foreach ($xml->einzelBody->brett as $ebrett) {
			if ($ebrett->brett % 2 != 0) {
				$html.= '<tr class="zeile1">';
			} else {
				$html.= '<tr class="zeile2">';
			}

		$html.= '
    <td class="paarung"><div>'.$ebrett->brett.'</div></td>
	<td class="paarung2" colspan ="1"><div>'.$ebrett->hname.'</div></td>
	<td class="paarung"><div style="text-align: center">'.$ebrett->hdwz.'</div></td>
        		
	<td class="paarung"><div style="text-align: center"><b>'.$ebrett->erg_text.'</b></div></td>			
    <td class="paarung2" colspan ="1"><div>'.$ebrett->gname.'</div></td>
    <td class="paarung"><div style="text-align: center">'.$ebrett->gdwz.'</div></td>
    </tr>
';
		}
		
		$html.= '
</table>';
		if ($this->params->get('ajax', 1) == 1) {
			$html.= '<div style="' . $this->get_css_style($style) . '" id="plg_clm_show_' . $number . '"></div>';
		}
		$html.= '
</div></div></div>
';
		}
		
// View spielplan -----------------------------------------------------------------------------------------------------------------
		if ($view == 4) { 
		$teilnehmer = $runde;
		$spzahl = 6;
		$html .= '
<div class="clm"><div id="paarungsliste"><div class="plg_clm_show_ext">
<table cellpadding="0" cellspacing="0" class="paarungsliste" style="' . $this->get_css_style($style) . '">
<tr>
	';
		// Suchen Mannschaftsname
		$team_name = '';
		foreach ($xml->paarungsliste->paarung as $onePaar) {
			if ($onePaar->tln_nr == $teilnehmer) $team_name = $onePaar->hname;
			if ($onePaar->gtln == $teilnehmer) $team_name = $onePaar->gname;
			if ($team_name != '') break;
		}
		if ($season != "") {
			$html.= '<th class="team" colspan="'.$spzahl.'"><div><a target="_blank" href="'.$source.'/index.php/component/clm/?view=paarungsliste&saison='.$sid.'&liga='.$liga_id.'">' .$team_name." - ".$xml->tname." - ".$season . '</a></div></th>';
		} else {
			$html.= '<th class="team" colspan="'.$spzahl.'"><div><a target="_blank" href="'.$source.'/index.php/component/clm/?view=paarungsliste&liga='.$liga_id.'">' .$team_name." - ".$xml->tname. '</a></div></th>';
		}
		$html.= '</tr>';
		$where = 0;
		$z1 = 0;
		foreach ($xml->paarungsliste->paarung as $onePaar) {
			if ($onePaar->tln_nr != $teilnehmer AND $onePaar->gtln != $teilnehmer) continue;
				  
			if ($z1 == 0) {
				$html.= '<tr>';
				$html.= '<th class="rnd"><div>' . JText::_('FIXTURE_DATE') . '</div></th>';
				$html.= '<th class="rnd"><div>' . JText::_('DG') . '</div></th>';
				$html.= '<th class="rnd"><div>' . JText::_('ROUND') . '</div></th>';
				$html.= '<th class="rnd"><div>' . JText::_('HOME') . '</div></th>';
				$html.= '<th class="erg"><div>' . JText::_('RESULT') . '</div></th>';
				$html.= '<th class="rnd"><div>' . JText::_('GUEST') . '</div></th>';
				$html.= '</tr>';
			} 
			if ($onePaar->rdatum > '1970-01-01') {
				//$rdatum = JHTML::_('date',  $onePaar->rdatum, JText::_('DATE_FORMAT_CLM_F')); 
				$rdatum = JHTML::_('date',  $onePaar->rdatum, "d M Y"); 
				if ($onePaar->startzeit != '00:00:00') $rdatum .= '  '.substr($onePaar->startzeit,0,5);
			} else {
				$rdatum = '';
			}
			if ($where % 2 == 0) {
				$html.= '<tr class="zeile1">';
			} else {
				$html.= '<tr class="zeile2">';
			}
			$html.= '<td class="heim"> ' . $rdatum . '</td>';
			$html.= '<td class="tln"> '. $onePaar->dg . '</td>';
			$html.= '<td class="heim">' . $onePaar->rname . '</td>';
			$html.= '<td class="heim"> '. $onePaar->hname . '</td>';
			$html.= '<td class="erg"> '. $onePaar->brettpunkte.':'.$onePaar->gbrettpunkte . '</td>';
			$html.= '<td class="gast"> '. $onePaar->gname . '</td>';
			$html.= '</tr>';
			if ($onePaar->comment != "") { 
				if ($where % 2 == 0) {
					$html.= '<tr class="zeile1">';
				} else {
					$html.= '<tr class="zeile2">';
				}
				$html.= '<td colspan="6"> '. $onePaar->comment . '</td>';
				$html.= '</tr>';
			}
			$where++;
			$z1++;
		}
		
		$html.= '
</table>';
		if ($this->params->get('ajax', 1) == 1) {
			$html.= '<div style="' . $this->get_css_style($style) . '" id="plg_clm_show_' . $number . '"></div>';
		}

		$html.= '
</div></div></div>
';
		}
		
// View spielplan verein -----------------------------------------------------------------------------------------------------------
		if ($view == 14) { 
		$club = $runde;
		$spzahl = 7;
		$html .= '
<div class="clm"><div id="paarungsliste"><div class="plg_clm_show_ext">
<table cellpadding="0" cellspacing="0" class="paarungsliste" style="' . $this->get_css_style($style) . '">
<tr>
	';
		$html.= '<th class="team" colspan="'.$spzahl.'"><div><a target="_blank" href="'.$source.'/index.php/component/clm/?view=schedule&season='.$sid.'&club='.$club.'">' .$xml->cname." - ".$season . '</a></div></th>';
		$html.= '</tr>';
		$where = 0;
		$z1 = 0;
		foreach ($xml->paarungsliste->paarung as $onePaar) {
			//if ($onePaar->tln_nr != $teilnehmer AND $onePaar->gtln != $teilnehmer) continue;
				  
			if ($z1 == 0) {
				$html.= '<tr>';
				$html.= '<th class="rnd"><div>' . JText::_('FIXTURE_DATE') . '</div></th>';
				$html.= '<th class="rnd"><div>' . JText::_('LEAGUE') . '</div></th>';
				$html.= '<th class="rnd"><div>' . JText::_('DG') . '</div></th>';
				$html.= '<th class="rnd"><div>' . JText::_('ROUND') . '</div></th>';
				$html.= '<th class="rnd"><div>' . JText::_('HOME') . '</div></th>';
				$html.= '<th class="erg"><div>' . JText::_('RESULT') . '</div></th>';
				$html.= '<th class="rnd"><div>' . JText::_('GUEST') . '</div></th>';
				$html.= '</tr>';
			} 
			if ($onePaar->rdatum > '1970-01-01') {
				$rdatum = JHTML::_('date',  $onePaar->rdatum, "d M Y"); 
				if ($onePaar->startzeit != '00:00:00') $rdatum .= '  '.substr($onePaar->startzeit,0,5);
			} else {
				$rdatum = '';
			}
			if ($where % 2 == 0) {
				$html.= '<tr class="zeile1">';
			} else {
				$html.= '<tr class="zeile2">';
			}
			$html.= '<td class="heim"> ' . $rdatum . '</td>';
			$html.= '<td class="heim"> ' . $onePaar->lname . '</td>';
			$html.= '<td class="tln"> '. $onePaar->dg . '</td>';
			$html.= '<td class="heim">' . $onePaar->rname . '</td>';
			$html.= '<td class="heim"> '. $onePaar->hname . '</td>';
			$html.= '<td class="erg"> '. $onePaar->brettpunkte.':'.$onePaar->gbrettpunkte . '</td>';
			$html.= '<td class="gast"> '. $onePaar->gname . '</td>';
			$html.= '</tr>';
			if ($onePaar->comment != "") { 
				if ($where % 2 == 0) {
					$html.= '<tr class="zeile1">';
				} else {
					$html.= '<tr class="zeile2">';
				}
				$html.= '<td colspan="6"> '. $onePaar->comment . '</td>';
				$html.= '</tr>';
			}
			$where++;
			$z1++;
		}
		
		$html.= '
</table>';
		if ($this->params->get('ajax', 1) == 1) {
			$html.= '<div style="' . $this->get_css_style($style) . '" id="plg_clm_show_' . $number . '"></div>';
		}

		$html.= '
</div></div></div>
';
		}
				
		
		$html .= '</div>';
		return array(true, $html);
	}
	function onContentPrepare($context, $row, $params, $page = 0) {
		$this->renderTS($row, $params, $page = 0);
	}
	function renderTS($article, $params, $limitstart) {
		$article->text = $this->mm_findItemWithArg($article->text, "showCLMData");
		return true;
	}
}
