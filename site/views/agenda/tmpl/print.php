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

?>
<h1><?php echo $this->agenda_old? JText::_('COM_RECEIVEMENTS_AGENDA_OLD') : JText::_('COM_RECEIVEMENTS_AGENDA_NEW'); ?></h1>
<a href="<?php echo JRoute::_('index.php?option=com_receivements&view=agenda'); ?>" title="<?php echo JText::_('COM_RECEIVEMENTS_BACK'); ?>"><?php echo JText::_('COM_RECEIVEMENTS_BACK'); ?></a>
<a href="#" onclick="window.print(); return false;"><?php echo JText::_('COM_RECEIVEMENTS_PRINT'); ?></a>
<br />&nbsp;
<?php foreach($this->data->ore as $iii => $ore) : ?>
<?php if (empty($ore)) continue; ?>
<fieldset>
        <legend>
        <?php echo $ore['title']; ?>
        </legend>
        <ul>
        <?php foreach($ore['agenda'] as $i => $day) : ?>
                <li>
                <?php echo ReceivementsFrontendHelper::convertDateFrom($day['data'], 'l, d/m/Y H:i'); ?>
                (<?php echo JText::plural('COM_RECEIVEMENTS_ONTOTAL',$day['totale_ric'],$ore['max_app']); ?>)
                <ol>
                <?php foreach($day['nested'] as $ii => $booking) : ?>
                        <li>
                        <?php echo $booking['nome'].', '.$booking['classe'].' ('.JText::_($booking['parentela']).')'; ?>
                        <?php if (!empty($booking['nota'])) : ?>
                        <em><pre><big>NOTA: <?php echo $this->escape($booking['nota']); ?></big></pre></em>
                        <?php endif; ?>
                        </li>
                <?php endforeach; ?>
                </ol>
                </li>
        <?php endforeach; ?>
        </ul>
</fieldset>
<?php endforeach; ?>
