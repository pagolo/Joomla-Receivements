<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
jimport( 'joomla.application.component.modellist' );
class ReceivementsModelClassi extends JModelList
{
    function getListQuery()
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('id,classe');
        $query->from('#__receivements_classi');
        $query->order('classe');
        return $query;
    }
    public function getItems() {
        $limit = $this->getState('list.limit');
        $this->setState('list.limit', 0);
        $return = parent::getItems();
        $this->setState('list.limit', $limit);
        return $return;
    }
}