<?php
/**
 * Chess League Manager Turnier Erweiterungen
 *
 * @copyright (C) 2021 Andreas Hrubesch; All rights reserved
 * @license GNU General Public License; see https://www.gnu.org/licenses/gpl.html
 * @author Andreas Hrubesch
 */

// No direct access to this file
defined('_JEXEC') or die;

$params = $displayData['params'];

?>
<?php if ($params->get('show_icons')) : ?>
	<span class="icon-envelope" aria-hidden="true"></span>
	<?php echo JText::_('COM_CLM_TURNIER_EMAIL'); ?>
<?php else : ?>
	<?php echo JText::_('COM_CLM_TURNIER_EMAIL'); ?>
<?php endif; ?>
