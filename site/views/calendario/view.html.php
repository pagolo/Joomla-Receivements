<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * HTML View class for the HelloWorld Component
 */
class ReceivementsViewCalendario extends JView
{
        // Overwriting JView display method
        function display($tpl = null) 
        {
                $this->items = $this->get('Items');
		$this->state		= $this->get('State');
 
                // Display the view
                parent::display($tpl);
        }
        protected function convertdate($date) {
                $t = explode('-', $date);
                $a = $t[2]; $c = $t[0];
                $t[0] = $a; $t[2] = $c;
                return implode('-', $t);
        }
}
