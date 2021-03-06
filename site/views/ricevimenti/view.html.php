<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * HTML View class for the HelloWorld Component
 */
class ReceivementsViewRicevimenti extends JViewLegacy
{
    protected $items;
    protected $pagination;
    protected $state;
        
        // Overwriting JView display method
        function display($tpl = null) 
        {
                $this->items = $this->get('Items');
                $this->pagination = $this->get('Pagination');
		$this->state = $this->get('State');
 
                // Display the view
                parent::display($tpl);
        }
}
