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
        $query->select('id_docente,u.name,classi,giorno');
        $query->from('#__receivements_ore');
        $query->join('LEFT', $db->quoteName('#__users', 'u') . ' ON (' . $db->quoteName('id_docente') . ' = ' . $db->quoteName('u.id') . ')');
        return $query;
    }
}