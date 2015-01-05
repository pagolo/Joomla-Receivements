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

        $this->state = $this->get('State');
        $this->form  = $this->get('Form');
        if (JRequest::getVar('layout', '', 'get', 'string') == 'confirmation') {
                $cookie = $app->input->cookie;
                $this->data = (array)unserialize(base64_decode($cookie->get('receivements_cookie')));
        }
        
        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors));
        }

        //$this->_prepareDocument();

        parent::display($tpl);
    }
}
