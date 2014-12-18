<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
jimport( 'joomla.application.component.modellist' );
class ReceivementsModelRicevimenti extends JModelList
{
    protected function populateState($ordering = null, $direction = null)
    {
        // Initialise variables.
        $app = JFactory::getApplication();

        // List state information
        $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'));
        $this->setState('list.limit', $limit);

        $limitstart = $app->input->getInt('limitstart', 0);
        $this->setState('list.start', $limitstart);
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
        //echo $query;
        return $query;
    }
}