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

$params = $displayData['params'];
$attribs = $displayData['attribs'];

?>
<?php if ($params->get('show_icons')) : ?>
   	<span class="<?php echo $attribs['span.class']; ?>" aria-hidden="true"></span>
   	<?php echo JText::_('COM_CLM_TURNIER_FILTER_TLNR'); ?>
<?php else : ?>
	<?php echo JText::_('COM_CLM_TURNIER_FILTER_TLNR'); ?>
<?php endif; ?>
