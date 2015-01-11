<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

/**
 * Form Field class for the Joomla Platform.
 * Displays options as a list of check boxes.
 * Multiselect may be forced to be true.
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @see         JFormFieldCheckbox
 * @since       11.1
 */
class JFormFieldReceivements extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $type = 'Receivements';
        protected $item;	

	protected function getInput()
	{
		// Initialize variables.
		$html = array();

		// Initialize some field attributes.
		$class = $this->element['class'] ? ' class="select_rcv ' . (string) $this->element['class'] . '"' : ' class="select_rcv"';

		// Start the checkbox field output.
		$html[] = '<fieldset id="' . $this->id . '" ' . $class . ' >';
                $html[] = '<legend>' . JText::_($this->element['label']) . '</legend>';
		$html[] = '<ul ' . $class . '>';
		$ids = JFactory::getApplication()->getUserState('com_receivements.init.prenota.id');
		$re = '^(' . str_replace('.', '|', $ids) . ')$';
		$db = JFactory::getDBO();
        	$query = 'SELECT o.id, o.id_docente, o.email AS use_email, o.inizio, o.fine, o.giorno, u.name, u.email FROM #__receivements_ore AS o LEFT JOIN #__users AS u ON ( u.id = o.id_docente ) WHERE (o.id REGEXP '.$db->Quote($re).')';
	
		// Set the query and get the result list.
		$db->setQuery($query);
		$items = $db->loadObjectlist();
		$count = 0;
		
		foreach ($items as $i => $_item)
		{
		        $this->item = $_item;
        		$options = (array) $this->getOptions();
        		if (empty($options)) continue;
        		$count++;
		        $html[] = '<li><div style="float:left;width:60%">' . JText::_('COM_RECEIVEMENTS_TEACHER') . ': ' . $_item->name . ', ' . JText::_('COM_RECEIVEMENTS_ORE_GIORNO_OPTION_' . $_item->giorno) . ' ' . substr($_item->inizio,0,5) . '/' . substr($_item->fine,0,5) . '</div><div>';
			$html[] = JHtml::_('select.genericlist', $options, 'jform[ricevimenti_'.$i.']', '', 'value', 'text', 'jform_ricevimenti_'.$i);
			$html[] = '<input type="hidden" name="jform[ricevimenti_user_'.$i.']" value="'.$_item->id_docente.'" id="jform_ricevimenti_user_'.$i.'" />';
			$html[] = '<input type="hidden" name="jform[ricevimenti_name_'.$i.']" value="'.$_item->name.'" id="jform_ricevimenti_name_'.$i.'" />';
			$html[] = '<input type="hidden" name="jform[ricevimenti_email_'.$i.']" value="'.($_item->use_email? $_item->email : '').'" id="jform_ricevimenti_email_'.$i.'" />';
			$html[] = '<input type="hidden" name="jform[ricevimenti_ora_'.$i.']" value="'.$_item->id.'" id="jform_ricevimenti_ora_'.$i.'" />';
			$html[] = '</div></li>';
		}
		$html[] = '</ul>';
		$html[] = '<input type="hidden" name="jform[ricevimenti_count]" value="'.$count.'" id="jform_ricevimenti_count" />';

		$html[] = '</fieldset>';

		return implode($html);
	}

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   11.1
	 */
	protected function getOptions()
	{
		// Initialize variables.
		$options = array();
		$n = ReceivementsFrontendHelper::getPreBooking();
		$par = 'today +' . $n . ' day';
		$start = JFactory::getDate($par);  // today date plus n days
		$ora = ' ' . $this->item->inizio;  // setup recv. time
		while ($this->item->giorno != $start->dayofweek - 1)  // go to first available day
		      $start = JFactory::getDate($start . ' +1 day');
		$start =  ReceivementsFrontendHelper::convertDateTo($start) . $ora;  // convert to datetime
		for ($i = 0; $i < ReceivementsFrontendHelper::getShowTotalDays(); $i++) {
		      $avail = ReceivementsFrontendHelper::isDateAvailable($start, $this->item->id_docente, $this->item->id);
		      if ($avail == -1) break; // if final holidays then exit
                      $text = ReceivementsFrontendHelper::convertDateFrom($start, DATE_FORMAT_LC);
        	      $options[] = JHtml::_('select.option', $start, $text, 'value', 'text', !$avail);
		      $start = JFactory::getDate($start . ' +7 day');
                }
		return $options;
	}
}