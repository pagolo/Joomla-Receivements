<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
jimport( 'joomla.application.component.modellist' );
class ReceivementsModelCalendario extends JModelList
{
    /*
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'inizio', 'a.inizio',
                'fine', 'a.fine',
                'descrizione', 'a.descrizione',
            );
        }
        parent::__construct($config);
    }
    */
    function getListQuery()
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('a.id,a.inizio,a.fine,a.descrizione');
        $query->from('#__receivements_calendario AS a');
        $query->where('a.festivo <> ' . $db->Quote('0'));
        $query->where('a.utente = ' . $db->Quote('0'));
        
        return $query;
    }
    protected function populateState($ordering = null, $direction = null)
    {
        $this->setState('list.limit', 0);
    }
}