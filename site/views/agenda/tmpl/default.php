<?php
/**
 * @version     1.0.5
 * @package     com_receivements
 * @copyright   Copyright (C) 2014. Tutti i diritti riservati.
 * @license     GNU General Public License versione 2 o successiva; vedi LICENSE.txt
 * @author      Paolo Bozzo - pagolo.bozzo AT gmail.com - http://dbfweb.com
 */
// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHTML::_('behavior.modal');

$doc = JFactory::getDocument();
$doc->addStyleSheet(JUri::base() . '/components/com_receivements/assets/css/form.css');
$doc->addScript(JUri::base() . '/components/com_receivements/assets/js/form.js');
?>
<h1><?php echo JText::_('COM_RECEIVEMENTS_AGENDA'); ?></h1>

<span><strong><?php echo $this->agenda_old? JText::_('COM_RECEIVEMENTS_AGENDA_OLD') : JText::_('COM_RECEIVEMENTS_AGENDA_NEW'); ?></strong></span>
<br /><em><?php echo JText::_('COM_RECEIVEMENTS_AGENDA_GOTO'); ?></em>
<?php if ($this->agenda_old) : ?>
<a href=<?php echo JRoute::_('index.php?option=com_receivements&task=agenda.prossimi'); ?>><?php echo JText::_('COM_RECEIVEMENTS_AGENDA_NEW'); ?></a>
<?php else : ?>
<a href=<?php echo JRoute::_('index.php?option=com_receivements&task=agenda.effettuati'); ?>><?php echo JText::_('COM_RECEIVEMENTS_AGENDA_OLD'); ?></a>
<?php endif; ?>

<?php if (!empty($this->data)) : ?>
<form id="form-agenda-print" action="<?php echo JRoute::_('index.php?option=com_receivements&view=agenda'); ?>" method="post">
        <button name="jform[print]" onclick="javascript:this.form.action = '<?php echo JRoute::_('index.php?option=com_receivements&view=agenda&tmpl=component&print=1&layout=print'); ?>';return true;" type="submit"><?php echo JText::_('COM_RECEIVEMENTS_PRINT_PAGE'); ?></button>
</form>
<br />&nbsp;<br />

<fieldset>
        <legend>
        <?php echo JText::_('COM_RECEIVEMENTS_ORE_GIORNO_OPTION_'.$this->data->ore['giorno']).' '.JText::_('COM_RECEIVEMENTS_HOURS').' '.substr($this->data->ore['inizio'],0,5); ?>
        </legend>
        <ul>
        <?php foreach($this->data->agenda as $i => $day) : ?>
                <li style="list-style:none">
                <input id="show_<?php echo $i?>" onclick="recv_show(<?php echo $i?>)" type="image"  style='vertical-align: bottom;<?php echo $day['id']==$this->agenda_open?'display:none':'display:inline'?>' src="<?php echo JURI::base(true) . '/components/com_receivements/assets/icons/plus.gif';?>" alt="show" />
                <input id="hide_<?php echo $i?>" onclick="recv_hide(<?php echo $i?>)" type="image" style='vertical-align: bottom;<?php echo $day['id']==$this->agenda_open?'display:inline':'display:none'?>' src="<?php echo JURI::base(true) . '/components/com_receivements/assets/icons/minus.gif';?>" alt="hide" />
                <?php echo ReceivementsFrontendHelper::convertDateFrom($day['data'], 'l, d/m/Y H:i'); ?>
                (<?php echo JText::plural('COM_RECEIVEMENTS_ONTOTAL',$day['totale_ric'],$this->data->ore['max_app']); ?>)
                <ul id="nested_<?php echo $i?>" style="padding: 0px 50px;<?php echo $day['id']==$this->agenda_open?'display:block':'display:none'?>;">
                <?php foreach($day['nested'] as $ii => $booking) : ?>
                        <li>
                        <?php echo $booking['nome'].', '.$booking['classe'].' ('.JText::_($booking['parentela']).')'; ?>
                        <?php if (!($this->agenda_old)) : ?>
                        <input title="<?php echo JText::_('COM_RECEIVEMENTS_DELETE_AND_SEND_EMAIL')?>" onclick="if (confirm('<?php echo JText::_('COM_RECEIVEMENTS_REALLY_DELETE_AND_SEND_EMAIL')?>')) window.location.href='<?php echo JRoute::_('index.php?option=com_receivements&task=agenda.email_delete&agenda='.$booking['id_agenda'].'&id='.$booking['id'],false,2)?>'" type="image"  style='vertical-align: bottom' src="<?php echo JURI::base(true) . '/components/com_receivements/assets/icons/email-delete.png';?>" alt="show" />
                        <?php endif; ?>
                        <input title="<?php echo JText::_($this->agenda_old? 'COM_RECEIVEMENTS_DELETE_THIS': 'COM_RECEIVEMENTS_DELETE_AND_NO_EMAIL')?>" onclick="if (confirm('<?php echo JText::_('COM_RECEIVEMENTS_REALLY_DELETE_AND_NO_EMAIL')?>')) window.location.href='<?php echo JRoute::_('index.php?option=com_receivements&task=agenda.delete&agenda='.$booking['id_agenda'].'&id='.$booking['id'],false,2)?>'" type="image"  style='vertical-align: bottom' src="<?php echo JURI::base(true) . '/components/com_receivements/assets/icons/delete.png';?>" alt="show" />
                        <a class="modal" style="background-color:transparent" href="<?php echo JRoute::_('index.php?option=com_receivements&tmpl=component&view=nota&id='.$booking['id'],false,2)?>" title="" rel="{handler: 'iframe', size: {x: 400, y: 360},onClose:function(){var js =window.location.reload();}}"><img class="hasTip" title="<?php echo JText::_('COM_RECEIVEMENTS_NOTE') . '::<pre><big>' . $this->escape($booking['nota']) . '</pre></big>' ?>" style="height:16px;border:none;" src="<?php echo JURI::base(true) . '/components/com_receivements/assets/icons/pen.png';?>" /></a>
                        </li>
                <?php endforeach ?>
                </ul>
                </li>
        <?php endforeach ?>
        </ul>
</fieldset>

<?php endif; ?>