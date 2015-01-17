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
jimport('joomla.event.dispatcher');

require_once JPATH_COMPONENT . '/helpers/receivements.php';

/**
 * Receivements model.
 */
class ReceivementsModelPrenota extends JModelForm
{
    
    var $_item = null;
    
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since	1.6
	 */
	protected function populateState()
	{
		$app = JFactory::getApplication('com_receivements');
		// Load state from the request userState on edit or from the passed variable on default
                $id = $app->input->get('id', null, 'raw');
                //$re = '^(' . str_replace('.', '|', $id) . ')$';
                $app->setUserState('com_receivements.init.prenota.id', $id);
		$this->setState('prenota.id', $id);
        }
        

	/**
	 * Method to get the profile form.
	 *
	 * The base form is loaded from XML 
     * 
	 * @param	array	$data		An optional array of data for the form to interogate.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	JForm	A JForm object on success, false on failure
	 * @since	1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$xml = ReceivementsFrontendHelper::getForcedLogin() ? 'prenota-loggato' :  'prenota';
		$form = $this->loadForm('com_receivements.prenota', $xml, array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		return $form;
	}
	public function getData()
	{
		if ($this->data === null) {

			$this->data	= new stdClass();
			$app	= JFactory::getApplication();

			// Override the base user data with any data in the session.
			$temp = (array)$app->getUserState('com_receivements.booking.data', array());
			// TODO: qui prelevare i dati dal database se la prenotazione è con utente loggato...
			if (empty($temp)) {
//			if (empty($temp) || (!isset($temp['ricevimenti_count'])) || $temp['ricevimenti_count'] == 0) {
                                $cookie = $app->input->cookie;
                                $temp = (array)unserialize(base64_decode($cookie->get('receivements_cookie')));
                        }
			foreach ($temp as $k => $v) {
				$this->data->$k = $v;
			}
		}
		return $this->data;
	}
	protected function loadFormData()
	{
		return $this->getData();
	}
	protected function myTest($data)
	{
                $db	= $this->getDbo();
                foreach ($data['ricevimenti'] as $i => $dt) {
                        $query = 'SELECT COUNT(*) FROM #__receivements_prenotazioni p LEFT JOIN #__receivements_agenda a ON (p.id_agenda = a.id)  LEFT JOIN #__receivements_ore o ON (a.id_ore = o.id) WHERE (o.id = '.$dt['ora_id'].') AND (a.data = '.$db->Quote($dt['datetime']).') AND (p.nome = '.$db->Quote($data['nome']).') AND (p.email = '.$db->Quote($data['email']).')';
                        $db->setQuery($query);
                        $result = $db->loadResult();
                        if ($result > 0) {
                                $this->setError(JText::sprintf('COM_RECEIVEMENTS_ALREADY_ONE_BOOKING',ReceivementsFrontendHelper::convertDateFrom($dt['datetime'], 'd/m/Y H:i')));
                                return false;
                        }
                }
                return true;
        }
	public function validate($form, $data)
	{
	       $ric = array();
	       for ($i = 0; $i < $data['ricevimenti_count']; $i++) {
	               $ric[] = array(
	               'ora_id' => $data['ricevimenti_ora_'.$i],
                       'datetime' => $data['ricevimenti_'.$i],
                       'teacher_id' => $data['ricevimenti_user_'.$i],
                       'teacher_name' => $data['ricevimenti_name_'.$i],
                       'teacher_email' => $data['ricevimenti_email_'.$i]
                       ); 
               }
               $data['ricevimenti'] = $ric;
	       $return = parent::validate($form, $data);
	       $mytest = $this->myTest($data);
	       if ($mytest === false) return false;
	       return $return;
        }
        private function ExistAgendaRecord($db, $dt, &$totale_ric, &$id)
        {
                //$qdate = $db->Quote(substr($dt['datetime'],0,10));// get only date no time
                $qdate = $db->Quote($dt['datetime']);// get full date
                $db->setQuery('SELECT * FROM #__receivements_agenda WHERE id_ore = '.$db->Quote($dt['ora_id']).' AND data = '.$qdate);
                $results = $db->loadObjectList();
                $exists = (count($results) > 0);
                if ($exists) {
                        $id = $results[0]->id;
                        $db->setQuery('SELECT COUNT(*) FROM #__receivements_prenotazioni WHERE id_agenda = '.$db->Quote($id));
                        $totale_ric = $db->loadResult();
                }
                return $exists;
        }
        private function UpdateAgendaRecord($db, $dt, $totale_ric, $id)
        {
                $db->setQuery('UPDATE #__receivements_agenda SET totale_ric = '.$db->Quote($totale_ric).' WHERE id = '.$db->Quote($id));
                $result = $db->execute();
                return $result;
       }
        private function CreateAgendaRecord($db, $dt, $totale_ric)
        {
                $agenda_id = null;
                $ore = $db->Quote($dt['ora_id']);
                $tot = $db->Quote($totale_ric);
                //$qdate = $db->Quote(substr($dt['datetime'],0,10));// get only date no time
                $qdate = $db->Quote($dt['datetime']);// get full date
                $db->setQuery('INSERT INTO #__receivements_agenda (id_ore, totale_ric, data) VALUES ('.$ore.', '.$tot.', '.$qdate.')');
                $result = $db->execute();
                $this->ExistAgendaRecord($db, $dt, $totale_ric, $agenda_id);
                return $agenda_id;
        }
        private function CreateBookingRecord($db, $dt, $agenda_id, &$data, $i)
        {
                $dt['guid'] = $data['ricevimenti'][$i]['guid'] = ReceivementsFrontendHelper::getGUID();
                $guid = $db->Quote($dt['guid']);
                //$guid_expire = JFactory::getDate('+1 days');
                $id_agenda = $db->Quote($agenda_id);
                $classe = $db->Quote($data['classe']);
                $db->setQuery('SELECT id FROM #__receivements_classi WHERE classe = '.$classe);
                $id_classe = $db->Quote($db->loadResult());
                if ($data['parentela'] === '*') $data['parentela'] = 'COM_RECEIVEMENTS_PARENT';
                $parentela = $db->Quote($data['parentela']);
                $email = $db->Quote($data['email']);
                $nome = $db->Quote($data['nome']);
                $user = JFactory::getUser();
                $utente = $db->Quote($user->get('id'));
                $db->setQuery('INSERT INTO #__receivements_prenotazioni (guid, id_agenda, id_classe, parentela, email, nome, utente) VALUES ('.$guid.', '.$id_agenda.', '.$id_classe.', '.$parentela.', '.$email.', '.$nome.', '.$utente.')');
                $result = $db->execute();
                //$db->setQuery('SELECT LAST_INSERT_ID()');
                //$data['ricevimenti'][$i]['insert_id'] = $db->loadResult();
                return $result;
        }
        public function SaveData(&$data)
        {
            $db		= $this->getDbo();
            
            foreach ($data['ricevimenti'] as $i => $dt)
            {
                $totale_ric = 0;
                $agenda_id = null;
                if ($this->ExistAgendaRecord($db, $dt, $totale_ric, $agenda_id)) {
                    $this->UpdateAgendaRecord($db, $dt, ++$totale_ric, $agenda_id);
                } else {
                    $agenda_id = $this->CreateAgendaRecord($db, $dt, ++$totale_ric);
                }
                //echo $totale_ric;
                if ($agenda_id == false)
                {
                    //Label1.Text = "Errore nel salvataggio dei dati (tabella Agenda)";
                    return false;
                }
                //echo $totale_ric;
                if (!($this->CreateBookingRecord($db, $dt, $agenda_id, $data, $i)))
                {
                    $this->UpdateAgendaRecord($db, $dt, --$totale_ric, $agenda_id);
                    //Label1.Text = "Errore nel salvataggio dei dati (tabella Prenotazioni)";
                    return false;
                }
                //echo $totale_ric;
                // inviare sms
                /*
                if (db.GetVar("sms_enabled") == "on" && dt.TeacherSms && !(String.IsNullOrEmpty(dt.TeacherPhone)))
                    SendSmsToTeacher(dt);
                */
                // inviare email
                if (!empty($dt['teacher_email'])) {
                        ReceivementsEmailHelper::sendEmailToTeacher($dt, $data['nome'], $data['classe'], $data['email'], $data['parentela']);
                }
            }
            return true;
        }
}