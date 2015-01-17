<?php
/**
 * @version     0.0.1
 * @package     com_receivements
 * @copyright   Copyright (C) 2014. Tutti i diritti riservati.
 * @license     GNU General Public License versione 2 o successiva; vedi LICENSE.txt
 * @author      Paolo Bozzo <info@dbfweb.com> - http://dbfweb.com
 */

defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');

JFormHelper::loadFieldClass('text');
JHTML::_('behavior.modal');

/**
 * Supports an HTML select list of categories
 */
class JFormFieldSetTime extends JFormFieldText
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'SetTime';

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
        
        	$html[] = parent::getInput();
        	$html[] = '<a class="modal my_field" rel="{handler: \'iframe\', size: {x: 175, y: 110}}" style="border:none" title="';
        	$html[] = JText::_('COM_RECEIVEMENTS_SELECT_TIME');
                $html[] = '" href="/index.php?option=com_receivements&view=oraform&layout=settime&tmpl=component&parent=';
                $html[] = $this->id;
                $html[] = '">';
        	$html[] = '<img style="padding:0px;height:120%" src="' . JURI::base(true) . '/components/com_receivements/assets/icons/pen.png' . '" alt="pen_icon">';
        	$html[] = "</a>";
        
		return implode($html);
	}
}