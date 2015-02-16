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

require_once JPATH_COMPONENT . DS . 'controller.php';
require_once JPATH_COMPONENT . DS . 'helpers' . DS . 'receivements.php';

/**
 * Assenza controller class.
 */
class ReceivementsControllerAgenda extends ReceivementsController {

        public function delete($send_email = false) {
                // Check for request forgeries.
                // TO DO: mettere la pagina chiamante in un form in modo da poter attivare la linea che segue
                // (comunque la funzione è solo per utenti registrati)
                // JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

                // get booking ids
                $id = JRequest::getVar('id','','get','string');
                $id_agenda = JRequest::getVar('agenda','','get','string');
                $app	= JFactory::getApplication();
                $app->setUserState('com_receivements.agenda.open', $id_agenda);
                $model = $this->getModel('Agenda', 'ReceivementsModel');
                if ($send_email === true) {
                        $email_data = $model->getEmailData($id);
                }
                $success = $model->deleteBooking($id, $id_agenda);
                if ($send_email === true && $success === true) {
                        ReceivementsEmailHelper::sendDeletionEmailToParent($email_data);
                }
                $this->setRedirect(JRoute::_('index.php?option=com_receivements&view=agenda', false));
        }
        public function email_delete() {
                $this->delete(true);
        }
}
