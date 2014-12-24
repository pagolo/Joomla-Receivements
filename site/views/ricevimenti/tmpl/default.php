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
<h1><?=JText::_('COM_RECEIVEMENTS')?></h1>

<form action="" method="post" name="publicForm">
		<div class="filter-select fltlft">
			<select name="filter_day" class="inputbox" onchange="this.form.submit()">
				<option value="*"><?php echo JText::_('COM_RECEIVEMENTS_ALL_DAYS');?></option>
				<?php echo JHtml::_('select.options', ReceivementsFrontendHelper::getWeekDayOptions(), 'value', 'text', $this->state->get('filter.giorno'));?>
			</select>
			<select name="filter_class" class="inputbox" onchange="this.form.submit()">
				<option value="*"><?php echo JText::_('COM_RECEIVEMENTS_ALL_CLASSES');?></option>
				<?php echo JHtml::_('select.options', ReceivementsFrontendHelper::getClassesOptions(), 'text', 'text', $this->state->get('filter.classe'));?>
			</select>
			<select name="filter_site" class="inputbox" onchange="this.form.submit()">
				<option value="*"><?php echo JText::_('COM_RECEIVEMENTS_ALL_SITES');?></option>
				<?php echo JHtml::_('select.options', ReceivementsFrontendHelper::getSitesOptions(), 'value', 'text', $this->state->get('filter.sede'));?>
			</select>
		</div>
		<div class="clr"><br /></div>
	<table class='front-end-list'>
		<thead>
			<tr>
				<th><input type="checkbox" name="toggle" value="" title="<?=JText::_('COM_RECEIVEMENTS_SELECT_ALL')?>" onclick="checkAll(this)" /></th>
				<th><?=JText::_('COM_RECEIVEMENTS_TEACHER')?></th>
				<th><?=JText::_('COM_RECEIVEMENTS_MATTERS')?></th>
				<th><?=JText::_('COM_RECEIVEMENTS_CLASSES')?></th>
				<th><?=JText::_('COM_RECEIVEMENTS_SITE')?></th>
				<th><?=JText::_('COM_RECEIVEMENTS_DAY_TIME')?></th>
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
				<td style="text-align:center"><?=$checked?></td>
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