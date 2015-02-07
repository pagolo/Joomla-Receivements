<?php

/**
 * @version     0.5.0
 * @package     com_receivements
 * @copyright   Copyright (C) 2014. Tutti i diritti riservati.
 * @license     GNU General Public License versione 2 o successiva; vedi LICENSE.txt
 * @author      Paolo Bozzo <info@dbfweb.com> - http://dbfweb.com
 */
// No direct access
defined('_JEXEC') or die;

require_once JPATH_COMPONENT . DS . 'controller.php';
require_once JPATH_COMPONENT . DS . 'helpers' . DS . 'receivements.php';

/**
 * Disdetta controller class.
 */
class ReceivementsControllerDisdetta extends ReceivementsController {

        public function remove() {
                // Check for request forgeries.
                JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

                $a = JRequest::getVar('jform', array(), 'post', 'array');
                $status = 'not_ok';
                $db = JFactory::getDBO();
                $query = 'DELETE FROM #__receivements_prenotazioni WHERE id = '.$db->Quote($a['id']);
                $db->setQuery($query);
                $result = $db->execute();
                if ($result) {  // successfully deleted
                        if ($a['use_email']) {
                                ReceivementsEmailHelper::sendDeletionEmailToTeacher($a);
                        }
                        $status = 'ok';
                }
                $this->setRedirect(JRoute::_('index.php?option=com_receivements&view=disdetta&layout=result&status='.$status, false));
        }
}
