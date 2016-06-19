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

// result data
$success = JFactory::getApplication()->input->get->get('result', '', 'int');
?>

<fieldset style="font-size:1.4em">
<legend><?php echo JText::_('COM_RECEIVEMENTS_INITIAL_CLEANUP'); ?></legend>
<br />
<em>
<?php
if ($success) {
  echo JText::_('COM_RECEIVEMENTS_CLEANED_OK');
} else {
  echo JText::_('COM_RECEIVEMENTS_CLEANED_FAIL');
}
?>
</em>
<br />&nbsp;<br />
</fieldset>
