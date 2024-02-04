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

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('behavior.tooltip');

$user = JFactory::getUser();
$userId = $user->get('id');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$saveOrder = $listOrder == 'a.name';

$canChange = clm_core::$access->access('BE_tournament_create');

if ($saveOrder) {
    $saveOrderingUrl = 'index.php?option=com_clm_turnier&task=grand_prix.saveOrderAjax&tmpl=component';
    JHtml::_('sortablelist.sortable', 'grand_prix_list', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}

$n = count($this->items);

?>

<form
	action="<?php echo JRoute::_('index.php?option=com_clm_turnier');?>"
	method="post" name="adminForm" id="adminForm">


	<div id="j-main-container">
		<?php echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>

		<?php if (empty($this->items)) : ?>
		<div class="alert alert-no-items">
			<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
		</div>
		<?php else : ?>
		<table class="table table-striped" id="grand_prix_list">
			<thead>
				<tr>
					<th class="center" width="10"><?php echo $n;?></th>
					<th class="center" width="20">
						<?php echo JHtml::_('grid.checkall'); ?>
					</th>
					<th class="title">
						<?php echo JHtml::_('searchtools.sort', 'COM_CLM_TURNIER_FIELD_NAME_LABEL', 'a.name', $listDirn, $listOrder); ?>
					</th>
					<th class="title">
						<?php echo JHtml::_('searchtools.sort', 'COM_CLM_TURNIER_FIELD_TYP_LABEL', 'a.typ', $listDirn, $listOrder); ?>
					</th>
					<th class="center">
						<?php echo JHtml::_('searchtools.sort', 'JSTATUS', 'a.published', $listDirn, $listOrder); ?>
					</th>
					<th class="center" nowrap="nowrap">
						<?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
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
				<?php
    $k = 0;
    foreach ($this->items as $i => $row) {
        $k ++;
        
        $canCreate = true;
        $canEdit = true;
        $canEditOwn = true;
        $canCheckin = $user->authorise('core.manage', 'com_checkin') || $row->checked_out == $userId || $row->checked_out == 0;
        ?>
				<tr class="<?php echo 'row'. $k; ?>">
					<td class="center"><?php echo $k; ?></td>
					<td class="center">
						<?php echo JHtml::_('grid.id', $i, $row->id); ?>
					</td>
					<td class="nowrap has-context">
						<?php if ($row->checked_out) : ?>
							<?php echo JHtml::_('jgrid.checkedout', $i, $row->editor, $row->checked_out_time, 'grand_prix.', $canCheckin); ?>
						<?php endif; ?>
						<?php if ($canEdit || $canEditOwn) : ?>
						<span class="editlinktip hasTip"
							title="<?php echo JText::_( 'COM_CLM_TURNIER_GRAND_PRIX_EDIT' );?>">
							<a
							href="<?php echo JRoute::_('index.php?option=com_clm_turnier&task=grand_prix_form.edit&id=' . (int) $row->id); ?>"><?php echo $this->escape($row->name); ?></a>
						</span>
							<?php else : ?>
							<?php echo $this->escape($row->name); ?>
						<?php endif; ?>
					</td>
					<td class="title"><?php echo JHtml::_('grand_prix.getGrandPrixModus', $row->typ);?></td>
					<td class="center"><?php echo JHtml::_('jgrid.published', $row->published, $i, 'grand_prix.', $canChange); ?></td>
					<td class="center"><?php echo $row->id; ?></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
		<?php endif; ?>
		
		<input type="hidden" name="task" value="" /> <input type="hidden"
			name="boxchecked" value="0" /> 
		<?php echo JHtml::_( 'form.token' ); ?>
	</div>
</form>
