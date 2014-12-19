<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
jimport( 'joomla.application.component.modellist' );
class ReceivementsModelRicevimenti extends JModelList
{
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'classi', 'a.classi',
                'giorno', 'a.giorno',
                'sede', 'a.sede',
            );
        }
        parent::__construct($config);
    }
    protected function populateState($ordering = null, $direction = null)
    {
        // Initialise variables.
        $app = JFactory::getApplication();

        // List state information
        $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'));
        $this->setState('list.limit', $limit);

        $limitstart = $app->input->getInt('limitstart', 0);
        $this->setState('list.start', $limitstart);
        
	// Load the filter "day".
	$search = $this->getUserStateFromRequest($this->context.'.filter.giorno', 'filter_day');
	$this->setState('filter.giorno', $search);
        
	// Load the filter "class".
	$search = $this->getUserStateFromRequest($this->context.'.filter.classe', 'filter_class');
	$this->setState('filter.classe', $search);
        
	// Load the filter "class".
	$search = $this->getUserStateFromRequest($this->context.'.filter.sede', 'filter_site');
	$this->setState('filter.sede', $search);
    }

    function getListQuery()
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('o.id,u.name,c.materie,s.sede,classi,giorno,inizio');
        $query->from('#__receivements_ore AS o');
        $query->join('LEFT', $db->quoteName('#__users', 'u') . ' ON (' . $db->quoteName('id_docente') . ' = ' . $db->quoteName('u.id') . ')');
        $query->join('LEFT', $db->quoteName('#__receivements_cattedre', 'c') . ' ON (' . $db->quoteName('cattedra') . ' = ' . $db->quoteName('c.id') . ')');
        $query->join('LEFT', $db->quoteName('#__receivements_sedi', 's') . ' ON (' . $db->quoteName('o.sede') . ' = ' . $db->quoteName('s.id') . ')');
        $query->where('o.attiva <> ' . $db->Quote('0'));
        
        // Filter by search in title
        $search = $this->getState('filter.giorno');
        if ((!empty($search) || $search === '0') && $search != -1 && $search != '*') {
                $query->where(' o.giorno = ' . $db->Quote($search));
        }
        // Filter by search in title
        $search = $this->getState('filter.classe');
        if (!empty($search) && $search != '0' && $search != '*') {
                $query->where(' FIND_IN_SET(' . $db->Quote($search) . ', ' . $db->quoteName('o.classi') . ') > 0 ');
        }
        // Filter by search in title
        $search = $this->getState('filter.sede');
        if (!empty($search) && $search != '0' && $search != '*') {
                $query->where(' o.sede = ' . $db->Quote($search));
        }

        return $query;
    }
}