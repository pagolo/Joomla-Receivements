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

require_once JPATH_COMPONENT . DS . 'controller.php';

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
        $addresses = JRequest::getVar('cid',false,'post', 'array');
        if (JRequest::getVar('do_book',false,'post', 'int')) { // 'book selected items' button
                foreach ($addresses as $address) {
                        if (!empty($address)) {
                                if ($val != '') $val .= '.';
                                $val .= $address;
                        }
                }
        } else { // rows limit selection
                // List state information
                $app = JFactory::getApplication();
                $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'));
                $this->getModel()->setState('list.limit', $limit);
        }
        if ($val === "") $this->setRedirect(JRoute::_('index.php?option=com_receivements&view=ricevimenti'));
        else {
                JFactory::getApplication()->setUserState('com_receivements.init.prenota.id', $val);
                $this->setRedirect(JRoute::_('index.php?option=com_receivements&view=prenota'));
        }
    }
}
