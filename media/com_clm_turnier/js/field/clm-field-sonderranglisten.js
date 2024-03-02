/**
 * Chess League Manager Turnier Erweiterungen
 *
 * @copyright (C) 2022 Andreas Hrubesch; All rights reserved
 * @license GNU General Public License; see https://www.gnu.org/licenses/gpl.html
 * @author Andreas Hrubesch
 */
 
/**
 * Öffnet einen modalen Dialog zur Auswahl der CLM Sonderranglisten
 * nutzt Choices.js
 *
 * Beispiel:
 * <clm-field-sonderranglisten ...attributes>
 * </clm-field-sonderranglisten>
 *
 * Mögliche Attribute:
 */
window.customElements.define('clm-field-sonderranglisten', class extends HTMLElement {
	// Attributes to monitor
	get url() { return this.getAttribute('url'); }
	
	get placeholder() { return this.getAttribute('placeholder'); }	
	get searchPlaceholder() { return this.getAttribute('search-placeholder'); }
	
	get value() { return this.choicesInstance.getValue(true); }
	set value($val) { this.choicesInstance.setChoiceByValue($val); }
	
	get buttonSelectClass() { return this.getAttribute('button-select'); }
	set buttonSelectClass(value) { this.setAttribute('button-select', value); }
	
	get buttonClearClass() { return this.getAttribute('button-clear'); }
	set buttonClearClass(value) { return this.setAttribute('button-clear', value); }
	
	get buttonSaveSelectClass() { return this.getAttribute('button-save-selected'); }
	
	get modalClass() { return this.getAttribute('modal'); }
	set modalClass(value) { this.setAttribute('modal', value); }
	
	get modalWidth() { return this.getAttribute('modal-width'); }
	set modalWidth(value) { this.setAttribute('modal-width', value); }
	
	get modalHeight() { return this.getAttribute('modal-height'); }
	set modalHeight(value) { this.setAttribute('modal-height', value); }

	/**
	 * Lifecycle
	 */
	constructor() {
		super();
		
		// Keycodes
		this.keyCode = {
			ENTER: 13,
		};
		
		if (!Joomla) {
			throw new Error('Joomla API is not properly initiated');
		}
		
		if (!window.Choices) {
			throw new Error('CLMFieldSonderranglisten requires Choices.js to work');
		}
		
		this.choicesCache = {};
		this.choicesInstance = null;
		this.isDisconnected = false;
	}

	/**
	 * Lifecycle
	 */
	connectedCallback() {
		// Make sure Choices are loaded
		if (window.Choices || document.readyState === 'complete') {
			// Set up elements
			this.buttonSelect = this.querySelector(this.buttonSelectClass);
			this.buttonClear = this.querySelector(this.buttonClearClass);
			this.modal = this.querySelector(this.modalClass);
			this.modalBody = this.querySelector('.modal-body');
			this.buttonSaveSelect = this.querySelector(this.buttonSaveSelectClass);
	  
			// Bootstrap modal init
			if (this.modal && window.bootstrap && window.bootstrap.Modal
					&& !window.bootstrap.Modal.getInstance(this.modal)) {
				Joomla.initialiseModal(this.modal, { isJoomla: true });
			}
	      
			if (this.buttonSelect) {
				this.buttonSelect.addEventListener('click', this.modalOpen.bind(this));
			}
	
			if (this.buttonClear) {
				this.buttonClear.addEventListener('click', this.clearValue.bind(this));
			}      
			
			if (this.buttonSaveSelect) {
				this.buttonSaveSelect.addEventListener('click', this.saveSelected.bind(this));
			}
			
			this.doConnect();
		} else {
			const callback = () => {
		        this.doConnect();
		        window.removeEventListener('load', callback);
			};
			window.addEventListener('load', callback);
	    }
	}

  	doConnect() {
	    // Get a <select> element
	    this.select = this.querySelector('select');

	    if (!this.select) {
	    	throw new Error('CLMFieldSonderranglisten requires <select> element to work');
	    }

		// The element was already initialised previously and perhaps was detached from DOM
		if (this.choicesInstance) {
			if (this.isDisconnected) {
				// Re init previous instance
			    this.choicesInstance.init();
			    this.isDisconnected = false;
			}
			return;
		}

    	this.isDisconnected = false;

		// Add placeholder option for multiple mode,
		// Because it not supported as parameter by Choices for <select> https://github.com/jshjohnson/Choices#placeholder
		if (this.select.multiple && this.placeholder) {
			const option = document.createElement('option');
			option.setAttribute('placeholder', '');
			option.textContent = this.placeholder;
			this.select.appendChild(option);
		}

		// Init Choices
		// eslint-disable-next-line no-undef
		this.choicesInstance = new Choices(this.select, {
			placeholderValue: this.placeholder,
			searchPlaceholderValue: this.searchPlaceholder,
			removeItemButton: true,
			searchFloor: 1,
			searchResultLimit: parseInt(this.select.dataset.maxResults, 10) || 10,
			renderChoiceLimit: parseInt(this.select.dataset.maxRender, 10) || -1,
			shouldSort: false,
			fuseOptions: {
				threshold: 0.3, // Strict search
			},
			noResultsText: Joomla.Text._('JGLOBAL_SELECT_NO_RESULTS_MATCH', 'No results found'),
			noChoicesText: Joomla.Text._('JGLOBAL_SELECT_NO_RESULTS_MATCH', 'No results found'),
			itemSelectText: Joomla.Text._('JGLOBAL_SELECT_PRESS_TO_SELECT', 'Press to select'),
			
			// Redefine some classes
			classNames: {
				button: 'choices__button_joomla', // It is need because an original styling use unavailable Icon.svg file
			},
		});
	
		this.choicesInstance.containerOuter.element.style.marginBottom = '0px';
  	}

	/**
	 * Lifecycle
	 */
	disconnectedCallback() {
		// Destroy Choices instance, to unbind event listeners
		if (this.choicesInstance) {
			this.choicesInstance.destroy();
			this.isDisconnected = true;
		}
	}

  	disableAllOptions() {
	    // Choices.js does not offer a public API for accessing the choices
	    // So we have to access the private store => don't eslint
	    // eslint-disable-next-line no-underscore-dangle
	    const { choices } = this.choicesInstance._store;
	
	    choices.forEach((elem, index) => {
	    	choices[index].disabled = true;
	    	choices[index].selected = false;
	    });
	
	    this.choicesInstance.clearStore();
	    this.choicesInstance.setChoices(choices, 'value', 'label', true);
  	}

  	enableAllOptions() {
	    // Choices.js does not offer a public API for accessing the choices
	    // So we have to access the private store => don't eslint
	    // eslint-disable-next-line no-underscore-dangle
	    const { choices } = this.choicesInstance._store;
	    const values = this.choicesInstance.getValue(true);
	
	    choices.forEach((elem, index) => {
	    	choices[index].disabled = false;
	    	choices[index].selected = true;
	    });
	
	    this.choicesInstance.clearStore();
	    this.choicesInstance.setChoices(choices, 'value', 'label', true);
	    this.value = values;
  	}

  	disableByValue($val) {
	    // Choices.js does not offer a public API for accessing the choices
	    // So we have to access the private store => don't eslint
	    // eslint-disable-next-line no-underscore-dangle
	    const { choices } = this.choicesInstance._store;
	    const values = this.choicesInstance.getValue(true);
	
	    choices.forEach((elem, index) => {
	    	if (elem.value === $val) {
	    		choices[index].disabled = true;
	    		choices[index].selected = false;
	    	}
	    });
	
	    const index = values.indexOf($val);
	    if (index > -1) {
	    	values.slice(index, 1);
	    }
	
	    this.choicesInstance.clearStore();
	    this.choicesInstance.setChoices(choices, 'value', 'label', true);
	    this.value = values;
  	}

  	enableByValue($val) {
	    // Choices.js does not offer a public API for accessing the choices
	    // So we have to access the private store => don't eslint
	    // eslint-disable-next-line no-underscore-dangle
	    const { choices } = this.choicesInstance._store;
	    const values = this.choicesInstance.getValue(true);
	
	    choices.forEach((elem, index) => {
	    	if (elem.value === $val) {
	    		choices[index].disabled = false;
	    		choices[index].selected = true;
	    	}
	    });
	
	    this.choicesInstance.clearStore();	
	    this.choicesInstance.setChoices(choices, 'value', 'label', true);
	    this.value = values;
  	}
  
    // Callback für Save Selected
    saveSelected() {
		this.iframeEl = this.modalBody.querySelector('iframe');
		const content = this.iframeEl.contentWindow.document;
		
		const checkbox = [].slice.call(content.adminForm.querySelectorAll('input[type=checkbox]'));
		checkbox.forEach((box) => {
			if (box.checked) {
				this.enableByValue(box.value);
			}
		});
    }
  
    // Remove the iframe
    removeIframe() {
      this.modalBody.innerHTML = '';
    }
  
	// Opens the modal dialog
	modalOpen() {
		// Reconstruct the iframe
		this.removeIframe();
		const iframe = document.createElement('iframe');
		iframe.setAttribute('name', 'field-sonderranglisten-modal');
		iframe.setAttribute('width', this.modalWidth);
		iframe.setAttribute('height', this.modalHeight);
		
		this.modalBody.appendChild(iframe);
		this.modal.open();
	}
  
	clearValue() {
	  	this.disableAllOptions();
	}  
});
