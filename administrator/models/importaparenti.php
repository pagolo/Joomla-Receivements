<?php
/**
 * @version     0.5.0
 * @package     com_receivements
 * @copyright   Copyright (C) 2014. Tutti i diritti riservati.
 * @license     GNU General Public License versione 2 o successiva; vedi LICENSE.txt
 * @author      Paolo Bozzo <info@dbfweb.com> - http://dbfweb.com
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');
require_once JPATH_COMPONENT . DS . 'helpers' . DS . 'receivements.php';

/**
 * Receivements model.
 */
class ReceivementsModelImportaParenti extends JModelAdmin
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
		$form = $this->loadForm('com_receivements.importaparenti', 'importaparenti', array('control' => 'jform', 'load_data' => $loadData));
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
		$data = JFactory::getApplication()->getUserState('com_receivements.importaparenti.form.data', array());
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
        protected function saveParent($id_classe, $utente, $studente, $parentela, $update) {
                // TODO gestire eventuali errori
                $parent_id = ReceivementsHelper::idFromName($utente, '#__receivements_parenti', 'utente');
                $db = $this->getDBO();
                if ($parent_id) { // aggiorna i dati
                        if ($update) {
                                $db->setQuery('UPDATE #__receivements_parenti  SET id_classe='.$db->Quote($id_classe).', utente='.$db->Quote($utente).', studente='.$db->Quote($studente).', parentela='.$db->Quote($parentela).' WHERE id = '.$db->Quote($parent_id));
                                return ($db->execute());
                        }
                        return false;
                }
                $db->setQuery('INSERT INTO #__receivements_parenti (id_classe, utente, studente, parentela) VALUES ('.$db->Quote($id_classe).', '.$db->Quote($utente).', '.$db->Quote($studente).', '.$db->Quote($parentela).')');
                return ($db->execute());
        }
        /**
         * Do import parents and students
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
                $data->parents_saved = 0;
                $data->parents_failed = 0;
                /* import users? */
                $import_users = false;
                /* access to uploaded file */
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
                                        if (empty($key)) continue;
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
                                        $user = ReceivementsHelper::createUser($username, $row->$password,$name,  $row->$email, ReceivementsFrontendHelper::getParentsGroup(), false);
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
                        if ($user == null) $user = JFactory::getUser($userid);
			if ($user == null && $userid == 0) {
                                $data->hours_failed++;
                                continue; // TODO accodare avviso
			}
                        
                        $_classe = $this->x('CLASS');
                        $classe = trim($row->$_classe);
                        $id_classe = ReceivementsHelper::idFromName($classe, '#__receivements_classi', 'classe');
                        if (!$id_classe) {
                                $data->parents_failed++;
                                continue;
                        }

                        $_studente = $this->x('STUDENT');
                        $studente = $row->$_studente;

                        $_parentela = $this->x('RELATIONSHIP');
                        $parentela = $row->$_parentela;
                        $array = array(
                                'COM_RECEIVEMENTS_PARENT',
                                'COM_RECEIVEMENTS_GRANDPARENT',
                                'COM_RECEIVEMENTS_UNCLE',
                                'COM_RECEIVEMENTS_BROTHER',
                                'COM_RECEIVEMENTS_TUTOR',
                                'COM_RECEIVEMENTS_OTHER'
                        );
                        for ($ii = 0; $ii < count($array); $ii++) {
                                if ($parentela == JText::_($array[$ii]))
                                        break;
                        }
                        if ($ii == count($array)) $parentela_finale = $parentela;
                        else $parentela_finale = $array[$ii];

                        $success = $this->saveParent($id_classe, $userid, $studente, $parentela_finale, $input['update']);
                        if ($success) $data->parents_saved++;
                        else $data->parents_failed++;
                }

                JFactory::getApplication()->setUserState('com_receivements.importaparenti.data', $data);
                return true;                
        }                                           
}