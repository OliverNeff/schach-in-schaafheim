<?php

/*
 *  pgn4web javascript chessboard
 *  copyright (C) 2009-2014 Paolo Casaschi
 *  see README file and http://pgn4web.casaschi.net
 *  for credits, license and more details
 */

defined('_JEXEC') or die('Restricted access');

function embedchessboard_postinstall_condition()
{
	$db = JFactory::getDbo();
	$query = $db->getQuery(true)
		->select('*')
		->from($db->qn('#__extensions'))
		->where($db->qn('type') . ' = ' . $db->q('plugin'))
		->where($db->qn('enabled') . ' = ' . $db->q('1'))
		->where($db->qn('folder') . ' = ' . $db->q('content'))
		->where($db->qn('element') . ' = ' . $db->q('embedchessboard'));
	$db->setQuery($query);
	$enabled_plugins = $db->loadObjectList();
	return count($enabled_plugins) == 0;
}

function embedchessboard_postinstall_action()
{
	$db = JFactory::getDbo();
	$query = $db->getQuery(true)
 		->select('*')
		->from($db->qn('#__extensions'))
		->where($db->qn('type') . ' = ' . $db->q('plugin'))
		->where($db->qn('enabled') . ' = ' . $db->q('0'))
		->where($db->qn('folder') . ' = ' . $db->q('content'))
		->where($db->qn('element') . ' = ' . $db->q('embedchessboard'));
	$db->setQuery($query);
	$enabled_plugins = $db->loadObjectList();

	$query = $db->getQuery(true)
		->update($db->qn('#__extensions'))
		->set($db->qn('enabled') . ' = ' . $db->q(1))
		->where($db->qn('type') . ' = ' . $db->q('plugin'))
		->where($db->qn('folder') . ' = ' . $db->q('content'))
		->where($db->qn('element') . ' = ' . $db->q('embedchessboard'));
	$db->setQuery($query);
	$db->execute();

	$url = 'index.php?option=com_plugins&task=plugin.edit&extension_id=' . $enabled_plugins[0]->extension_id;
	JFactory::getApplication()->redirect($url);
}

?>
