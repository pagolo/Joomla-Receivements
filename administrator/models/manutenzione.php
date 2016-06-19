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
class ReceivementsModelManutenzione extends JModelAdmin
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
	public function getForm($data = array(), $loadData = false)
	{
		// Get the form.
		$form = $this->loadForm('com_receivements.manutenzione', 'manutenzione', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		return $form;
	}

        /**
         */
        public function doTruncate($input)
        {
                $user = JFactory::getUser();
                if (!($user->authorise('core.delete', 'com_receivements')))
                        return false;
                $db = JFactory::getDbo();
                if ($input['main']) {
                        $db->truncateTable('#__receivements_prenotazioni');
                        $db->truncateTable('#__receivements_agenda');
                        $db->truncateTable('#__receivements_ore');
                        $db->truncateTable('#__receivements_generali');
                }
                if ($input['vacations']) 
                        $db->truncateTable('#__receivements_calendario');
                if ($input['matters']) 
                        $db->truncateTable('#__receivements_cattedre');
                if ($input['sites']) 
                        $db->truncateTable('#__receivements_sedi');
                if ($input['classes']) 
                        $db->truncateTable('#__receivements_classi');
                if (isset($input['parents']) && $input['parents']) 
                        $db->truncateTable('#__receivements_parenti');
                return true;                
        }                                           
        
}