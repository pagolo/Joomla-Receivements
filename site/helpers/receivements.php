<?php
/**
 * @version     0.0.1
 * @package     com_receivements
 * @copyright   Copyright (C) 2014. Tutti i diritti riservati.
 * @license     GNU General Public License versione 2 o successiva; vedi LICENSE.txt
 * @author      Paolo Bozzo <info@dbfweb.com> - http://dbfweb.com
 */
defined('_JEXEC') or die;
class ReceivementsFrontendHelper

{
	static
	function getWeekDayOptions()
	{

		// Build the filter options.

		$options = array();
		for ($i = 0; $i < 6; $i++) {
			$options[] = JHtml::_('select.option', $i, JText::_('COM_RECEIVEMENTS_ORE_GIORNO_OPTION_' . $i));
		}

		return $options;
	}

	static
	function getClassesOptions()
	{
		$db = JFactory::getDbo();
		$db->setQuery('SELECT a.id AS value, a.classe AS text' . ' FROM #__receivements_classi AS a' . ' ORDER BY a.classe ASC');
		$options = $db->loadObjectList();

		// Check for a database error.

		if ($db->getErrorNum()) {
			JError::raiseNotice(500, $db->getErrorMsg());
			return null;
		}

		return $options;
	}

	static
	function getSitesOptions()
	{
		$db = JFactory::getDbo();
		$db->setQuery('SELECT a.id AS value, a.sede AS text' . ' FROM #__receivements_sedi AS a' . ' ORDER BY a.sede ASC');
		$options = $db->loadObjectList();

		// Check for a database error.

		if ($db->getErrorNum()) {
			JError::raiseNotice(500, $db->getErrorMsg());
			return null;
		}

		return $options;
	}

	static
	function convertDateFrom($date, $fmt_str = 'DATE_FORMAT_LC3')
	{
		$myDate = JFactory::getDate($date);
		$format = JText::_($fmt_str);
		return JHTML::_('date', $myDate, $format);
	}

	static
	function convertDateTo($date)
	{
		$myDate = JFactory::getDate($date);
		$format = JText::_('Y-m-d');
		return JHTML::_('date', $myDate, $format);;
	}

	static
	function getGUID()
	{
		if (function_exists('com_create_guid')) {
			return com_create_guid();
		}
		else {
			mt_srand((double)microtime() * 10000); //optional for php 4.2.0 and up.
			$charid = strtoupper(md5(uniqid(rand() , true)));
			$hyphen = chr(45); // "-"
			$uuid = chr(123) // "{"
			 . substr($charid, 0, 8) . $hyphen . substr($charid, 8, 4) . $hyphen . substr($charid, 12, 4) . $hyphen . substr($charid, 16, 4) . $hyphen . substr($charid, 20, 12) . chr(125); // "}"
			return $uuid;
		}
	}
	
	static
	function getPreBooking()
	{
                $params = JFactory::getApplication()->getParams();
                $params_array = $params->toArray();
                return (isset($params_array['pre_booking'])? $params_array['pre_booking'] : 3); 
        }
	
	static
	function getShowTotalDays()
	{
                $params = JFactory::getApplication()->getParams();
                $params_array = $params->toArray();
                return (isset($params_array['show_total_days'])? $params_array['show_total_days'] : 7); 
        }
        
        static
        function isDateAvailable($date, $utente, $ore) {
		$db = JFactory::getDbo();
		$qdate = $db->Quote($date);
                // controlliamo che non siano le vacanze estive di fine anno, se sì disabilitiamo e restituiamo -1
		$db->setQuery('SELECT COUNT(*) FROM #__receivements_calendario WHERE inizio <= DATE('.$qdate.') AND finale = TRUE');
                $found = $db->loadResult();
                if ($found > 0) {
                        return -1;
                }
                // controlliamo che non sia un giorno di festa, se sì disabilitiamolo e continuiamo
                $db->setQuery('SELECT COUNT(*) FROM #__receivements_calendario WHERE inizio <= DATE('.$qdate.') AND fine >= DATE('.$qdate.') AND (utente = 0 OR utente = '.$utente.')');
                $found = $db->loadResult();
                if ($found > 0) {
                        return 0;
                }
                // controlliamo che il giorno non abbia raggiunto il max dei ricevimenti
                $db->setQuery('SELECT COUNT(*) FROM #__receivements_agenda AS a LEFT JOIN #__receivements_ore AS o ON (a.id_ore = o.id) WHERE (a.data = '.$qdate.' AND a.id_ore = '.$ore.' AND a.totale_ric >= o.max_app)');
                $found = $db->loadResult();
                if ($found > 0) {
                        return 0;
                }
                return 1;
        }       
}
