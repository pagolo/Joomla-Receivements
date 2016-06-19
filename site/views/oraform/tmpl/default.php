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

//Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_receivements', JPATH_ADMINISTRATOR);
$doc = JFactory::getDocument();
$doc->addStyleSheet(JUri::base() . '/components/com_receivements/assets/css/form.css');
$doc->addStyleSheet(JUri::base() . '/components/com_receivements/assets/css/list.css');
$doc->addScript(JUri::base() . '/components/com_receivements/assets/js/form.js');
//JForm::addFieldPath(JPATH_COMPONENT . '/models/fields');
?>

<div class="front-end-edit">
    <?php if (!empty($this->item->id)): ?>
        <h1><?php echo JText::_('COM_RECEIVEMENTS_EDIT_RECEIVEMENT_HOUR')?></h1>
    <?php else: ?>
        <h1><?php echo JText::_('COM_RECEIVEMENTS_CREATE_RECEIVEMENT_HOUR')?></h1>
    <?php endif; ?>
    
<?php if (!(empty($this->options))) : ?>
    <p><em>&nbsp;&nbsp;&nbsp;<a href="<?php echo JRoute::_('index.php?option=com_receivements&tmpl=component&view=oraform&layout=create', false, 2); ?>" class="modal" rel="{handler: 'iframe', size: {x: 500, y: 340}}" style="border:none" title="<?php echo JText::_('COM_RECEIVEMENTS_ADD_GENERAL_TITLE')?>"><?php echo JText::_('COM_RECEIVEMENTS_ADD_GENERAL')?></a></em></p>
<?php endif; ?>

    <form id="form-ora" action="" method="post" class="form-validate">
        <fieldset class='panelform' style='border-width:1px; width:500px; height:110%'>
                <legend><?php echo JFactory::getUser()->name; ?></legend>
           		<input type="hidden" name="jform[una_tantum]" value="<?php echo $this->item->una_tantum; ?>" />
                         <?php echo $this->form->getInput('id_docente'); ?>
                        <table class='front-end-list'>
                                <tr>
				<td class="ora-edit-label" style='width:20%'><?php echo $this->form->getLabel('id'); ?></td>
				<td class="ora-edit"><?php echo $this->form->getInput('id'); ?></td>
				</tr>
                                <tr>
				<td class="ora-edit-label" style='width:20%'><?php echo $this->form->getLabel('cattedra'); ?></td>
				<td class="ora-edit"><?php echo $this->form->getInput('cattedra'); ?></td>
				</tr>
                                <tr>
				<td class="ora-edit-label"><?php echo $this->form->getLabel('classi'); ?></td>
				<td class="ora-edit"><?php echo $this->form->getInput('classi'); ?></td>
				</tr>
                                <tr>
				<td class="ora-edit-label"><?php echo $this->form->getLabel('giorno'); ?></td>
<?php if ($this->item->una_tantum == 0) : ?>
				<td class="ora-edit"><?php echo $this->form->getInput('giorno'); ?></td>
<?php else : ?>
				<td class="ora-edit"><?php echo ReceivementsFrontendHelper::getSingleDate($this->item->una_tantum); ?></td>
<?php endif; ?>
				</tr>
                                <tr>
				<td class="ora-edit-label"><?php echo $this->form->getLabel('inizio'); ?></td>
				<td class="ora-edit"><?php echo $this->form->getInput('inizio'); ?></td>
				</tr>
                                <tr>
				<td class="ora-edit-label"><?php echo $this->form->getLabel('fine'); ?></td>
				<td class="ora-edit"><?php echo $this->form->getInput('fine'); ?></td>
				</tr>
                                <tr>
				<td class="ora-edit-label"><?php echo $this->form->getLabel('max_app'); ?></td>
				<td class="ora-edit"><?php echo $this->form->getInput('max_app'); ?></td>
				</tr>
                                <tr>
				<td class="ora-edit-label"><?php echo $this->form->getLabel('sede'); ?></td>
				<td class="ora-edit"><?php echo $this->form->getInput('sede'); ?></td>
				</tr>
                                <tr>
				<td class="ora-edit-label"><?php echo $this->form->getLabel('email'); ?></td>
				<td class="ora-edit"><?php echo $this->form->getInput('email'); ?></td>
				</tr>
                                <tr>
				<td class="ora-edit-label"><?php echo $this->form->getLabel('attiva'); ?></td>
				<td class="ora-edit"><?php echo $this->form->getInput('attiva'); ?></td>
				</tr>
				<tr>
				<td colspan = '2' style='text-align:center'><hr />
                                <button type="submit" class="validate button btn"><span><?php echo JText::_('JSAVE'); ?></span></button>
<?php if ($this->item->una_tantum > 0) : ?>
                                <button type="submit" onclick="return delete_button()" class="validate button btn"><span><?php echo JText::_('COM_RECEIVEMENTS_ORE_DELETE'); ?></span></button>
<?php endif; ?>
                                <a class="button btn btn-small" href="<?php echo JRoute::_(JURI::base(), false, 2); ?>"><?php echo JText::_('JCANCEL')?></a> 
				</td>
				</tr>
			</table>

                <input type="hidden" name="option" value="com_receivements" />
                <input type="hidden" name="task" value="oraform.save" />
                <?php echo JHtml::_('form.token'); ?>
        </fieldset>
    </form>
</div>
<script type="text/javascript">
getScript('//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js', function() {
$(document).ready(function(){
  $('#jform_classi').prop('readonly', true);
  $('#jform_inizio').prop('readonly', true);
  $('#jform_fine').prop('readonly', true);
  $('#jform_id').attr('onchange', 'document.location.href="<?php echo JURI::base()?>?option=com_receivements&task=oraform.edit&id="+this.value');
  });
});
function delete_button() {
  if (!confirm('<?php echo JText::_('COM_RECEIVEMENTS_REALLY_DELETE_HOUR') ?>')) return false;
  el = document.getElementsByName('task');
  el[0].value = "oraform.remove";
  return true;
}
</script>  