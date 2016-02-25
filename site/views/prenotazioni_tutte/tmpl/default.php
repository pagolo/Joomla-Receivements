<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
JHTML::_( 'behavior.calendar' );
JHTML::_( 'behavior.tooltip' );

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::base() . 'components/com_receivements/assets/css/form.css');
$document->addStyleSheet(JUri::base() . 'components/com_receivements/assets/css/list.css');


?>
<h1><?php echo JText::_('COM_RECEIVEMENTS_BOOKINGS_ALL')?></h1>

<form id="primo" action="<?php echo JRoute::_('index.php?option=com_receivements&amp;view=prenotazioni_tutte') ?>" method="post">
        <div class="filter-search">
            <div style="float:left">
            <label id="filter_from-lbl" for="filter_from"><?php echo JText::_('COM_RECEIVEMENTS_FROM'); ?></label>
<?php
            $from=$this->state->get('filter.from');
            $from =  $this->escape($from?$from:JHTML::_('date', JFactory::getDate(), 'd-m-Y'));
            echo JHtml::calendar($from, 'filter_from', 'filter_from', '%d-%m-%Y', 
            array(
              'style' => 'float:left',
              ) 
            ); 
?>                      </div><div style="float:left">
                        <label id="filter_teacher-lbl" for="filter_teachers"><?php echo JText::_('COM_RECEIVEMENTS_TEACHER'); ?></label>
                        <div class="input-append">
        		&nbsp;<select id="filter_teachers" name="filter_teachers" class="inputbox"">
				<option value="*"><?php echo JText::_('COM_RECEIVEMENTS_TEACHER_SELECT');?></option>
				<?php echo JHtml::_('select.options', ReceivementsFrontendHelper::getTeachersOptions(), 'value', 'text', $this->state->get('filter.teacher'));?>
			</select>
                        </div>
                        </div>
        </div>
        <div class="filter-search fltlft">
            <div style="float:left">
            <label id="filter_to-lbl" for="filter_to"><?php echo JText::_('COM_RECEIVEMENTS_TO'); ?></label>
<?php
            $to=$this->state->get('filter.to');
            $to =  $this->escape($to);
            echo JHtml::calendar($to, 'filter_to', 'filter_to', '%d-%m-%Y', 
            array(
              'style' => 'float:left',
              ) 
            ); 
?>
                        </div><div style="float:left"><label id="filter_classes-lbl" for="filter_classes"><?php echo JText::_('COM_RECEIVEMENTS_CLASS'); ?></label>
                        <div class="input-append">
        		&nbsp;<select id="filter_classes" name="filter_classes" class="inputbox"">
				<option value="*"><?php echo JText::_('COM_RECEIVEMENTS_CLASS_SELECT');?></option>
				<?php echo JHtml::_('select.options', ReceivementsFrontendHelper::getClassesOptions(), 'value', 'text', $this->state->get('filter.class'));?>
			</select>
                        </div>
                        </div>
        </div>        
            <div class="filter-search">
            &nbsp;<br />
            <button type="submit" class="validate"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
            <button type="button" onclick="document.id('filter_from').value = '<?php echo JHTML::_('date', JFactory::getDate(), 'd-m-Y')?>';document.id('filter_to').value = '';document.id('filter_teachers').value = document.id('filter_classes').value = '*';
                    this.form.submit();"><?php echo JText::_('COM_RECEIVEMENTS_RESET'); ?></button>
            <br />&nbsp;
            </div>
</form>

    <table class="front-end-list">
        <thead>
            <tr>
				<th><?php echo JText::_('COM_RECEIVEMENTS_TEACHER')?></th>
				<th><?php echo JText::_('COM_RECEIVEMENTS_STUDENT')?></th>
				<th><?php echo JText::_('COM_RECEIVEMENTS_DAY_TIME')?></th>
				<th><?php echo JText::_('COM_RECEIVEMENTS_BOOKING_TIME')?></th>
				<th><?php echo JText::_('COM_RECEIVEMENTS_SITE')?></th>
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
            foreach ($this->items as $i => $item) :
                ?>
                <tr class="row<?php echo $i % 2; ?>">
                        <td>
                    <?php if (isset($item->docente)) { ?>
			     <?php echo $this->escape($item->docente); ?>
                    <?php } ?>
                        </td>
                        <td>
                    <?php if (isset($item->studente)) { ?>
			     <?php echo $this->escape($item->studente).' ('.$this->escape($item->classe).')'; ?>
                    <?php } ?>
                        </td>
                        <td>
                    <?php if (isset($item->data)) { ?>
			     <?php echo ReceivementsFrontendHelper::convertDateFrom($item->data,'d F Y, H:i'); ?>
                    <?php } ?>
                        </td>
                        <td>
                    <?php if (isset($item->creato)) { ?>
			     <?php echo ReceivementsFrontendHelper::convertDateFrom($item->creato,'d F Y, H:i'); ?>
                    <?php } ?>
                        </td>
                        <td>
                    <?php if (isset($item->sede)) { ?>
			     <?php echo $this->escape($item->sede); ?>
                    <?php } ?>
                        </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
