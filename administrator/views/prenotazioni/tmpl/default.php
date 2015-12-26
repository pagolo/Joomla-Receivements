<?php
/**
 * @version     1.0.5
 * @package     com_receivements
 * @copyright   Copyright (C) 2014. Tutti i diritti riservati.
 * @license     GNU General Public License versione 2 o successiva; vedi LICENSE.txt
 * @author      Paolo Bozzo - pagolo.bozzo AT gmail.com - http://dbfweb.com
 */
// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHTML::_('behavior.formvalidation');
JHTML::_('script', 'system/multiselect.js', false, true);

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_receivements/assets/css/receivements.css');

$user = JFactory::getUser();
$userId = $user->get('id');
$listOrder = $this->state->get('list.ordering');
$listDirn = $this->state->get('list.direction');
$canOrder = $user->authorise('core.edit.state', 'com_receivements');
$saveOrder = $listOrder == 'p.ordering';

// include frontend helpers
//$language =& JFactory::getLanguage();
//$language->load('com_receivements', JPATH_SITE, $language->getTag(), true);
require_once JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_receivements'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'receivements.php';

?>

<script type="text/javascript">
    Joomla.submitbutton = function(task)
    {
        if (task == 'prenotazioni.email_delete')
        {
            if (document.adminForm.boxchecked.value == 0) {
                alert('<?php echo JText::_('JLIB_HTML_PLEASE_MAKE_A_SELECTION_FROM_THE_LIST')?>');
                return false;
            }
            if (confirm('<?php echo JText::_('COM_RECEIVEMENTS_REALLY_EMAIL_N_DELETE')?>')) {
                Joomla.submitform(task);
            } else {
                return false;
            }
        } else {
            Joomla.submitform(task);
        }
    }
    window.addEvent('domready', function(){
    document.formvalidator.setHandler('date', function(value) {
      regex=/^\d{2}-\d{2}-\d{4}$/;
      if (regex.test(value)) {
        var a = value.split('-');
        var size=a.length;
        if (size != 3) return false;
        if (a[0] < 1 || a[0] > 31) return false;
        if (a[1] < 1 || a[1] > 12) return false;
        return true;
      }
      return false;
      });
    });
</script>

<form action="<?php echo JRoute::_('index.php?option=com_receivements&view=prenotazioni'); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
    <fieldset id="filter-bar" style="height:auto">

        <div class="filter-search fltlft">
            <label class="filter-search-lbl" for="filter_from"><?php echo JText::_('COM_RECEIVEMENTS_FROM'); ?></label>
            <input type="text" name="filter_from" id="filter_from" class="required validate-date" size="12" value="<?php $from=$this->state->get('filter.from');echo $this->escape($from?$from:JHTML::_('date', JFactory::getDate(), 'd-m-Y')); ?>" title="<?php echo JText::_('Search'); ?>" />
            <label class="filter-search-lbl" for="filter_to"><?php echo JText::_('COM_RECEIVEMENTS_TO'); ?></label>
            <input type="text" name="filter_to" id="filter_to" class="validate-date" size="12" value="<?php echo $this->escape($this->state->get('filter.to')); ?>" title="<?php echo JText::_('Search'); ?>" />
            <button type="submit" class="validate"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
            <button type="button" onclick="document.id('filter_from').value = '<?php echo JHTML::_('date', JFactory::getDate(), 'd-m-Y')?>';document.id('filter_to').value = '';
                    this.form.submit();"><?php echo JText::_('COM_RECEIVEMENTS_FROM_NOW'); ?></button>
        </div>
    </fieldset>
    <div class="clr"> </div>

    <table class="table table-striped adminlist">
        <thead>
            <tr>
                <th width="1%">
							<input type="checkbox" name="checkall-toggle" value="" onclick="Joomla.checkAll(this)" />
                </th>
                
                <?php if (isset($this->items[0]->state)) : ?>
                    <th width="5%">
                        <?php echo JHtml::_('grid.sort', 'JPUBLISHED', 'p.state', $listDirn, $listOrder); ?>
                    </th>
                <?php endif; ?>

                <?php if (isset($this->items[0]->ordering)) : ?>
                    <th width="10%">
                        <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ORDERING', 'p.ordering', $listDirn, $listOrder); ?>
                        <?php if ($canOrder && $saveOrder) : ?>
                            <?php echo JHtml::_('grid.order', $this->items, 'filesave.png', 'prenotazioni.saveorder'); ?>
                        <?php endif; ?>
                    </th>
                <?php endif; ?>

                <?php if (isset($this->items[0]->id)) : ?>
                    <th width="1%" class="nowrap">
                        <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'p.id', $listDirn, $listOrder); ?>
                    </th>
                <?php endif; ?>
                <?php if (isset($this->items[0]->docente)) : ?>
                    <th class="nowrap left">
                        <?php echo JHtml::_('grid.sort', 'COM_RECEIVEMENTS_TEACHER', 'u.name', $listDirn, $listOrder); ?>
                    </th>
                <?php endif; ?>
                <?php if (isset($this->items[0]->studente)) : ?>
                    <th class="nowrap left">
                        <?php echo JHtml::_('grid.sort', 'COM_RECEIVEMENTS_STUDENT', 'p.nome', $listDirn, $listOrder); ?>
                    </th>
                <?php endif; ?>
                <?php if (isset($this->items[0]->email)) : ?>
                    <th class="nowrap left">
                        <?php
                        $orderby = ReceivementsFrontendHelper::getForcedLogin() ? 'u2.name' : 'p.email';
                        echo JHtml::_('grid.sort', 'COM_RECEIVEMENTS_PARENT', $orderby, $listDirn, $listOrder);
                        ?>
                    </th>
                <?php endif; ?>
                <?php if (isset($this->items[0]->data)) : ?>
                    <th class="nowrap left">
                        <?php echo JHtml::_('grid.sort', 'COM_RECEIVEMENTS_RECEIVEMENT', 'a.data', $listDirn, $listOrder); ?>
                    </th>
                <?php endif; ?>
                <?php if (isset($this->items[0]->creato)) : ?>
                    <th class="nowrap left">
                        <?php echo JHtml::_('grid.sort', 'COM_RECEIVEMENTS_CREATED', 'p.creato', $listDirn, $listOrder); ?>
                    </th>
                <?php endif; ?>
                <?php if (isset($this->items[0]->sede)) : ?>
                    <th class="nowrap left">
                        <?php echo JHtml::_('grid.sort', 'COM_RECEIVEMENTS_SITE', 's.sede', $listDirn, $listOrder); ?>
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
                $ordering = ($listOrder == 'p.ordering');
                $canCreate = $user->authorise('core.create', 'com_receivements');
                $canEdit = $user->authorise('core.edit', 'com_receivements');
                $canCheckin = $user->authorise('core.manage', 'com_receivements');
                $canChange = $user->authorise('core.edit.state', 'com_receivements');
                ?>
                <tr class="row<?php echo $i % 2; ?>">
                    <td class="center">
                        <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                    </td>
                    
                    <?php if (isset($this->items[0]->state)) { ?>
                        <td class="center">
                            <?php echo JHtml::_('jgrid.published', $item->state, $i, 'prenotazioni.', $canChange, 'cb'); ?>
                        </td>
                    <?php } ?>

                    <?php if (isset($this->items[0]->ordering)) { ?>
                        <td class="order">
                            <?php if ($canChange) : ?>
                                <?php if ($saveOrder) : ?>
                                    <?php if ($listDirn == 'asc') : ?>
                                        <span><?php echo $this->pagination->orderUpIcon($i, true, 'prenotazioni.orderup', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
                                        <span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, true, 'prenotazioni.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
                                    <?php elseif ($listDirn == 'desc') : ?>
                                        <span><?php echo $this->pagination->orderUpIcon($i, true, 'prenotazioni.orderdown', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
                                        <span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, true, 'prenotazioni.orderup', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <?php $disabled = $saveOrder ? '' : 'disabled="disabled"'; ?>
                                <input type="text" name="order[]" size="5" value="<?php echo $item->ordering; ?>" <?php echo $disabled ?> class="text-area-order" />
                            <?php else : ?>
                                <?php echo $item->ordering; ?>
                            <?php endif; ?>
                        </td>
                    <?php } ?>

                    <?php if (isset($this->items[0]->id)) { ?>
                        <td class="center">
                            <?php echo (int) $item->id; ?>
                        </td>
                    <?php } ?>
                    <?php if (isset($this->items[0]->docente)) { ?>
                        <td class="left">
			     <?php echo $this->escape($item->docente); ?>
                        </td>
                    <?php } ?>
                    <?php if (isset($this->items[0]->studente)) { ?>
                        <td class="left">
			     <?php echo $this->escape($item->studente).' ('.$this->escape($item->classe).')'; ?>
                        </td>
                    <?php } ?>
                    <?php if (isset($this->items[0]->email)) { ?>
                        <td class="left">
			     <a href="mailto:<?php echo $this->escape($item->email); ?>" title="<?php echo JText::_('COM_RECEIVEMENTS_CLICK_TO_SEND_EMAIL');?>">
                             <?php echo $item->genitore? $this->escape($item->genitore) : $this->escape($item->email) ?>
                             </a>
                        </td>
                    <?php } ?>
                    <?php if (isset($this->items[0]->data)) { ?>
                        <td class="left">
			     <?php echo ReceivementsFrontendHelper::convertDateFrom($item->data,'d F Y, H:i'); ?>
                        </td>
                    <?php } ?>
                    <?php if (isset($this->items[0]->creato)) { ?>
                        <td class="left">
			     <?php echo ReceivementsFrontendHelper::convertDateFrom($item->creato,'d F Y, H:i'); ?>
                        </td>
                    <?php } ?>
                    <?php if (isset($this->items[0]->sede)) { ?>
                        <td class="left">
			     <?php echo $this->escape($item->sede); ?>
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