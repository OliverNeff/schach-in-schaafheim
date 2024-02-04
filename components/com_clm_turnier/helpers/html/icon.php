<?php
/**
 * Chess League Manager Turnier Erweiterungen
 *
 * @copyright (C) 2018 Andreas Hrubesch; All rights reserved
 * @license GNU General Public License; see https://www.gnu.org/licenses/gpl.html
 * @author Andreas Hrubesch
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * HTML Utility Klasse für Icons
 *
 * @since 2.1
 */
abstract class JHtmlIcon {

	/**
	 * erstellt einen Popup Link zum Drucken der Grand Prix Tabelle.
	 *
	 * @param CMSObject $state
	 *        	Grand Prix Model State
	 * @param Registry $params
	 *        	Grand Prix Parameters
	 * @param array $attribs
	 *        	optionale Atrribute für den Link
	 * @param boolean $legacy
	 *        	True für legacy Images, false für icomoon basierte Grafiken
	 * @return string HTML Markup für den Link
	 */
	public static function print_popup($state, $params, $attribs = array(), $legacy = false) {
		$link = Grand_PrixHelperRoute::getGrandPrixRoute($state->get('grand_prix.id'), $state->get('grand_prix.catidEdition'), $state->get('grand_prix.tids'), $state->get('grand_prix.filter'));
		$link .= '&tmpl=component&print=1';

		$status = 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no';

		$text = JLayoutHelper::render('joomla.content.icons.print_popup', array('params' => $params,'legacy' => $legacy));

		$attribs['title'] = JText::sprintf('JGLOBAL_PRINT_TITLE', htmlspecialchars($params->get('page_title'), ENT_QUOTES, 'UTF-8'));
		$attribs['onclick'] = "window.open(this.href,'win2','" . $status . "'); return false;";
		$attribs['rel'] = 'nofollow';

		return JHtml::_('link', JRoute::_($link), $text, $attribs);
	}

	/**
	 * erstellt einen Link zum Drucken der Grand Prix Tabelle.
	 *
	 * @param CMSObject $state
	 *        	Grand Prix Model State
	 * @param Registry $params
	 *        	Grand Prix Parameters
	 * @param array $attribs
	 *        	optionale Atrribute für den Link
	 * @param boolean $legacy
	 *        	True für legacy Images, false für icomoon basierte Grafiken
	 * @return string HTML Markup für den Link
	 */
	public static function print_screen($state, $params, $attribs = array(), $legacy = false) {
		$text = JLayoutHelper::render('joomla.content.icons.print_screen', array('params' => $params,'legacy' => $legacy));

		return '<a href="#" onclick="window.print();return false;">' . $text . '</a>';
	}

	/**
	 * erstellt einen Link um einen Link der Grand Prix Tabelle per E-Mail zu versenden.
	 *
	 * @param CMSObject $state
	 *        	Grand Prix Model State
	 * @param Registry $params
	 *        	Grand Prix Parameters
	 * @param array $attribs
	 *        	optionale Atrribute für den Link
	 * @param boolean $legacy
	 *        	True für legacy Images, false für icomoon basierte Grafiken
	 * @return string HTML Markup für den Link
	 */
	public static function email($state, $params, $attribs = array(), $legacy = false) {
		JLoader::register('MailtoHelper', JPATH_SITE . '/components/com_mailto/helpers/mailto.php');

		$uri = JUri::getInstance();
		$base = $uri->toString(array('scheme','host','port'));
		$template = JFactory::getApplication()->getTemplate();
		$link = $base . JRoute::_(Grand_PrixHelperRoute::getGrandPrixRoute($state->get('grand_prix.id'), $state->get('grand_prix.catidEdition'), $state->get('grand_prix.tids'), $state->get('grand_prix.filter')), false);
		$url = 'index.php?option=com_mailto&tmpl=component&template=' . $template . '&link=' . MailtoHelper::addLink($link);

		$status = 'width=400,height=450,menubar=yes,resizable=yes';

		$text = JLayoutHelper::render('joomla.content.icons.email', array('params' => $params,'legacy' => $legacy));

		$attribs['title'] = JText::_('JGLOBAL_EMAIL_TITLE');
		$attribs['onclick'] = "window.open(this.href,'win2','" . $status . "'); return false;";
		$attribs['rel'] = 'nofollow';

		return JHtml::_('link', JRoute::_($url), $text, $attribs);
	}

	/**
	 * erstellt einen Link zum Filtern der Grand Prix Tabelle.
	 *
	 * @param CMSObject $state
	 *        	Grand Prix Model State
	 * @param Registry $params
	 *        	Grand Prix Parameters
	 * @param array $attribs
	 *        	optionale Atrribute für den Link
	 * @param boolean $legacy
	 *        	True für legacy Images, false für icomoon basierte Grafiken
	 * @return string HTML Markup für den Link
	 */
	public static function filter($state, $params, $attribs = array(), $legacy = false) {
		$filter = $state->get('grand_prix.filter');
		$filter['tlnr'] = ! $filter['tlnr'];

		$link = Grand_PrixHelperRoute::getGrandPrixRoute($state->get('grand_prix.id'), $state->get('grand_prix.catidEdition'), $state->get('grand_prix.tids'), $filter);

		$attribs['text'] = 'COM_CLM_TURNIER_FILTER_TLNR';
		$attribs['span.class'] = empty($filter['tlnr']) ? 'icon-plus' : 'icon-minus';
		$text = JLayoutHelper::render('icons.filter', array('params' => $params,'attribs' => $attribs,'legacy' => $legacy), JPATH_CLM_TURNIER_COMPONENT);

		$lable = empty($filter['tlnr']) ? 'COM_CLM_TURNIER_FILTER_TLNR_PLUS' : 'COM_CLM_TURNIER_FILTER_TLNR_MINUS';
		$attribs['title'] = JText::sprintf($lable, htmlspecialchars('COM_CLM_TURNIER_FILTER_TLNR', ENT_QUOTES, 'UTF-8'));

		return JHtml::_('link', JRoute::_($link), $text, $attribs);
	}
}