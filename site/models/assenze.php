<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
jimport( 'joomla.application.component.modellist' );
class ReceivementsModelAssenze extends JModelList
{
    function getListQuery()
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('a.id,a.inizio,a.fine,a.descrizione');
        $query->from('#__receivements_calendario AS a');
        $user = JFactory::getUser();
        $userId = $user->get('id');
        $query->where('a.utente = ' . $db->Quote($userId));
        
        return $query;
    }
    protected function populateState($ordering = null, $direction = null)
    {
        $this->setState('list.limit', 0);
    }
}