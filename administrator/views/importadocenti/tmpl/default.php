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

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
// Import CSS
$doc = JFactory::getDocument();
$doc->addScript(JUri::base() . 'components/com_receivements/assets/js/spin.min.js');
?>

<form action="<?php echo JRoute::_('index.php?option=com_receivements&task=importadocenti.go&tmpl=component'); ?>" method="post" name="adminForm" id="import-form" enctype="multipart/form-data">
        <fieldset style="font-size:1.4em">
            <legend><?php echo JText::_('COM_RECEIVEMENTS_TEACHERS_IMPORT'); ?></legend>
            <ul class="adminformlist">

				<li><?php echo $this->form->getLabel('upload_teachers'); ?>
				<?php echo $this->form->getInput('upload_teachers'); ?></li>
				<li><?php echo $this->form->getLabel('separator'); ?>
				<?php echo $this->form->getInput('separator'); ?></li>
				<li><?php echo $this->form->getLabel('update'); ?>
				<?php echo $this->form->getInput('update'); ?></li>
				<li><?php echo $this->form->getLabel('max_app'); ?>
				<?php echo $this->form->getInput('max_app'); ?></li>
				<li><?php echo $this->form->getLabel('username'); ?>
				<?php echo $this->form->getInput('username'); ?></li>
				<li><?php echo $this->form->getLabel('name'); ?>
				<?php echo $this->form->getInput('name'); ?></li>
				<li><button onclick="myspinner(this)" style="font-size:1.2em" type="submit"><?php echo JText::_('COM_RECEIVEMENTS_IMPORT'); ?></button></li>
            </ul>
        </fieldset>

    <input type="hidden" name="task" value="importadocenti.go" />
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
