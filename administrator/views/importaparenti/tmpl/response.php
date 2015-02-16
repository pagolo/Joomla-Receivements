<?php
/**
 * @version     0.5.0
 * @package     com_receivements
 * @copyright   Copyright (C) 2014. Tutti i diritti riservati.
 * @license     GNU General Public License versione 2 o successiva; vedi LICENSE.txt
 * @author      Paolo Bozzo - pagolo.bozzo AT gmail.com - http://dbfweb.com
 */
// no direct access
defined('_JEXEC') or die;

// result data
$data = JFactory::getApplication()->getUserState('com_receivements.importaparenti.data', null);
?>

<fieldset style="font-size:1.4em">
<legend><?php echo JText::_('COM_RECEIVEMENTS_PARENTS_IMPORT'); ?></legend>
<br />
<em>
<?php
if ($data) {
echo JText::plural('COM_RECEIVEMENTS_CREATED_USERS', $data->users_created).'<br />';
if ($data->users_failed) echo JText::plural('COM_RECEIVEMENTS_FAILED_USERS', $data->users_failed).'<br />';
echo JText::plural('COM_RECEIVEMENTS_EXISTING_USERS', $data->users_existing).'<br />';
echo JText::plural('COM_RECEIVEMENTS_SAVED_PARENTS', $data->parents_saved).'<br />';
if ($data->parents_failed) echo JText::plural('COM_RECEIVEMENTS_FAILED_PARENTS', $data->parents_failed).'<br />';
}
?>
</em>
<br />&nbsp;<br />
</fieldset>
