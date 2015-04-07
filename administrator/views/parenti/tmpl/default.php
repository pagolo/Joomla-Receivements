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

JHtml::_('behavior.modal');
JHtml::_('behavior.tooltip');
JHTML::_('script', 'system/multiselect.js', false, true);
// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_receivements/assets/css/receivements.css');

$user = JFactory::getUser();
$userId = $user->get('id');
$listOrder = $this->state->get('list.ordering');
$listDirn = $this->state->get('list.direction');
$canOrder = $user->authorise('core.edit.state', 'com_receivements');
$saveOrder = $listOrder == 'a.ordering';
$forcedLogin = ReceivementsFrontendHelper::getForcedLogin();
?>
<?php if ($forcedLogin) : ?>
<form action="<?php echo JRoute::_('index.php?option=com_receivements&view=parenti'); ?>" method="post" name="adminForm" id="adminForm">
    <fieldset id="filter-bar" style="height:auto">
        <div class="filter-search fltlft">
            <label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
            <input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('Search'); ?>" />
            <button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
            <button type="button" onclick="document.id('filter_search').value = '';
                    this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
        </div>

        

    </fieldset>
    <div class="clr"> </div>

    <table class="table table-striped adminlist">
        <thead>
            <tr>
                <th width="1%">
							<input type="checkbox" name="checkall-toggle" value="" onclick="Joomla.checkAll(this)" />
                </th>
                
                <?php if (isset($this->items[0]->id)) : ?>
                    <th width="1%" class="nowrap">
                        <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
                    </th>
                    <th width="16%" class="nowrap left">
                        <?php echo JHtml::_('grid.sort', 'COM_RECEIVEMENTS_NAME', 'u.name', $listDirn, $listOrder); ?>
                    </th>
                    <th width="16%" class="nowrap left">
                        <?php echo JHtml::_('grid.sort', 'COM_RECEIVEMENTS_RELATIONSHIP', 'a.parentela', $listDirn, $listOrder); ?>
                    </th>
                    <th width="16%" class="nowrap left">
                        <?php echo JHtml::_('grid.sort', 'COM_RECEIVEMENTS_STUDENT', 'a.studente', $listDirn, $listOrder); ?>
                    </th>
                    <th class="nowrap left">
                        <?php echo JHtml::_('grid.sort', 'COM_RECEIVEMENTS_CLASS', 'c.classe', $listDirn, $listOrder); ?>
                    </th>
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
                        <td class="left">
			     <a href="<?php echo JRoute::_('index.php?option=com_receivements&task=parente.edit&id='.(int) $item->id); ?>" title="<?php echo JText::sprintf('COM_RECEIVEMENTS_EDIT_PARENT', $this->escape($item->classe)); ?>">
			     <?php echo $this->escape($item->name); ?></a>
                        </td>
                        <td class="left">
			     <?php echo JText::_($item->parentela); ?>
                        </td>
                        <td class="left">
			     <?php echo $this->escape($item->studente); ?>
                        </td>
                        <td class="left">
			     <?php echo $this->escape($item->classe); ?>
                        </td>
                    <?php } ?>
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
<?php else : ?>
<div class="warning"><?php echo JText::_('COM_RECEIVEMENTS_ONLY_FORCED_LOGIN') ?></div>
<?php endif; ?>
