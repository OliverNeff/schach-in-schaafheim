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

JHtml::_('behavior.formvalidator');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', '#jform_catid', null, array(
    'disable_search_threshold' => 0
));
JHtml::_('formbehavior.chosen', 'select');

$app = JFactory::getApplication();
$input = $app->input;

JFactory::getDocument()->addScriptDeclaration('
	Joomla.submitbutton = function(task) {
		if (task == "grand_prix_form.cancel" || document.formvalidator.isValid(document.getElementById("grand-prix-form")))	{
			Joomla.submitform(task, document.getElementById("grand-prix-form"));
		}
	};
');
?>


<form
	action="<?php echo JRoute::_('index.php?option=com_clm_turnier&layout=edit&id=' . (int) $this->item->id); ?>"
	method="post" name="adminForm" id="grand-prix-form"
	class="form-validate">

	<?php echo JLayoutHelper::render('joomla.edit.title_alias', $this); ?>
	
	<div class="form-horizontal">
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'details')); ?>
	
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'details', empty($this->item->id) ? JText::_('COM_CLM_TURNIER_NEW_GRAND_PRIX') : JText::_('COM_CLM_TURNIER_EDIT_GRAND_PRIX')); ?>
		<div class="row-fluid">
			<div class="span9">
				<?php echo $this->form->renderField('typ'); ?>
				<?php echo $this->form->renderField('typ_calculation'); ?>
				<?php echo $this->form->renderField('use_tiebreak'); ?>
				<?php echo $this->form->renderField('best_of'); ?>
				<?php echo $this->form->renderField('min_tournaments'); ?>
				<?php echo $this->form->renderField('num_tournaments'); ?>
				<?php echo $this->form->renderField('extra_points'); ?>
				<?php echo $this->form->renderField('precision'); ?>
				<?php echo $this->form->renderField('col_header'); ?>
				<?php echo $this->form->renderField('published'); ?>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>
		
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'misc', JText::_('COM_CLM_TURNIER_INTRODUCTION')); ?>
		<div class="row-fluid form-horizontal-desktop">
			<div class="form-vertical">
					<?php echo $this->form->renderField('introduction'); ?>
				</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>
		
		<?php echo JHtml::_('bootstrap.endTabSet'); ?>
		
	</div>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_( 'form.token' ); ?>
	
</form>
