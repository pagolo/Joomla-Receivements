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

//JHTML::_('behavior.modal');
JHtml::_('behavior.keepalive');
//JHtml::_('behavior.tooltip');
//JHtml::_('behavior.formvalidation');

//echo $this->regexp;
$doc = JFactory::getDocument();
$doc->addStyleSheet(JUri::base() . '/components/com_receivements/assets/css/form.css');

?>


<div class="front-end-edit">

        <form id="form-prenota" action="" method="post" class="form-validate">

        <?php echo $this->form->getInput('ricevimenti'); ?>
        <fieldset>
                <button type="submit">OK</button>
                <input type="hidden" name="option" value="com_receivements" />
                <input type="hidden" name="task" value="prenota.save" />
                <?php echo JHtml::_('form.token'); ?>
        </fieldset>
        </form>
        
</div>