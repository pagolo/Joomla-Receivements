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
//$lang = JFactory::getLanguage();
//$lang->load('com_receivements', JPATH_ADMINISTRATOR);
$doc = JFactory::getDocument();
$doc->addStyleSheet(JUri::base() . '/components/com_receivements/assets/css/form.css');
$doc->addStyleSheet(JUri::base() . '/components/com_receivements/assets/css/list.css');
$doc->addScript(JUri::base() . '/components/com_receivements/assets/js/form.js');
//JForm::addFieldPath(JPATH_COMPONENT . '/models/fields');
?>
<style>
label {
    display: block;
}
</style>
        <h1><?php echo JText::_('COM_RECEIVEMENTS_ADD_GENERAL')?></h1>

    <form id="form-ora" action="" method="post" class="form-validate">
        <fieldset class='panelform'>
        <?php echo JHTML::_('select.radiolist', $this->options, 'receivement', 'class="inputbox"', 'value', 'text', $this->options[0]->value );?>
        <br />
        <button type="submit" class="validate button btn"><span><?php echo JText::_('COM_RECEIVEMENTS_ADD_ITEM'); ?></span></button>
        <input type="hidden" name="option" value="com_receivements" />
        <input type="hidden" name="task" value="oraform.create" />
        <?php echo JHtml::_('form.token'); ?>        
        </fieldset>
    </form>

