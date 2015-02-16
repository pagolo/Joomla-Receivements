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
 * View to agenda
 */
class ReceivementsViewGiornaliero extends JView {
    protected $data;
    protected $date;
    protected $form;
    protected $items;

    /**
     * Display the view
     */
    public function display($tpl = null)
    {
        $app = JFactory::getApplication();
        $pathway = $app->getPathway();
        $pathway->addItem(JText::_('COM_RECEIVEMENTS_DAILY'));
        
        $this->data = $this->get('Data');
        $this->date = $this->data['data'];
        $this->form = $this->get('Form');
        $this->items= $this->get('Items');
        
        parent::display($tpl);
    }//function
}
