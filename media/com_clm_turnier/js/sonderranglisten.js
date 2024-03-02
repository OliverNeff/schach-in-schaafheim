/**
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Field sonderranglisten
 */
;(function($){
	'use strict';

	$.fieldSonderranglisten = function(container, options){

		// Merge options with defaults
		this.options = $.extend({}, $.fieldSonderranglisten.defaults, options);

		// Set up elements
		this.$container = $(container);
		this.$modal = this.$container.find(this.options.modal);
		this.$modalBody = this.$modal.children('.modal-body');
		this.$input = this.$container.find(this.options.input);
		this.$inputName = this.$container.find(this.options.inputName);
		this.$buttonSelect = this.$container.find(this.options.buttonSelect);
		this.$buttonSaveSelected = this.$container.find(this.options.buttonSaveSelected);
		this.$buttonClear  = this.$container.find(this.options.buttonClear);
		
		// Bind events
		this.$buttonSelect.on('click', this.modalOpen.bind(this));
		this.$buttonSaveSelected.on('click', this.saveSelected.bind(this));
		this.$buttonClear.on('click', this.clearValue.bind(this));		
		this.$modal.on('hide', this.removeIframe.bind(this));

		// Check for onchange callback,
		var onchangeStr =  this.$input.attr('data-onchange'), onchangeCallback;
		if(onchangeStr) {
			onchangeCallback = new Function(onchangeStr);
			this.$input.on('change', onchangeCallback.bind(this.$input));
		}

	};
	
	$.fieldSonderranglisten.prototype.saveSelected = function() {
		var content = this.$modalBody.contents('iframe');
		var adminForm = content.contents().find('#adminForm');
		var self = this; // save context

		adminForm.find('input[type=checkbox]').each(function(){
			if ($(this).is(':checked')) {			
				var id = $(this).val();
				var xx = 'option[value=' + id + ']'
				var $input = self.$inputName.find(xx);
				$input.attr('selected', 'selected');
			}
		});
		
		this.$inputName.chosen().trigger("liszt:updated.chosen");
	}
	
	// display modal for select the file
	$.fieldSonderranglisten.prototype.modalOpen = function() {
		this.$iframe = $('<iframe>', {
			name: 'field-sonderranglisten-modal',
			src: this.options.url.replace('{field-sonderranglisten-id}', this.$input.attr('id')),
			width: this.options.modalWidth,
			height: this.options.modalHeight
		});
		this.$modalBody.append(this.$iframe);
		this.$modal.modal('show');
		$('body').addClass('modal-open');

		var self = this; // save context
		this.$iframe.load(function(){
			var content = $(this).contents();

			// handle value select
			content.on('click', '.button-select', function(){			
				self.setValue($(this).data('sonderranglisten-value'), $(this).data('sonderranglisten-name'));
				self.modalClose();
				$('body').removeClass('modal-open');
			});
		});
	};

	// close modal
	$.fieldSonderranglisten.prototype.modalClose = function() {
		this.$modal.modal('hide');
		this.$modalBody.empty();
		$('body').removeClass('modal-open');
	};

	// close modal
	$.fieldSonderranglisten.prototype.removeIframe = function() {
		this.$modalBody.empty();
		$('body').removeClass('modal-open');
	};

	// set the value
	$.fieldSonderranglisten.prototype.setValue = function(value, name) {
		this.$input.val(value).trigger('change');
		this.$inputName.val(name || value).trigger('change');
	};

	$.fieldSonderranglisten.prototype.clearValue = function() {
		this.$inputName.find("option:selected").removeAttr("selected");
		this.$inputName.chosen().trigger("liszt:updated.chosen");
	}
	
	// default options
	$.fieldSonderranglisten.defaults = {
		buttonClear: '.button-clear', // selector for button to clear the value
		buttonSelect: '.button-select', // selector for button to change the value
		input: '.field-sonderranglisten-input', // selector for the input for the user id
		inputName: '.field-sonderranglisten-input-name', // selector for the input for the user name
		buttonSaveSelected: '.button-save-selected', // selector for button to save the selected value
		modal: '.modal', // modal selector
		url : 'index.php?option=com_clm_turnier&view=sonderranglisten&layout=modal&tmpl=component',
		modalWidth: '100%', // modal width
		modalHeight: '300px' // modal height
	};

	$.fn.fieldSonderranglisten = function(options){
		return this.each(function(){
			var $el = $(this), instance = $el.data('fieldSonderranglisten');
			if(!instance){
				var options = options || {},
					data = $el.data();

				// Check options in the element
				for (var p in data) {
					if (data.hasOwnProperty(p)) {
						options[p] = data[p];
					}
				}

				instance = new $.fieldSonderranglisten(this, options);
				$el.data('fieldSonderranglisten', instance);
			}
		});
	};

	// Initialise all defaults
	$(document).ready(function(){
		$('.field-sonderranglisten-wrapper').fieldSonderranglisten();
	});

})(jQuery);

// Compatibility with mootools modal layout
function jSelectSonderranglisten(element) {
	var $el = jQuery(element),
		value = $el.data('sonderranglisten-value'),
		name  = $el.data('sonderranglisten-name'),
		fieldId = $el.data('sonderranglisten-field'),
		$inputValue = jQuery('#' + fieldId + '_id'),
		$inputName  = jQuery('#' + fieldId);

	if (!$inputValue.length) {
		// The input not found
		return;
	}

	// Update the value
	$inputValue.val(value).trigger('change');
	$inputName.val(name || value).trigger('change');

	// Check for onchange callback,
	var onchangeStr = $inputValue.attr('data-onchange'), onchangeCallback;
	if(onchangeStr) {
		onchangeCallback = new Function(onchangeStr);
		onchangeCallback.call($inputValue[0]);
	}
	jModalClose();
}
