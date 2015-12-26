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
                if (task == 'parente.cancel') {
                    Joomla.submitform(task, document.getElementById('parente-form'));
                }
                else{
                    
                    if (task != 'parente.cancel' && document.formvalidator.isValid(document.id('parente-form'))) {
                        
                        Joomla.submitform(task, document.getElementById('parente-form'));
                    }
                    else {
                        alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
                    }
                }
            }
        });
    });
</script>

<form action="<?php echo JRoute::_('index.php?option=com_receivements&view=parente&layout=edit&id=' . (int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="parente-form" class="form-validate">
    <div class="width-60 fltlft">
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_RECEIVEMENTS_LEGEND_PARENTE'); ?></legend>
            <ul class="adminformlist">

                <input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
		<li><?php echo $this->form->getLabel('utente'); ?>
		<?php echo $this->form->getInput('utente'); ?></li>
		<li><?php echo $this->form->getLabel('parentela'); ?>
		<?php echo $this->form->getInput('parentela'); ?></li>
<?php if (ReceivementsHelper::groupidFromGroupname(ReceivementsFrontendHelper::getStudentsGroup())) : ?>
		<li><?php echo $this->form->getLabel('id_studente'); ?>
		<?php echo $this->form->getInput('id_studente'); ?></li>
<script type="text/javascript">
  getScript('//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js', function() {
  $(document).ready(function(){
    $("#jform_id_studente").change(function(){
        var student = $("#jform_id_studente option:selected").text();
        var student_id = $("#jform_id_studente").val();
        if (student_id) $("#jform_studente").val(student);
      });
    });
  });
</script>
<?php endif; ?>
		<li><?php echo $this->form->getLabel('studente'); ?>
		<?php echo $this->form->getInput('studente'); ?></li>
		<li><?php echo $this->form->getLabel('id_classe'); ?>
		<?php echo $this->form->getInput('id_classe'); ?></li>


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