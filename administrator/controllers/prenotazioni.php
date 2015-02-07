<?php
/**
 * @version     0.5.0
 * @package     com_receivements
 * @copyright   Copyright (C) 2014. Tutti i diritti riservati.
 * @license     GNU General Public License versione 2 o successiva; vedi LICENSE.txt
 * @author      Paolo Bozzo <info@dbfweb.com> - http://dbfweb.com
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

// include frontend helpers
$language =& JFactory::getLanguage();
$language->load('com_receivements', JPATH_SITE, $language->getTag(), true);
require_once JPATH_SITE.DS.'components'.DS.'com_receivements'.DS.'helpers'.DS.'receivements.php';

/**
 * Sedi list controller class.
 */
class ReceivementsControllerPrenotazioni extends JControllerAdmin
{
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function getModel($name = 'Prenotazione', $prefix = 'ReceivementsModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
    
        public function email_delete() {
                $model = $this->getModel();
                $array = JRequest::getVar( 'cid', array(0), 'post', 'array' );
                $table = $model->getTable();
                foreach($array as $booking_id) {
                        $data = $model->getBookingData($booking_id);
                        if (!empty($data)) {
                                if ($table->delete($booking_id)) {
                                        ReceivementsEmailHelper::sendDeletionEmailToParent($data);
                                }
                        }
                }
                $message = count($array) == 1 ? 'COM_RECEIVEMENTS_ITEM_DELETED_SUCCESSFULLY' : 'COM_RECEIVEMENTS_ITEMS_DELETED_SUCCESSFULLY';
                $app = JFactory::getApplication();
                $app->enqueueMessage(JText::_($message), 'message');
                //$this->setRedirect(JRoute::_('index.php?option=com_receivements&view=prenotazioni'));
                $this->setRedirect('index.php?option=com_receivements&view=prenotazioni');
        }
    
	/**
	 * Method to save the submitted ordering values for records via AJAX.
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public function saveOrderAjax()
	{
		// Get the input
		$input = JFactory::getApplication()->input;
		$pks = $input->post->get('cid', array(), 'array');
		$order = $input->post->get('order', array(), 'array');

		// Sanitize the input
		JArrayHelper::toInteger($pks);
		JArrayHelper::toInteger($order);

		// Get the model
		$model = $this->getModel();

		// Save the ordering
		$return = $model->saveorder($pks, $order);

		if ($return)
		{
			echo "1";
		}

		// Close the application
		JFactory::getApplication()->close();
	}
    
    
    
}