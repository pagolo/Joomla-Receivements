<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
jimport( 'joomla.application.component.modellist' );
class ReceivementsModelPrenotazioni extends JModelList
{
    function getListQuery()
    {
        $app = JFactory::getApplication();
        $all =  !ReceivementsFrontendHelper::getForcedLogin();
        if ($all) {
                $cookie = $app->input->cookie;
                $temp = (array)unserialize(base64_decode($cookie->get('receivements_cookie')));
                if (empty($temp) || !isset($temp['email'])) {
                        //$app->enqueueMessage(JText::_('COM_RECEIVEMENTS_COOKIE_EXPIRED'), 'warning');
                        return false;
                }
        }
        $user = JFactory::getUser();
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('a.*,b.data,d.name');
        $query->from('#__receivements_prenotazioni a');
        $query->join('LEFT', '#__receivements_agenda b ON (a.id_agenda = b.id)');
        $query->join('LEFT', '#__receivements_ore c ON (b.id_ore = c.id)');
        $query->join('LEFT', '#__users d ON (c.id_docente = d.id)');
        $query->where('a.utente = ' . $db->Quote($user->id));
        if ($all) $query->where('a.email = ' . $db->Quote($temp['email']));
        $query->where('b.data > NOW()');
        $query->order('b.data ASC');
        
        return $query;
    }
}