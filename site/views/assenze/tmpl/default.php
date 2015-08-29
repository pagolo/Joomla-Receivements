<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.tooltip');
JHTML::_('script', 'system/multiselect.js', false, true);
// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::base() . 'components/com_receivements/assets/css/form.css');
$document->addStyleSheet(JUri::base() . 'components/com_receivements/assets/css/list.css');
//Load admin language file
$lang = JFactory::getLanguage();
$lang->load('', JPATH_ADMINISTRATOR);

?>
<h1><?php echo JText::_('COM_RECEIVEMENTS_ASSENZE') . ' (' . JFactory::getUser()->name . ')'?></h1>

<form action="" method="post">
        <div><a class="button btn" href="<?php echo JRoute::_('index.php?option=com_receivements&task=assenza&id=0', false, 2); ?>"><?php echo JText::_('COM_RECEIVEMENTS_ADD_ITEM')?></a></div>
	<table class='front-end-list'>
		<thead>
			<tr>
				<th><?php echo JText::_('COM_RECEIVEMENTS_START')?></th>
				<th><?php echo JText::_('COM_RECEIVEMENTS_FINISH')?></th>
				<th><?php echo JText::_('COM_RECEIVEMENTS_DESCRIPTION')?></th>
				<th><?php echo JText::_('COM_RECEIVEMENTS_ACTIONS')?></th>
			</tr>
		</thead>
		<tbody>
<?php
		foreach ($this->items as &$row)
		{
?>
			<tr>
				<td><?php echo ReceivementsFrontendHelper::convertDateFrom($row->inizio)?></td>
				<td><?php echo ReceivementsFrontendHelper::convertDateFrom($row->fine)?></td>
				<td><em><?php echo $row->descrizione?></em></td>
				<td>
        			<a class="button btn" href="<?php echo JRoute::_('index.php?option=com_receivements&task=assenza&id=' . $row->id, false, 2); ?>"><?php echo JText::_('COM_RECEIVEMENTS_ORE_EDIT')?></a>
                                </td>
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
