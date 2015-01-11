<?php
/**
 * @version     0.0.1
 * @package     com_receivements
 * @copyright   Copyright (C) 2014. Tutti i diritti riservati.
 * @license     GNU General Public License versione 2 o successiva; vedi LICENSE.txt
 * @author      Paolo Bozzo <info@dbfweb.com> - http://dbfweb.com
 */

// No direct access.
defined('_JEXEC') or die;

require_once JPATH_COMPONENT.'/controller.php';

/**
 * Ore list controller class.
 */
class ReceivementsControllerRicevimenti extends ReceivementsController
{
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function &getModel($name = 'Ore', $prefix = 'ReceivementsModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
    public function init_booking() {
        $val = '';
        $addresses = JRequest::getVar('cid',false,'post', 'array');
        foreach ($addresses as $address) {
                if (!empty($address)) {
                        if ($val != '') $val .= '.';
                        $val .= $address;
                }
        }
        if ($val === "") $this->setRedirect(JRoute::_('index.php?option=com_receivements&view=ricevimenti'));
        else $this->setRedirect(JRoute::_('index.php?option=com_receivements&view=prenota&id=' . $val));
    }
}