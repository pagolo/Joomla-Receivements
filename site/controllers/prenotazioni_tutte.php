<?php
/**
 * @version     1.0.5
 * @package     com_receivements
 * @copyright   Copyright (C) 2014. Tutti i diritti riservati.
 * @license     GNU General Public License versione 2 o successiva; vedi LICENSE.txt
 * @author      Paolo Bozzo - pagolo.bozzo AT gmail.com - http://dbfweb.com
 */

// No direct access.
defined('_JEXEC') or die;

require_once JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'controller.php';

/**
 * Ore list controller class.
 */
class ReceivementsControllerPrenotazioni_tutte extends ReceivementsController
{
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
    public function &getModel($name = 'Prenotazioni_tutte', $prefix = 'ReceivementsModel') {
	$model = parent::getModel($name, $prefix, array('ignore_request' => true));
	return $model;
    }
    public function change() {
        $app = JFactory::getApplication();
                // List state information
                $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'));
                $this->getModel()->setState('list.limit', $limit);
        	$limitstart = $app->input->getInt('limitstart', 0, 'uint');
		$app->setUserState('com_receivements.list.start', $limitstart);
        $this->setRedirect(JRoute::_('index.php?option=com_receivements&view=prenotazioni_tutte'));
    }
}
