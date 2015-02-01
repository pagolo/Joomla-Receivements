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

jimport('joomla.application.component.modeladmin');
require_once JPATH_COMPONENT . '/helpers/receivements.php';

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
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 * @since	1.6
	public function getTable($type = 'Sede', $prefix = 'ReceivementsTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	 */

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
        protected function saveHour($userid, $classi, $giorno, $inizio, $fine, $materia_id, $sede_id, $update, $max_app) {
                // TODO gestire eventuali errori
                $hour_id = ReceivementsHelper::idFromName($userid, '#__receivements_ore', 'id_docente');
                $db = $this->getDBO();
                if ($hour_id) { // aggiorna i dati
                        if ($update) {
                                $db->setQuery('UPDATE #__receivements_ore  SET classi='.$db->Quote($classi).', giorno='.$db->Quote($giorno).', inizio='.$db->Quote($inizio).', fine='.$db->Quote($fine).', max_app='.$db->Quote($max_app).', cattedra='.$db->Quote($materia_id).', sede='.$db->Quote($sede_id).' WHERE id = '.$db->Quote($hour_id));
                                $db->execute();
                        }
                        return;
                }
                $db->setQuery('INSERT INTO #__receivements_ore (id_docente, classi, giorno, inizio, fine, max_app, cattedra, sede, email, attiva) VALUES ('.$db->Quote($userid).', '.$db->Quote($classi).', '.$db->Quote($giorno).', '.$db->Quote($inizio).', '.$db->Quote($fine).', '.$db->Quote($max_app).', '.$db->Quote($materia_id).', '.$db->Quote($sede_id).', TRUE, TRUE)');
                $db->execute();
        }
        /**
         * Do import hours and (perhaps) teachers
         * @input input parameters
         * @filename path and cvs filename
         * @return boolean success
         */
        public function doImport($input, $filename)
        {
                $import_users = false;
                $fh = fopen($filename, 'r');
                $headers = fgetcsv($fh, 0, $input['separator']);
                if (in_array ($this->x('FIRSTNAME'), $headers) && in_array($this->x('LASTNAME'), $headers)) {
                        $import_users = true;
                }
                else if (!in_array ($this->x('USERNAME'), $headers)) {
                        return false;
                }
                // TODO: check for correct columns in cvs file

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
                                $username = $base = sprintf($username_fmt, strtolower($row->$nome), strtolower($row->$cognome));
                                $userid = 0;
                                if ($row->$new) {
                                        $c = 1;
                                        while (ReceivementsHelper::useridFromUsername($username)) {
                                                $username = $base . '.' . $c++;
                                                // TODO aggiungere un limite
                                        }
                                } else {
                                        $userid = ReceivementsHelper::useridFromUsername($username);
                                }
                                if (!$userid) {
                                        $user = ReceivementsHelper::createUser($username, $row->$password,$name,  $row->$email, ReceivementsFrontendHelper::getTeachersGroup(), false);
                                        if ($user == null) {
                                                continue; // TODO accodare avviso
                                        }
                                        $userid = $user->id;
                                }
                        } else { // don't import users, userid must be specified
                                $_username = $this->x('USERNAME');
                                $username = $row->$_username;
                                $userid = ReceivementsHelper::useridFromUsername($username);
                                if (!$userid) {
                                        continue; // TODO accodare avviso
                                }
                        }
                        if ($user == null) $user = JFactory::getUser($userid);
                        $_materia = $this->x('MATTER');
                        $materia = $row->$_materia;
                        $materia_id = ReceivementsHelper::idFromName($materia, '#__receivements_cattedre', 'materie');
                        if (!$materia_id) $materia_id = ReceivementsHelper::InsertField($materia, '#__receivements_cattedre', 'materie');
                        $_sede = $this->x('SITE');
                        $sede = $row->$_sede;
                        $sede_id = ReceivementsHelper::idFromName($sede, '#__receivements_sedi', 'sede');
                        if (!$sede_id) $sede_id = ReceivementsHelper::InsertField($sede, '#__receivements_sedi', 'sede');
                        $_classi = $this->x('CLASSES');
                        $classi = $row->$_classi;
                        $nomi_classi = explode(',', $classi);
                        $ids_classi = array();
                        foreach($nomi_classi as $classe) {
                                $classe = trim($classe);
                                //$classe_id = ReceivementsHelper::idFromName($classe, '#__receivements_classi', 'classe');
                                //if (!$classe_id) $classe_id = ReceivementsHelper::InsertField($classe, '#__receivements_classi', 'classe');
                                $ids_classi[] = $classe;//$classe_id;
                        }
                        $classi_finale = implode(',', $ids_classi);
                        $_giorno = $this->x('DAY');
                        $giorno = $row->$_giorno;
                        for ($ii = 0, $giorno_finale = -1; $ii < 6; $ii++) {
                                if ($giorno == JText::_('COM_RECEIVEMENTS_ORE_GIORNO_OPTION_'.$ii)) {
                                        $giorno_finale = $ii;
                                        break;
                                }
                        }
                        if ($giorno_finale == -1) {
                                continue; // TODO accodare avviso
                        }
                        $_inizio = $this->x('START');
                        $inizio = $row->$_inizio;
                        if (strlen($inizio) != 5 || $inizio[2] != ':') {
                                continue; // TODO accodare avviso
                        }
                        $inizio_finale = $inizio . ':00';
                        $_fine = $this->x('END');
                        $fine = $row->$_fine;
                        if (strlen($fine) != 5 || $fine[2] != ':') {
                                continue; // TODO accodare avviso
                        }
                        $fine_finale = $fine . ':00';
                        $success = $this->saveHour($userid, $classi_finale, $giorno_finale, $inizio_finale, $fine_finale, $materia_id, $sede_id, $input['update'], $input['max_app']);
                }
                //exit;
                return true;                
        }                                           
        
}