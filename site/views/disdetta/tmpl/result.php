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

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
?>

<?php if ($this->result == 'ok') : ?>
        <em><strong><?php echo JText::_('COM_RECEIVEMENTS_BOOKING_DELETED_SUCCESSFULLY')?></strong></em>
<?php else : ?>
        <em><strong><?php echo JText::_('COM_RECEIVEMENTS_BOOKING_NOT_DELETED')?></strong></em>
<?php endif; ?>
