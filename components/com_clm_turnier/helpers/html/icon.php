<?php
/**
 * Chess League Manager Turnier Erweiterungen
 *
 * @copyright (C) 2021 Andreas Hrubesch; All rights reserved
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
	 * @return string HTML Markup für den Link
	 */
	public static function print_popup($state, $params, $attribs = array ()) {
		$link = CLMTurnierRoute::getGrandPrixRoute($state->get('grand_prix.id'), $state->get('grand_prix.catidEdition'), $state->get('grand_prix.tids'), $state->get('grand_prix.filter'), null, $state->get('grand_prix.rid'));

		$link .= '&tmpl=component&print=1';

		$status = 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no';

		$text = JLayoutHelper::render('icons.print_popup', array (
				'params' => $params ));

		$attribs['onclick'] = "window.open(this.href,'win2','" . $status .
				"'); return false;";
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
	 * @return string HTML Markup für den Link
	 */
	public static function print_screen($state, $params, $attribs = array ()) {
		$text = JLayoutHelper::render('icons.print_screen', array (
				'params' => $params ));

		return '<a href="#" onclick="window.print();return false;">' . $text .
				'</a>';
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
	 * @return string HTML Markup für den Link
	 */
	public static function email($state, $params, $attribs = array ()) {
		$base = JUri::getInstance()->toString(array ('scheme', 'host', 'port' ));
		$link = $base .
		JRoute::_(CLMTurnierRoute::getGrandPrixRoute($state->get('grand_prix.id'), $state->get('grand_prix.catidEdition'), $state->get('grand_prix.tids'), $state->get('grand_prix.filter'), $state->get('grand_prix.rids'), $state->get('grand_prix.rid')), false);

		$subject = JFactory::getApplication()->get('sitename') . ': ' .
				JFactory::getApplication()->getDocument()->getTitle();
		$body = JText::sprintf('COM_CLM_TURNIER_EMAIL_MSG', urlencode($link));
				
		$text = JLayoutHelper::render('icons.email', array ('params' => $params ));

		$url = 'mailto:?subject=' . $subject . '&body=' . $body;

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
	 * @return string HTML Markup für den Link
	 */
	public static function filter($state, $params, $attribs = array ()) {
		$filter = $state->get('grand_prix.filter');
		$filter['tlnr'] = ! $filter['tlnr'];

		$link = CLMTurnierRoute::getGrandPrixRoute($state->get('grand_prix.id'), $state->get('grand_prix.catidEdition'), $state->get('grand_prix.tids'), $filter, $state->get('grand_prix.rids'), $state->get('grand_prix.rid'));

		$attribs['span.class'] = empty($filter['tlnr']) ? 'icon-plus' : 'icon-minus';
		$text = JLayoutHelper::render('icons.filter', array (
				'params' => $params, 'attribs' => $attribs ));

		$lable = empty($filter['tlnr']) ? 'COM_CLM_TURNIER_FILTER_TLNR_PLUS' : 'COM_CLM_TURNIER_FILTER_TLNR_MINUS';
		$attribs['title'] = JText::sprintf($lable, htmlspecialchars('COM_CLM_TURNIER_FILTER_TLNR', ENT_QUOTES, 'UTF-8'));

		return JHtml::_('link', JRoute::_($link), $text, $attribs);
	}
}