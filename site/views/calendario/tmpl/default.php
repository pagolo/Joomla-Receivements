<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.tooltip');
JHTML::_('script', 'system/multiselect.js', false, true);
// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_receivements/assets/css/list.css');
//Load admin language file
$lang = JFactory::getLanguage();
$lang->load('', JPATH_ADMINISTRATOR);

?>
<h1><?=JText::_('COM_RECEIVEMENTS_CALENDAR')?></h1>

<form action="" method="post" name="publicForm">
	<table class='front-end-list'>
		<thead>
			<tr>
				<th><?=JText::_('COM_RECEIVEMENTS_START')?></th>
				<th><?=JText::_('COM_RECEIVEMENTS_FINISH')?></th>
				<th><?=JText::_('COM_RECEIVEMENTS_DESCRIPTION')?></th>
			</tr>
		</thead>
		<tbody>
<?php
		foreach ($this->items as &$row)
		{
?>
			<tr class="row">
				<td><?=ReceivementsFrontendHelper::convertDateFrom($row->inizio)?></td>
				<td><?=ReceivementsFrontendHelper::convertDateFrom($row->fine)?></td>
				<td><em><?=$row->descrizione?></em></td>
			</tr>
<?php
		}
?>
		</tbody>
	</table>
        <div>
        <input type="hidden" name="task" value=""/>
        <?php echo JHtml::_('form.token'); ?>
        </div>
</form>