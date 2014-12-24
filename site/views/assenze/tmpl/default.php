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
<h1><?=JText::_('COM_RECEIVEMENTS_ASSENZE') . ' (' . JFactory::getUser()->name . ')'?></h1>

<form action="" method="post" name="publicForm">
        <button type="button"
                onclick="window.location.href = '<?php echo JRoute::_('index.php?option=com_receivements&task=assenza&id=0', false, 2); ?>';"><?php echo JText::_('COM_RECEIVEMENTS_ADD_ITEM'); ?></button>
	<table class='front-end-list'>
		<thead>
			<tr>
				<th><?=JText::_('COM_RECEIVEMENTS_START')?></th>
				<th><?=JText::_('COM_RECEIVEMENTS_FINISH')?></th>
				<th><?=JText::_('COM_RECEIVEMENTS_DESCRIPTION')?></th>
				<th><?=JText::_('COM_RECEIVEMENTS_ACTIONS')?></th>
			</tr>
		</thead>
		<tbody>
<?php
		$i = 0;
		foreach ($this->items as &$row)
		{
?>
			<tr class="row">
				<td><?=ReceivementsFrontendHelper::convertdate($row->inizio)?></td>
				<td><?=ReceivementsFrontendHelper::convertdate($row->fine)?></td>
				<td><em><?=$row->descrizione?></em></td>
				<td><button onclick="window.location.href = '<?php echo JRoute::_('index.php?option=com_receivements&task=assenza&id=' . $row->id, false, 2); ?>';" class="btn btn-mini" type="button"><?php echo JText::_('COM_RECEIVEMENTS_ORE_EDIT'); ?></button></td>
			</tr>
<?php
			$i++;
		}
?>
		</tbody>
	</table>
        <div>
        <input type="hidden" name="task" value=""/>
        <?php echo JHtml::_('form.token'); ?>
        </div>
</form>