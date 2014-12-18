<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.tooltip');
JHTML::_('script', 'system/multiselect.js', false, true);
// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_receivements/assets/css/list.css');

?>
<h1><?=JText::_('COM_RECEIVEMENTS')?></h1>

<form action="" method="post" name="publicForm">
	<table class='front-end-list category'>
		<thead>
			<tr>
				<th><input type="checkbox" name="toggle" value="" title="Seleziona/deseleziona tutti" onclick="checkAll(this)" /></th>
				<th>DOCENTE</th>
				<th>MATERIE</th>
				<th>CLASSI</th>
				<th>SEDE</th>
				<th>GIORNO/ORA</th>
			</tr>
		</thead>
                <tfoot>
                        <tr>
                                <td style="text-align:center" colspan="<?php echo isset($this->items[0]) ? count(get_object_vars($this->items[0])) : 10; ?>">
                                <?php echo $this->pagination->getListFooter(); ?>
                                </td>
                        </tr>
                </tfoot>
		<tbody>
<?php
		$i = 0;
		foreach ($this->items as &$row)
		{
		      $checked    = JHTML::_( 'grid.id', $i, $row->id );
?>
			<tr class="row">
				<td><?=$checked?></td>
				<td><?=$row->name?></td>
				<td><?=$row->materie?></td>
				<td><?=$row->classi?></td>
				<td><?=$row->sede?></td>
				<td>
                                <?php 
                                echo JText::_('COM_RECEIVEMENTS_ORE_GIORNO_OPTION_' . $row->giorno);
                                echo '&nbsp;'  . substr($row->inizio,0,5);
                                ?>
                                </td>
			</tr>
<?php
			$i++;
		}
?>
		</tbody>
	</table>
        <div>
        <input type="hidden" name="task" value=""/>
        <input type="hidden" name="boxchecked" value="0"/>
        <?php echo JHtml::_('form.token'); ?>
        </div>
</form>