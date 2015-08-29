<?php
/**
 * @version     0.5.0
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
class ReceivementsControllerRicevimenti extends ReceivementsController
{
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
    public function &getModel($name = 'Ricevimenti', $prefix = 'ReceivementsModel') {
	$model = parent::getModel($name, $prefix, array('ignore_request' => true));
	return $model;
    }
    public function init_booking() {
        $val = '';
        $app = JFactory::getApplication();
        $addresses = $app->input->post->get('cid', false, 'array');
        if ($app->input->post->get('do_book', false, 'int')) { // 'book selected items' button
                foreach ($addresses as $address) {
                        if (!empty($address)) {
                                if ($val != '') $val .= '.';
                                $val .= $address;
                        }
                }
        } else { // rows limit selection
                // List state information
                $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'));
                $this->getModel()->setState('list.limit', $limit);
        }
        if ($val === "") $this->setRedirect(JRoute::_('index.php?option=com_receivements&view=ricevimenti'));
        else {
                $app->setUserState('com_receivements.init.prenota.id', $val);
                $this->setRedirect(JRoute::_('index.php?option=com_receivements&view=prenota'));
        }
    }
}
