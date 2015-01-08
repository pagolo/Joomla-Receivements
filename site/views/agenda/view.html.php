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

jimport('joomla.application.component.view');

/**
 * View to agenda
 */
class ReceivementsViewAgenda extends JView {
    protected $data;

    /**
     * Display the view
     */
    public function display($tpl = null)
    {
        $app = JFactory::getApplication();
        $pathway = $app->getPathway();
        $pathway->addItem(JText::_('COM_RECEIVEMENTS_AGENDA'));
        
        $this->data = $this->get('Data');
        /*
        if (JRequest::getVar('layout', '', 'get', 'string') == 'result') {
                $this->result = JRequest::getVar('status', '', 'get', 'string');
        } else {
                $this->data = $this->get('Data');
        }
        */
        parent::display($tpl);
    }//function
}
