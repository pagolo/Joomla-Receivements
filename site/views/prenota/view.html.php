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
 * View to edit
 */
class ReceivementsViewPrenota extends JView {
    protected $state;
    protected $form;
    /**
     * Display the view
     */
    public function display($tpl = null) {
        $app = JFactory::getApplication();
        $user = JFactory::getUser();
        $pathway = $app->getPathway();
        $pathway->addItem(JText::_('COM_RECEIVEMENTS_DO_BOOKING'));

        if (!(ReceivementsFrontendHelper::canBook())) {
                $app->redirect('index.php?option=com_receivements&view=ricevimenti');
        }

        $this->state = $this->get('State');
        $this->form  = $this->get('Form');
        if (JRequest::getVar('layout', '', 'get', 'string') == 'confirmation') {
                $this->data = $app->getUserState('com_receivements.booking.data', array());
        }
        
        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors));
        }

        //$this->_prepareDocument();

        parent::display($tpl);
    }
}
