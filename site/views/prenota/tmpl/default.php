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

//JHTML::_('behavior.modal');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
//JHtml::_('behavior.formvalidation');

//echo $this->regexp;
$doc = JFactory::getDocument();
$doc->addStyleSheet(JUri::base() . '/components/com_receivements/assets/css/form.css');
$doc->addScript(JUri::base() . '/components/com_receivements/assets/js/form.js');

?>


<div class="front-end-edit">

        <form id="form-prenota" action="" method="post" class="form-validate">

        <?php echo $this->form->getInput('ricevimenti'); ?>
        <fieldset class="select_rcv">
                <legend><?php echo JText::_('COM_RECEIVEMENTS_PERSONAL_DATA')?></legend>
                <ul class="ul_rcv">
                        <li>                                
                                <?php echo $this->form->getLabel('nome'); ?>
                                <?php echo $this->form->getInput('nome'); ?>
                        </li>
                        <li>
                                <?php echo $this->form->getLabel('classe'); ?>
                                <?php echo $this->form->getInput('classe'); ?>
                        </li>
                        <li>
                                <?php echo $this->form->getLabel('email'); ?>
                                <?php echo $this->form->getInput('email'); ?>
                        </li>
                        <li>
                                <?php echo $this->form->getLabel('parentela'); ?>
                                <?php echo $this->form->getInput('parentela'); ?>
                        </li>
                </ul>
        </fieldset>
        <?php if (ReceivementsFrontendHelper::getCaptcha() == 'recaptcha') : ?>
        <fieldset class="select_rcv">
                <legend>Controllo</legend>
                                <?php echo $this->form->getInput('captcha'); ?>
        </fieldset>
        <?php endif; ?>
        <fieldset class="center">
                <button class="button btn" type="submit"><?php echo JText::_('COM_RECEIVEMENTS_SEND_REQUEST')?></button>
                <a class="button btn btn-small" href="<?php echo ReceivementsRoute::_('index.php?option=com_receivements&task=ricevimenti', false, 2); ?>"><?php echo JText::_('JCANCEL')?></a> 
                <input type="hidden" name="option" value="com_receivements" />
                <input type="hidden" name="task" value="prenota.save" />
                <?php echo JHtml::_('form.token'); ?>
        </fieldset>
        </form>
        
</div>



<script type="text/javascript">
getScript('//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js', function() {
$(document).ready(function(){
  var count = $("#jform_ricevimenti_count").val();
  if (screen.width <= 800) {
        for (var i=0; i < count; i++) {
                $("#doc_"+i).prop("style","float:left;width:100%;height:1em;white-space: nowrap");
        }
  }
<?php if (ReceivementsFrontendHelper::getForcedLogin()) : ?>
  $("#jform_classe").prop("readonly", "readonly");
  $("#jform_nome").change(function(){
        var student = $("#jform_nome").val();
        var uri = 'index.php?option=com_receivements&view=ajax&layout=change-student&format=raw&student='+encodeURIComponent(student);
        $.get( uri, function( data ) {
                $("#jform_classe").val(data.classe);
                $("#jform_parentela").val(data.parentela == 'COM_RECEIVEMENTS_PARENT' ? '*' : data.parentela);
        }, "json" );
  });
<?php endif; ?>
});
});
</script>

