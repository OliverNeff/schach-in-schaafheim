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
    /**
     * This method is executed before any Joomla uninstall action, such as file
     * removal or database changes.
     *
     * @param \stdClass $parent
     *            - Parent object calling this method.
     *            
     * @return void
     */
    public function uninstall($parent) {
        $removeTables = false;
        if (file_exists(JPATH_SITE . '/components/com_clm/clm/index.php')) {
            require_once JPATH_SITE . '/components/com_clm/clm/index.php';
            $removeTables = ! clm_core::$db->config()->database_safe;
        } else {
            try {
                $db = JFactory::getDBO();
                $columns = $db->getTableColumns('#__clm_config');
            } catch (RuntimeException $r) {
                $removeTables = true;
            }
        }

        if ($removeTables == true) {
            $element = new SimpleXMLElement('<sql><file driver="mysql" charset="utf8">sql/uninstall.sql</file></sql>');
            $result = $parent->getParent()->parseSQLFiles($element);

            $this->enqueueMessage(JText::_('COM_CLM_TURNIER_DELETE_TABLES'), 'notice');
        }
    }

    /**
     * This method is called after a component is updated.
     *
     * @param \stdClass $parent
     *            - Parent object calling object.
     *            
     * @return void
     */
    public function update($parent) {
        // remove depricated language files
        foreach (array(
            'de-DE/de-DE.com_clm_turnier.sys.ini',
            'en-GB/en-GB.com_clm_turnier.sys.ini'
        ) as $file) {
            $filename = JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'language' . DIRECTORY_SEPARATOR . $file;
            if (file_exists($filename)) {
                unlink($filename);
            }
        }
    }

    /**
     * Runs just before any installation action is preformed on the component.
     * Verifications and pre-requisites should run in this function.
     *
     * @param string $type
     *            - Type of PreFlight action. Possible values are:
     *            - * install
     *            - * update
     *            - * discover_install
     * @param \stdClass $parent
     *            - Parent object calling object.
     *            
     * @return void
     */
    public function preflight($type, $parent) {
        // Chess League Manager installiert ?
        if (! JComponentHelper::isInstalled('com_clm')) {
            $this->enqueueMessage(JText::_('COM_CLM_TURNIER_REQ_COM_CLM'), 'warning');
        }
    }

    /**
     * Runs right after any installation action is preformed on the component.
     *
     * @param string $type
     *            - Type of PostFlight action. Possible values are:
     *            - * install
     *            - * update
     *            - * discover_install
     * @param \stdClass $parent
     *            - Parent object calling object.
     *            
     * @return void
     */
    public function postflight($type, $parent) {
        $notice = array();

        if ($type == 'install') {
            $notice[] = JText::_('COM_CLM_TURNIER_CONFIG_OPTIONS');
        }

        // Beispieldaten importieren
        $query = 'SELECT id FROM #__clm_turniere_grand_prix';
        $db = JFactory::getDbo();
        $db->setQuery($query);
        $db->execute();
        $num_rows = $db->getNumRows();
        if ($num_rows == 0) {
            $element = new SimpleXMLElement('<sql><file driver="mysql" charset="utf8">sql/samples.sql</file></sql>');
            $result = $parent->getParent()->parseSQLFiles($element);

            $notice[] = JText::_('COM_CLM_TURNIER_INSTALL_SAMPLES');
        }

        if (! empty($notice)) {
            $this->enqueueMessage(implode("\n", $notice), 'notice');
        }
    }

    /**
     * Enqueue a system message.
     *
     * @param   string  $msg   The message to enqueue.
     * @param   string  $type  The message type. Default is message.
     *
     * @return  void
     */
    private function enqueueMessage($msg, $type = 'message') {
        // Don't add empty messages.
        if (!strlen(trim($msg))) {
            return;
        }
    
        // Enqueue the message.
        JFactory::getApplication()->enqueueMessage(
            '<h3>' . JText::_('COM_CLM_TURNIER_DESC') . '</h3>'
                . '<pre style="line-height: 1.6em;">' . $msg . '</pre>', 
            $type
        );
    }
}