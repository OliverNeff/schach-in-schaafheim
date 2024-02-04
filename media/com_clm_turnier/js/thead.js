/**
 * Chess League Manager Turnier Erweiterungen
 *
 * @copyright (C) 2018 Andreas Hrubesch; All rights reserved
 * @license GNU General Public License; see https://www.gnu.org/licenses/gpl.html
 * @author Andreas Hrubesch
 */

function floatThead() {
	jQuery(".theadFloatingHeader").floatThead({position: "absolute"});
}

jQuery(window).ready(function(){
	var bounceInDuration = jQuery(".wow.bounceInDown").attr("data-wow-duration");
	if (bounceInDuration) {
		window.setTimeout( floatThead, (parseInt(bounceInDuration) * 1000) + 100 );
	} else {
		floatThead();
	}
});
