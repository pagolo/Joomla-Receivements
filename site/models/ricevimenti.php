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
                'una_tantum', 'a.una_tantum',
            );
        }
        parent::__construct($config);
    }
    protected function populateState($ordering = null, $direction = null)
    {
        // Initialise variables.
        $app = JFactory::getApplication();
        $default_class = false;
        
        // if fordedlogin then get/set data
        if (ReceivementsFrontendHelper::getForcedLogin() && ReceivementsFrontendHelper::canBook()) {
                // get data from db
                $user = JFactory::getUser();
                $db = $this->getDBO();
                $db->setQuery('SELECT p.studente AS nome, c.classe AS classe, p.parentela AS parentela FROM #__receivements_parenti p LEFT JOIN #__receivements_classi c ON (p.id_classe = c.id) WHERE p.utente = '.$db->Quote($user->get('id')));
                $temp = $db->loadAssoc();
                // put data on session
                if (!(empty($temp))) {
                        $default_class = $temp['classe'];
                }
                $temp['email'] = $user->get('email');
                $app->setUserState('com_receivements.booking.data', $temp);
        }
        
        // List state information
        $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'));
        $this->setState('list.limit', $limit);

        $start = JRequest::getVar('start', 0, '', 'int');
        if ($start == 0) $start = $app->getUserStateFromRequest('com_receivements.list.start', 'start', 0);
        $this->setState('list.start', $start);
        
        $schoolgroup = ReceivementsFrontendHelper::getSchoolsGroup();
        if (!(empty($schoolgroup))) {
		// Load the filter "school".
		$search = $this->getUserStateFromRequest($this->context.'.filter.scuola', 'filter_school');
		$this->setState('filter.scuola', $search);
        }
        
	// Load the filter "day".
	$search = $this->getUserStateFromRequest($this->context.'.filter.giorno', 'filter_day');
	$this->setState('filter.giorno', $search);
        
	// Load the filter "class".
	$search = $this->getUserStateFromRequest($this->context.'.filter.classe', 'filter_class');
	if (!$search && $default_class) $search = $default_class;
	$this->setState('filter.classe', $search);
        
	// Load the filter "site".
	$search = $this->getUserStateFromRequest($this->context.'.filter.sede', 'filter_site');
	$this->setState('filter.sede', $search);
        
	// Load the filter "type".
	$search = $this->getUserStateFromRequest($this->context.'.filter.type', 'filter_type');
        if (empty($search)) $search = 0;
	$this->setState('filter.type', $search);
        if ($search > 0) {
                $db = $this->getDBO();
                $booking_date = ReceivementsFrontendHelper::getBookingDate($search);
                $app->setUserState('com_receivements.booking.date', $booking_date);
        }
    }

    function getListQuery()
    {
        $db = JFactory::getDBO();
        $app = JFactory::getApplication();
        $booking_date = $app->getUserState('com_receivements.booking.date', null);
        if (empty($booking_date)) { // refresh value
                $search = $this->getState('filter.type');
                $booking_date = ReceivementsFrontendHelper::getBookingDate($search);
                $app->setUserState('com_receivements.booking.date', $booking_date);
        }
        
        $schoolgroup = ReceivementsFrontendHelper::getSchoolsGroup();
        $search = $this->getState('filter.scuola');
        $query = $db->getQuery(true);
        $query->select('DISTINCT o.id,u.name,c.materie,s.sede,una_tantum,classi,giorno,inizio');
        $query->from('#__receivements_ore AS o');
	$query->join('LEFT', $db->quoteName('#__users', 'u') . ' ON (' . $db->quoteName('id_docente') . ' = ' . $db->quoteName('u.id') . ')');
        if (!(empty($schoolgroup)) && $search != '*' && $search != '0' && $search != false) {
		$query->join('LEFT', $db->quoteName('#__user_usergroup_map', 'g') . ' ON (' . $db->quoteName('id_docente') . ' = ' . $db->quoteName('g.user_id') . ')');
	}
        $query->join('LEFT', $db->quoteName('#__receivements_cattedre', 'c') . ' ON (' . $db->quoteName('cattedra') . ' = ' . $db->quoteName('c.id') . ')');
        $query->join('LEFT', $db->quoteName('#__receivements_sedi', 's') . ' ON (' . $db->quoteName('o.sede') . ' = ' . $db->quoteName('s.id') . ')');
//        $query->join('LEFT', $db->quoteName('#__receivements_generali', 't') . ' ON (' . $db->quoteName('una_tantum') . ' = ' . $db->quoteName('t.id') . ')');
        $query->where('o.attiva <> ' . $db->Quote('0'));
        $query->order('u.name');

        // Filter by search in school
        if (!(empty($schoolgroup)) && $search != '*' && $search != '0' && $search != false) {
		$query->where(' g.group_id = ' . $db->Quote($search));
        }

        // Filter by search in day
        $search = $this->getState('filter.giorno');
        if ((!empty($search) || $search === '0') && $search != -1 && $search != '*') {
                $query->where(' o.giorno = ' . $db->Quote($search));
        }
        // Filter by search in class
        $search = $this->getState('filter.classe');
        if (!empty($search) && $search != '0' && $search != '*') {
                $query->where(' FIND_IN_SET(' . $db->Quote($search) . ', ' . $db->quoteName('o.classi') . ') > 0 ');
        }
        // Filter by search in site
        $search = $this->getState('filter.sede');
        if (!empty($search) && $search != '0' && $search != '*') {
                $query->where(' o.sede = ' . $db->Quote($search));
        }
        // Filter by search in type
        $search = $this->getState('filter.type');
        $query->where(' o.una_tantum = ' . $db->Quote($search));

        $query->where('(o.una_tantum = 0 OR CURDATE() + INTERVAL '.ReceivementsFrontendHelper::getPreBooking().' DAY < DATE('.$db->Quote(ReceivementsFrontendHelper::convertDateTo($booking_date)).'))');
        
        //echo $query;exit;

        return $query;
    }
}