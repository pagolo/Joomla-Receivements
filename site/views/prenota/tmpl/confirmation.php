<?php
/**
 * @version     0.0.1
 * @package     com_receivements
 * @copyright   Copyright (C) 2014. Tutti i diritti riservati.
 * @license     GNU General Public License versione 2 o successiva; vedi LICENSE.txt
 * @author      Paolo Bozzo <info@dbfweb.com> - http://dbfweb.com
 */
// no direct access
defined('_JEXEC') or die;

//JHTML::_('behavior.modal');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
//JHtml::_('behavior.formvalidation');

$html = array();
$html[] = JText::_('COM_RECEIVEMENTS_CONFIRMATION_BODY_1');
foreach ($this->data['ricevimenti'] as $datum) {
        $html[] = JText::sprintf('COM_RECEIVEMENTS_CONFIRMATION_BODY_2',
                $datum['teacher_name'],
                ReceivementsFrontendHelper::convertDateFrom($datum['datetime'], 'l, d/m/Y H:i'),
                ReceivementsFrontendHelper::createUndoAddress($datum['guid'])
                );
        }
$html[] = JText::_('COM_RECEIVEMENTS_CONFIRMATION_BODY_3');
echo implode($html).'<br/>';

//print_r($this->data);