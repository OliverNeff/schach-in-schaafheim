<?php
use \Joomla\CMS\Version;

/**
 * Chess League Manager Turnier Erweiterungen
 *
 * @copyright (C) 2021 Andreas Hrubesch; All rights reserved
 * @license GNU General Public License; see https://www.gnu.org/licenses/gpl.html
 * @author Andreas Hrubesch
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

JHtml::_('bootstrap.dropdown');

$dataToggle = ((Version::MAJOR_VERSION == 4) ? 'data-bs-toggle' : 'data-toggle') . '="dropdown"';

?>

<div class="icons">
	<?php if (empty($displayData['print'])) : ?>

		<?php if ($displayData['params']->get('show_print_icon') || $displayData['params']->get('show_email_icon') || $displayData['params']->get('show_filter_icon')) : ?>
        	<div class="btn-group pull-right">
        		<a class="btn dropdown-toggle" <?php echo $dataToggle?> href="#"> <span
        			class="icon-cog"></span><span class="caret"></span>
        		</a>

        		<ul class="dropdown-menu">
        			<?php if ($displayData['params']->get('show_print_icon')) : ?>
        				<li class="print-icon"><?php echo JHtml::_('icon.print_popup', $displayData['state'], $displayData['params']); ?></li>
        			<?php endif; ?>
        			<?php if ($displayData['params']->get('show_email_icon')) : ?>
        				<li class="email-icon"> <?php echo JHtml::_('icon.email', $displayData['state'], $displayData['params']); ?> </li>
        			<?php endif; ?>
        			<?php if ($displayData['params']->get('show_filter_icon')) : ?>
        				<li class="filter-icon"> <?php echo JHtml::_('icon.filter', $displayData['state'], $displayData['params']); ?> </li>
        			<?php endif; ?>
        		</ul>
        	</div>
		<?php endif; ?>
	
	<?php else : ?>

		<div class="pull-right">
			<?php echo JHtml::_('icon.print_screen', $displayData['state'], $displayData['params']); ?>
		</div>

	<?php endif; ?>	
</div>