<?php 
/**
 * Chess League Manager Turnier Erweiterungen
 *
 * @copyright (C) 2021 Andreas Hrubesch; All rights reserved
 * @license GNU General Public License; see https://www.gnu.org/licenses/gpl.html
 * @author Andreas Hrubesch
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use \Joomla\CMS\Version;

/**
 * HTML Utility Klasse
 * 
 * @since 3.1.2
 */
abstract class JHtmlGrandPrix {
	/**
	 * @var array Array containing information for loaded files
	 */
	protected static $loaded = array ();	

	/**
	 * läd die CLM CSS Style Sheets
	 */
	public static function stylesheet() {
		// Only load once
		if (! empty(static::$loaded[__METHOD__])) {
			return;
		}
		
		$config = clm_core::$db->config();
		if (! $config->template) {
			return;
		}

		// CLM Template Einstellungen
		// Lesehilfe
		$document = JFactory::getDocument();
		$document->addStyleDeclaration('
	#clm .clm .zeile1 { background-color: #' . $config->zeile1 . '; }');
		$document->addStyleDeclaration('
	#clm .clm .zeile2 {	background-color: #' . $config->zeile2 . '; }');
		
		if ($config->lesehilfe == '1') {
			$document->addStyleDeclaration('
	#clm .clm tr.zeile1:hover td, #clm .clm tr.zeile2:hover td { background-color: #FFFFBB !important; }');
		}
		
		$document->addStyleDeclaration('
	#clm .clm table th { background-color: #' . $config->tableth . '; color: #' . $config->tableth_s1 . ' !important; }');
		$document->addStyleDeclaration('
	#clm .clm table th, #clm .clm table td {
		padding-top: ' . $config->cellin_top . ';
		padding-left: ' . $config->cellin_left . ';
		padding-right: ' . $config->cellin_right . ';
		padding-bottom: ' . $config->cellin_bottom . ';
		border: ' .	$config->border_length . ' ' . $config->border_style . ' #' . $config->border_color . ';
	}');
		
		$document->addStyleDeclaration('
	#clm .clm table .anfang, #clm .clm table .ende, #clm .clm .clm-navigator ul li, #clm .clm .clm-navigator ul li ul {
		background-color: #' .	$config->subth . ';
		color: #' . $config->tableth_s2 . ' !important;
	}');
		
		$document->addStyleDeclaration('
	#wrong, .wrong { background: #' . $config->wrong1 . '; border: ' . $config->wrong2_length . ' ' . $config->wrong2_style . ' #' . $config->wrong2_color . '; }');
		
		JHTML::_('stylesheet', 'com_clm_turnier/content.css', array (
				'version' => 'auto', 'relative' => true ));
		
		static::$loaded[__METHOD__] = true;
		
		return;
	}

	/**
	 * Joomla 3 / 4 Tooltip im Admin Bereich
	 */
	public static function tooltip() {
		switch (Version::MAJOR_VERSION) {
			case 3:
				JHtml::_('behavior.tooltip');
				break;
		}
	}
	
	/**
	 * Grand Prix Modus
	 * 
	 * @param int $id
	 * @return string
	 */
	public static function modus($id = null) {
		$modus = array();
		
		$modus[0] = JText::_('COM_CLM_TURNIER_FIELD_VALUE_SELECT');
		$modus[1] = JText::_('COM_CLM_TURNIER_FIELD_VALUE_TYPE_1');
		$modus[2] = JText::_('COM_CLM_TURNIER_FIELD_VALUE_TYPE_2');
		$modus[3] = JText::_('COM_CLM_TURNIER_FIELD_VALUE_TYPE_3');
		
		if ($id != null && $id >= 0 and $id <= count($modus)) {
			return $modus[$id];
		}
		
		return $modus[0];
	}
}
?>