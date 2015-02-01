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
//JFactory::$document = JDocument::getInstance('raw');
jimport('joomla.application.component.view');

/**
 * View to remove appointment
 */
class ReceivementsViewAjax extends JView {
    /**
     * Display the view
     */
    public function display($tpl = null)
    {
        if (JRequest::getVar('layout', '', 'get', 'string') == 'change-student') {
                $student_name = JRequest::getVar('student', '', 'get', 'string');
                $user_id = JFactory::getUser()->get('id');
                $data = ReceivementsAjaxHelper::changeStudent($user_id, $student_name);
                if (!empty($data)) {
                         echo json_encode($data);
                         return;
                }
        }
    }//function
}
