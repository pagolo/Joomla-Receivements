<?php

/**
 * @version     0.5.0
 * @package     com_receivements
 * @copyright   Copyright (C) 2014. Tutti i diritti riservati.
 * @license     GNU General Public License versione 2 o successiva; vedi LICENSE.txt
 * @author      Paolo Bozzo - pagolo.bozzo AT gmail.com - http://dbfweb.com
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View to remove appointment
 */
class ReceivementsViewDisdetta extends JViewLegacy {
    protected $data;
    protected $result;
    /**
     * Display the view
     */
    public function display($tpl = null)
    {
        $app = JFactory::getApplication();
        $pathway = $app->getPathway();
        $pathway->addItem(JText::_('COM_RECEIVEMENTS_DO_REMOVE'));

        if ($app->input->get->get('layout', '', 'string') == 'result') {
                $this->result = $app->input->get->get('status', '', 'string');
        } else {
                $this->data = $this->get('Data');
        }
        parent::display($tpl);
    }//function
}
