<?php
/**
 * @version     1.0.5
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
class ReceivementsModelAgenda extends JModelLegacy
{
        protected $data = null;

        public function getData()
        {
		if ($this->data === null) {
                        $app	= JFactory::getApplication();
                        $agenda_old = $app->getUserState('com_receivements.agenda.old');
			$this->data	= new stdClass();
                        $db = JFactory::getDBO();
                        $query = $db->getQuery(true);
                        $query->select('id,una_tantum,giorno,inizio,max_app');
                        $query->from('#__receivements_ore');
                        $uid = JFactory::getUser()->get('id');
                        $query->where('id_docente = ' . $db->Quote($uid));
                        $query->order('una_tantum ASC');
                        $db->setQuery($query);
                        $this->data->ore = $db->loadAssocList();
/*
                        if (empty($this->data->ore)) {
                                $this->data = null;
                                return false;
                        }
*/
                        foreach($this->data->ore as &$ore) {
                                if ($ore['una_tantum']) {
                                        $query = $db->getQuery(true);
                                        $query->select('titolo');
                                        $query->from('#__receivements_generali');
                                        $uid = $ore['una_tantum'];
                                        $query->where('id = ' . $db->Quote($uid));
                                        if ($agenda_old)
                                                $query->where('data < NOW()');
                                        else
                                                $query->where('data >= NOW()');
                                        $db->setQuery($query);
                                        $res = $db->loadAssoc();
                                        if (empty($res)) {
                                                $ore = array();
                                                continue;
                                        }
                                        $ore['title'] = $res['titolo'];
                                } else {
                                        $ore['title'] = JText::_('COM_RECEIVEMENTS_WEEKLY');
                                }
                                $query = $db->getQuery(true);
                                $query->select('id,totale_ric,data');
                                $query->from('#__receivements_agenda');
                                $oid = $ore['id'];
                                $query->where('id_ore = ' . $db->Quote($oid));
                                if ($agenda_old)
                                        $query->where('data < NOW()');
                                else
                                        $query->where('data >= NOW()');
                                $query->where('totale_ric > 0');
                                $query->order('data ASC');
                                $db->setQuery($query);
                                $ore['agenda'] = $db->loadAssocList();
                                /*
                                if (empty($ore['agenda'])) {
                                        //$this->data = null;
                                        return $this->data;
                                }
                                */
                                foreach($ore['agenda'] as $i => &$day) {
                                        $query = $db->getQuery(true);
                                        $query->select('p.id,id_agenda,parentela,email,nome,c.classe,nota');
                                        $query->from('#__receivements_prenotazioni AS p');
                                        $query->join('LEFT', '#__receivements_classi AS c ON (p.id_classe = c.id)');
                                        $query->where('id_agenda = ' . $db->Quote($day['id']));
                                        $db->setQuery($query);
                                        $day['nested'] = $db->loadAssocList();
                                }
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
        public function updateNote($id, $note) {
                if (JFactory::getUser()->authorise('core.edit', 'com_receivements') !== true) {
                        JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
                        return false;
                }
                $db = JFactory::getDBO();
                $query = 'UPDATE #__receivements_prenotazioni SET nota = '.$db->Quote($note).' WHERE id = '.$db->Quote($id);
                $db->setQuery($query);
                return $db->execute();
        }
}