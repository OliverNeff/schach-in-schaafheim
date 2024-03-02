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

$state = $displayData['state'];
$print = isset($displayData['print']) ? $displayData['print'] : false;

if ($state->get('grand_prix.rids')) :
	JHTML::_('stylesheet', 'com_clm_turnier/submenu.css', array ('relative' => true ));

	if (! $print) :
?>

<div align="center">
<nav class="clm-navigator" role="navigation">
	<ul class="clm-nav clm-nav-pills">
	<li>
	<?php
	$link = CLMTurnierRoute::getGrandPrixRoute($state->get('grand_prix.id'), $state->get('grand_prix.catidEdition'), $state->get('grand_prix.tids'), $state->get('grand_prix.filter'), $state->get('grand_prix.rids'));
		echo JHtml::_('link', JRoute::_($link), JText::_('TOURNAMENT_RANKING'));
	?>
		<ul class="clm-nav-child">
	<?php
		foreach ($displayData['ranglisten'] as $rangliste) {
			$link = CLMTurnierRoute::getGrandPrixRoute($state->get('grand_prix.id'), $state->get('grand_prix.catidEdition'), $state->get('grand_prix.tids'), $state->get('grand_prix.filter'), $state->get('grand_prix.rids'), $rangliste->id);

			echo '<li>';
			echo JHtml::_('link', JRoute::_($link), $rangliste->name);
			echo '</li>';
		}
	?>
		</ul>
	</li>
	</ul>
</nav>
</div>	<!-- submenu align="center" -->
	<?php endif; ?>

	<?php 
	if ($state->get('grand_prix.rid')) :
		$key = array_search($state->get('grand_prix.rid'), array_column($displayData['ranglisten'], 'id'));
	?>
	<div class="center">
		<h4>
		<?php echo $displayData['ranglisten'][$key]->name . ' ' .
			JText::_('TOURNAMENT_RANKING'); ?>
		</h4>
	</div>
	<?php endif; ?>
<?php endif; ?>
