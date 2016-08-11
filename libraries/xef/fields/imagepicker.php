<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

/**
 * Form Field to display a list of the layouts for module display from the module or template overrides.
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @since       11.1
 */
class JFormFieldImagePicker extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $type = 'ImagePicker';

	protected function getLabel(){
		return '';
	}

	/**
	 * Method to get the field input for module layouts.
	 *
	 * @return  string  The field input.
	 *
	 * @since   11.1
	 */
	protected function getInput()
	{

		// Load Dependencies
		$doc = JFactory::getDocument();
		$doc->addStyleSheet( JURI::root(TRUE) . '/libraries/xef/assets/css/image-picker.css' );
		$doc->addScript( JURI::root(TRUE) . '/libraries/xef/assets/js/image-picker.min.js' );
		$js = 'jQuery(document).ready(function(){
			jQuery(".img-list").imagepicker({show_label: true});
		});';
		$doc->addScriptDeclaration($js);

		// Get the client id.
		$clientId = $this->element['client_id'];

		if (is_null($clientId) && $this->form instanceof JForm)
		{
			$clientId = $this->form->getValue('client_id');
		}
		$clientId = (int) $clientId;

		$client = JApplicationHelper::getClientInfo($clientId);

		// Get the module.
		$module = (string) $this->element['module'];

		if (empty($module) && ($this->form instanceof JForm))
		{
			$module = $this->form->getValue('module');
		}

		$module = preg_replace('#\W#', '', $module);

		// Get the template.
		$template = (string) $this->element['template'];
		$template = preg_replace('#\W#', '', $template);

		// Get the style.
		if ($this->form instanceof JForm)
		{
			$template_style_id = $this->form->getValue('template_style_id');
		}

		$template_style_id = preg_replace('#\W#', '', $template_style_id);

		// If an extension and view are present build the options.
		if ($module && $client)
		{

			// Load language file
			$lang = JFactory::getLanguage();
			$lang->load($module . '.sys', $client->path, null, false, false)
				|| $lang->load($module . '.sys', $client->path . '/modules/' . $module, null, false, false)
				|| $lang->load($module . '.sys', $client->path, $lang->getDefault(), false, false)
				|| $lang->load($module . '.sys', $client->path . '/modules/' . $module, $lang->getDefault(), false, false);

			// Get the database object and a new query object.
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);

			// Build the query.
			$query->select('element, name');
			$query->from('#__extensions as e');
			$query->where('e.client_id = ' . (int) $clientId);
			$query->where('e.type = ' . $db->quote('template'));
			$query->where('e.enabled = 1');

			if ($template)
			{
				$query->where('e.element = ' . $db->quote($template));
			}

			if ($template_style_id)
			{
				$query->join('LEFT', '#__template_styles as s on s.template=e.element');
				$query->where('s.id=' . (int) $template_style_id);
			}

			// Set the query and load the templates.
			$db->setQuery($query);
			$templates = $db->loadObjectList('element');

			// Check for a database error.
			if ($db->getErrorNum())
			{
				JError::raiseWarning(500, $db->getErrorMsg());
			}

			// Build the search paths for module layouts.
			$module_path = JPath::clean($client->path . '/modules/' . $module . '/tmpl');

			// Prepare array of component layouts
			$module_layouts = array();

			// Prepare the grouped list
			$data = array();

			// Add the layout options from the module path.
			if (is_dir($module_path) && ($module_layouts = JFolder::files($module_path, '^[^_]*\.php$')))
			{
				foreach ($module_layouts as $file)
				{
					// Add an option to the module group
					$value = JFile::stripExt($file);
					$text = $lang->hasKey($key = strtoupper($module . '_LAYOUT_' . $value)) ? JText::_($key) : $value;

					$image_path = JURI::root(TRUE) . '/modules/' . $module . '/tmpl/' . $value . '.png';
					$data[] = array(
						'value' => $value,
						'text' => $text,
						'attr' => array('data-img-src'=> $image_path)
					);
				}
			}

			// Loop on all templates
			if ($templates)
			{
				foreach ($templates as $template)
				{
					// Load language file
					$lang->load('tpl_' . $template->element . '.sys', $client->path, null, false, false)
						|| $lang->load('tpl_' . $template->element . '.sys', $client->path . '/templates/' . $template->element, null, false, false)
						|| $lang->load('tpl_' . $template->element . '.sys', $client->path, $lang->getDefault(), false, false)
						|| $lang->load(
						'tpl_' . $template->element . '.sys', $client->path . '/templates/' . $template->element, $lang->getDefault(),
						false, false
					);

					$template_path = JPath::clean($client->path . '/templates/' . $template->element . '/html/' . $module);

					// Add the layout options from the template path.
					if (is_dir($template_path) && ($files = JFolder::files($template_path, '^[^_]*\.php$')))
					{
						foreach ($files as $i => $file)
						{
							// Remove layout that already exist in component ones
							if (in_array($file, $module_layouts))
							{
								unset($files[$i]);
							}
						}

						if (count($files))
						{

							foreach ($files as $file)
							{
								// Add an option to the template group
								$value = JFile::stripExt($file);
								$text = $lang->hasKey($key = strtoupper('TPL_' . $template->element . '_' . $module . '_LAYOUT_' . $value))
									? JText::_($key) : $value;
								
								$image_path = JURI::root(TRUE) . '/templates/' . $template->element . '/html/' . $module . '/' . $value . '.png';
								if( !file_exists($image_path) )
								{
									$image_path = JURI::root(TRUE) . '/libraries/xef/assets/images/imagepicker_layout_not_found.png';
								}

								$data[] = array(
									'value' => $value,
									'text' => $text,
									'attr' => array('data-img-src'=> $image_path)
								);
							}
						}
					}
				}
			}
			// Compute the current selected values
			$selected = array($this->value);

			// Pass all options
			$options = array(
			    'id' => $this->id, // HTML id for select field
			    'list.attr' => array( // additional HTML attributes for select field
			        'class'=>'img-list',
			    ),
			    'list.translate'=>false, // true to translate
			    'option.key'=>'value', // key name for value in data array
			    'option.text'=>'text', // key name for text in data array
			    'option.attr'=>'attr', // key name for attr in data array
			    'list.select'=> $selected, // value of the SELECTED field
			);

			$html = JHtmlSelect::genericlist($data, $this->name , $options);

			return $html; 
		}
		else
		{

			return '';
		}
	}
}
