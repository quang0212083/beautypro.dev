<?php
/**
 * CategoryBlock Joomla! 3.0 Native Component
 * @version 1.8.0
 * @author DesignCompass corp <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/


// No direct access
defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.formvalidation');
JHtml::_('behavior.tooltip');
echo '<p style="text-align:left;">Upgrade to <a href="http://www.joomlaboat.com/category-block#pro-version" target="_blank">PRO version</a> to get more features</p>';
echo '<form id="adminForm" action="'.JRoute::_('index.php?option=com_categoryblock').'" method="post" class="form-inline">';
?>

        <fieldset class="adminform">
                <legend><?php echo JText::_( 'COM_CATEGORYBLOCK_FORM_DETAILS' ); ?></legend>
                
                <p>
                <?php echo $this->form->getLabel('profilename'); ?>
				<?php echo $this->form->getInput('profilename'); ?>
                </p>
                <br />
                <div style="border: 1px dotted #000000;padding:10px;margin:0px;">
                        <h4>Category</h4>
                        <table style="border:none;">
                                <tbody>
                                        <tr><td><?php echo $this->form->getLabel('showtitle'); ?></td><td>:</td><td><fieldset class="radio btn-group"><?php echo $this->form->getInput('showtitle'); ?></td></tr>
					<tr><td><?php echo $this->form->getLabel('categorytitlecssstyle'); ?></td><td>:</td><td>IN PRO VERSION ONLY<br/>*Use [level] for sub-category level value.</td></tr>
                                        <tr><td><?php echo $this->form->getLabel('showcatdesc'); ?></td><td>:</td><td><fieldset class="radio btn-group"><?php echo $this->form->getInput('showcatdesc'); ?></td></tr>
					<tr><td><?php echo $this->form->getLabel('categorydescriptioncssstyle'); ?></td><td>:</td><td>IN PRO VERSION ONLY</td></tr>
                                </tbody>
                        </table>
                </div>
                <br />
                <div style="border: 1px dotted #000000;padding:10px;margin:0px;">
                        <h4>Articles</h4>
                        <table style="border:none;">
                                <tbody>
                                        <tr><td><?php echo $this->form->getLabel('showfeaturedonly'); ?></td><td>:</td><td><fieldset class="radio btn-group"><?php  echo $this->form->getInput('showfeaturedonly'); ?></fieldset></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('recursive'); ?></td><td>:</td><td><fieldset class="radio btn-group"><?php  echo $this->form->getInput('recursive'); ?></fieldset></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('randomize'); ?></td><td>:</td><td>IN PRO VERSION ONLY</td></tr>
                                        <tr><td><?php echo $this->form->getLabel('orderby'); ?></td><td>:</td><td><?php  echo $this->form->getInput('orderby'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('orderdirection'); ?></td><td>:</td><td><fieldset class="radio btn-group"><?php  echo $this->form->getInput('orderdirection'); ?></fieldset></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('thelimit'); ?></td><td>:</td><td><?php  echo $this->form->getInput('thelimit'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('skipnarticles'); ?></td><td>:</td><td><?php  echo $this->form->getInput('skipnarticles'); ?></td></tr>
					<tr><td><?php echo $this->form->getLabel('targetwindow'); ?></td><td>:</td><td><?php  echo $this->form->getInput('targetwindow'); ?></td></tr>
                                </tbody>
                        </table>
                </div>
                
                <br />
                <div style="border: 1px dotted #000000;padding:10px;margin:0px;">
                        <h4>View Settings (General)</h4>
                        <table style="border:none;">
                                <tbody>
                                        <tr><td><?php echo $this->form->getLabel('modulecssstyle'); ?></td><td>:</td><td>IN PRO VERSION ONLY</td></tr>
                                        <tr><td><?php echo $this->form->getLabel('columns'); ?></td><td>:</td><td><?php  echo $this->form->getInput('columns'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('padding'); ?></td><td>:</td><td><?php  echo $this->form->getInput('padding'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('pagination'); ?></td><td>:</td><td><?php  echo $this->form->getInput('pagination'); ?></td></tr>
					<tr><td><?php echo $this->form->getLabel('orientation'); ?></td><td>:</td><td><?php  echo $this->form->getInput('orientation'); ?></td></tr>

                                </tbody>
                        </table>
                </div>
                
                <br />
                <div style="border: 1px dotted #000000;padding:10px;margin:0px;">
                        <h4>Single Article Processing Settings</h4>
                        <table style="border:none;">
                                <tbody>
					<tr><td><?php echo $this->form->getLabel('contentsource'); ?></td><td>:</td><td><?php  echo $this->form->getInput('contentsource'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('wordcount'); ?></td><td>:</td><td><?php  echo $this->form->getInput('wordcount'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('charcount'); ?></td><td>:</td><td><?php  echo $this->form->getInput('charcount'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('imagewidth'); ?></td><td>:</td><td><?php  echo $this->form->getInput('imagewidth'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('imageheight'); ?></td><td>:</td><td><?php  echo $this->form->getInput('imageheight'); ?></td></tr>
					<tr><td><?php echo $this->form->getLabel('default_image'); ?></td><td>:</td><td><?php  echo $this->form->getInput('default_image'); ?></td></tr>
					<tr><td><?php echo $this->form->getLabel('storethumbnails'); ?></td><td>:</td><td><?php  echo $this->form->getInput('storethumbnails'); ?></td></tr>
					<tr><td><?php echo $this->form->getLabel('thumbnailspath'); ?></td><td>:</td><td>IN PRO VERSION ONLY (default: images/categoryblock)</td></tr>
					
                                        <tr><td><?php echo $this->form->getLabel('cleanbraces'); ?></td><td>:</td><td><fieldset class="radio btn-group"><?php  echo $this->form->getInput('cleanbraces'); ?></fieldset></td></tr>
					<tr><td><?php echo $this->form->getLabel('customitemid'); ?></td><td>:</td><td><?php  echo $this->form->getInput('customitemid'); ?></td></tr>
					<tr><td><?php echo $this->form->getLabel('overwritearticleid'); ?></td><td>:</td><td>IN PRO VERSION ONLY</td></tr>
					<tr><td><?php echo $this->form->getLabel('connectwithmenu'); ?></td><td>:</td><td><fieldset class="radio btn-group"><?php  echo $this->form->getInput('connectwithmenu'); ?></fieldset></td></tr>
                                        
                                </tbody>
                        </table>
                </div>
                
                

                <br/>
	
                <table style="display: block;">
                        <tbody>
                                <tr>
                                        <td style="vertical-align: top;border: 1px dotted #000000;padding:10px;margin:0px;">
                
                        <h4>Layout Settings</h4>
                        <table style="border:none;">
                                <tbody>
                                        <tr><td><?php echo $this->form->getLabel('blockcssstyle'); ?></td><td>:</td><td>IN PRO VERSION ONLY<br/>*Use [level] for sub-category level value.</td></tr>
                                        <tr><td><?php echo $this->form->getLabel('showarticletitle'); ?></td><td>:</td><td><fieldset class="radio btn-group"><?php  echo $this->form->getInput('showarticletitle'); ?></fieldset></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('titlecssstyle'); ?></td><td>:</td><td>IN PRO VERSION ONLY</td></tr>
                                        <tr><td><?php echo $this->form->getLabel('imagecssstyle'); ?></td><td>:</td><td>IN PRO VERSION ONLY</td></tr>
                                        <tr><td><?php echo $this->form->getLabel('descriptioncssstyle'); ?></td><td>:</td><td>IN PRO VERSION ONLY</td></tr>
                                        <tr><td><?php echo $this->form->getLabel('showcreationdate'); ?></td><td>:</td><td><fieldset class="radio btn-group"><?php  echo $this->form->getInput('showcreationdate'); ?></fieldset></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('dateformat'); ?></td><td>:</td><td><?php  echo $this->form->getInput('dateformat'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('datecssstyle'); ?></td><td>:</td><td>IN PRO VERSION ONLY</td></tr>
                                        <tr><td><?php echo $this->form->getLabel('showreadmore'); ?></td><td>:</td><td><fieldset class="radio btn-group"><?php  echo $this->form->getInput('showreadmore'); ?></fieldset></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('readmorestyle'); ?></td><td>:</td><td>IN PRO VERSION ONLY</td></tr>                                        
                                        <tr><td><?php echo $this->form->getLabel('gotocomment'); ?></td><td>:</td><td><fieldset class="radio btn-group"><?php  echo $this->form->getInput('gotocomment'); ?></fieldset></td></tr>
					<tr><td><?php echo $this->form->getLabel('allowcontentplugins'); ?></td><td>:</td><td><fieldset class="radio btn-group"><?php  echo $this->form->getInput('allowcontentplugins'); ?></fieldset></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('titleimagepos'); ?></td><td>:</td><td><?php  echo $this->form->getInput('titleimagepos'); ?></td></tr>
                                       
                                        
                                </tbody>
                        </table>
                
                </td>
                <td style="vertical-align: top;border: 1px dotted #000000;padding:10px;margin:0px;">
			
				<p>
					<?php echo $this->form->getLabel('blocklayout'); ?>
					<?php echo $this->form->getInput('blocklayout'); ?>
				</p>
				
				<h4>Custom Block Layout <br/><span style="font-size:12px;font-weight:normal;">This may overwrite Layout Settings</span></h4>
				<?php echo $this->form->getLabel('customblocklayouttop'); ?><br/>
				<?php echo $this->form->getInput('customblocklayouttop'); ?><br/>
				<?php echo $this->form->getLabel('customblocklayout'); ?><br/>
                                <?php echo $this->form->getInput('customblocklayout'); ?><br/>
				<?php echo $this->form->getLabel('customblocklayoutbottom'); ?><br/>
				<?php echo $this->form->getInput('customblocklayoutbottom'); ?>
			
		</td>                       
		<td style="vertical-align: middle;padding-left:10px;">
			<p style="font-weight:bold;">
                                Use specail "tags":
                        </p>
                        <p>
				[image] - Image<br/>
				[image:width,height,any custom options] - Image<br/>
				[alt] - Image Alt<br/>
                                [link] - Link (without &li;a href...)<br/>
                                [articletitle] - Article Title<br/>
                                [article] - Shortened Article <br/>
				[article:image_intro,width,height,any custom options] - Article Intro Image<br/>
				[article:image_fulltext,width,height,any custom options] - Article Full Text Image<br/>
				[article:urla] - Article URL (A) Link<br/>
				[article:urlb] - Article URL (B) Link<br/>
				[article:urlc] - Article URL (C) Link<br/>
				[introtext] - Full Intro Text<br/>
				[fulltext] - Full Text<br/>
				[hits] - View Count<br/>
				[createdby] - Author<br/>
				[username]- Author Username<br/>
                                [creationdate:dateformat] - Creation Date<br/>
                                [readmore] - Readmore<br/>
				[readmore:custom label,target,custom options] - Readmore<br/>
                                [gotocomments] - Goto Comments<br/>
				[gotocomments:custom label,target,custom options] - Goto Comments<br/>
				[id] - Article ID<br/>
				[metakey] - Meta Keywords<br/>
				[metadescription] - Meta Description<br/>
				[metadata:<i>author | robots | rights | xreference</i>]<br/>
				<br/><b>If statements:</b><br/>
				
				[if:active] .... [endif:active]<br/>
				[ifnot:active] .... [endifnot:active]<br/>
				
				[if:beforeactive] .... [endif:beforeactive]<br/>
				[ifnot:beforeactive] .... [endifnot:beforeactive]<br/>
				
				[if:first] .... [endif:first]<br/>
				[ifnot:first] .... [endifnot:first]<br/>
				[if:last] .... [endif:last]<br/>
				[ifnot:last] .... [endifnot:last]<br/>
				
				[if:item_even] .... [endif:item_even]<br/>
				[if:item_odd] .... [endif:item_odd]<br/>
				
				[if:column_even] .... [endif:column_even]<br/>
				[if:column_odd] .... [endif:column_odd]<br/>
				
				[if:line_even] .... [endif:line_even]<br/>
				[if:line_odd] .... [endif:line_odd]<br/>

                        </p>                  
		</td>
                </tr>
                </tbody>
			
			
                </table>
		

                
                <br />
                <div style="border: 1px dotted #000000;padding:10px;margin:0px;">
                        <h4>Module Related</h4>
                        <table style="border:none;">
                                <tbody>
                                        <tr><td><?php echo $this->form->getLabel('modulewidth'); ?></td><td>:</td><td><?php  echo $this->form->getInput('modulewidth'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('moduleheight'); ?></td><td>:</td><td><?php  echo $this->form->getInput('moduleheight'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('overflow'); ?></td><td>:</td><td><?php  echo $this->form->getInput('overflow'); ?></td></tr>
                                </tbody>
                        </table>
                </div>
                
                

        </fieldset>
        <div>
                <input type="hidden" name="task" value="profileform.edit" />
		<input type="hidden" name="id" value="<?php echo (int) $this->item->id; ?>" />
                <?php echo JHtml::_('form.token'); ?>
        </div>
</form>
