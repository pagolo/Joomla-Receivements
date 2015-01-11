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

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');

$doc = JFactory::getDocument();
$doc->addStyleSheet(JUri::base() . '/components/com_receivements/assets/css/form.css');
$doc->addScript(JUri::base() . '/components/com_receivements/assets/js/form.js');
?>

<?php if (!empty($this->data)) : ?>

<fieldset>
        <legend>
        <?php echo JText::_('COM_RECEIVEMENTS_ORE_GIORNO_OPTION_'.$this->data->ore['giorno']).' '.JText::_('COM_RECEIVEMENTS_HOURS').' '.substr($this->data->ore['inizio'],0,5); ?>
        </legend>
        <ul>
        <?php foreach($this->data->agenda as $i => $day) : ?>
                <li style="list-style:none">
                <input id="show_<?=$i?>" onclick="recv_show(<?=$i?>)" type="image"  style='vertical-align: bottom;<?=$day['id']==$this->agenda_open?'display:none':'display:inline'?>' src="<?php echo JURI::base(true) . '/components/com_receivements/assets/icons/plus.gif';?>" alt="show" />
                <input id="hide_<?=$i?>" onclick="recv_hide(<?=$i?>)" type="image" style='vertical-align: bottom;<?=$day['id']==$this->agenda_open?'display:inline':'display:none'?>' src="<?php echo JURI::base(true) . '/components/com_receivements/assets/icons/minus.gif';?>" alt="hide" />
                <?php echo ReceivementsFrontendHelper::convertDateFrom($day['data'], 'l, d/m/Y H:i'); ?>
                (<?php echo JText::sprintf('COM_RECEIVEMENTS_ONTOTAL',$day['totale_ric'],$this->data->ore['max_app']); ?>)
                <ul id="nested_<?=$i?>" style="padding: 0px 50px;<?=$day['id']==$this->agenda_open?'display:block':'display:none'?>;">
                <?php foreach($day['nested'] as $ii => $booking) : ?>
                        <li>
                        <?php echo $booking['nome'].', '.$booking['classe'].' ('.JText::_($booking['parentela']).')'; ?>
                        <input title="<?=JText::_('COM_RECEIVEMENTS_DELETE_AND_SEND_EMAIL')?>" onclick="if (confirm('<?=JText::_('COM_RECEIVEMENTS_REALLY_DELETE_AND_SEND_EMAIL')?>')) window.location.href='<?=JRoute::_('index.php?option=com_receivements&task=agenda.email_delete&agenda='.$booking['id_agenda'].'&id='.$booking['id'],false,2)?>'" type="image"  style='vertical-align: bottom' src="<?php echo JURI::base(true) . '/components/com_receivements/assets/icons/email-delete.png';?>" alt="show" />
                        <input title="<?=JText::_('COM_RECEIVEMENTS_DELETE_AND_NO_EMAIL')?>" onclick="if (confirm('<?=JText::_('COM_RECEIVEMENTS_REALLY_DELETE_AND_NO_EMAIL')?>')) window.location.href='<?=JRoute::_('index.php?option=com_receivements&task=agenda.delete&agenda='.$booking['id_agenda'].'&id='.$booking['id'],false,2)?>'" type="image"  style='vertical-align: bottom' src="<?php echo JURI::base(true) . '/components/com_receivements/assets/icons/delete.png';?>" alt="show" />
                        </li>
                <?php endforeach ?>
                </ul>
                </li>
        <?php endforeach ?>
        </ul>
</fieldset>

<?php endif; ?>