<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
jimport( 'joomla.application.component.modellist' );
class ReceivementsModelRicevimenti extends JModelList
{
    function getListQuery()
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('o.id,u.name,c.materie,classi,giorno,inizio');
        $query->from('#__receivements_ore AS o');
        $query->join('LEFT', $db->quoteName('#__users', 'u') . ' ON (' . $db->quoteName('id_docente') . ' = ' . $db->quoteName('u.id') . ')');
        $query->join('LEFT', $db->quoteName('#__receivements_cattedre', 'c') . ' ON (' . $db->quoteName('cattedra') . ' = ' . $db->quoteName('c.id') . ')');
        $query->where('o.attiva <> ' . $db->Quote('0'));
        return $query;
    }
}