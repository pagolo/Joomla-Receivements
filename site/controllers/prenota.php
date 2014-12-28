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

/**
 * Prenota controller class.
 */
class ReceivementsControllerPrenota extends ReceivementsController {

        public function save() {
        echo "1";
        //$model = $this->getModel('Prenota', 'ReceivementsModel');
        echo "2";
        //$form = $model->getForm();
        echo "3";
        $data = JFactory::getApplication()->input->get('jform', array(), 'array');
        echo "4";
        print_r($data);
        exit;
        }
}
