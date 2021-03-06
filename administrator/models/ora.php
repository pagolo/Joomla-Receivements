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

/**
 * Receivements model.
 */
class ReceivementsModelOra extends JModelAdmin
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
	 */
	public function getTable($type = 'Ora', $prefix = 'ReceivementsTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

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
		$form = $this->loadForm('com_receivements.ora', 'ora', array('control' => 'jform', 'load_data' => $loadData));
        
        
			if($form->getFieldAttribute('inizio', 'default') == 'NOW'){
				$form->setFieldAttribute('inizio', 'default', date('H:i:s'));
			}
			if($form->getFieldAttribute('fine', 'default') == 'NOW'){
				$form->setFieldAttribute('fine', 'default', date('H:i:s'));
			}
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
		$data = JFactory::getApplication()->getUserState('com_receivements.edit.ora.data', array());

		if (empty($data)) {
			$data = $this->getItem();
            
		}

		return $data;
	}

	/**
	 * Method to get a single record.
	 *
	 * @param	integer	The id of the primary key.
	 *
	 * @return	mixed	Object on success, false on failure.
	 * @since	1.6
	 */
	public function getItem($pk = null)
	{
		if ($item = parent::getItem($pk)) {

			//Do any procesing on fields here if needed

		}

		return $item;
	}

	/**
	 * Prepare and sanitise the table prior to saving.
	 *
	 * @since	1.6
	 */
	protected function prepareTable(&$table)
	{
		jimport('joomla.filter.output');

		if (empty($table->id)) {

			// Set ordering to the last item if not set
			if (@$table->ordering === '') {
				$db = JFactory::getDbo();
				$db->setQuery('SELECT MAX(ordering) FROM #__receivements_ore');
				$max = $db->loadResult();
				$table->ordering = $max+1;
			}

		}
	}

	public function validate($form, $data)
	{
                if ($data['id'] || $data['permit_dups'] == 1) { // if updating or permit duplicates, it is ok
                        return parent::validate($form, $data);
                }
		$db = JFactory::getDbo();
		$db->setQuery('SELECT COUNT(id) FROM #__receivements_ore WHERE id_docente = '.$db->quote($data['id_docente']).' AND una_tantum = '.$db->quote($data['una_tantum']) );
		$count = $db->loadResult();
                if ($count > 0) {
                        $this->setError(JText::_('COM_RECEIVEMENTS_ALREADY_ONE_HOUR'));
                        return false;
                }
                //echo "<pre>";print_r($data);exit;
                return parent::validate($form, $data);
        }

        public function setUseEmail(&$pks, $value) {
            $user		= JFactory::getUser();
	    $table		= $this->getTable();
	    $pks		= (array) $pks;
            foreach ($pks as $i => $pk) {
                if ($table->load($pk)) {
                        $old	= $table->getProperties();
			$allow	= $user->authorise('core.edit.state', 'com_receivements');
			if ($allow) {
                                // Skip changing of same state
			        if ($table->email == $value) {
				    unset($pks[$i]);
				    continue;
				}
				$table->email = $value;
				try {
				    // Store the table.
				    if (!$table->store())
				    {
				            $this->setError($table->getError());
					    return false;
				    }
                                }
				catch (Exception $e)
					{
						$this->setError($e->getMessage());
						return false;
					}
                        }
		}
	    }
	    return true;
        }

        public function setActivation(&$pks, $value) {
            $user		= JFactory::getUser();
	    $table		= $this->getTable();
	    $pks		= (array) $pks;
            foreach ($pks as $i => $pk) {
                if ($table->load($pk)) {
                        $old	= $table->getProperties();
			$allow	= $user->authorise('core.edit.state', 'com_receivements');
			if ($allow) {
                                // Skip changing of same state
			        if ($table->attiva == $value) {
				    unset($pks[$i]);
				    continue;
				}
				$table->attiva = $value;
				try {
				    // Store the table.
				    if (!$table->store())
				    {
				            $this->setError($table->getError());
					    return false;
				    }
                                }
				catch (Exception $e)
					{
						$this->setError($e->getMessage());
						return false;
					}
                        }
		}
	    }
	    return true;
        }
}