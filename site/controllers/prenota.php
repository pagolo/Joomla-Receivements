<?php

/**
 * @version     1.0.5
 * @package     com_receivements
 * @copyright   Copyright (C) 2014. Tutti i diritti riservati.
 * @license     GNU General Public License versione 2 o successiva; vedi LICENSE.txt
 * @author      Paolo Bozzo - pagolo.bozzo AT gmail.com - http://dbfweb.com
 */
// No direct access
defined('_JEXEC') or die;

require_once JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'controller.php';
require_once JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'receivements.php';

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
		$t = explode ('.', JVERSION);
		if ($t[0] >= 3) {
                        $all = $app->input->post->getArray();
                        $requestData = $all['jform'];
                } else {
                        $requestData = JRequest::getVar('jform', array(), 'post', 'array');
                }
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

                if (!($model->SaveData($data))) {
                        $app->enqueueMessage(JText::_('COM_RECEIVEMENTS_ERROR_SAVING_DATA'), 'warning');
			$this->setRedirect(JRoute::_(JURI::getInstance()->toString(), false));
			return false;
                }
                
                // send long-time cookie
                $temp = array();
                $temp['nome'] = $data['nome'];
                $temp['classe'] = $data['classe'];
                $temp['email'] = $data['email'];
                $temp['parentela'] = $data['parentela'];
                $cookie = $app->input->cookie;
                $cookie->set('receivements_cookie', base64_encode(serialize($temp)), strtotime( '+90 days' ), '/');
                // Save the data in the session.
               	$app->setUserState('com_receivements.booking.data', $data);
                // if forcedlogin then save to db
                // no, no need to do this
                /*
                if (ReceivementsFrontendHelper::getForcedLogin() && ReceivementsFrontendHelper::canBook()) {
                        ReceivementsFrontendHelper::handleParentData($data);
                }
                */

                ReceivementsEmailHelper::sendConfirmationEmail($data);
                
                $this->setRedirect(JRoute::_('index.php?option=com_receivements&view=prenota&layout=confirmation', false));
                
                return true;
        }
}
