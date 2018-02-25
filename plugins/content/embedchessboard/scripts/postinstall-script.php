<?php

/*
 *  pgn4web javascript chessboard
 *  copyright (C) 2009-2014 Paolo Casaschi
 *  see README file and http://pgn4web.casaschi.net
 *  for credits, license and more details
 */

defined('_JEXEC') or die('Restricted access');

class plgContentembedchessboardInstallerScript
{
	function install( $parent ) {
		$db = JFactory::getDbo();
		$query = 'INSERT INTO '. $db->quoteName('#__postinstall_messages') .
			' (
				`extension_id`,
				`title_key`,
				`description_key`,
				`action_key`,
				`language_extension`,
				`language_client_id`,
				`type`,
				`action_file`,
				`action`,
				`condition_file`,
				`condition_method`,
				`version_introduced`,
				`enabled`
			) VALUES (
				700,
				"PLG_CONTENT_EMBEDCHESSBOARD_POSTINSTALL_TITLE",
				"PLG_CONTENT_EMBEDCHESSBOARD_POSTINSTALL_BODY",
				"PLG_CONTENT_EMBEDCHESSBOARD_POSTINSTALL_ACTION",
				"plg_content_embedchessboard",
				1,
				"action",
				"site://plugins/content/embedchessboard/scripts/postinstall-actions.php",
				"embedchessboard_postinstall_action",
				"site://plugins/content/embedchessboard/scripts/postinstall-actions.php",
				"embedchessboard_postinstall_condition",
				"3.2.0",
				1
			)';

		$db->setQuery($query);
		$db->execute();
	}

	function uninstall( $parent )
	{
		$db = JFactory::getDbo();
		$query = 'DELETE FROM '.$db->quoteName('#__postinstall_messages') .
			' WHERE ' . $db->quoteName('language_extension') . ' = ' . $db->quote('plg_content_embedchessboard');
		$db->setQuery($query);
		$db->execute();
	}

	// function update( $parent ) { }
	// function preflight( $type, $parent ) { }
	// function postflight( $type, $parent ) { }
}

?>
