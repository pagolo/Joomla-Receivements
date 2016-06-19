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
?>

<form action="<?php echo JRoute::_('index.php?option=com_receivements&task=manutenzione.go&tmpl=component'); ?>" method="post" name="adminForm" id="import-form" enctype="multipart/form-data">
        <fieldset style="font-size:1.4em">
            <legend><?php echo JText::_('COM_RECEIVEMENTS_INITIAL_CLEANUP'); ?></legend>
            <div><em><?php echo JText::_('COM_RECEIVEMENTS_INITIAL_CLEANUP_WARNING'); ?></em></div><br />
            <ul class="adminformlist">
				<li><?php echo $this->form->getLabel('main'); ?>
				<?php echo $this->form->getInput('main'); ?></li>
				<li><?php echo $this->form->getLabel('vacations'); ?>
				<?php echo $this->form->getInput('vacations'); ?></li>
				<li><?php echo $this->form->getLabel('matters'); ?>
				<?php echo $this->form->getInput('matters'); ?></li>
				<li><?php echo $this->form->getLabel('classes'); ?>
				<?php echo $this->form->getInput('classes'); ?></li>
				<li><?php echo $this->form->getLabel('sites'); ?>
				<?php echo $this->form->getInput('sites'); ?></li>
<?php if (ReceivementsFrontendHelper::getForcedLogin()) : ?>
				<li><?php echo $this->form->getLabel('parents'); ?>
				<?php echo $this->form->getInput('parents'); ?></li>
<?php endif; ?>
            </ul>
				<br /><button onclick="return confirm('<?php echo JText::_('COM_RECEIVEMENTS_SURE_SURE_DELETE'); ?>')" style="font-size:1.2em" type="submit"><?php echo JText::_('COM_RECEIVEMENTS_DELETE'); ?></button>
        </fieldset>

    <input type="hidden" name="task" value="manutenzione.go" />
    <?php echo JHtml::_('form.token'); ?>
    <div class="clr"></div>

    <style type="text/css">
        /* Temporary fix for drifting editor fields */
        .adminformlist li {
            clear: both;
        }
    </style>
</form>
<script>
function myspinner(target) {
alert("<?php echo JText::_('COM_RECEIVEMENTS_LONG_TIME_WAIT'); ?>");
var opts = {
  lines: 13, // The number of lines to draw
  length: 20, // The length of each line
  width: 10, // The line thickness
  radius: 30, // The radius of the inner circle
  corners: 1, // Corner roundness (0..1)
  rotate: 0, // The rotation offset
  direction: 1, // 1: clockwise, -1: counterclockwise
  color: '#000', // #rgb or #rrggbb or array of colors
  speed: 1, // Rounds per second
  trail: 60, // Afterglow percentage
  shadow: false, // Whether to render a shadow
  hwaccel: false, // Whether to use hardware acceleration
  className: 'spinner', // The CSS class to assign to the spinner
  zIndex: 2e9, // The z-index (defaults to 2000000000)
  top: '50%', // Top position relative to parent
  left: '50%' // Left position relative to parent
};
var spinner = new Spinner(opts).spin(target);
}
</script>
