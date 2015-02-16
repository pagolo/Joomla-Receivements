<?php
/**
 * @version     0.5.0
 * @package     com_receivements
 * @copyright   Copyright (C) 2014. Tutti i diritti riservati.
 * @license     GNU General Public License versione 2 o successiva; vedi LICENSE.txt
 * @author      Paolo Bozzo - pagolo.bozzo AT gmail.com - http://dbfweb.com
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modelform');

/**
 * Receivements model.
 */
class ReceivementsModelAgenda extends JModel
{
        protected $data = null;

        public function getData()
        {
		if ($this->data === null) {
			$this->data	= new stdClass();
                        $db = JFactory::getDBO();
                        $query = $db->getQuery(true);
                        $query->select('id,giorno,inizio,max_app');
                        $query->from('#__receivements_ore');
                        $uid = JFactory::getUser()->get('id');
                        $query->where('id_docente = ' . $db->Quote($uid));
                        $db->setQuery($query);
                        $this->data->ore = $db->loadAssoc();
                        if (empty($this->data->ore)) {
                                $this->data = null;
                                return false;
                        }
                        $query = $db->getQuery(true);
                        $query->select('id,totale_ric,data');
                        $query->from('#__receivements_agenda');
                        $oid = $this->data->ore['id'];
                        $query->where('id_ore = ' . $db->Quote($oid));
                        $query->where('data > NOW()');
                        $db->setQuery($query);
                        $this->data->agenda = $db->loadAssocList();
                        if (empty($this->data->agenda)) {
                                //$this->data = null;
                                return $this->data;
                        }
                        foreach($this->data->agenda as $i => $day) {
                                $query = $db->getQuery(true);
                                $query->select('p.id,id_agenda,parentela,email,nome,c.classe');
                                $query->from('#__receivements_prenotazioni AS p');
                                $query->join('LEFT', '#__receivements_classi AS c ON (p.id_classe = c.id)');
                                $query->where('id_agenda = ' . $db->Quote($day['id']));
                                $db->setQuery($query);
                                $this->data->agenda[$i]['nested'] = $db->loadAssocList();
                        }
		}
                return $this->data;
        }
        
        public function getEmailData($id)
        {
                $db = JFactory::getDBO();
                $query = 'SELECT p.nome AS student, p.email, a.data FROM #__receivements_prenotazioni p LEFT JOIN #__receivements_agenda a ON (p.id_agenda = a.id) WHERE p.id = '.$db->Quote($id);
                $db->setQuery($query);
                $data = $db->loadAssoc();
                $data['name'] = JFactory::getUser()->get('name');
                return $data;
        }

        public function deleteBooking($id, $id_agenda) {
                if (JFactory::getUser()->authorise('core.delete', 'com_receivements') !== true) {
                        JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
                        return false;
                }
                $db = JFactory::getDBO();
                $app = JFactory::getApplication();
                $query = 'DELETE FROM #__receivements_prenotazioni WHERE id = '.$db->Quote($id);
                $db->setQuery($query);
                $result = $db->execute();
                if ($result) { // if deleted ok then decrement counter
                        $app->enqueueMessage(JText::_('COM_RECEIVEMENTS_BOOKING_DELETED'), 'message');
                        $db->setQuery('SELECT COUNT(*) FROM #__receivements_prenotazioni WHERE id_agenda = '.$db->Quote($id_agenda));
                        $totale_ric = $db->loadResult();
                        $db->setQuery('UPDATE #__receivements_agenda SET totale_ric = '.$db->Quote($totale_ric).' WHERE id = '.$db->Quote($id_agenda));
                        $result = $db->execute();
                }
                return $result;
        }    
}