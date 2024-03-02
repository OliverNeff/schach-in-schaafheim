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

/**
 * Script file of CLM Turnier component.
 *
 * The name of this class is dependent on the component being installed.
 * The class name should have the component's name, directly followed by
 * the text InstallerScript (ex:. com_helloWorldInstallerScript).
 *
 * This class will be called by Joomla!'s installer, if specified in your component's
 * manifest file, and is used for custom automation actions in its installation process.
 *
 * In order to use this automation script, you should reference it in your component's
 * manifest file as follows:
 * <scriptfile>install.php</scriptfile>
 *
 * @copyright Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license GNU General Public License version 2 or later; see LICENSE.txt
 */
class com_clm_turnierInstallerScript {

	// the version we are updating from
	protected $fromVersion = null;

	/**
	 * This method is executed before any Joomla uninstall action, such as file
	 * removal or database changes.
	 *
	 * @param \stdClass $parent
	 *        	- Parent object calling this method.
	 *        	
	 * @return void
	 */
	public function uninstall($parent) {
		$removeTables = true;

		// Chess League Manager Einstellung
		try {
			// Standard Einstellung: Tabellen nicht löschen
			if (file_exists(JPATH_SITE .
					'/components/com_clm/clm/includes/config.php')) {
				define('clm', '1');
				require_once JPATH_SITE .
						'/components/com_clm/clm/includes/config.php';
				if (isset($config['database_safe'])) {
					$removeTables = ! ($config['database_safe'][2]); // default value
				}
			}

			// Datenbank Einstellung
			$db = JFactory::getDBO();
			$query = $db->getQuery(true)->select($db->quoteName(array ('id',
					'value' )))->from($db->quoteName('#__clm_config'))->where($db->quoteName('id') .
					' = ' . $db->quote('119'));
			$db->setQuery($query);

			$row = $db->loadObject();
			$removeTables = (isset($row)) ? (! ($row->value)) : 0;
		} catch (Exception $e) {
			// NOP
		}

		if ($removeTables === true) {
			$element = new SimpleXMLElement('<sql><file driver="mysql" charset="utf8">sql/uninstall.sql</file></sql>');
			if ($parent->getParent()->parseSQLFiles($element) > 0) {
				$this->enqueueMessage(JText::_('COM_CLM_TURNIER_DELETE_TABLES'), 'notice');
			}
		}
	}

	/**
	 * This method is called after a component is updated.
	 *
	 * @param \stdClass $parent
	 *        	- Parent object calling object.
	 *        	
	 * @return void
	 */
	public function update($parent) {
		// remove depricated language files
		$this->deleteUnexistingFiles();
	}

	/**
	 * Runs just before any installation action is preformed on the component.
	 * Verifications and pre-requisites should run in this function.
	 *
	 * @param string $type
	 *        	- Type of PreFlight action. Possible values are:
	 *        	- * install
	 *        	- * update
	 *        	- * discover_install
	 * @param \stdClass $parent
	 *        	- Parent object calling object.
	 *        	
	 * @return void
	 */
	public function preflight($type, $parent) {
		// Chess League Manager installiert ?
		$db = JFactory::getDbo();
		$result = $db->setQuery($db->getQuery(true)->select('COUNT(' .
				$db->quoteName('extension_id') . ')')->from($db->quoteName('#__extensions'))->where($db->quoteName('element') .
				' = ' . $db->quote('com_clm'))->where($db->quoteName('type') .
				' = ' . $db->quote('component')))->loadResult();
		if ($result == 0) {
			$this->enqueueMessage(JText::_('COM_CLM_TURNIER_REQ_COM_CLM'), 'warning');
		}

		// vorherige Version ermitteln
		if ($type === 'update') {
			$component = JComponentHelper::getComponent($parent->getElement());
			$extension = JTable::getInstance('extension');
			$extension->load($component->id);
			$manifest = new \Joomla\Registry\Registry($extension->manifest_cache);
			$this->fromVersion = $manifest->get('version');
		}
	}

	/**
	 * Runs right after any installation action is preformed on the component.
	 *
	 * @param string $type
	 *        	- Type of PostFlight action. Possible values are:
	 *        	- * install
	 *        	- * update
	 *        	- * discover_install
	 * @param \stdClass $parent
	 *        	- Parent object calling object.
	 *        	
	 * @return void
	 */
	public function postflight($type, $parent) {
		$this->loadConfigXml($parent);

		$this->loadSamples($parent);

		if ($type === 'update' &&
				version_compare($this->fromVersion, $parent->getManifest()->version, '<')) {
			$this->enqueueMessage($this->getChangelog($parent));
		}
	}

	/**
	 * Enqueue a system message.
	 *
	 * @param string $msg
	 *        	The message to enqueue.
	 * @param string $type
	 *        	The message type. Default is message.
	 *        	
	 * @return void
	 */
	private function enqueueMessage($msg, $type = 'message') {
		// Don't add empty messages.
		if (! strlen(trim($msg))) {
			return;
		}

		// Enqueue the message
		if (! preg_match('#^<pre|^<div#i', $msg)) {
			$msg = '<pre style="line-height: 1.6em;">' . $msg . '</pre>';
		}
		JFactory::getApplication()->enqueueMessage('<h3>' .
				JText::_('COM_CLM_TURNIER_DESC') . '</h3>' . $msg, $type);
	}

	/**
	 * Delete files that should not exist
	 *
	 * @return void
	 *
	 * @see joomla-cms/administrator/components/com_admin/script.php
	 */
	private function deleteUnexistingFiles() {
		$files = array (
				// Release 2.0
				'/administrator/language/de-DE/de-DE.com_clm_turnier.sys.ini',
				'/administrator/language/en-GB/en-GB.com_clm_turnier.sys.ini',
				// Release 3.2.0
				'/administrator/components/com_clm_turnier/layouts/form/fields/sonderranglisten-j4.php',
				'/components/com_clm_turnier/helpers/html/clm_turnier.php',
				// Release 3.2.1
				'/media/com_clm_turnier/js/sonderranglisten-j4.php'
		);

		$folders = array (
				// Release 3.0
				'/components/com_clm_turnier/includes',
				// Release 3.2.0
				'/administrator/components/com_clm_turnier/helpers/html',
				'/administrator/components/com_clm_turnier/helpers',
				// Release 3.2.1
				'/components/com_clm_turnier/classes/'
		);

		jimport('joomla.filesystem.file');
		foreach ($files as $file) {
			if (JFile::exists(JPATH_ROOT . $file) &&
					! JFile::delete(JPATH_ROOT . $file)) {
				$this->enqueueMessage(JText::sprintf('FILES_JOOMLA_ERROR_FILE_FOLDER', $file), 'warning');
			}
		}

		jimport('joomla.filesystem.folder');
		foreach ($folders as $folder) {
			if (JFolder::exists(JPATH_ROOT . $folder) &&
					! JFolder::delete(JPATH_ROOT . $folder)) {
				$this->enqueueMessage(JText::sprintf('FILES_JOOMLA_ERROR_FILE_FOLDER', $folder), 'warning');
			}
		}
	}

	/**
	 * Method to load and merge the ''config.xml'' file with the extens params.
	 *
	 * @param \stdClass $parent
	 *        	- Parent object calling object.
	 * @return void
	 */
	private function loadConfigXml($parent) {
		$file = $parent->getParent()->getPath('extension_administrator') .
				'/config.xml';
		$defaults = $this->parseConfigFile($file);
		if ($defaults === '{}') {
			return;
		}

		$manifest = $parent->getParent()->getManifest();
		$type = $manifest->attributes()->type;

		try {
			$db = JFactory::getDBO();
			$query = $db->getQuery(true)->select($db->quoteName(array (
					'extension_id', 'params' )))->from($db->quoteName('#__extensions'))->where($db->quoteName('type') .
					' = ' . $db->quote($type))->where($db->quoteName('element') .
					' = ' . $db->quote($parent->getElement()))->where($db->quoteName('name') .
					' = ' . $db->quote($parent->getName()));
			$db->setQuery($query);
			$row = $db->loadObject();
			if (! isset($row)) {
				$this->enqueueMessage(JText::_('COM_CLM_TURNIER_ERROR_CONFIG_LOAD'), 'warning');
				return;
			}

			$params = json_decode($row->params, true);
			if (json_last_error() == JSON_ERROR_NONE && is_array($params)) {
				$result = array_merge(json_decode($defaults, true), $params);
				$defaults = json_encode($result);
			}
		} catch (Exception $e) {
			$this->enqueueMessage($e->getMessage(), 'warning');
			return;
		}

		try {
			$query = $db->getQuery(true);
			$query->update($db->quoteName('#__extensions'));
			$query->set($db->quoteName('params') . ' = ' . $db->quote($defaults));
			$query->where($db->quoteName('extension_id') . ' = ' .
					$db->quote($row->extension_id));

			$db->setQuery($query);
			$db->execute();
		} catch (Exception $e) {
			$this->enqueueMessage($e->getMessage(), 'warning');
		}
	}

	/**
	 * Method to parse the ''config.xml'' of an extension, build the JSON
	 * string for its default parameters, and return the JSON string.
	 *
	 * @param string $file
	 *
	 * @return string JSON string of parameter values
	 *        
	 * @note This method must always return a JSON compliant string
	 * @see joomla-cms/libraries/cms/installer/installer.php
	 */
	private function parseConfigFile($file) {
		if (! file_exists($file)) {
			return '{}';
		}

		$xml = simplexml_load_file($file);
		if (! ($xml instanceof SimpleXMLElement)) {
			return '{}';
		}

		if (! isset($xml->fieldset)) {
			return '{}';
		}

		// Getting the fieldset tags
		$fieldsets = $xml->fieldset;

		// Creating the data collection variable:
		$ini = array ();

		// Iterating through the fieldsets:
		foreach ($fieldsets as $fieldset) {
			if (! count($fieldset->children())) {
				// Either the tag does not exist or has no children therefore we return zero files processed.
				return '{}';
			}

			// Iterating through the fields and collecting the name/default values:
			foreach ($fieldset as $field) {
				// Check against the null value since otherwise default values like "0"
				// cause entire parameters to be skipped.

				if (($name = $field->attributes()->name) === null) {
					continue;
				}

				if (($value = $field->attributes()->default) === null) {
					continue;
				}

				$ini[(string) $name] = (string) $value;
			}
		}

		return json_encode($ini);
	}

	/**
	 * Method to load the sample data into the database.
	 *
	 * @param \stdClass $parent
	 *        	- Parent object calling object.
	 * @return void
	 */
	private function loadSamples($parent) {
		try {
			$db = JFactory::getDbo();
			$query = $db->getQuery(true)->select($db->quoteName('id'))->from($db->quoteName('#__clm_turniere_grand_prix'));
			$db->setQuery($query);
			$db->execute();

			if ($db->getNumRows() == 0) {
				$element = new SimpleXMLElement('<sql><file driver="mysql" charset="utf8">sql/samples.sql</file></sql>');
				if ($parent->getParent()->parseSQLFiles($element) > 0) {
					$this->enqueueMessage(JText::_('COM_CLM_TURNIER_INSTALL_SAMPLES'), 'notice');
				}
			}
		} catch (Exception $e) {
			$this->enqueueMessage($e->getMessage(), 'warning');
		}
	}

	/**
	 * Method to generate the changelog data.
	 * 
	 * @param \stdClass $parent
	 *        	- Parent object calling object.
	 * @return string
	 */
	private function getChangelog($parent) {
		$file = $parent->getParent()->getPath('extension_administrator') .
				'/changelog.txt';
		if (! file_exists($file)) {
			return '';
		}

		$changelog = file_get_contents($file);
		$changelog = preg_replace("#\r#s", '', $changelog);

		$parts = explode("\n\n", $changelog);
		if (empty($parts)) {
			return '';
		}

		$version = '';
		$changelog = array ();

		foreach ($parts as $part) {
			$part = trim($part);

			// changeloh eintrag ?!
			if (! preg_match('#.*(\d+\.\d+\.\d+ - \[.*\]).*#i', $part)) {
				continue;
			}

			// changelog version ermitteln
			if (preg_match('#.*(\d+\.\d+\.\d+) - \[.*\].*#i', $part, $version)) {
				$version = $version[1];
			}

			if (version_compare($version, $this->fromVersion, '<=')) {
				break;
			}

			$part = preg_replace('#.*(\d+\.\d+\.\d+ - \[.*\]).*#', '<b>$1</b><pre style="line-height: 1.6em;">', $part);

			$changelog[] = $part . '</pre>';
		}

		$changelog = implode("\n\n", $changelog);

		// Change Type: @see changelog.txt
		$change_types = [ '+' => [ 'Addition', 'success' ],
				'-' => [ 'Removed', 'danger' ], '^' => [ 'Change', 'warning' ],
				'*' => [ 'Security Fix', 'info' ], '#' => [ 'Bug Fix', 'info' ],
				'!' => [ 'Note', 'info' ],
				'$' => [ 'Language fix or change', 'info' ] ];
		foreach ($change_types as $char => $type) {
			$changelog = preg_replace('#\n' . preg_quote($char, '#') . ' #', "\n" .
					'<span class="label label-sm label-' . $type[1] . '" title="' .
					$type[0] . '">' . $char . '</span> ', $changelog);
		}
		$changelog = preg_replace('#\n  #', "\n   ", $changelog);

		$title = JText::sprintf('COM_CLM_TURNIER_CHANGELOG', $this->fromVersion);

		return '<div style="max-height: 240px; padding-right: 20px; margin-right: -20px; overflow: auto;">' .
				'<p><b>' . $title . '</b></p>' . $changelog . '</div>';
	}
}