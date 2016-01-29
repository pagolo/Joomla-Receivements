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
 * Disdetta controller class.
 */
class ReceivementsControllerDisdetta extends ReceivementsController {

        public function remove() {
                // Check for request forgeries.
                JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

                $app = JFactory::getApplication();
		$t = explode ('.', JVERSION);
		if ($t[0] >= 3) {
                        $all = $app->input->post->getArray();
                        $a = $all['jform'];
                } else {
                        $a = JRequest::getVar('jform', array(), 'post', 'array');
                }
                $status = 'not_ok';
                $model = $this->getModel('Disdetta', 'ReceivementsModel');
                if ($model->deleteBooking($a) == TRUE) {
                        if ($a['use_email']) {
                                ReceivementsEmailHelper::sendDeletionEmailToTeacher($a);
                        }
                        $status = 'ok';
                }
                $this->setRedirect(JRoute::_('index.php?option=com_receivements&view=disdetta&layout=result&status='.$status, false));
        }
}
