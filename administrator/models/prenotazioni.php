<?php

/**
 * @version     1.0.5
 * @package     com_receivements
 * @copyright   Copyright (C) 2014. Tutti i diritti riservati.
 * @license     GNU General Public License versione 2 o successiva; vedi LICENSE.txt
 * @author      Paolo Bozzo - pagolo.bozzo AT gmail.com - http://dbfweb.com
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Receivements records.
 */
class ReceivementsModelPrenotazioni extends JModelList {

    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     * @see        JController
     * @since    1.6
     */
    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id', 'p.id',
                'docente','u.name',
                'studente','p.nome',
                'genitore','u2.name',
                'data','a.data',
                'creato','p.creato',
                'sede','s.sede',
            );
        }

        parent::__construct($config);
    }
	public function getTable($type = 'Prenotazione', $prefix = 'ReceivementsTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     */
    protected function populateState($ordering = null, $direction = null) {
        // Initialise variables.
        $app = JFactory::getApplication('administrator');

        // Load the filter state.
        $from = $app->getUserStateFromRequest($this->context . '.filter.from', 'filter_from');
        $this->setState('filter.from', $from);
        $to = $app->getUserStateFromRequest($this->context . '.filter.to', 'filter_to');
        $this->setState('filter.to', $to);

        // Load the parameters.
        $params = JComponentHelper::getParams('com_receivements');
        $this->setState('params', $params);

        // List state information.
        parent::populateState('p.id', 'asc');
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return	JDatabaseQuery
     * @since	1.6
     */
    protected function getListQuery() {
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query->select('p.id,u.name AS docente,p.nome AS studente,c.classe,u2.name AS genitore,p.email,a.data,p.creato,s.sede');
        $query->from('`#__receivements_prenotazioni` AS p');
        $query->join('LEFT', '#__receivements_classi c ON (p.id_classe = c.id)');
        $query->join('LEFT', '#__receivements_agenda a ON (p.id_agenda = a.id)');
        $query->join('LEFT', '#__receivements_ore o ON (o.id = a.id_ore)');
        $query->join('LEFT', '#__users u ON ( o.id_docente = u.id )');
        $query->join('LEFT', '#__users u2 ON ( p.utente = u2.id )');
        $query->join('LEFT', '#__receivements_sedi s ON (o.sede = s.id)');

        $from = $this->getState('filter.from');
        if ($from === null) $from = JText::_('COM_RECEIVEMENTS_TODAY');
        if (!empty($from)) {
            if ($from == JText::_('COM_RECEIVEMENTS_TODAY')) {
                $query->where('DATE(a.data) >= DATE(NOW())');
            } else {
                // TODO: check $from format
                $myDate = JFactory::getDate($from);
                $format = JText::_('Y-m-d');
                $outdate = JHTML::_('date', $myDate, $format);
                $query->where('DATE(a.data) >= '.$db->Quote($outdate));
            }
        }

        $to = $this->getState('filter.to');
        if (!empty($to)) {
                // TODO: check $to format
                $myDate = JFactory::getDate($to);
                $format = JText::_('Y-m-d');
                $outdate = JHTML::_('date', $myDate, $format);
                $query->where('DATE(a.data) <= '.$db->Quote($outdate));
        }
        
        // Add the list ordering clause.
        $orderCol = $this->state->get('list.ordering');
        $orderDirn = $this->state->get('list.direction');
        if ($orderCol && $orderDirn) {
            $query->order($db->escape($orderCol . ' ' . $orderDirn));
        }

        return $query;
    }

    public function getItems() {
        $items = parent::getItems();
        
        return $items;
    }

}

