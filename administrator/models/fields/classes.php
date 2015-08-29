<?php
/**
 * @version     0.5.0
 * @package     com_receivements
 * @copyright   Copyright (C) 2014. Tutti i diritti riservati.
 * @license     GNU General Public License versione 2 o successiva; vedi LICENSE.txt
 * @author      Paolo Bozzo - pagolo.bozzo AT gmail.com - http://dbfweb.com
 */

defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');

JFormHelper::loadFieldClass('text');
JHTML::_('behavior.modal');

/**
 * Supports an HTML select list of categories
 */
class JFormFieldClasses extends JFormFieldText
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'Classes';

	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	protected function getInput()
	{
		// Initialize variables.
		$html = array();
                
                $html[] = '<div style="width:120%"><div style="float:left">';
        	$html[] = parent::getInput(); 
        	$html[] = '</div><div style="float:left">';
        	$html[] = '<a class="modal my_field" rel="{handler: \'iframe\', size: {x: 140, y: 400}}" style="border:none" title="';
        	$html[] = JText::_('COM_RECEIVEMENTS_SELECT_CLASSES');
                $html[] = '" href="/index.php?option=com_receivements&view=classi&tmpl=component">';
        	$html[] = '<img style="padding:0px;height:120%;border:none" src="' . JURI::base(true) . '/components/com_receivements/assets/images/pen.png' . '" alt="pen_icon">';
        	$html[] = "</a></div></div>";

        	
        
		return implode($html);
	}
}