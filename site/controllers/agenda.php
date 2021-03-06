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
 * Assenza controller class.
 */
class ReceivementsControllerAgenda extends ReceivementsController {

        public function delete($send_email = false) {
                // Check for request forgeries.
                // TO DO: mettere la pagina chiamante in un form in modo da poter attivare la linea che segue
                // (comunque la funzione è solo per utenti registrati)
                // JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

                // get booking ids
                $app = JFactory::getApplication();
                $id = $app->input->get('id','','string');
                $id_agenda = $app->input->get('agenda','','string');
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
        public function notesave() {
                $app = JFactory::getApplication();
                $myvalues = $app->input->getArray(array(
                  'jform' => array(
                    'id' => 'int',
                    'note' => 'string',
                    'delete' => 'int',
                  )
                ));
                if ($myvalues['jform']['delete'] === 1)
                        $note = '';
                else
                        $note = $myvalues['jform']['note'];
                $model = $this->getModel('Agenda', 'ReceivementsModel');
                $success = $model->updateNote($myvalues['jform']['id'], $note);
                if ($success)
                        $app->enqueueMessage($note == ''? JText::_('COM_RECEIVEMENTS_NOTE_DELETED') : JText::_('COM_RECEIVEMENTS_NOTE_SAVED'));
                else
                        $app->enqueueMessage(JText::_('COM_RECEIVEMENTS_NOTE_NOT_SAVED'), 'warning');
                $this->setRedirect(JRoute::_('index.php?option=com_receivements&tmpl=component&view=nota&id=' . $myvalues['jform']['id'], false));
        }
        public function effettuati() {
                $app	= JFactory::getApplication();
                $app->setUserState('com_receivements.agenda.old', true);
                $this->setRedirect(JRoute::_('index.php?option=com_receivements&view=agenda', false));
        }
        public function prossimi() {
                $app	= JFactory::getApplication();
                $app->setUserState('com_receivements.agenda.old', false);
                $this->setRedirect(JRoute::_('index.php?option=com_receivements&view=agenda', false));
        }
}
