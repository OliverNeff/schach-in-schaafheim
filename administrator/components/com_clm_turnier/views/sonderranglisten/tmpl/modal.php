<?php
/**
 * Chess League Manager Turnier Erweiterungen
 *
 * @copyright (C) 2019 Andreas Hrubesch; All rights reserved
 * @license GNU General Public License; see https://www.gnu.org/licenses/gpl.html
 * @author Andreas Hrubesch
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

JHtml::_('grandprix.tooltip');

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));

if ($listOrder === 'r.name') {
	$saveOrderingUrl = 'index.php?option=com_clm_turnier&task=sonderranglisten.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'sonderranglisten_list', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}

?>

<form
	action="<?php echo JRoute::_('index.php?option=com_clm_turnier&view=sonderranglisten&layout=modal&tmpl=component');?>"
 	method="post" name="adminForm" id="adminForm">

	<div id="j-main-container">
		<?php echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>
		
		<table class="table table-striped" id="sonderranglisten_list">
			<thead>
				<tr>
					<th class="center"> <?php echo count($this->items); ?> </th>
					<th class="center" width="20">
						<?php echo JHtml::_('grid.checkall'); ?>
					</th>
					<th>
						<?php echo JHtml::_('searchtools.sort', 'COM_CLM_TURNIER_SONDERRANGLISTEN_NAME_LABEL', 'r.name', $listDirn, $listOrder); ?>
					</th>
					<th>
						<?php echo JHtml::_('searchtools.sort', 'COM_CLM_TURNIER_SONDERRANGLISTEN_TURNIER_NAME_LABEL', 't.name', $listDirn, $listOrder); ?>
					</th>
					<th class="center">
						<?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_ORDERING', 'r.ordering', $listDirn, $listOrder); ?>
					</th>
					<th class="center">
						<?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_ID', 'r.id', $listDirn, $listOrder); ?>
					</th>
				</tr>
			</thead>
		
			<tfoot>
				<tr>
					<td colspan="6">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
		
			<tbody>
				<?php $k = 0; ?>
				<?php foreach ($this->items as $i => $row) : $k++ ?>
					<tr class="row<?php echo $k % 2?>">
						<td class="center"><?php echo $k; ?></td>
						<td class="center">
							<?php echo JHtml::_('grid.id', $i, $row->id); ?>
						</td>
						<td><?php echo $row->name; ?></td>
						<td><?php echo $row->turnier; ?></td>
						<td class="center"><?php echo $row->ordering; ?></td>
						<td class="center"><?php echo $row->id; ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

	    <input type="hidden" name="task" value="" />
	    <input type="hidden" name="boxchecked" value="0" />
	    <?php echo JHtml::_('form.token'); ?>
	</div>	<!-- id="j-main-container" -->
</form>
