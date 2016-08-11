<?php
/**
 * @package    Jmb_Tree
 * @author     Sherza & Dmitry Rekun <support@norrnext.com>
 * @copyright  Copyright (C) 2012 - 2016 NorrNext. All rights reserved.
 * @license    GNU General Public License version 3 or later; see license.txt
 */

defined('_JEXEC') or die;

/**
 * Description field class.
 *
 * @package  Jmb_Tree
 * @since    1.0
 */
class JmbFormFieldDescription extends JFormField
{
	/**
	 * Field name.
	 *
	 * @var  string
	 */
	protected $type = 'description';

	/**
	 * Method to get field input.
	 *
	 * @return  string  HTML output.
	 */
	protected function getInput()
	{
		$html = '<div class="row-fluid">';
		$html .= '<img class="pull-left img-polaroid" style="margin-right:10px;width:125px;" src="'. JURI::root() .'modules/mod_jmb_tree/fields/jmb-tree.png" />';
		$html .= JText::_('MOD_JMB_TREE_DESCRIPTION');
		$html .= '</div>';
		$html .= '<div class="row-fluid" style="margin-top: 20px">';
		$html .= JText::_('MOD_JMB_TREE_ABOUT_NORRNEXT');
		$html .= $this->getSocialButtons();
		$html .= $this->getPaypalDonation();
		$html .= '</div>';

		return $html;
	}

	/**
	 * Method to get social buttons
	 *
	 * @return  string  Social buttons layout.
	 *
	 * @since   1.0
	 */
	private function getSocialButtons()
	{
		$html = '
			<a href="https://twitter.com/norrnext"
				class="twitter-follow-button" data-show-count="false" data-show-screen-name="false" data-lang="en"></a>
			<script>
				! function(d, s, id) {
					var js, fjs = d.getElementsByTagName(s)[0];
					if (!d.getElementById(id)) {
						js = d.createElement(s);
						js.id = id;
						js.src = "//platform.twitter.com/widgets.js";
						fjs.parentNode.insertBefore(js, fjs);
					}
				}(document, "script", "twitter-wjs");
			</script>
			<iframe
				src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Ffacebook.com%2Fnorrnext&amp;width&amp;layout=button_count&amp;action=like&amp;show_faces=false&amp;share=false&amp;height=21"
				scrolling="no"
				frameborder="0"
				style="border:none; overflow:hidden; height:20px; width:120px"
				allowTransparency="true">
			</iframe><br />
			<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
			<g:plus href="https://plus.google.com/108999239898392136664" width="200" height="131"></g:plus>
		';

		return $html;
	}

	/**
	 * Method to get PayPal donation layout
	 *
	 * @return  string  PayPal donation form layout
	 *
	 * @since   1.0
	 */
	private function getPaypalDonation()
	{
		// If we do not use form element then the next form element will not be displayed.
		// Some kind of bug...
		$html = '<div style="display: none"><form></form></div>';

		$html .= '
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
			<input type="hidden" name="cmd" value="_s-xclick">
			<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
">
			<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
			<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
		</form>
		';

		return $html;
	}
}
