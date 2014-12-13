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
        return $query;
    }
}