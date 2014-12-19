<?php

/**
 * @version     0.0.1
 * @package     com_receivements
 * @copyright   Copyright (C) 2014. Tutti i diritti riservati.
 * @license     GNU General Public License versione 2 o successiva; vedi LICENSE.txt
 * @author      Paolo Bozzo <info@dbfweb.com> - http://dbfweb.com
 */
defined('_JEXEC') or die;

class ReceivementsFrontendHelper {
	static function getWeekDayOptions()
	{
		// Build the filter options.
		$options = array();
		for ($i = 0; $i < 6; $i++) {
		      $options[] = JHtml::_('select.option', $i, JText::_('COM_RECEIVEMENTS_ORE_GIORNO_OPTION_' . $i));
		}
		return $options;
	}    
	static function getClassesOptions()
	{
		$db = JFactory::getDbo();
		$db->setQuery(
			'SELECT a.id AS value, a.classe AS text' .
			' FROM #__receivements_classi AS a' .
			' ORDER BY a.classe ASC'
		);
		$options = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum())
		{
			JError::raiseNotice(500, $db->getErrorMsg());
			return null;
		}

		return $options;
	}
	static function getSitesOptions()
	{
		$db = JFactory::getDbo();
		$db->setQuery(
			'SELECT a.id AS value, a.sede AS text' .
			' FROM #__receivements_sedi AS a' .
			' ORDER BY a.sede ASC'
		);
		$options = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum())
		{
			JError::raiseNotice(500, $db->getErrorMsg());
			return null;
		}

		return $options;
	}
}
