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
<h1><?=JText::_('COM_RECEIVEMENTS_ASSENZE')?></h1>

<form action="" method="post" name="publicForm">
	<table class='front-end-list'>
		<thead>
			<tr>
				<th>INIZIO</th>
				<th>FINE</th>
				<th>DESCRIZIONE</th>
			</tr>
		</thead>
		<tbody>
<?php
		$i = 0;
		foreach ($this->items as &$row)
		{
?>
			<tr class="row">
				<td><?=$this->convertdate($row->inizio)?></td>
				<td><?=$this->convertdate($row->fine)?></td>
				<td><em><?=$row->descrizione?></em></td>
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