<?php
/**
 * Chess League Manager Turnier Erweiterungen 
 *  
 * @copyright (C) 2017 Andreas Hrubesch; All rights reserved
 * @license GNU General Public License; see https://www.gnu.org/licenses/gpl.html
 * @author Andreas Hrubesch
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * HTML helper class
 */
abstract class JHtmlGrand_Prix {

    /**
     * 
     * @param integer $id
     * @return string
     */
    public static function getGrandPrixModus($id = null) {
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