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
JHtml::_('behavior.formvalidation');

//Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_receivements', JPATH_ADMINISTRATOR);
$doc = JFactory::getDocument();
$doc->addStyleSheet(JUri::base() . '/components/com_receivements/assets/css/form.css');
$doc->addScript(JUri::base() . '/components/com_receivements/assets/js/form.js');
//JForm::addFieldPath(JPATH_COMPONENT . '/models/fields');
?>

<script type="text/javascript">

    getScript('//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js', function() {
        jQuery(document).ready(function() {
            jQuery('#form-ora').submit(function(event) {
                
            });

            
        });
    });

</script>

<div class="ora-edit front-end-edit">
    <?php if (!empty($this->item->id)): ?>
        <h1>Modifica ora di ricevimento</h1>
    <?php else: ?>
        <h1>Imposta ora di ricevimento</h1>
    <?php endif; ?>

    <form id="form-ora" action="<?php echo JRoute::_('index.php?option=com_receivements&task=ora.save'); ?>" method="post" class="form-validate" enctype="multipart/form-data">
        <fieldset addfieldpath="/components/com_ricevimenti/models/fields" class='panelform' style='border-width:1px'>
                <legend><?php echo JFactory::getUser()->name; ?></legend>
            			<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
                                <?php echo $this->form->getInput('id_docente'); ?>
				<div class="ora-edit-label"><?php echo $this->form->getLabel('classi'); ?></div>
				<div class="ora-edit"><?php echo $this->form->getInput('classi'); ?></div><br />
				<div class="ora-edit-label"><?php echo $this->form->getLabel('giorno'); ?></div>
				<div class="ora-edit"><?php echo $this->form->getInput('giorno'); ?></div><br />
				<div class="ora-edit-label"><?php echo $this->form->getLabel('inizio'); ?></div>
				<div class="ora-edit"><?php echo $this->form->getInput('inizio'); ?></div><br />
				<div class="ora-edit-label"><?php echo $this->form->getLabel('fine'); ?></div>
				<div class="ora-edit"><?php echo $this->form->getInput('fine'); ?></div><br />
				<div class="ora-edit-label"><?php echo $this->form->getLabel('max_app'); ?></div>
				<div class="ora-edit"><?php echo $this->form->getInput('max_app'); ?></div><br />
				<div class="ora-edit-label"><?php echo $this->form->getLabel('sede'); ?></div>
				<div class="ora-edit"><?php echo $this->form->getInput('sede'); ?></div><br />
				<div class="ora-edit-label"><?php echo $this->form->getLabel('attiva'); ?></div>
				<div class="ora-edit"><?php echo $this->form->getInput('attiva'); ?></div><br />
        
        <div class="button-div">
            <button type="submit" class="validate"><span><?php echo JText::_('JSUBMIT'); ?></span></button>
            <a href="<?php echo JRoute::_('index.php?option=com_receivements&task=oraform.cancel'); ?>" title="<?php echo JText::_('JCANCEL'); ?>"><?php echo JText::_('JCANCEL'); ?></a>            
        </div>

        <input type="hidden" name="option" value="com_receivements" />
        <input type="hidden" name="task" value="oraform.save" />
        <?php echo JHtml::_('form.token'); ?>
        </fieldset>
    </form>
</div>
