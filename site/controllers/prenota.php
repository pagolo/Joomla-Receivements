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
require_once JPATH_COMPONENT . '/helpers/receivements.php';

/**
 * Prenota controller class.
 */
class ReceivementsControllerPrenota extends ReceivementsController {

        public function save() {
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$app	= JFactory::getApplication();
                $model = $this->getModel('Prenota', 'ReceivementsModel');
                $form = $model->getForm();
		// Get the user data.
		$requestData = JRequest::getVar('jform', array(), 'post', 'array');
		// validate data...
		$data	= $model->validate($form, $requestData);

		// Check for validation errors.
		if ($data === false) {
        		// Save the data in the session.
	               	$app->setUserState('com_receivements.booking.data', $requestData);
			// Get the validation messages.
			$errors	= $model->getErrors();
			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
				if ($errors[$i] instanceof Exception) {
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				} else {
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}
			// Redirect back to the registration screen.
			$this->setRedirect(JRoute::_(JURI::getInstance()->toString(), false));
			return false;
		}

                $model->SaveData($data);
                
                // send long-time cookie
                $cookie = $app->input->cookie;
                $cookie->set('receivements_cookie', base64_encode(serialize($data)), strtotime( '+90 days' ));

                ReceivementsEmailHelper::sendConfirmationEmail($data);
                
                $this->setRedirect(JRoute::_('index.php?option=com_receivements&view=prenota&layout=confirmation', false));
        }
}
