<?php
/**
 * @package 	OT Social Share Plugin for Joomla! 3.3
 * @version 	$Id: otsodialshare.php - Aug 2014  OmegaTheme
 * @author 		OmegaTheme Extensions (services@omegatheme.com) - http://omegatheme.com
 * @copyright 	Copyright(C) 2014 - OmegaTheme Extensions
 * @license 	http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
**/
// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.plugin.plugin');

class plgContentOTsocialshare extends JPlugin {

    protected $autoloadLanguage = true;

    public function onContentAfterDisplay($context, &$row, &$params, $page = 0) {
    	if($context == 'com_content.article') { 
       
            $document = JFactory::getDocument();
            $ot_float = $this->params->get('ot_float', 'right');
            $ot_display = (int) $this->params->get('ot_display',1);

            require_once( dirname(__FILE__) . '/shareCount.php' );
            $url = JURI::current();
            $obj = new shareCount($url);

            $ot_facebook =(int) $this->params->get('ot_facebook',1);
            $ot_twitter =(int) $this->params->get('ot_twitter',1);
            $ot_google_plus =(int) $this->params->get('ot_google_plus',1);
            $ot_pinterest =(int) $this->params->get('ot_pinterest',1);
            $ot_linkedin =(int) $this->params->get('ot_linkedin',1);

            @$html .= '<div class="ot-social-buttons" style="float:' . $ot_float . ' ">';
            $html .='<link rel="stylesheet" type="text/css" href="' . JURI::root()  . 'plugins/content/otsocialshare/css/mod_otsocialshare.css" />
			<script src="' . JURI::root() . 'plugins/content/otsocialshare/js/jquery.reveal.js"></script>
			<div id="ot_socialshare">
			<div class="social-sharing">
			    <ul class="ot_socialshare">
			    ';
            if ($ot_facebook == 1) :
                $html .='   <li>
			            <a target="_blank" href="http://www.facebook.com/sharer.php?u=' . $url . '" class="share-facebook share">
			                <span class="share-title"><img src="' . JURI::root() . 'plugins/content/otsocialshare/images/fb.png"></span>
			                <span class="share-count">' . $obj->get_fb() . '</span>
			            </a>
			        </li>
			        ';
            endif;
            if ($ot_twitter == 1) :
                $html .=' <li>
			            <a target="_blank" href="http://twitter.com/share?url=' . $url . '" class="share-twitter share">
			                <span class="share-title"><img src="' . JURI::root() . 'plugins/content/otsocialshare/images/tw.png"></span>
			                <span class="share-count"></span>
			            </a>
			        </li>';
            endif;
            if ($ot_pinterest == 1) :
                $html .=' <li>
			            <a class="share-pinterest" href="javascript:void((function()%7Bvar%20e=document.createElement(&apos;script&apos;);e.setAttribute(&apos;type&apos;,&apos;text/javascript&apos;);e.setAttribute(&apos;charset&apos;,&apos;UTF-8&apos;);e.setAttribute(&apos;src&apos;,&apos;http://assets.pinterest.com/js/pinmarklet.js?r=&apos;+Math.random()*99999999);document.body.appendChild(e)%7D)());">
			                <span class="share-title"><img src="' . JURI::root() . 'plugins/content/otsocialshare/images/pinterest.png" /></span>
			                <span class="share-count">' . $obj->get_pinterest() . '</span>
			            </a>
			        </li> ';
            endif;
            if ($ot_google_plus == 1) :
                $html .=' <li>
			            <a target="_blank" href="http://plus.google.com/share?url=' . $url. '>" class="share-google share">
			                <span class="share-title"><img src="' . JURI::root() . 'plugins/content/otsocialshare/images/g.png"></span>
			                <span class="share-count">' . $obj->get_plusones() . '</span>
			            </a>
			        </li> ';
            endif;
            if ($ot_linkedin == 1) :
                $html .='<li>
			            <a target="_blank" href="https://www.linkedin.com/cws/share?url=' . $url . '" class="share-linkedin share">
			                <span class="share-title"><img src="' . JURI::root() . 'plugins/content/otsocialshare/images/linkedin.png"></span>
			                <span class="share-count">' . $obj->get_linkedin() . '</span>
			            </a>
			        </li> ';
            endif;
			    $html .= '<li>
			            <a href="#" class="big-link" data-reveal-id="myModal">
			            </a>
			        </li>';
				/* REMOVING Copyright warning 
				The Joomla plugin: OT Social Share is free for all websites. We're welcome any developer want to contributes the plugin. But you must keep our credits that is the very tiny image under the plugin. If you want to remove it, you may visit http://www.omegatheme.com/member/signup/additional to purchase the Removing copyrights, then you can free your self to remove it. Thank you very much. Omegatheme.com
				*/	
				$html .= '<a href="http://wwww.omegatheme.com"  style="padding:5px;">
						<img src="'.JURI::base().'plugins/content/otsocialshare/images/powered_icon.png" title="Joomla Plugin OT Social Share powered by OmegaTheme.com" alt="Joomla Plugin OT Social Share powered by OmegaTheme.com">
					</a>';	
			$html .='<ul/>
			</div>
			<div id="myModal" class="reveal-modal">
		    <h2>Share on</h2>
		    <div class="social-sharing">
		        <ul class="ot_socialshare">
		        <li>
		        <a target="_blank" href="http://www.facebook.com/sharer.php?u='.$url.' " class="share-facebook share">
		            <span class="share-title"><img src="'.JURI::root().'plugins/content/otsocialshare/images/fb.png"></span>
		        </a>
		             </li>
		        <li>
		        <a target="_blank" href="http://twitter.com/share?url=' . $url . ' " class="share-twitter share">
		            <span class="share-title"><img src="' . JURI::root() . 'plugins/content/otsocialshare/images/tw.png"></span>
		        </a>
		             </li>
		        <li>
		        <a class="share-pinterest" href="javascript:void((function()%7Bvar%20e=document.createElement(&apos;script&apos;);e.setAttribute(&apos;type&apos;,&apos;text/javascript&apos;);e.setAttribute(&apos;charset&apos;,&apos;UTF-8&apos;);e.setAttribute(&apos;src&apos;,&apos;http://assets.pinterest.com/js/pinmarklet.js?r=&apos;+Math.random()*99999999);document.body.appendChild(e)%7D)());">
		            <span class="share-title"><img src="' . JURI::root() . 'plugins/content/otsocialshare/images/pinterest.png" /></span>
		        </a>
		             </li>
		        <li>
		        <a target="_blank" href="http://plus.google.com/share?url=' . $url . '" class="share-google share">
		            <span class="share-title"><img src="' . JURI::root() . 'plugins/content/otsocialshare/images/g.png"></span>
		        </a>
		             </li>
		        <li>
		        <a target="_blank" href="https://www.linkedin.com/cws/share?url=' . $url . '" class="share-linkedin share">
		            <span class="share-title"><img src="' . JURI::root() . 'plugins/content/otsocialshare/images/linkedin.png"></span>
		        </a>
		             </li>
		        <li>
		        <a target="_blank" href="https://delicious.com/post?url=' . $url . '" class="share-linkedin share">
		            <span class="share-title"><img src="' . JURI::root() . 'plugins/content/otsocialshare/images/delicious.png"></span>
		        </a>
		             </li>
		        <li>
		        <a target="_blank" href="http://www.stumbleupon.com/submit?url=' . $url . '" class="share-google share">
		            <span class="share-title"><img src="' . JURI::root() . 'plugins/content/otsocialshare/images/stumbleupon.png"></span>
		        </a>
		             </li>
		        <li>
		        <a target="_blank" href="http://digg.com/submit?phase=2&url=' . $url . '" class="share-digg share">
		            <span class="share-title"><img src="' . JURI::root() . 'plugins/content/otsocialshare/images/digg.png"></span>
		        </a>
		             </li>
		        <li>
        		<a target="_blank" href="https://www.tumblr.com/login?share_redirect_to=/share/link?url=' . $url . '" class="share-tumblr share">
		            <span class="share-title"><img src="' . JURI::root() . 'plugins/content/otsocialshare/images/tumblr.png"></span>
		        </a>
		             </li>
		        <li>
		        <a target="_blank" href="https://ssl.reddit.com/login?dest=' . $url . '" class="share-reddit share">
		            <span class="share-title"><img src="' . JURI::root() . 'plugins/content/otsocialshare/images/reddit.png"></span>
		        </a>
		             </li>
		        <li>
		        <a target="_blank" href="https://www.blogger.com/blog-this.g?height=370&width=580&b=<a href=' . $url . '>" class="share-blogger share">
		            <span class="share-title"><img src="' . JURI::root() . 'plugins/content/otsocialshare/images/blogger.png"></span>
		        </a>
		             </li>
		        <li>
		        <a target="_blank" href="https://bufferapp.com/add?url=' . $url . '" class="share-buffer share">
		            <span class="share-title"><img src="' . JURI::root() . 'plugins/content/otsocialshare/images/buffer.png"></span>
		        </a>
		             </li>
		        <li>
		        <a target="_blank" href="http://oauth.vk.com/authorize?client_id=-1&redirect_uri=http://vk.com/share.php?url=' . $url . '&display=widget" class="share-vk share">
		            <span class="share-title"><img src="' . JURI::root() . 'plugins/content/otsocialshare/images/vk.png"></span>
		        </a>
		        </li>
		    </div>
		    <div style="clear: both"></div>
		    <a class="close-reveal-modal">&#215;</a>
			</div>
			<script src="' . JURI::root() . 'plugins/content/otsocialshare/js/social-buttons.js"></script>
			</div>';
			$html .= '<div style="clear: both;"></div></div><div style="clear: both;"></div>';
            if ($ot_display == 1) {
                $row->text = $html . $row->text;
            } else {
                $row->text .= $html;
            }
            return;
        }
    }
}

?>
