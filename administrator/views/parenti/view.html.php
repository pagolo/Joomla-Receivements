<?php

/**
 * @version     0.5.0
 * @package     com_receivements
 * @copyright   Copyright (C) 2014. Tutti i diritti riservati.
 * @license     GNU General Public License versione 2 o successiva; vedi LICENSE.txt
 * @author      Paolo Bozzo - pagolo.bozzo AT gmail.com - http://dbfweb.com
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of Receivements.
 */
class ReceivementsViewParenti extends JViewLegacy {

    protected $items;
    protected $pagination;
    protected $state;

    /**
     * Display the view
     */
    public function display($tpl = null) {
        $this->state = $this->get('State');
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors));
        }

        $this->addToolbar();

        $input = JFactory::getApplication()->input;
        $view = $input->getCmd('view', 'parenti');
        ReceivementsHelper::addSubmenu($view);

        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @since	1.6
     */
    protected function addToolbar() {
        require_once JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'receivements.php';

        $state = $this->get('State');
        $canDo = ReceivementsHelper::getActions($state->get('filter.category_id'));

        JToolBarHelper::title(JText::_('COM_RECEIVEMENTS_TITLE_PARENTI'), 'parents.png');

        if (!ReceivementsFrontendHelper::getForcedLogin()) {
                if ($canDo->get('core.admin')) {
                    JToolBarHelper::preferences('com_receivements');
                }
                return;
        }
        //Check if the form exists before showing the add/edit buttons
        $formPath = JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'parente';
        if (file_exists($formPath)) {

            if ($canDo->get('core.create')) {
                JToolBarHelper::addNew('parente.add', 'JTOOLBAR_NEW');
            }

            if ($canDo->get('core.edit') && isset($this->items[0])) {
                JToolBarHelper::editList('parente.edit', 'JTOOLBAR_EDIT');
            }
        }

        JToolBarHelper::divider();
//        JToolBarHelper::custom( 'ore.importadocenti', 'import-export.png', 'import-export.png', JText::_('COM_RECEIVEMENTS_IMPORT'), false, false );
        $import =
        '<a class="toolbar btn btn-small button modal" rel="{handler: \'iframe\', size: {x: 600, y: 320}, onClose: function(){window.parent.document.location.reload(true)}}" '.
        ' href="index.php?option=com_receivements&amp;tmpl=component&amp;view=importaparenti">'.
        '<span title="'.JText::_('COM_RECEIVEMENTS_PARENTS_IMPORT').'" class="icon-32-import-export"></span>'.JText::_('COM_RECEIVEMENTS_IMPORT').'</a>';      
        $bar =& JToolBar::getInstance();
        $bar->appendButton('Custom', $import);
        
        if ($canDo->get('core.edit.state')) {

            if (isset($this->items[0])) {
                //If this component does not use state then show a direct delete button as we can not trash
                JToolBarHelper::divider();
                JToolBarHelper::deleteList('', 'parenti.delete', 'JTOOLBAR_DELETE');
                JToolBarHelper::divider();
            }

        }

        if ($canDo->get('core.admin')) {
            JToolBarHelper::preferences('com_receivements');
        }
    }

}
