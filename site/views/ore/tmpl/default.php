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
$document->addStyleSheet('components/com_receivements/assets/css/list.css');

$user = JFactory::getUser();
$userId = $user->get('id');
$listOrder = $this->state->get('list.ordering');
$listDirn = $this->state->get('list.direction');
$ordering = ($listOrder == 'a.ordering');
$canCreate = $user->authorise('core.create', 'com_receivements');
$canEdit = $user->authorise('core.edit', 'com_receivements');
$canCheckin = $user->authorise('core.manage', 'com_receivements');
$canChange = $user->authorise('core.edit.state', 'com_receivements');
$canDelete = $user->authorise('core.delete', 'com_receivements');
?>

<form action="<?php echo JRoute::_('index.php?option=com_receivements&view=ore'); ?>" method="post"
      name="adminForm" id="adminForm">

    
    <table class="front-end-list">
        <thead>
        <tr>
            
            <?php if (isset($this->items[0]->state)) : ?>
                <th class="align-left">
                    <?php echo JHtml::_('grid.sort', 'JPUBLISHED', 'a.state', $listDirn, $listOrder); ?>
                </th>
            <?php endif; ?>

            <?php if (isset($this->items[0]->id)) : ?>
                <th class="nowrap align-left">
                    <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
                </th>
            <?php endif; ?>

            				<?php if ($canEdit || $canDelete): ?>
					<th class="align-center">
				<?php echo JText::_('COM_RECEIVEMENTS_ORE_ACTIONS'); ?>
				</th>
				<?php endif; ?>

        </tr>
        </thead>
        <tfoot>
        <tr>
            <td colspan="<?php echo isset($this->items[0]) ? count(get_object_vars($this->items[0])) : 10; ?>">
                <?php echo $this->pagination->getListFooter(); ?>
            </td>
        </tr>
        </tfoot>
        <tbody>
        <?php foreach ($this->items as $i => $item) : ?>
            <?php $canEdit = $user->authorise('core.edit', 'com_receivements'); ?>

            				<?php if (!$canEdit && $user->authorise('core.edit.own', 'com_receivements')): ?>
					<?php $canEdit = JFactory::getUser()->id == $item->created_by; ?>
				<?php endif; ?>


            <tr class="row<?php echo $i % 2; ?>">
                
                <?php if (isset($this->items[0]->state)) : ?>
                    <td class="align-left">
                        <button
                            type="button" <?php echo ($canEdit || $canChange) ? 'onclick="window.location.href=\'' . JRoute::_('index.php?option=com_receivements&task=oraform.publish&id=' . $item->id . '&state=' . (($item->state + 1) % 2) . '\'"', false, 2) : 'disabled="disabled"'; ?>>
                            <?php if ($item->state == 1): ?>
                                <?php echo JText::_('JPUBLISHED'); ?>
                            <?php else: ?>
                                <?php echo JText::_('JUNPUBLISHED'); ?>
                            <?php endif; ?>
                        </button>
                    </td>
                <?php endif; ?>
                <?php if (isset($this->items[0]->id)) : ?>
                    <td class="align-left">
                        <?php echo (int)$item->id; ?>
                    </td>
                <?php endif; ?>

                				<?php if ($canEdit || $canDelete): ?>
					<td class="align-center">
						<?php if ($canEdit): ?>
							<button onclick="window.location.href = '<?php echo JRoute::_('index.php?option=com_receivements&task=oraform.edit&id=' . $item->id, false, 2); ?>';" class="btn btn-mini" type="button"><?php echo JText::_('COM_RECEIVEMENTS_ORE_EDIT'); ?></button>
						<?php endif; ?>
						<?php if ($canDelete): ?>
							<button data-item-id="<?php echo $item->id; ?>" class="delete-button" type="button"><?php echo JText::_('COM_RECEIVEMENTS_ORE_DELETE'); ?></button>
						<?php endif; ?>
					</td>
				<?php endif; ?>

            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <?php if ($canCreate): ?>
        <button type="button"
                onclick="window.location.href = '<?php echo JRoute::_('index.php?option=com_receivements&task=oraform.edit&id=0', false, 2); ?>';"><?php echo JText::_('COM_RECEIVEMENTS_ADD_ITEM'); ?></button>
    <?php endif; ?>

    <div>
        <input type="hidden" name="task" value=""/>
        <input type="hidden" name="boxchecked" value="0"/>
        <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
        <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>

<script type="text/javascript">
    if (typeof jQuery == 'undefined') {
        var headTag = document.getElementsByTagName("head")[0];
        var jqTag = document.createElement('script');
        jqTag.type = 'text/javascript';
        jqTag.src = '//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js';
        jqTag.onload = jQueryCode;
        headTag.appendChild(jqTag);
    } else {
        jQueryCode();
    }

    function jQueryCode() {
        jQuery('.delete-button').click(function () {
            var item_id = jQuery(this).attr('data-item-id');
            if (confirm("<?php echo JText::_('COM_RECEIVEMENTS_DELETE_MESSAGE'); ?>")) {
                window.location.href = '<?php echo JRoute::_('index.php?option=com_receivements&task=oraform.remove&id=', false, 2) ?>' + item_id;
            }
        });
    }

</script>