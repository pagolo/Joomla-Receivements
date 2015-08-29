<?php
/**
 * @version     0.5.0
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

//Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_receivements', JPATH_ADMINISTRATOR);
$doc = JFactory::getDocument();
$doc->addStyleSheet(JUri::base() . 'components/com_receivements/assets/css/form.css');
$doc->addStyleSheet(JUri::base() . 'components/com_receivements/assets/css/list.css');
?>

<div class="front-end-edit">
    <?php if (!empty($this->item->id)): ?>
        <h1><?php
                echo JText::_('COM_RECEIVEMENTS_EDIT_ASSENZA');
        ?></h1>
    <?php else: ?>
        <h1><?php echo JText::_('COM_RECEIVEMENTS_CREATE_ASSENZA')?></h1>
    <?php endif; ?>

    <form id="form-assenza" action="<?php echo JRoute::_('index.php?option=com_receivements&task=assenza.save'); ?>" method="post" class="form-validate">
        <fieldset class='panelform' style='border-width:1px; width:500px; height:110%'>
                <legend><?php echo JFactory::getUser()->name; ?></legend>
            		<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
                        <?php echo $this->form->getInput('utente'); ?>
                        <?php echo $this->form->getInput('festivo'); ?>
                        <?php echo $this->form->getInput('finale'); ?>
                        <table class='front-end-list'>
                                <tr>
				<td class="ora-edit-label"><?php echo $this->form->getLabel('inizio'); ?></td>
				<td class="ora-edit"><?php echo $this->form->getInput('inizio'); ?></td>
				</tr>
                                <tr>
				<td class="ora-edit-label"><?php echo $this->form->getLabel('fine'); ?></td>
				<td class="ora-edit"><?php echo $this->form->getInput('fine'); ?></td>
				</tr>
                                <tr>
				<td class="ora-edit-label"><?php echo $this->form->getLabel('descrizione'); ?></td>
				<td class="ora-edit"><?php echo $this->form->getInput('descrizione'); ?></td>
				</tr>
				<tr>
				<td colspan = '2' style='text-align:center'><hr />
                                <button type="submit" class="validate"><span><?php echo JText::_('JSAVE'); ?></span></button>
    <?php if (!empty($this->item->id)): ?>
                                <a class="button btn" href="<?php echo JRoute::_('index.php?option=com_receivements&task=assenza.remove&id='.$this->item->id, false, 2); ?>"><?php echo JText::_('COM_RECEIVEMENTS_ORE_DELETE')?></a>
    <?php endif; ?>
                                <a class="button btn" href="<?php echo JRoute::_('index.php?option=com_receivements&task=assenze', false, 2); ?>"><?php echo JText::_('JCANCEL')?></a>
				</td>
				</tr>
			</table>

                <input type="hidden" name="option" value="com_receivements" />
                <input type="hidden" name="task" value="assenza.save" />
                <?php echo JHtml::_('form.token'); ?>
        </fieldset>
    </form>
</div>

