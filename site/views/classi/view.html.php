<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * HTML View class for the HelloWorld Component
 */
class ReceivementsViewClassi extends JViewLegacy
{
        protected $items;

        // Overwriting JView display method
        function display($tpl = null) 
        {
                $this->items = $this->get('Items');
 
                // Display the view
                parent::display($tpl);
        }
}
