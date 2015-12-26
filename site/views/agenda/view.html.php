<?php

/**
 * @version     1.0.5
 * @package     com_receivements
 * @copyright   Copyright (C) 2014. Tutti i diritti riservati.
 * @license     GNU General Public License versione 2 o successiva; vedi LICENSE.txt
 * @author      Paolo Bozzo - pagolo.bozzo AT gmail.com - http://dbfweb.com
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View to agenda
 */
class ReceivementsViewAgenda extends JViewLegacy {
    protected $data;
    protected $agenda_open;
    protected $agenda_old;

    /**
     * Display the view
     */
    public function display($tpl = null)
    {
        $app = JFactory::getApplication();
        $pathway = $app->getPathway();
        $pathway->addItem(JText::_('COM_RECEIVEMENTS_AGENDA'));
        
        $this->data = $this->get('Data');
        $app	= JFactory::getApplication();
        $this->agenda_open = $app->getUserState('com_receivements.agenda.open');
        $this->agenda_old = $app->getUserState('com_receivements.agenda.old');
        $app->setUserState('com_receivements.agenda.open', 0);
         
        parent::display($tpl);
    }//function
}
