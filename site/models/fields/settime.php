<?php
/**
 * @version     1.0.5
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
		/*
                $v = new JVersion();
                if ($v->RELEASE[0] == '3') {
                        $x = 480; $y = 140;
                } else {
                        $x = 175; $y = 110;
                }
                */
                $x = 175; $y = 140;
        
        	$html[] = parent::getInput();
        	$html[] = '<script type="text/javascript">';
        	$html[] = '/* <![CDATA[ */';
                $html[] = 'document.write("';
        	$html[] = '<a class=\"modal my_field\" rel=\"{handler: \'iframe\', size: {x: '.$x.', y: '.$y.'}}\" style=\"border:none\" title=\"';
        	$html[] = JText::_('COM_RECEIVEMENTS_SELECT_TIME');
                $html[] = '\" href=\"'.JRoute::_('index.php?option=com_receivements&amp;view=oraform&amp;layout=settime&amp;tmpl=component&amp;parent='.$this->id);
                $html[] = '\">';
        	$html[] = '<img style=\"padding:0px;height:120%\" src=\"' . JURI::base(true) . '/components/com_receivements/assets/icons/pen.png' . '\" alt=\"pen_icon\" />';
        	$html[] = '</a>");';
        	$html[] = '/* ]]> */';
                $html[] = '</script>';
        
		return implode($html);
	}
}