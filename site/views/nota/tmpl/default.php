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

JHTML::_('behavior.modal');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

$doc = JFactory::getDocument();
$doc->addStyleSheet(JUri::base() . '/components/com_receivements/assets/css/form.css');
$doc->addStyleSheet(JUri::base() . '/components/com_receivements/assets/css/list.css');
?>

<div class="front-end-edit">
        <h1><?php echo JText::_('COM_RECEIVEMENTS_NOTE_EDIT')?></h1>

    <form id="form-nota" action="" method="post" class="form-validate">
            		<input id="jform_id" type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
                <textarea id="jform_note" name="jform[note]" style="width:100%" cols="80" rows="8"><?php echo $this->item->nota; ?></textarea>
                <button type="submit" class="button btn"><?php echo JText::_('JSAVE'); ?></button>
<?php if (!(empty($this->item->nota))) : ?>
                <button type="submit" name="jform[delete]" value="1" class="button btn"><?php echo JText::_('COM_RECEIVEMENTS_DELETE'); ?></button>
<?php endif; ?>
                <input type="hidden" name="option" value="com_receivements" />
                <input type="hidden" name="task" value="agenda.notesave" />
                <?php echo JHtml::_('form.token'); ?>
    </form>
</div>
