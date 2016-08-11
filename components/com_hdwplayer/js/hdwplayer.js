/*
 * @version		$Id: webplayer.js 3.0 2012-10-10 $
 * @package		Joomla
 * @copyright   Copyright (C) 2012-2013 HD Webplayer
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

function hdwplayerflashcallback(title, description, uid) {	
	if(!uid) { uid = ''; }
	if(!description) { description = ''; }
	var title_id = "hdwplayer_title" + uid;
	var desc_id  = "hdwplayer_description" + uid;
	
	effectFadeIn(title_id, title);
	effectFadeIn(desc_id, description);
}

function effectFadeIn(idname, val) {
	try {
		document.getElementById(idname).innerHTML = val;
	} catch(err) { }
}
	
function effectFadeOut(idname, val) {
	try {
	} catch(err) {}
}

function hideAd(cont, over) {
 document.getElementById(cont).style.display = 'none';
 document.getElementById(over).style.display = 'none';
}