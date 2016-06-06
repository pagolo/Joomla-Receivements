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

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_receivements/assets/css/receivements.css');
?>
<script type="text/javascript">
    function getScript(url,success) {
        var script = document.createElement('script');
        script.src = url;
        var head = document.getElementsByTagName('head')[0],
        done = false;
        // Attach handlers for all browsers
        script.onload = script.onreadystatechange = function() {
            if (!done && (!this.readyState
                || this.readyState == 'loaded'
                || this.readyState == 'complete')) {
                done = true;
                success();
                script.onload = script.onreadystatechange = null;
                head.removeChild(script);
            }
        };
        head.appendChild(script);
    }
    getScript('//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js',function() {
        js = jQuery.noConflict();
        js(document).ready(function(){
            

            Joomla.submitbutton = function(task)
            {
                if (task == 'generale.cancel') {
                    Joomla.submitform(task, document.getElementById('generale-form'));
                }
                else{
                    
                    if (task != 'generale.cancel' && document.formvalidator.isValid(document.id('generale-form'))) {
                        
                        Joomla.submitform(task, document.getElementById('generale-form'));
                    }
                    else {
                        alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
                    }
                }
            }
        });
    });
</script>

<form action="<?php echo JRoute::_('index.php?option=com_receivements&view=generale&layout=edit&id=' . (int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="generale-form" class="form-validate">
    <div class="width-60 fltlft">
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_RECEIVEMENTS_LEGEND_GENERALE'); ?></legend>
            <ul class="adminformlist">
                <input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
		<li><?php echo $this->form->getLabel('titolo'); ?>
		<?php echo $this->form->getInput('titolo'); ?></li>
		<li><?php echo $this->form->getLabel('descrizione'); ?>
		<?php echo $this->form->getInput('descrizione'); ?></li>
		<li><?php echo $this->form->getLabel('data'); ?>
		<?php echo $this->form->getInput('data'); ?></li>
		<li><?php echo $this->form->getLabel('inizio'); ?>
		<?php echo $this->form->getInput('inizio'); ?></li>
		<li><?php echo $this->form->getLabel('fine'); ?>
		<?php echo $this->form->getInput('fine'); ?></li>
		<li><?php echo $this->form->getLabel('sede'); ?>
		<?php echo $this->form->getInput('sede'); ?></li>
		<li><?php echo $this->form->getLabel('attiva'); ?>
		<?php echo $this->form->getInput('attiva'); ?></li>
            </ul>
        </fieldset>
    </div>

    

    <input type="hidden" name="task" value="" />
    <?php echo JHtml::_('form.token'); ?>
    <div class="clr"></div>

    <style type="text/css">
        /* Temporary fix for drifting editor fields */
        .adminformlist li {
            clear: both;
        }
    </style>
</form>