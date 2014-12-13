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
        
        	$html[] = parent::getInput();
        	$html[] = '<nobr /><a class="modal" rel="{handler: \'iframe\', size: {x: 200, y: 400}}" title="';
        	$html[] = JText::_("Select classes...");
                $html[] = '" href="/index.php?option=com_receivements&view=classi&tmpl=component">';
        	$html[] = "<strong>&lt;-</strong>";
        	$html[] = "</a>";
        
		return implode($html);
	}
}