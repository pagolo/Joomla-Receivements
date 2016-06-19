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

jimport('joomla.application.component.controllerform');

/**
 * Sede controller class.
 */
class ReceivementsControllerManutenzione extends JControllerForm
{
	public function getModel($name = 'Manutenzione', $prefix = 'ReceivementsModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
        public function go () {
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

                $jinput = JFactory::getApplication()->input;
                $jform = $jinput->get('jform', null, null);

                //get model
                $model = $this->getModel();
                $result = $model->doTruncate($jform);
                $this->setRedirect('index.php?option=com_receivements&view=manutenzione&layout=response&tmpl=component&result='.$result);
        }
}