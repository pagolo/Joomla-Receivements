<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.tooltip');

?>
<h1><?=JText::_('COM_RECEIVEMENTS_BOOKINGS')?></h1>

<?php
if (empty($this->items)) echo JText::_('COM_RECEIVEMENTS_NO_BOOKING_AVAILABLE');
else {
?>
<ul>
<?php
}
?>
<?php
foreach ($this->items as $row) :
?>
<li>
<?php
 echo ReceivementsFrontendHelper::convertDateFrom($row->data, JText::_('l d/m/Y'))
. ', ' . JText::_('COM_RECEIVEMENTS_HOURS')
. ' ' . ReceivementsFrontendHelper::convertDateFrom($row->data, JText::_('H:i'))
. ', ' . strtolower(JText::_('COM_RECEIVEMENTS_TEACHER')) . ' ' . $row->name . '.'; 
?>
</li>
<?php
endforeach;
?>
<?php if (count($this->items)) : ?>
</ul>
<?php endif; ?>