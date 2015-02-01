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

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
// Import CSS
?>

<form action="<?php echo JRoute::_('index.php?option=com_receivements&task=importadocenti.go&tmpl=component'); ?>" method="post" name="adminForm" id="import-form" enctype="multipart/form-data">
        <fieldset>
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
				<li><button type="submit"><?php echo JText::_('COM_RECEIVEMENTS_IMPORT'); ?></button></li>
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