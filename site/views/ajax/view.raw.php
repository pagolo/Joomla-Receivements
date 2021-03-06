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
//JFactory::$document = JDocument::getInstance('raw');
jimport('joomla.application.component.view');

/**
 * View to remove appointment
 */
class ReceivementsViewAjax extends JViewLegacy {
    /**
     * Display the view
     */
    public function display($tpl = null)
    {
        $app = JFactory::getApplication();
        if ($app->input->get->get('layout', '', 'string') == 'change-student') {
                $student_name = $app->input->get->get('student', '', 'string');
                $user_id = JFactory::getUser()->get('id');
                $data = ReceivementsAjaxHelper::changeStudent($user_id, $student_name);
                if (!empty($data)) {
                         echo json_encode($data);
                         return;
                }
        }
    }//function
}
