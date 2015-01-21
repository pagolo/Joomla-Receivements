<?php
/**
 * @version     0.0.1
 * @package     com_receivements
 * @copyright   Copyright (C) 2014. Tutti i diritti riservati.
 * @license     GNU General Public License versione 2 o successiva; vedi LICENSE.txt
 * @author      Paolo Bozzo <info@dbfweb.com> - http://dbfweb.com
 */
// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHTML::_('script', 'system/multiselect.js', false, true);
// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_receivements/assets/css/receivements.css');

$user = JFactory::getUser();
$userId = $user->get('id');
$listOrder = $this->state->get('list.ordering');
$listDirn = $this->state->get('list.direction');
$canChange = $canOrder = $user->authorise('core.edit.state', 'com_receivements');
$saveOrder = $listOrder == 'a.ordering';
?>

<form action="<?php echo JRoute::_('index.php?option=com_receivements&view=ore'); ?>" method="post" name="adminForm" id="adminForm">
    <fieldset id="filter-bar">
        <div class="filter-search fltlft">
            <label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
            <input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('Search'); ?>" />
            <button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
            <button type="button" onclick="document.id('filter_search').value = '';
                    this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
        </div>

        

    </fieldset>
    <div class="clr"> </div>

    <table class="adminlist">
        <thead>
            <tr>
                <th width="1%">
                    <input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)" />
                </th>
                
                <?php if (isset($this->items[0]->id)) : ?>
                    <th width="1%" class="nowrap">
                        <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
                    </th>
                <?php endif; ?>
                <?php if (isset($this->items[0]->name)) : ?>
                    <th class="nowrap left">
                        <?php echo JHtml::_('grid.sort', 'COM_RECEIVEMENTS_NAME', 'u.name', $listDirn, $listOrder); ?>
                    </th>
                <?php if (isset($this->items[0]->id)) : ?>
		    <th class="nowrap" width="5%">
			<?php echo JHtml::_('grid.sort', 'COM_RECEIVEMENTS_EMAIL', 'a.email', $listDirn, $listOrder); ?>
		    </th>
		    <th class="nowrap" width="5%">
			<?php echo JHtml::_('grid.sort', 'COM_RECEIVEMENTS_ACTIVATED', 'a.attiva', $listDirn, $listOrder); ?>
		    </th>
                    <th class="nowrap">
                        <?php echo JHtml::_('grid.sort', 'COM_RECEIVEMENTS_CLASSES', 'a.classi', $listDirn, $listOrder); ?>
                    </th>
                    <th class="nowrap">
                        <?php echo JHtml::_('grid.sort', 'COM_RECEIVEMENTS_DAY', 'a.giorno', $listDirn, $listOrder); ?>
                    </th>
                    <th class="nowrap">
                        <?php echo JHtml::_('grid.sort', 'COM_RECEIVEMENTS_START', 'a.inizio', $listDirn, $listOrder); ?>
                    </th>
                    <th class="nowrap">
                        <?php echo JHtml::_('grid.sort', 'COM_RECEIVEMENTS_END', 'a.fine', $listDirn, $listOrder); ?>
                    </th>
                    <th width="2%" class="nowrap">
                        <?php echo JHtml::_('grid.sort', 'COM_RECEIVEMENTS_MAXAPP', 'a.max_app', $listDirn, $listOrder); ?>
                    </th>
                    <th class="nowrap">
                        <?php echo JHtml::_('grid.sort', 'COM_RECEIVEMENTS_SITE', 's.sede', $listDirn, $listOrder); ?>
                    </th>
                <?php endif; ?>
                <?php endif; ?>
            </tr>
        </thead>
        <tfoot>
            <?php
            if (isset($this->items[0])) {
                $colspan = count(get_object_vars($this->items[0])) + 1;
            } else {
                $colspan = 10;
            }
            ?>
            <tr>
                <td colspan="<?php echo $colspan ?>">
                    <?php echo $this->pagination->getListFooter(); ?>
                </td>
            </tr>
        </tfoot>
        <tbody>
            <?php
            foreach ($this->items as $i => $item) :
                $ordering = ($listOrder == 'a.ordering');
                $canCreate = $user->authorise('core.create', 'com_receivements');
                $canEdit = $user->authorise('core.edit', 'com_receivements');
                $canCheckin = $user->authorise('core.manage', 'com_receivements');
                $canChange = $user->authorise('core.edit.state', 'com_receivements');
                ?>
                <tr class="row<?php echo $i % 2; ?>">
                    <td class="center">
                        <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                    </td>
                    
                    <?php if (isset($this->items[0]->id)) { ?>
                        <td class="center">
                            <?php echo (int) $item->id; ?>
                        </td>
                    <?php } ?>
                    <?php if (isset($this->items[0]->name)) { ?>
                        <td class="left">
			     <a href="<?php echo JRoute::_('index.php?option=com_receivements&task=ora.edit&id='.(int) $item->id); ?>" title="<?php echo JText::sprintf('COM_RECEIVEMENTS_EDIT_HOUR', $this->escape($item->name)); ?>">
			     <?php echo $this->escape($item->name); ?></a>
                        </td>
                    <?php } ?>
                    <?php if (isset($this->items[0]->id)) : ?>
                        <td class="center">
			    <?php if ($canChange) : ?>
				<?php echo JHtml::_('grid.boolean', $i, $item->email=='1', 'ore.yes_email', 'ore.no_email'); ?>
			    <?php endif; ?>
                        </td>
                        <td class="center">
			    <?php if ($canChange) : ?>
				<?php echo JHtml::_('grid.boolean', $i, $item->attiva=='1', 'ore.activate', 'ore.unactivate'); ?>
			    <?php endif; ?>
                        </td>
                        <td class="left">
                            <?php echo $item->classi; ?>
                        </td>
                        <td class="left">
                            <?php echo JText::_('COM_RECEIVEMENTS_ORE_GIORNO_OPTION_'.$item->giorno); ?>
                        </td>
                        <td class="left">
                            <?php echo substr($item->inizio, 0, 5); ?>
                        </td>
                        <td class="left">
                            <?php echo substr($item->fine, 0, 5); ?>
                        </td>
                        <td class="center">
                            <?php echo $item->max_app; ?>
                        </td>
                        <td class="left">
                            <?php echo $item->sede; ?>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div>
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="0" />
        <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
        <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>