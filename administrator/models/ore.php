<?php

/**
 * @version     0.5.0
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
class ReceivementsModelOre extends JModelList {

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
                'id', 'a.id',
                'name', 'u.name',
                'id_docente', 'a.id_docente',
                'classi', 'a.classi',
                'giorno', 'a.giorno',
                'inizio', 'a.inizio',
                'fine', 'a.fine',
                'max_app', 'a.max_app',
                'sede', 's.sede',
                'email', 'a.email',
                'attiva', 'a.attiva',

            );
        }

        parent::__construct($config);
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
        $search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $published = $app->getUserStateFromRequest($this->context . '.filter.state', 'filter_published', '', 'string');
        $this->setState('filter.state', $published);

        

        // Load the parameters.
        $params = JComponentHelper::getParams('com_receivements');
        $this->setState('params', $params);

        // List state information.
        parent::populateState('a.id,u.name', 'asc');
    }

    /**
     * Method to get a store id based on model configuration state.
     *
     * This is necessary because the model is used by the component and
     * different modules that might need different sets of data or different
     * ordering requirements.
     *
     * @param	string		$id	A prefix for the store id.
     * @return	string		A store id.
     * @since	1.6
     */
    protected function getStoreId($id = '') {
        // Compile the store id.
        $id.= ':' . $this->getState('filter.search');
        $id.= ':' . $this->getState('filter.state');

        return parent::getStoreId($id);
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
        $query->select('a.*,u.name,s.sede,c.materie');
        $query->from('`#__receivements_ore` AS a');
        $query->join('LEFT', $db->quoteName('#__receivements_cattedre', 'c') . ' ON (' . $db->quoteName('a.cattedra') . ' = ' . $db->quoteName('c.id') . ')');
        $query->join('LEFT', $db->quoteName('#__users', 'u') . ' ON (' . $db->quoteName('a.id_docente') . ' = ' . $db->quoteName('u.id') . ')');        
        $query->join('LEFT', $db->quoteName('#__receivements_sedi', 's') . ' ON (' . $db->quoteName('a.sede') . ' = ' . $db->quoteName('s.id') . ')');        
        

        // Filter by search in title
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('a.id = ' . (int) substr($search, 3));
            } else {
                $search = $db->Quote('%' . $db->escape($search, true) . '%');
                $conditions = array( 
                        $db->quoteName('u.name') . ' LIKE ' . $search,  
                        $db->quoteName('a.classi') . ' LIKE ' . $search,
                        $db->quoteName('s.sede') . ' LIKE ' . $search,
                        $db->quoteName('c.materie') . ' LIKE ' . $search,
                        // TODO add weekday and dates
                ); 
                $query->where($conditions, 'OR'); 
            }
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
