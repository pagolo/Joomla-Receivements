<?php
/**
 * @version     0.5.0
 * @package     com_receivements
 * @copyright   Copyright (C) 2014. Tutti i diritti riservati.
 * @license     GNU General Public License versione 2 o successiva; vedi LICENSE.txt
 * @author      Paolo Bozzo <info@dbfweb.com> - http://dbfweb.com
 */
// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
?>

<?php if (empty($this->data)) : ?>
        <em><strong><?=JText::_('COM_RECEIVEMENTS_BOOKING_NOT_FOUND')?></strong></em>
<?php else : ?>
        <div class="front-end-edit">
        <form action="<?=JRoute::_('index?option=com_receivements&task=disdetta.remove')?>" id="form-remove" method="post" class="form-validate">
        <fieldset>
        <legend><?=JText::_('COM_RECEIVEMENTS_REMOVE_BOOKING')?></legend>
        <?=JText::sprintf('COM_RECEIVEMENTS_REALLY_REALLY_DELETE',$this->data['name'],ReceivementsFrontendHelper::convertDateFrom($this->data['data'], 'd/m/Y H:i'))?><br />&nbsp;<br />
        <input type="hidden" id="jform_id" name="jform[id]" value="<?=$this->data['id']?>" />
        <input type="hidden" id="jform_student" name="jform[student]" value="<?=$this->data['student']?>" />
        <input type="hidden" id="jform_classe" name="jform[classe]" value="<?=$this->data['classe']?>" />
        <input type="hidden" id="jform_data" name="jform[data]" value="<?=$this->data['data']?>" />
        <input type="hidden" id="jform_name" name="jform[name]" value="<?=$this->data['name']?>" />
        <input type="hidden" id="jform_email" name="jform[email]" value="<?=$this->data['email']?>" />
        <input type="hidden" id="jform_use_email" name="jform[use_email]" value="<?=$this->data['use_email']?>" />
        <button type="submit"><?=JText::_('COM_RECEIVEMENTS_REMOVE')?></button>
        <button class="cancel-button" onclick="window.location.href = '<?php echo JRoute::_('index.php', false, 2); ?>';" type="button"><?php echo JText::_('JCANCEL'); ?></button>
        <input type="hidden" name="option" value="com_receivements" />
        <input type="hidden" name="task" value="disdetta.remove" />
        <?php echo JHtml::_('form.token'); ?>
        </fieldset>
        </form>
        </div>
<?php endif; ?>
