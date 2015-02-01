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
class ReceivementsModelGiornaliero extends JModelForm
{
        protected $date = null;
        
	public function getData($id = null)
	{
	        $app = JFactory::getApplication();
	        $data = JRequest::getVar('jform','','post','array');
	        if (!isset($data['data'])) {
	               $data['data'] = ReceivementsFrontendHelper::convertDateFrom('now','d-m-Y');
                }

                $this->date = ReceivementsFrontendHelper::convertDateTo($data['data']);

	        return $data;
	}

        public function getItems() {
                $db = $this->getDBO();               
                $query = $db->getQuery(true);
                $query->select('a.id, u.name, s.sede, o.inizio');
                $query->from('#__receivements_agenda a');
                $query->join('LEFT', '#__receivements_ore o ON (a.id_ore = o.id)');
                $query->join('LEFT', '#__users u ON (u.id = o.id_docente)');
                $query->join('LEFT', '#__receivements_sedi s ON (s.id = o.sede)');
                $query->where('DATE(data) = '.$db->Quote($this->date));
                $db->setQuery($query);
                $items = $db->loadAssocList();
		foreach ($items as $i => $item) {
                        $query = $db->getQuery(true);
                        $query->select('p.nome as studente, c.classe');
                        $query->from('#__receivements_prenotazioni p');
                        $query->join('LEFT', '#__receivements_classi c ON (p.id_classe = c.id)');
                        $query->where('p.id_agenda = '.$db->Quote($item['id']));
                        $db->setQuery($query);
                        $items[$i]['lista'] = $db->loadAssocList();
		}
                return $items;
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
		$form = $this->loadForm('com_receivements.giornaliero', 'giornaliero', array('control' => 'jform', 'load_data' => $loadData));
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
            return $this->getData();
	}
    
}