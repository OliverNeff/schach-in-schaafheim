<?php
/**********************************************************************************
 * @package Embed Chessboard plugin for joomla 1.6 or later
 * @version 3.04.00
 * @copyright copyright (c) 2009-2019 Paolo Casaschi
 * @license GNU General Public License version 2
 * @author: http://pgn4web.casaschi.net
 * @tutorial: http://pgn4web-project.casaschi.net/wiki/User_Notes_joomla/
 *
 * @history:
 * 1.00: Initial experimental version for joomla 1.6 based on pgn4web version 1.96
 * 1.01: Added .sys.ini language file
 * 1.02: Added custom jscolor field as color picker for the plugin admin page
 * 1.03: Upgraded pgn4web to 1.97
 * 1.04: Upgraded pgn4web to 1.98
 * 1.05: Upgraded pgn4web to 2.02 with improved PGN error handling
 * 1.06: Upgraded pgn4web to 2.03
 * 1.07: Upgraded pgn4web to 2.04
 * 1.08: added rawurlencode() to url parameters and upgraded pgn4web to 2.05
 * 1.09: improved board.html URL detection
 * 1.10: added extendedOptions switch to the [pgn] tag and upgraded pgn4web to 2.06
 * 1.11: upgraded pgn4web to 2.07, inlcuding Chess960 support
 *       added "one-click" extension update support
 * 1.12: upgraded pgn4web to 2.08, fixing a bug in the square highlight code
 * 1.13: upgraded pgn4web to 2.09, fixing a bug with IE
 * 1.14: upgraded pgn4web to 2.10 and minor bug fix
 * 1.15: upgraded pgn4web to 2.11 and minor bug fix
 * 1.16: upgraded pgn4web to 2.12
 * 1.17: enhanced frame height management and upgraded pgn4web to 2.13
 * 1.18: upgraded pgn4web to 2.14
 * 1.19: upgraded pgn4web to 2.15
 * 1.20: upgraded pgn4web to 2.16
 * 1.21: upgraded pgn4web to 2.17
 * 1.22: upgraded pgn4web to 2.18
 * 1.23: upgraded pgn4web to 2.21
 * 1.24: upgraded pgn4web to 2.22
 * 1.25: upgraded pgn4web to 2.23
 * 1.26: upgraded pgn4web to 2.24
 * 2.01: plugin now supports joomla 1.6 or later, legacy version available for joomla 1.5
 * 2.02: renamed automatic update url
 * 2.03: upgraded pgn4web to 2.25
 * 2.04: upgraded pgn4web to 2.26
 * 2.05: upgraded pgn4web to 2.27
 * 2.06: upgraded pgn4web to 2.29
 * 2.07: minor bug fixes
 * 2.08: upgraded pgn4web to 2.31
 * 2.09: upgraded pgn4web to 2.32
 * 2.10: minor bug fixes and upgraded pgn4web to 2.32
 * 2.11: added undocumented global extended options setting and upgraded pgn4web to 2.34
 * 2.12: upgraded pgn4web to 2.35
 * 2.13: minor bug fix for IE9 and upgraded pgn4web to 2.36
 * 2.14: minor bug fix for IE9 and upgraded pgn4web to 2.37
 * 2.15: minor bug fix for IE9 and upgraded pgn4web to 2.38
 * 2.16: minor bug fix for IE9 and upgraded pgn4web to 2.39
 * 2.17: upgraded pgn4web to 2.40
 * 2.18: upgraded pgn4web to 2.41
 * 2.19: upgraded pgn4web to 2.42
 * 2.20: upgraded pgn4web to 2.43
 * 2.21: upgraded pgn4web to 2.46 with variations support
 * 2.22: upgraded pgn4web to 2.47
 * 2.23: upgraded pgn4web to 2.48
 * 2.24: upgraded pgn4web to 2.49
 * 2.25: upgraded pgn4web to 2.51
 * 2.26: upgraded pgn4web to 2.52
 * 2.27: upgraded pgn4web to 2.53
 * 2.28: upgraded pgn4web to 2.54
 * 2.29: upgraded pgn4web to 2.55 and minor bug fix
 * 2.30: upgraded pgn4web to 2.56
 * 2.31: upgraded pgn4web to 2.57
 * 2.32: minor fix for joomla 3.0 compatibility
 * 2.33: minor improvements
 * 2.34: upgraded pgn4web to 2.58
 * 2.35: upgraded pgn4web to 2.59
 * 2.36: added [pgn4web] tag and upgraded pgn4web to 2.60
 * 2.37: minor fix
 * 2.38: upgraded pgn4web to 2.61
 * 2.39: minor fix
 * 2.40: upgraded pgn4web to 2.62
 * 2.41: upgraded pgn4web to 2.63
 * 2.42: upgraded pgn4web to 2.64
 * 2.43: upgraded pgn4web to 2.65
 * 2.44: upgraded pgn4web to 2.66
 * 2.45: upgraded pgn4web to 2.67
 * 2.46: upgraded pgn4web to 2.68
 * 2.47: upgraded pgn4web to 2.69
 * 2.48: upgraded pgn4web to 2.70
 * 2.49: upgraded pgn4web to 2.71
 * 2.50: upgraded pgn4web to 2.72
 * 2.51: upgraded pgn4web to 2.73
 * 2.52: upgraded pgn4web to 2.74
 * 2.75.00: changed version numbering scheme and upgraded pgn4web to 2.75
 * 2.76.00: upgraded pgn4web to 2.76
 * 2.77.00: upgraded pgn4web to 2.77 with improved chess informant style game text
 * 2.78.00: upgraded pgn4web to 2.78
 * 2.79.00: upgraded pgn4web to 2.79
 * 2.79.01: minor fix
 * 2.80.00: added post-install message, minor fix and upgraded pgn4web to 2.80
 * 2.81.00: upgraded pgn4web to 2.81
 * 2.81.01: changed update server url to avoid authentication requirement
 * 2.82.00: upgraded pgn4web to 2.82
 * 2.83.00: upgraded pgn4web to 2.83
 * 2.84.00: upgraded pgn4web to 2.84
 * 2.85.00: minor fix and upgraded pgn4web to 2.85
 * 2.86.00: upgraded pgn4web to 2.86
 * 2.87.00: upgraded pgn4web to 2.87
 * 2.88.00: upgraded pgn4web to 2.88
 * 2.89.00: upgraded pgn4web to 2.89
 * 2.89.01: moving repository and autoupdate from joomlacode.org to sourceforge.net
 * 2.89.02: testing autoupdate from sourceforge.net
 * 2.90.00: upgraded pgn4web to 2.90
 * 2.90.01: moving repository and autoupdate from /p/joec to /p/pgn4web/joec
 * 2.90.02: testing autoupdate from /p/pgn4web/joec
 * 2.91.00: upgraded pgn4web to 2.91
 * 2.92.00: upgraded pgn4web to 2.92
 * 2.93.00: upgraded pgn4web to 2.93
 * 2.94.00: upgraded pgn4web to 2.94
 * 2.95.00: upgraded pgn4web to 2.95
 * 2.96.00: upgraded pgn4web to 2.96
 * 3.00.00: upgraded pgn4web to 3.00
 * 3.01.00: upgraded pgn4web to 3.01
 * 3.02.00: upgraded pgn4web to 3.02
 * 3.03.00: upgraded pgn4web to 3.03
 * 3.04.00: upgraded pgn4web to 3.04
 **********************************************************************************/

// No direct access allowed to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

// Import Joomla! Plugin library file
jimport('joomla.event.plugin');

//The Content plugin Loadmodule
class plgContentEmbedchessboard extends JPlugin {
	/*
	 * Plugin to embed a javascript chessboard in joomla articles to replay chess games.
	 *
	 * Usage: [pgn parameter=value ...] chess games notation in PGN format [/pgn]
	 *
	 * Tag parameters:
	 *   layout=horizontal|vertical
	 *   height=auto|'number'
	 *   showMoves=figurine|text|puzzle|hidden
	 *   initialGame=first|last|random|'number'
	 *   initialVariation='number'
	 *   initialHalfmove=start|end|random|comment|'number'
	 *   autoplayMode=game|loop|none
	 *
	 * Example: [pgn autoplayMode=none initialHalfmove=end] e4 e6 d4 d5 [/pgn]
	 *
	 */

	// onContentPrepare, meaning the plugin is rendered at the first stage in preparing content for output
	function onContentPrepare($context, &$row, &$params, $page = 0) {

		// check whether plugin has been unpublished
		if ( !$this->params->get( 'enabled', 1 ) ) { return true; }

		// expression to search for
		$regex = "#\[(pgn|pgn4web)(\b.*?)?\](.*?)\[/\\1\]#s";

		// find all instances of plugin and put in $matches
		preg_match_all( $regex, $row->text, $matches );

		// Number of plugins
		$count = count( $matches[0] );

		// Read plugin parameters
		$hl_site = trim($this->params->get('horizontalLayout'));
		if (strlen($hl_site) == 0) { $hl_site = "t"; }
		$height_site = trim($this->params->get('height'));
		if (strlen($height_site) == 0) { $height_site = "auto"; }

		$bch = trim($this->params->get('backgroundColor'));
		if (strlen($bch) == 0) { $bch = "F6F6F6"; }
		$lch = trim($this->params->get('lightColor'));
		if (strlen($lch) == 0) { $lch = "F6F6F6"; }
		$dch = trim($this->params->get('darkColor'));
		if (strlen($dch) == 0) { $dch = "E0E0E0"; }
		$bbch = trim($this->params->get('boardBorderColor'));
		if (strlen($bbch) == 0) { $bbch = "E0E0E0"; }
		$hch = trim($this->params->get('highlightColor'));
		if (strlen($hch) == 0) { $hch = "ABABAB"; }
		$cbch = trim($this->params->get('controlBackgroundColor'));
		if (strlen($cbch) == 0) { $cbch = "F0F0F0"; }
		$ctch = trim($this->params->get('controlTextColor'));
		if (strlen($ctch) == 0) { $ctch = "696969"; }
		$fhch = trim($this->params->get('fontHeaderColor'));
		if (strlen($fhch) == 0) { $fhch = "000000"; }
		$fmch = trim($this->params->get('fontMovesColor'));
		if (strlen($fmch) == 0) { $fmch = "000000"; }
		$hmch = trim($this->params->get('highlightMovesColor'));
		if (strlen($hmch) == 0) { $hmch = "E0E0E0"; }
		$fcch = trim($this->params->get('fontCommentsColor'));
		if (strlen($fcch) == 0) { $fcch = "808080"; }

		$am_site = trim($this->params->get('autoplayMode'));
		if (strlen($am_site) == 0) { $am_site = "l"; }

		$cs = trim($this->params->get('containerStyle'));
		if (strlen($cs) == 0) { $csDef = ""; }
		else { $csDef = " style='" . $cs . "' "; }

		$ig_site = "f";
		$iv_site = "0";
		$ih_site = "s";
		$md_site = "f";
		$hd_site = "j";

		for ( $k=0; $k < $count; $k++ ) {
			$pgnOptionsInput = $matches[2][$k];
			$pgnOptions = preg_replace("@<.*?>@", " ", $pgnOptionsInput);
			if (preg_match("#(^|\s)(layout|l)=(.*?)(\s|$)#si", $pgnOptions, $thisOption)) {
				if (($thisOption[3] == "vertical") || ($thisOption[3] == "v")) { $hl = "f"; }
				elseif (($thisOption[3] == "horizontal") || ($thisOption[3] == "h")) { $hl = "t"; }
				else { $hl = $hl_site; }
			} else {
				$hl = $hl_site;
			}
			if (preg_match("#(^|\s)(height|h)=(.*?)(\s|$)#si", $pgnOptions, $thisOption))
				$height = $thisOption[3];
			else
				$height = $height_site;
			if (preg_match("#(^|\s)(showMoves|sm)=(.*?)(\s|$)#si", $pgnOptions, $thisOption))
				$md = $thisOption[3];
			else
				$md = "f";
			if (($md == "puzzle") || ($md == "p"))
				$hd = "v";
			else
				$hd = $hd_site;
			if (preg_match("#(^|\s)(autoplayMode|am)=(.*?)(\s|$)#si", $pgnOptions, $thisOption))
				$am = $thisOption[3];
			else
				$am = $am_site;
			if (preg_match("#(^|\s)(initialGame|ig)=(.*?)(\s|$)#si", $pgnOptions, $thisOption))
				$ig = $thisOption[3];
			else
				$ig = $ig_site;
			if (preg_match("#(^|\s)(initialVariation|iv)=(.*?)(\s|$)#si", $pgnOptions, $thisOption))
				$iv = $thisOption[3];
			else
				$iv = $iv_site;
			if (preg_match("#(^|\s)(initialHalfmove|ih)=(.*?)(\s|$)#si", $pgnOptions, $thisOption))
				$ih = $thisOption[3];
			else
				$ih = $ih_site;

			$skipParameters = array('layout', 'l', 'showmoves', 'sm', 'height', 'h', 'initialgame', 'ig', 'initialvariation', 'iv', 'initialhalfmove', 'ih', 'autoplaymode', 'am', 'extendedoptions', 'eo');
			$pgnParameters = array('pgntext', 'pt', 'pgnencoded', 'pe', 'fenstring', 'fs', 'pgnid', 'pi', 'pgndata', 'pd');
                        $pgnSourceOverride = false;
                        $extendedOptionsString = trim($this->params->get('extendedOptions'));
			if ($extendedOptionsString != '') {
				$extendedOptionsString = preg_replace('/^\s+/', '', $extendedOptionsString);
				$extendedOptionsString = preg_replace('/\s+$/', '', $extendedOptionsString);
				$extendedOptionsString = preg_replace('/&amp;/', '&', $extendedOptionsString);
				$extendedOptionsString = preg_replace('/&/', ' ', $extendedOptionsString);
			}
                        if (preg_match("#(^|\s)(extendedOptions|eo)=(.*?)(\s|$)#si", $pgnOptions, $thisOption))
                                $eo = $thisOption[3];
                        else
                                $eo = 'false';
                        if (($eo == 'true') || ($eo == 't'))
                                $extendedOptionsString .= ' ' . $pgnOptions;
			if (strlen($extendedOptionsString) > 0) {
                                foreach ($skipParameters as $value) {
                                        $extendedOptionsString = preg_replace('#(^|\s+)' . $value . '=\S*#si', '', $extendedOptionsString);
                                }
                                foreach ($pgnParameters as $value) {
                                        if (preg_match('#(^|\s+)' . $value . '=\S*#si', $extendedOptionsString)) {
                                                $pgnSourceOverride = true;
                                                break;
                                        }
                                }
                                if (strlen($extendedOptionsString) > 0) {
                                        $extendedOptionsString = ' ' . $extendedOptionsString;
                                }
                                $extendedOptionsString = preg_replace('#\s+#si', '&', $extendedOptionsString);
			}

			$pgnText = preg_replace("@<.*?>@", " ", $matches[3][$k]);
			$pgnText = str_replace(array("<", ">"), array("&lt;", "&gt;"), $pgnText);

			$pgnId = "pgn4web_" . dechex(crc32($pgnText));

                        if (($height == "auto") || (strlen($height) == 0)) {
                                $height = 268; // 26*8 squares + 3*2 border + 13*2 padding + 28 buttons
                                // guessing if one game or multiple games are supplied
                                $multiGamesRegexp = '/\s*\[\s*\w+\s*"[^"]*"\s*\]\s*[^\s\[\]]+[\s\S]*\[\s*\w+\s*"[^"]*"\s*\]\s*/';
                                if ($pgnSourceOverride || (preg_match($multiGamesRegexp, $pgnText) > 0)) { $height += 34; }
                                if ($hl == "t") {
                                        $fh = "b";
                                } else {
					$height += 75; // header
                                        if (($md != 'hidden') && ($md != 'h')) { $height += 300; } // moves
                                        $fh = $height;
                                }
                        } else {
                                $fh = $height;
                        }

			$boardUrl = JURI::root(true) . "/plugins/content/embedchessboard/pgn4web/board.html";

			$replacement  = "<div " . $csDef . " class='chessboard-wrapper'>";
			$replacement .= "<textarea id='" . $pgnId . "' style='display:none;' rows='40' cols='8'>" . $pgnText . "</textarea>";
			$replacement .= "<iframe src='" . $boardUrl . "?";
			$replacement .= "am=" . rawurlencode($am);
			$replacement .= "&amp;d=3000";
			$replacement .= "&amp;ig=" . rawurlencode($ig);
			$replacement .= "&amp;iv=" . rawurlencode($iv);
			$replacement .= "&amp;ih=" . rawurlencode($ih);
			$replacement .= "&amp;ss=26&amp;ps=d&amp;pf=d";
			$replacement .= "&amp;lch=" . rawurlencode($lch);
			$replacement .= "&amp;dch=" . rawurlencode($dch);
			$replacement .= "&amp;bbch=" . rawurlencode($bbch);
			$replacement .= "&amp;hm=b";
			$replacement .= "&amp;hch=" . rawurlencode($hch);
			$replacement .= "&amp;bd=c";
			$replacement .= "&amp;cbch=" . rawurlencode($cbch);
			$replacement .= "&amp;ctch=" . rawurlencode($ctch);
			$replacement .= "&amp;hd=" . rawurlencode($hd);
			$replacement .= "&amp;md=" . rawurlencode($md);
			$replacement .= "&amp;tm=13";
			$replacement .= "&amp;fhch=" . rawurlencode($fhch);
			$replacement .= "&amp;fhs=14";
			$replacement .= "&amp;fmch=" . rawurlencode($fmch);
			$replacement .= "&amp;fcch=" . rawurlencode($fcch);
			$replacement .= "&amp;hmch=" . rawurlencode($hmch);
			$replacement .= "&amp;fms=14&amp;fcs=m&amp;cd=i";
			$replacement .= "&amp;bch=" . rawurlencode($bch);
			$replacement .= "&amp;fp=13";
			$replacement .= "&amp;hl=" . rawurlencode($hl);
			$replacement .= "&amp;fh=" . rawurlencode($fh);
                        $replacement .= "&amp;fw=p";
                        if (!$pgnSourceOverride) { $replacement .= "&amp;pi=" . rawurlencode($pgnId); }
                        $replacement .= preg_replace("/&/", "&amp;", $extendedOptionsString) . "' ";
 			$replacement .= "frameborder='0' width='100%' height='" . $height . "' ";
			$replacement .= "scrolling='no' marginheight='0' marginwidth='0'>your web browser and/or your host do not support iframes as required to display the chessboard</iframe>";
			$replacement .= "</div>";
			$row->text = str_replace( $matches[0][$k], $replacement, $row->text );
                }
	}
}
?>
