<?php
/**
 * @version     0.0.1
 * @package     com_receivements
 * @copyright   Copyright (C) 2014. Tutti i diritti riservati.
 * @license     GNU General Public License versione 2 o successiva; vedi LICENSE.txt
 * @author      Paolo Bozzo <info@dbfweb.com> - http://dbfweb.com
 */

defined('JPATH_PLATFORM') or die;

/**
 * Form Rule class for the Joomla Platform.
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @since       11.1
 */
class JFormRuleRicevimenti extends JFormRule
{
	/**
	 * Method to test the email address and optionally check for uniqueness.
	 *
	 * @param   SimpleXMLElement  &$element  The SimpleXMLElement object representing the <field /> tag for the form field object.
	 * @param   mixed             $value     The form field value to validate.
	 * @param   string            $group     The field name group control value. This acts as as an array container for the field.
	 *                                       For example if the field has name="foo" and the group value is set to "bar" then the
	 *                                       full field name would end up being "bar[foo]".
	 * @param   JRegistry         &$input    An optional JRegistry object with the entire data set to validate against the entire form.
	 * @param   object            &$form     The form object for which the field is being tested.
	 *
	 * @return  boolean  True if the value is valid, false otherwise.
	 *
	 * @since   11.1
	 * @throws  JException on invalid rule.
	 */
	public function test(&$element, $value, $group = null, &$input = null, &$form = null)
	{
		// If the field is empty and not required, the field is valid.
		$required = ((string) $element['required'] == 'true' || (string) $element['required'] == 'required');
		if (!$required && empty($value))
		{
			return true;
		}
		if (empty($value))
		{
			return false;
		}
		$count = 0;
		foreach($value as $i => $item) {
		      if (!empty($item) && !empty($item['datetime']) && !empty($item['teacher_id']))
                        $count++;
                }
		return ($count > 0);
	}
}
