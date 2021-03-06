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

/**
 * Assenza controller class.
 */
class ReceivementsControllerAssenza extends ReceivementsController {

    /**
     * Method to check out an item for editing and redirect to the edit form.
     *
     * @since	1.6
     */
    public function edit() {
        $app = JFactory::getApplication();

        // Get the previous edit id (if any) and the current edit id.
        $previousId = (int) $app->getUserState('com_receivements.edit.assenza.id');
        $editId = JFactory::getApplication()->input->getInt('id', null, 'array');

        // Set the user id for the user to edit in the session.
        $app->setUserState('com_receivements.edit.assenza.id', $editId);

        // Get the model.
        $model = $this->getModel('Assenza', 'ReceivementsModel');

        // Check out the item
        if ($editId) {
            $model->checkout($editId);
        }

        // Check in the previous user.
        if ($previousId) {
            $model->checkin($previousId);
        }

        // Redirect to the edit screen.
        $this->setRedirect(JRoute::_('index.php?option=com_receivements&view=assenza&layout=edit&id='.$editId, false));
    }

    /**
     * Method to save a user's profile data.
     *
     * @return	void
     * @since	1.6
     */
    public function save() {
        // Check for request forgeries.
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        // Initialise variables.
        $app = JFactory::getApplication();
        $model = $this->getModel('Assenza', 'ReceivementsModel');

        // Get the user data.
        $data = JFactory::getApplication()->input->get('jform', array(), 'array');

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
            $jform = &$input->get('jform', array(), 'ARRAY');
            $id = $jform['id'];
            $app->setUserState('com_receivements.edit.assenza.id', $id);

            // Save the data in the session.
            /*
            $id = (int) $app->getUserState('com_receivements.edit.assenza.id');
            if ($id == 0) {
                $jform['inizio'] = $jform['fine'] = null;
            } else {
                $jform['inizio'] = $jform['inizio.back'];
                $jform['fine'] = $jform['fine.back'];
            }
            $app->setUserState('com_receivements.edit.assenza.data', $jform, array());
            */
            //$app->setUserState('com_receivements.edit.assenza.data', null);

            // Redirect back to the edit screen.
            $id = $jform['id']; //(int) $app->getUserState('com_receivements.edit.assenza.id');
            $app->setUserState('com_receivements.edit.assenza.id', $id);
            $this->setRedirect('index.php?option=com_receivements&view=assenza&layout=edit&id=' . $id);
            return false;
        }

        // Attempt to save the data.
        $return = $model->save($data);

        // Check for errors.
        if ($return === false) {
            // Save the data in the session.
            $app->setUserState('com_receivements.edit.assenza.data', $data);

            // Redirect back to the edit screen.
            $id = (int) $app->getUserState('com_receivements.edit.assenza.id');
            $this->setMessage(JText::sprintf('Save failed', $model->getError()), 'warning');
            $this->setRedirect(JRoute::_('index.php?option=com_receivements&view=assenza&layout=edit&id=' . $id, false));
            return false;
        }


        // Check in the profile.
        if ($return) {
            $model->checkin($return);
        }

        // Clear the profile id from the session.
        $app->setUserState('com_receivements.edit.assenza.id', null);

        // Redirect to the list screen.
        $this->setMessage(JText::_('COM_RECEIVEMENTS_ITEM_SAVED_SUCCESSFULLY'));
        $menu = JFactory::getApplication()->getMenu();
        $item = $menu->getActive();
        $url = (empty($item->link) ? 'index.php?option=com_receivements&view=assenze' : $item->link);
        $this->setRedirect(JRoute::_($url, false));

        // Flush the data from the session.
        $app->setUserState('com_receivements.edit.assenza.data', null);
    }

    function cancel() {
        
        $app = JFactory::getApplication();

        // Get the current edit id.
        $editId = (int) $app->getUserState('com_receivements.edit.assenza.id');

        // Get the model.
        $model = $this->getModel('Assenza', 'ReceivementsModel');

        // Check in the item
        if ($editId) {
            $model->checkin($editId);
        }
        
        $menu = JFactory::getApplication()->getMenu();
        $item = $menu->getActive();
        $url = (empty($item->link) ? 'index.php?option=com_receivements&view=assenza' : $item->link);
        $this->setRedirect(JRoute::_($url, false));
    }

    public function remove() {
        // Check for request forgeries.
        // TODO supportare la riga seguente
        // JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        // Initialise variables.
        $app = JFactory::getApplication();
        $model = $this->getModel('Assenza', 'ReceivementsModel');

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
            $app->setUserState('com_receivements.edit.assenza.data', $data);

            // Redirect back to the edit screen.
            $id = (int) $app->getUserState('com_receivements.edit.assenza.id');
            $this->setRedirect(JRoute::_('index.php?option=com_receivements&view=assenza&layout=edit&id=' . $id, false));
            return false;
        }

        // Attempt to save the data.
        $return = $model->delete($data);

        // Check for errors.
        if ($return === false) {
            // Save the data in the session.
            $app->setUserState('com_receivements.edit.assenza.data', $data);

            // Redirect back to the edit screen.
            $id = (int) $app->getUserState('com_receivements.edit.assenza.id');
            $this->setMessage(JText::sprintf('Delete failed', $model->getError()), 'warning');
            $this->setRedirect(JRoute::_('index.php?option=com_receivements&view=assenza&layout=edit&id=' . $id, false));
            return false;
        }


        // Check in the profile.
        if ($return) {
            $model->checkin($return);
        }

        // Clear the profile id from the session.
        $app->setUserState('com_receivements.edit.assenza.id', null);

        // Redirect to the list screen.
        $this->setMessage(JText::_('COM_RECEIVEMENTS_ITEM_DELETED_SUCCESSFULLY'));
        $menu = JFactory::getApplication()->getMenu();
        $item = $menu->getActive();
        $url = (empty($item->link) ? 'index.php?option=com_receivements&view=assenze' : $item->link);
        $this->setRedirect(JRoute::_($url, false));

        // Flush the data from the session.
        $app->setUserState('com_receivements.edit.assenza.data', null);
    }

}
