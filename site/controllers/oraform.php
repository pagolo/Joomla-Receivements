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
 * Ora controller class.
 */
class ReceivementsControllerOraForm extends ReceivementsController {

    /**
     * Method to check out an item for editing and redirect to the edit form.
     *
     * @since	1.6
     */
    public function edit() {
        $app = JFactory::getApplication();

        // Get the previous edit id (if any) and the current edit id.
        $previousId = (int) $app->getUserState('com_receivements.edit.ora.id');
        $editId = $app->input->getInt('id', null, 'array');

        // Set the user id for the user to edit in the session.
        $app->setUserState('com_receivements.edit.ora.id', $editId);

        // Get the model.
        $model = $this->getModel('OraForm', 'ReceivementsModel');

        // Check out the item
        if ($editId) {
            $model->checkout($editId);
        }

        // Check in the previous user.
        if ($previousId) {
            $model->checkin($previousId);
        }

        // Redirect to the edit screen.
        $this->setRedirect(JRoute::_('index.php?option=com_receivements&view=oraform&layout=edit&id='.$editId, false));
    }

   public function create() {
        // Check for request forgeries.
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        // Initialise variables.
        $app = JFactory::getApplication();
        $model = $this->getModel('OraForm', 'ReceivementsModel');
        
        $data = array();
        $data['id'] = '';
        $data['id_docente'] = JFactory::getUser()->id;
        $data['una_tantum'] = $app->input->get('receivement', 0, 'int');
        $recv = ReceivementsAjaxHelper::changeReceivement($data['una_tantum']);
        $data['inizio'] = $recv['inizio'];
        $data['fine'] = $recv['fine'];
        $data['giorno'] = '';
        $data['sede'] = $recv['sede'];
        $data['max_app'] = 40;
        $teacher = ReceivementsAjaxHelper::changeTeacher($data['id_docente']);
        $data['classi'] = $teacher['classi'];
        $data['cattedra'] = $teacher['cattedra'];
        $data['email'] = 1;
        $data['attiva'] = 1;
        $id = $model->save($data);
        $this->setRedirect(JRoute::_('index.php?option=com_receivements&tmpl=component&view=oraform&layout=create&id=' . $id, false));
   }

   public function save() {
        // Check for request forgeries.
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        // Initialise variables.
        $app = JFactory::getApplication();
        $model = $this->getModel('OraForm', 'ReceivementsModel');

        // Get the user data.
        $data = $app->input->get('jform', array(), 'array');

        // Validate the posted data.
        $form = $model->getForm();
        if (!$form) {
            JError::raiseError(500, $model->getError());
            return false;
        }

        // Validate the posted data.
        $data = $model->validate($form, $data);

        // Check for errors.
        if ($data === false) {
            // Get the validation messages.
            $errors = $model->getErrors();

            // Push up to three validation messages out to the user.
            for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
                if ($errors[$i] instanceof Exception) {
                    $app->enqueueMessage($errors[$i]->getMessage(), 'warning');
                } else {
                    $app->enqueueMessage($errors[$i], 'warning');
                }
            }

            $input = $app->input;
            $jform = $input->get('jform', array(), 'ARRAY');

            // Save the data in the session.
            $app->setUserState('com_receivements.edit.ora.data', $jform, array());

            // Redirect back to the edit screen.
            $id = (int) $app->getUserState('com_receivements.edit.ora.id');
            $this->setRedirect(JRoute::_('index.php?option=com_receivements&view=oraform&layout=edit&id=' . $id, false));
            return false;
        }

        // Attempt to save the data.
        $return = $model->save($data);

        // Check for errors.
        if ($return === false) {
            // Save the data in the session.
            $app->setUserState('com_receivements.edit.ora.data', $data);

            // Redirect back to the edit screen.
            $id = (int) $app->getUserState('com_receivements.edit.ora.id');
            $this->setMessage(JText::sprintf('Save failed', $model->getError()), 'warning');
            $this->setRedirect(JRoute::_('index.php?option=com_receivements&view=oraform&layout=edit&id=' . $id, false));
            return false;
        }


        // Check in the profile.
        if ($return) {
            $model->checkin($return);
        }

        // Clear the profile id from the session.
        $app->setUserState('com_receivements.edit.ora.id', null);

        // Redirect to the list screen.
        $this->setMessage(JText::_('COM_RECEIVEMENTS_ITEM_SAVED_SUCCESSFULLY'));
        //$menu = JFactory::getApplication()->getMenu();
        //$item = $menu->getActive();
        //$url = (empty($item->link) ? 'index.php?option=com_receivements&view=oraform' : $item->link);
        //$this->setRedirect(JRoute::_($url, false));
        $this->setRedirect(JRoute::_('index.php?option=com_receivements&view=oraform&layout=edit&id=' . $data['id'], false));

        // Flush the data from the session.
        $app->setUserState('com_receivements.edit.ora.data', null);
    }

    function cancel() {
        
        $app = JFactory::getApplication();

        // Get the current edit id.
        $editId = (int) $app->getUserState('com_receivements.edit.ora.id');

        // Get the model.
        $model = $this->getModel('OraForm', 'ReceivementsModel');

        // Check in the item
        if ($editId) {
            $model->checkin($editId);
        }
        
        $menu = JFactory::getApplication()->getMenu();
        $item = $menu->getActive();
        $url = (empty($item->link) ? 'index.php?option=com_receivements&view=oraform' : $item->link);
        $this->setRedirect(JRoute::_($url, false));
    }

    public function remove() {

        // Initialise variables.
        $app = JFactory::getApplication();
        $model = $this->getModel('OraForm', 'ReceivementsModel');

        // Get the user data.
        $data = array();
        $data['id'] = $app->input->getInt('id');

        // Check for errors.
        if (empty($data['id'])) {
            // Get the validation messages.
            $errors = $model->getErrors();

            // Push up to three validation messages out to the user.
            for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
                if ($errors[$i] instanceof Exception) {
                    $app->enqueueMessage($errors[$i]->getMessage(), 'warning');
                } else {
                    $app->enqueueMessage($errors[$i], 'warning');
                }
            }

            // Save the data in the session.
            $app->setUserState('com_receivements.edit.ora.data', $data);

            // Redirect back to the edit screen.
            $id = (int) $app->getUserState('com_receivements.edit.ora.id');
            $this->setRedirect(JRoute::_('index.php?option=com_receivements&view=ora&layout=edit&id=' . $id, false));
            return false;
        }

        // Attempt to save the data.
        $return = $model->delete($data);

        // Check for errors.
        if ($return === false) {
            // Save the data in the session.
            $app->setUserState('com_receivements.edit.ora.data', $data);

            // Redirect back to the edit screen.
            $id = (int) $app->getUserState('com_receivements.edit.ora.id');
            $this->setMessage(JText::sprintf('Delete failed', $model->getError()), 'warning');
            $this->setRedirect(JRoute::_('index.php?option=com_receivements&view=ora&layout=edit&id=' . $id, false));
            return false;
        }


        // Check in the profile.
        if ($return) {
            $model->checkin($return);
        }

        // Clear the profile id from the session.
        $app->setUserState('com_receivements.edit.ora.id', null);

        // Redirect to the list screen.
        $this->setMessage(JText::_('COM_RECEIVEMENTS_ITEM_DELETED_SUCCESSFULLY'));
        $menu = JFactory::getApplication()->getMenu();
        $item = $menu->getActive();
        $url = (empty($item->link) ? 'index.php?option=com_receivements&view=oraform' : $item->link);
        $this->setRedirect(JRoute::_($url, false));

        // Flush the data from the session.
        $app->setUserState('com_receivements.edit.ora.data', null);
    }

}
