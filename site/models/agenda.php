<?php
/**
 * @version     0.0.1
 * @package     com_receivements
 * @copyright   Copyright (C) 2014. Tutti i diritti riservati.
 * @license     GNU General Public License versione 2 o successiva; vedi LICENSE.txt
 * @author      Paolo Bozzo <info@dbfweb.com> - http://dbfweb.com
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modelform');

/**
 * Receivements model.
 */
class ReceivementsModelAgenda extends JModel
{
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
                                $query->select('p.id,parentela,email,nome,c.classe');
                                $query->from('#__receivements_prenotazioni AS p');
                                $query->join('LEFT', '#__receivements_classi AS c ON (p.id_classe = c.id)');
                                $query->where('id_agenda = ' . $db->Quote($day['id']));
                                $db->setQuery($query);
                                $this->data->agenda[$i]['nested'] = $db->loadAssocList();
                        }
		}
                return $this->data;
        }    
}