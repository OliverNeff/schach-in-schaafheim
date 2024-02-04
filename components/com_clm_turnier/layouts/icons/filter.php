<?php
/**
 * Chess League Manager Turnier Erweiterungen
 *
 * @copyright (C) 2018 Andreas Hrubesch; All rights reserved
 * @license GNU General Public License; see https://www.gnu.org/licenses/gpl.html
 * @author Andreas Hrubesch
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

$params = $displayData['params'];
$legacy = $displayData['legacy'];
$attribs = $displayData['attribs'];

?>

<?php if ($params->get('show_icons')) : ?>
	<?php if ($legacy) : ?>
		<?php echo JHtml::_('image', 'system/rating_star.png', JText::_($attribs['text']), null, true); ?>	
	<?php else : ?>
    	<span class="<?php echo $attribs['span.class']; ?>"></span>
    	<?php echo JText::_($attribs['text']); ?>
	<?php endif; ?>
<?php else : ?>
	<?php echo JText::_($attribs['text']); ?>
<?php endif; ?>
