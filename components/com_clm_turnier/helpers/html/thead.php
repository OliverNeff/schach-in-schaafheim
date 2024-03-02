<?php
/**
 * Chess League Manager Turnier Erweiterungen
 *
 * @copyright (C) 2018 Andreas Hrubesch; All rights reserved
 * @license GNU General Public License; see https://www.gnu.org/licenses/gpl.html
 * @author Andreas Hrubesch
 */

// No direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * HTML Utility Klasse für "jquery.floatThead" JavaScript Bibliothek
 *
 * @since 2.0.1
 * @see https://github.com/mkoryak/floatThead
 * @see http://mkoryak.github.io/floatThead/
 * @see https://cdnjs.com/libraries/floatthead
 */
abstract class JHtmlThead {
	
	/**
	 *
	 * @var array Array containing information for loaded files
	 */
	protected static $loaded = array ();
	
	/**
	 * läd die JavaScript Frameworks für schwebenden Tabellenüberschriften in
	 * den HTML Dokumentkopf.
	 */
	public static function framework() {
		// Only load once
		if (! empty ( static::$loaded [__METHOD__] )) {
			return;
		}
		
		// load jQuery JavaScript framework
		JHtml::_ ( 'jquery.framework' );
		
		// load floatThead JavaScript framework
		JHtml::_ ( 'script', 'https://cdnjs.cloudflare.com/ajax/libs/floatthead/2.2.4/jquery.floatThead.js' );
		JHtml::_ ( 'script', 'com_clm_turnier/thead.js', array('relative' => true) );
		
		static::$loaded [__METHOD__] = true;
		
		return;
	}
	
	/**
	 * erstellt das Table Class Element
	 *
	 * @param boolean $active
	 *        	true = schwebenden Tabellenüberschriften aktiv
	 *        	
	 */
	public static function tableClass($active = true) {
		if ($active == true) {
			echo 'class="theadFloatingHeader"'; // als ReturnValue !!
		}
	}
}

?>
