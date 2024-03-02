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

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Version;

extract($displayData);

/**
 * Layout variables
 * -----------------
 * @var   string   $autocomplete    Autocomplete attribute for the field.
 * @var   boolean  $autofocus       Is autofocus enabled?
 * @var   string   $class           Classes for the input.
 * @var   string   $description     Description of the field.
 * @var   boolean  $disabled        Is this field disabled?
 * @var   string   $group           Group the field belongs to. <fields> section in form XML.
 * @var   boolean  $hidden          Is this field hidden in the form?
 * @var   string   $hint            Placeholder for the field.
 * @var   string   $id              DOM id of the field.
 * @var   string   $label           Label of the field.
 * @var   string   $labelclass      Classes to apply to the label.
 * @var   boolean  $multiple        Does this field support multiple values?
 * @var   string   $name            Name of the input field.
 * @var   string   $onchange        Onchange attribute for the field.
 * @var   string   $onclick         Onclick attribute for the field.
 * @var   string   $pattern         Pattern (Reg Ex) of value of the form field.
 * @var   boolean  $readonly        Is this field read only?
 * @var   boolean  $repeat          Allows extensions to duplicate elements.
 * @var   boolean  $required        Is this field required?
 * @var   integer  $size            Size attribute of the input.
 * @var   boolean  $spellcheck      Spellcheck state for the form field.
 * @var   string   $validate        Validation rules to apply.
 * @var   string   $value           Value attribute of the field.
 * @var   array    $checkedOptions  Options that will be set as checked.
 * @var   boolean  $hasValue        Has this field a value assigned?
 * @var   array    $options         Options available for this field.
 * @var   string   $dataAttribute   Miscellaneous data attributes preprocessed for HTML output
 * @var   array    $dataAttributes  Miscellaneous data attribute for eg, data-*
 * @var   string   $saison         
 */

$html = array();
$attr = '';

// Initialize the field attributes.
$attr .= !empty($size) ? ' size="' . $size . '"' : '';
$attr .= $multiple ? ' multiple' : '';
$attr .= $autofocus ? ' autofocus' : '';
$attr .= $onchange ? ' onchange="' . $onchange . '"' : '';
$attr .= $dataAttribute;

// To avoid user's confusion, readonly="readonly" should imply disabled="disabled".
if ($readonly || $disabled) {
	$attr .= ' disabled="disabled"';
}

if ($required) {
	$attr  .= ' required class="required"';
	$htmlTagAttributes[] = 'required';
}

// Create a read-only list (no name) with hidden input(s) to store the value(s).
if ($readonly) {
	$html[] = HTMLHelper::_('select.genericlist', $options, '', trim($attr), 'id', 'name', $value, $id);

	// E.g. form field type tag sends $this->value as array
	if ($multiple && is_array($value)) {
		if (!count($value))	{
			$value[] = '';
		}

		foreach ($value as $val) {
			$html[] = '<input type="hidden" name="' . $name . '" value="' . htmlspecialchars($val, ENT_COMPAT, 'UTF-8') . '">';
		}
	} else {
		$html[] = '<input type="hidden" name="' . $name . '" value="' . htmlspecialchars($value, ENT_COMPAT, 'UTF-8') . '">';
	}
} else {
	$uri = new Uri('index.php?option=com_clm_turnier&view=sonderranglisten&layout=modal&tmpl=component');
	$uri->setVar('field', $this->escape($id));
	
	// Create a regular list.
	switch (Version::MAJOR_VERSION) {
		case 3:
			$attr .= ' id="' . $id . '"';
			$attr .= ' name=' . $name . '"';
			$attr .= ' class="field-sonderranglisten-input-name ';
			$attr .= !empty($class) ? $class : '';
			$attr .= '"';

			$htmlTag = 'div';
			$htmlTagAttributes[] = 'class="field-sonderranglisten-wrapper"';
			$htmlTagAttributes[] = 'data-url="' . (string)$uri . '"';
			$htmlTagAttributes[] = 'data-modal=".modal"';
			$htmlTagAttributes[] = 'data-modal-width="100%"';
			$htmlTagAttributes[] = 'data-modal-height="400px"';
			$htmlTagAttributes[] = 'data-input=".field-sonderranglisten-input"';
			$htmlTagAttributes[] = 'data-input-name=".field-sonderranglisten-input-name"';
			$htmlTagAttributes[] = 'data-button-select=".button-select"';
			$htmlTagAttributes[] = 'data-button-save-selected=".button-save-selected"';
			$dataDismiss='data-dismiss="modal"';
			
			JHtml::_('script', 'com_clm_turnier/sonderranglisten.js', array('version' => 'auto', 'relative' => true));
			break;
		case 4:
			$htmlTag = 'clm-field-sonderranglisten';
			$htmlTagAttributes[] = !empty($class) ? 'class="' . $class . '"' : '';
			$htmlTagAttributes[] = 'placeholder="' . $this->escape($hint ?: JText::_('JGLOBAL_TYPE_OR_SELECT_SOME_OPTIONS')) . '" ';
			$htmlTagAttributes[] = 'url="' . (string) $uri . '"';
			$htmlTagAttributes[] = 'modal=".modal"';
			$htmlTagAttributes[] = 'modal-width="100%"';
			$htmlTagAttributes[] = 'modal-height="400px"';
			$htmlTagAttributes[] = 'button-select=".button-select"';
			$htmlTagAttributes[] = 'button-clear=".button-clear"';
			$htmlTagAttributes[] = 'button-save-selected=".button-save-selected"';
			$dataDismiss='data-bs-dismiss="modal"';

			Text::script('JGLOBAL_SELECT_NO_RESULTS_MATCH');
			Text::script('JGLOBAL_SELECT_PRESS_TO_SELECT');

			$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
			$wa->getRegistry()->addRegistryFile('media/com_clm_turnier/joomla.asset.json');
			$wa->usePreset('choicesjs')
				->useScript('webcomponent.field-sonderranglisten');
			break;
			// <!-- TODO: modal über ID, da class nicht eindeutig -->
	}
	
	// TODO: id = value, name = text
	$html[] = HTMLHelper::_('select.genericlist', $options, $name, trim($attr), 'id', 'name', $value, $id);
	
	$modalHTML = HTMLHelper::_(
			'bootstrap.renderModal',
			'sonderranglistenModal_' . $id,
			array (
					'url' => $uri,
					'title' => JText::_('COM_CLM_TURNIER_FORM_FIELD_SONDERRANGLISTEN_DIALOG') .
						' (' . JText::_('SAISON') . ': ' . $this->escape($saison) . ')',
					'closeButton' => true,
					'height' => '100%',
					'width' => '100%',
					'modalWidth' => 80,
					'bodyHeight' => 60,
					'footer' => '<button type="button" class="btn button-save-selected"' . $dataDismiss .'>' . JText::_('JGLOBAL_FIELD_ADD') . '</button>'
						. '<button type="button" class="btn btn-secondary" ' . $dataDismiss .'>' . JText::_('JCANCEL') . '</button>'
			)
		);
}
?>

<<?php echo $htmlTag . ' ' . implode($htmlTagAttributes); ?>>
	<div class="input-append">
		<?php echo implode($html); ?>

		<?php if (!$readonly) : ?>
			<button type="button" class="btn btn-primary button-select" title="<?php echo JText::_('JSELECT'); ?>">
				<span class="icon-list icon-white" aria-hidden="true"></span> 
				<span><?php echo JText::_('JSELECT'); ?></span>
			</button>		
			<button type="button" class="btn btn-danger button-clear" title="<?php echo JText::_('JCLEAR'); ?>">
				<span class="icon-remove" aria-hidden="true"></span>
			</button>

			<?php echo $modalHTML; ?>		
		<?php endif; ?>	
	</div>
</<?php echo $htmlTag ?>>
