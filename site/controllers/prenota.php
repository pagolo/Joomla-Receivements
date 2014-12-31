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

require_once JPATH_COMPONENT . '/controller.php';

/**
 * Prenota controller class.
 */
class ReceivementsControllerPrenota extends ReceivementsController {

        public function save() {
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
                $model = $this->getModel('Prenota', 'ReceivementsModel');
                $form = $model->getForm();
		// Get the user data.
		$requestData = JRequest::getVar('jform', array(), 'post', 'array');
		$data	= $model->validate($form, $requestData);
                print_r($data);
                exit;
        }
}
