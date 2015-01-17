<?php

/**
 * @version     0.0.1
 * @package     com_receivements
 * @copyright   Copyright (C) 2014. Tutti i diritti riservati.
 * @license     GNU General Public License versione 2 o successiva; vedi LICENSE.txt
 * @author      Paolo Bozzo <info@dbfweb.com> - http://dbfweb.com
 */
// No direct access
defined('_JEXEC') or die;

/**
 * Receivements helper.
 */
class ReceivementsHelper {

    /**
     * Configure the Linkbar.
     */
    public static function addSubmenu($vName = '') {
        		JSubMenuHelper::addEntry(
			JText::_('COM_RECEIVEMENTS_TITLE_BOOKINGS'),
			'index.php?option=com_receivements&view=prenotazioni',
			$vName == 'prenotazioni'
		);
        		JSubMenuHelper::addEntry(
			JText::_('COM_RECEIVEMENTS_TITLE_ORE'),
			'index.php?option=com_receivements&view=ore',
			$vName == 'ore'
		);
        		JSubMenuHelper::addEntry(
			JText::_('COM_RECEIVEMENTS_TITLE_SEDI'),
			'index.php?option=com_receivements&view=sedi',
			$vName == 'sedi'
		);
        		JSubMenuHelper::addEntry(
			JText::_('COM_RECEIVEMENTS_TITLE_CATTEDRE'),
			'index.php?option=com_receivements&view=cattedre',
			$vName == 'cattedre'
		);
        		JSubMenuHelper::addEntry(
			JText::_('COM_RECEIVEMENTS_TITLE_CLASSI'),
			'index.php?option=com_receivements&view=classi',
			$vName == 'classi'
		);
        		JSubMenuHelper::addEntry(
			JText::_('COM_RECEIVEMENTS_TITLE_VACANZE'),
			'index.php?option=com_receivements&view=vacanze',
			$vName == 'vacanze'
		);
    }

    /**
     * Gets a list of the actions that can be performed.
     *
     * @return	JObject
     * @since	1.6
     */
    public static function getActions() {
        $user = JFactory::getUser();
        $result = new JObject;

        $assetName = 'com_receivements';

        $actions = array(
            'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
        );

        foreach ($actions as $action) {
            $result->set($action, $user->authorise($action, $assetName));
        }

        return $result;
    }


}
