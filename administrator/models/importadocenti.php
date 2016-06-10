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

jimport('joomla.application.component.modeladmin');
require_once JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'receivements.php';

/**
 * Receivements model.
 */
class ReceivementsModelImportaDocenti extends JModelAdmin
{
	/**
	 * @var		string	The prefix to use with controller messages.
	 * @since	1.6
	 */

	protected $text_prefix = 'COM_RECEIVEMENTS';

	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		An optional array of data for the form to interogate.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	JForm	A JForm object on success, false on failure
	 * @since	1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Initialise variables.
		$app	= JFactory::getApplication();

		// Get the form.
		$form = $this->loadForm('com_receivements.importadocenti', 'importadocenti', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_receivements.importadocenti.form.data', array());
		return $data;
	}
	protected function x($ref) {
	       return strtolower(JText::_('COM_RECEIVEMENTS_'.$ref));
        }
        protected function swap(&$x,&$y) {
                $tmp=$x;
                $x=$y;
                $y=$tmp;
        }
        protected function saveHour($userid, $classi, $giorno, $inizio, $fine, $materia_id, $sede_id, $update, $recv, $max_app) {
                // TODO gestire eventuali errori
                $hour_id = ReceivementsHelper::idFromName($userid, '#__receivements_ore', 'id_docente', 'una_tantum', $recv);
                $db = $this->getDBO();
                if ($hour_id) { // aggiorna i dati
                        if ($update) {
                                $db->setQuery('UPDATE #__receivements_ore  SET classi='.$db->Quote($classi).', giorno='.$db->Quote($giorno).', inizio='.$db->Quote($inizio).', fine='.$db->Quote($fine).', max_app='.$db->Quote($max_app).', cattedra='.$db->Quote($materia_id).', sede='.$db->Quote($sede_id).' WHERE id = '.$db->Quote($hour_id));
                                return ($db->execute());
                        }
                        return false;
                }
                $db->setQuery('INSERT INTO #__receivements_ore (id_docente, classi, una_tantum, giorno, inizio, fine, max_app, cattedra, sede, email, attiva) VALUES ('.$db->Quote($userid).', '.$db->Quote($classi).', '.$db->Quote($recv).', '.$db->Quote($recv==0?$giorno:'').', '.$db->Quote($inizio).', '.$db->Quote($fine).', '.$db->Quote($max_app).', '.$db->Quote($materia_id).', '.$db->Quote($sede_id).', TRUE, TRUE)');
                return ($db->execute());
        }
        /**
         * Do import hours and (perhaps) teachers
         * @input input parameters
         * @filename path and cvs filename
         * @return boolean success
         */
        public function doImport($input, $filename)
        {
                /* data to show */
                $data = new stdClass;
                $data->users_created = 0;
                $data->users_failed = 0;
                $data->users_existing = 0;
                $data->hours_saved = 0;
                $data->hours_failed = 0;
                /* import users? */
                $import_users = false;

                /* una tantum stuff */
                $una_tantum = false;
                if ($input['una_tantum'] > 0) {
	               $db = JFactory::getDBO();
	               $db->setQuery('SELECT * FROM #__receivements_generali WHERE id = '.$db->Quote($input['una_tantum']));
                       $una_tantum = $db->loadObject();
                }

                /* access to uploaded file */
                $fh = fopen($filename, 'r');
                $headers = fgetcsv($fh, 0, $input['separator']);
                if (in_array ($this->x('FIRSTNAME'), $headers) && in_array($this->x('LASTNAME'), $headers)) {
                        $import_users = true;
                }
                else if (!in_array ($this->x('USERNAME'), $headers)) {
                        return false;
                }
                // CHECK if wrong import file
                if (!in_array ($this->x('DAY'), $headers)) {
                        return false;
                }

                $filedata = array();
                while (!feof($fh))
                {
                        $row = fgetcsv($fh, 0, $input['separator']);
                        if (!empty($row))
                        {
                                $obj = new stdClass;
                                foreach ($row as $i => $value)
                                {
                                        $key = $headers[$i];
                                        $obj->$key = trim(ReceivementsHelper::convertUTF8($value));
                                }
                                $filedata[] = $obj;
                        }
                }
                fclose($fh);
                JFile::delete($filename);

                foreach($filedata as $i => $row) {
                        $user = null;
                        if ($import_users) {
                                $use_existing = false;
                                $nome = $this->x('FIRSTNAME');
                                $cognome = $this->x('LASTNAME');
                                $password = $this->x('PASSWORD');
                                $email = $this->x('EMAIL');
                                $new = $this->x('NEW');
                                $name = $input['name'] == 'lastfirst' ? $row->$cognome.' '.$row->$nome : $row->$nome.' '.$row->$cognome;
                                $username_fmt = $input['username'];
                                if ($username_fmt[0] == '#') {
                                        $username_fmt = substr($username_fmt, 1);
                                        $this->swap($nome, $cognome);
                                }
                                $username = sprintf($username_fmt, strtolower($row->$nome), strtolower($row->$cognome));
                                $username = $base = str_replace(' ', '_', $username);
                                $userid = 0;
                                if ($row->$new) {
                                        $c = 1;
                                        while (ReceivementsHelper::useridFromUsername($username)) {
                                                $username = $base . '.' . $c++;
                                                // TODO aggiungere un limite
                                        }
                                } else {
                                        $userid = ReceivementsHelper::useridFromUsername($username);
                                        if ($userid) $data->users_existing++;
                                }
                                if (!$userid) {
                                        $user = ReceivementsHelper::createUser($username, $row->$password, $name,  $row->$email, ReceivementsFrontendHelper::getTeachersGroup(), false);
                                        if ($user == null) {
                                                $data->users_failed++;
                                                continue; // TODO accodare avviso
                                        }
                                        $data->users_created++;
                                        $userid = $user->id;
                                }
                        } else { // don't import users, userid must be specified
                                $_username = $this->x('USERNAME');
                                $username = $row->$_username;
                                $userid = ReceivementsHelper::useridFromUsername($username);
                                if (!$userid) {
                                        $data->users_failed++;
                                        continue; // TODO accodare avviso
                                }
                                $data->users_existing++;
                        }
                        if ($user == null && $userid > 0) $user = JFactory::getUser($userid);
			   if ($user == null && $userid == 0) {
                                $data->hours_failed++;
                                continue; // TODO accodare avviso
			   }

                        $_materia = $this->x('MATTER');
                        $materia = $row->$_materia;
                        $materia_id = ReceivementsHelper::idFromName($materia, '#__receivements_cattedre', 'materie');
                        if (!$materia_id && !empty($materia)) 
                                $materia_id = ReceivementsHelper::InsertField($materia, '#__receivements_cattedre', 'materie');

                        if ($una_tantum) $sede_id = $una_tantum->sede;
                        else {
                                $_sede = $this->x('SITE');
                                $sede = $row->$_sede;
                                $sede_id = ReceivementsHelper::idFromName($sede, '#__receivements_sedi', 'sede');
                                if (!$sede_id  && !empty($sede)) 
                                        $sede_id = ReceivementsHelper::InsertField($sede, '#__receivements_sedi', 'sede');
                        }
                        
                        $_classi = $this->x('CLASSES');
                        $classi = $row->$_classi;
                        $nomi_classi = explode(',', $classi);
                        $ids_classi = array();
                        foreach($nomi_classi as $_classe) {
                                $classe = trim($_classe);
                                $classe_id = ReceivementsHelper::idFromName($classe, '#__receivements_classi', 'classe');
                                if (!$classe_id && !empty($classe)) {
                                        $classe_id = ReceivementsHelper::InsertField($classe, '#__receivements_classi', 'classe');
                                }
                                $ids_classi[] = $classe;
                        }
                        $classi_finale = implode(',', $ids_classi);

                        if ($una_tantum) $giorno_finale = '';
                        else {
                                $_giorno = $this->x('DAY');
                                $giorno = $row->$_giorno;
                                for ($ii = 0, $giorno_finale = -1; $ii < 6; $ii++) {
                                        if ($giorno == JText::_('COM_RECEIVEMENTS_ORE_GIORNO_OPTION_'.$ii)) {
                                                $giorno_finale = $ii;
                                                break;
                                        }
                                }
                                if ($giorno_finale == -1) {
                                        $data->hours_failed++;
                                        continue; // TODO accodare avviso
                                }
                        }

                        $_inizio = $this->x('START');
                        if ($una_tantum) $inizio = $una_tantum->inizio;
                        else $inizio = $row->$_inizio;
                        if ($inizio[2] != ':') {
                                $data->hours_failed++;
                                continue; // TODO accodare avviso
                        }
                        if ($una_tantum)  $inizio_finale = $inizio;
                        else $inizio_finale = $inizio . ':00';

                        $_fine = $this->x('END');
                        if ($una_tantum) $fine = $una_tantum->fine;
                        else $fine = $row->$_fine;
                        if ($fine[2] != ':') {
                                $data->hours_failed++;
                                continue; // TODO accodare avviso
                        }
                        if ($una_tantum)  $fine_finale = $fine;
                        else $fine_finale = $fine . ':00';

                        $success = $this->saveHour($userid, $classi_finale, $giorno_finale, $inizio_finale, $fine_finale, $materia_id, $sede_id, $input['update'], $input['una_tantum'], $input['max_app']);
                        if ($success) $data->hours_saved++;
                        else $data->hours_failed++;
                }

                JFactory::getApplication()->setUserState('com_receivements.importadocenti.data', $data);
                return true;                
        }                                           
        
}