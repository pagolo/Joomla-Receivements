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
jimport('joomla.event.dispatcher');

require_once JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'receivements.php';

/**
 * Receivements model.
 */
class ReceivementsModelNota extends JModelForm
{
    
    var $_item = null;

	/**
	 * Method to get an ojbect.
	 *
	 * @param	integer	The id of the object to get.
	 *
	 * @return	mixed	Object on success, false on failure.
	 */
	public function &getData($id = null)
	{
		if ($this->_item === null)
		{
			$this->_item = false;

			if (empty($id)) {
            $app = JFactory::getApplication();
             $id = $app->input->get('id','','string');
			}

			// Get a level row instance.
			$table = $this->getTable();

			// Attempt to load the row.
			if ($table->load($id, true, 'id'))
			{
                
                $user = JFactory::getUser();
                $id = $table->id;
                $canEdit = $user->authorise('core.edit', 'com_receivements') || $user->authorise('core.create', 'com_receivements');

                if (!$canEdit) {
                    JError::raiseError('500', JText::_('JERROR_ALERTNOAUTHOR'));
                }
                
				// Convert the JTable to a clean JObject.
				$properties = $table->getProperties(1);
				$this->_item = JArrayHelper::toObject($properties, 'JObject');
			} elseif ($error = $table->getError()) {
				$this->setError($error);
			}
		}
		return $this->_item;
	}
    
	public function getTable($type = 'Prenotazione', $prefix = 'ReceivementsTable', $config = array())
	{   
        $this->addTablePath(JPATH_COMPONENT_ADMINISTRATOR.DIRECTORY_SEPARATOR.'tables');
        return JTable::getInstance($type, $prefix, $config);
	}         

	public function getForm($data = array(), $loadData = true)
	{
			return false;
	}

	protected function loadFormData()
	{
        return $this->getData();
	}
}