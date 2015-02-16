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

// message
$message_trans = JRequest::getVar('msg', '', 'get', 'string');
?>

<fieldset style="font-size:1.4em">
<legend><?php echo JText::_('COM_RECEIVEMENTS_TEACHERS_IMPORT'); ?></legend>
<br />
<em>
<strong>
<?php echo JText::_($message_trans); ?>
</strong>
</em>
<br />&nbsp;<br />
</fieldset>
