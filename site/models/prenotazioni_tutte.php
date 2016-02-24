<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
jimport( 'joomla.application.component.modellist' );
class ReceivementsModelPrenotazioni_Tutte extends JModelList
{
    protected function populateState($ordering = null, $direction = null)
    {
        // Initialise variables.
        $app = JFactory::getApplication();
        
        // List state information
        $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'));
        $this->setState('list.limit', $limit);

        $limitstart = $app->getUserStateFromRequest('com_receivements.list.start', 'start', 0);
        $this->setState('list.start', $limitstart);
        
	// Load the filter "from".
	$search = $this->getUserStateFromRequest($this->context.'.filter.from', 'filter_from');
	$this->setState('filter.from', $search);
        
	// Load the filter "to".
	$search = $this->getUserStateFromRequest($this->context.'.filter.to', 'filter_to');
	$this->setState('filter.to', $search);
        
	// Load the filter "teacher".
	$teacher = $this->getUserStateFromRequest($this->context.'.filter.teacher', 'filter_teachers');
	$this->setState('filter.teacher', $teacher);
        
	// Load the filter "class".
	$class = $this->getUserStateFromRequest($this->context.'.filter.class', 'filter_classes');
	$this->setState('filter.class', $class);
        
    }
    function getListQuery()
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query->select('p.id,u.name AS docente,p.nome AS studente,c.classe,u2.name AS genitore,a.data,p.creato,s.sede');
        $query->from('`#__receivements_prenotazioni` AS p');
        $query->join('LEFT', '#__receivements_classi c ON (p.id_classe = c.id)');
        $query->join('LEFT', '#__receivements_agenda a ON (p.id_agenda = a.id)');
        $query->join('LEFT', '#__receivements_ore o ON (o.id = a.id_ore)');
        $query->join('LEFT', '#__users u ON ( o.id_docente = u.id )');
        $query->join('LEFT', '#__users u2 ON ( p.utente = u2.id )');
        $query->join('LEFT', '#__receivements_sedi s ON (o.sede = s.id)');

        $from = $this->getState('filter.from');
            if ($from == null) {
                $query->where('DATE(a.data) >= DATE(NOW())');
            } else {
                // TODO: check $from format
                $myDate = JFactory::getDate($from);
                $format = JText::_('Y-m-d');
                $outdate = JHTML::_('date', $myDate, $format);
                $query->where('DATE(a.data) >= '.$db->Quote($outdate));
            }

        $to = $this->getState('filter.to');
        if (!empty($to)) {
                // TODO: check $to format
                $myDate = JFactory::getDate($to);
                $format = JText::_('Y-m-d');
                $outdate = JHTML::_('date', $myDate, $format);
                $query->where('DATE(a.data) <= '.$db->Quote($outdate));
        }
        
        $teacher = $this->getState('filter.teacher');
        if (!empty($teacher) && $teacher != '*') {
                $query->where('o.id_docente = '.$db->Quote($teacher));
        }
        
        $class = $this->getState('filter.class');
        if (!empty($class) && $class != '*') {
                $query->where('p.id_classe = '.$db->Quote($class));
        }
        
        return $query;
    }
}