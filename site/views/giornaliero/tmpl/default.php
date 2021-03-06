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

<form id="form-giornaliero" action="<?php echo JRoute::_('index.php?option=com_receivements&view=giornaliero'); ?>" method="post" class="form-validate">
        <?php echo $this->form->getLabel('data'); ?>
        <?php echo $this->form->getInput('data'); ?>
        <button type="submit"><?php echo JText::_('COM_RECEIVEMENTS_GO'); ?></button>
        <button name="jform[print]" onclick="javascript:this.form.action = '<?php echo JRoute::_('index.php?option=com_receivements&view=giornaliero&tmpl=component&print=1'); ?>';return true;" type="submit"><?php echo JText::_('COM_RECEIVEMENTS_PRINT_PAGE'); ?></button>
</form>
<hr />
<fieldset>
        <legend>
        <?php echo JText::_('COM_RECEIVEMENTS_OF_DAY') . $this->date; ?>
        </legend>
        <ul>
        <?php if (empty($this->items)) : ?>
                <li>
                        <?php echo JText::_('COM_RECEIVEMENTS_NO_RECV_TODAY'); ?>
                </li>
        <?php endif; ?>
        <?php foreach($this->items as $item) : ?>
                <li>
                <?php echo JText::sprintf('COM_RECEIVEMENTS_AGENDA_ITEM',$item['name'],$item['sede'],substr($item['inizio'],0,5)); ?>
                <ol>
                <?php foreach($item['lista'] as $appointment) : ?>
                        <li>
                        <?php echo JText::sprintf('COM_RECEIVEMENTS_APPOINTMENT_ITEM',$appointment['studente'],$appointment['classe']); ?>
                        </li>
                <?php endforeach; ?>
                </ol>
                </li>
        <?php endforeach; ?>
        </ul>
</fieldset>
