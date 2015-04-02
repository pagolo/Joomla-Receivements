<?php



/**

 * @version     0.5.0

 * @package     com_receivements

 * @copyright   Copyright (C) 2014. Tutti i diritti riservati.

 * @license     GNU General Public License versione 2 o successiva; vedi LICENSE.txt

 * @author      Paolo Bozzo - pagolo.bozzo AT gmail.com - http://dbfweb.com

 */

// No direct access

defined('_JEXEC') or die;



require_once JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_receivements'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'receivements.php';



/**

 * Receivements helper.

 */

class ReceivementsHelper {



    /**

     * Configure the Linkbar.

     */

    public static function addSubmenu($vName = '') {

        		JSubMenuHelper::addEntry(

			JText::_('COM_RECEIVEMENTS_TITLE_BOOKINGS'),

			'index.php?option=com_receivements&view=prenotazioni',

			$vName == 'prenotazioni'

		);

		if (ReceivementsFrontendHelper::getForcedLogin()) {

        		JSubMenuHelper::addEntry(

			JText::_('COM_RECEIVEMENTS_TITLE_PARENTI'),

			'index.php?option=com_receivements&view=parenti',

			$vName == 'parenti'

		);}

        		JSubMenuHelper::addEntry(

			JText::_('COM_RECEIVEMENTS_TITLE_ORE'),

			'index.php?option=com_receivements&view=ore',

			$vName == 'ore'

		);

        		JSubMenuHelper::addEntry(

			JText::_('COM_RECEIVEMENTS_TITLE_SEDI'),

			'index.php?option=com_receivements&view=sedi',

			$vName == 'sedi'

		);

        		JSubMenuHelper::addEntry(

			JText::_('COM_RECEIVEMENTS_TITLE_CATTEDRE'),

			'index.php?option=com_receivements&view=cattedre',

			$vName == 'cattedre'

		);

        		JSubMenuHelper::addEntry(

			JText::_('COM_RECEIVEMENTS_TITLE_CLASSI'),

			'index.php?option=com_receivements&view=classi',

			$vName == 'classi'

		);

        		JSubMenuHelper::addEntry(

			JText::_('COM_RECEIVEMENTS_TITLE_VACANZE'),

			'index.php?option=com_receivements&view=vacanze',

			$vName == 'vacanze'

		);

    }



    /**

     * Gets a list of the actions that can be performed.

     *

     * @return	JObject

     * @since	1.6

     */

    public static function getActions() {

        $user = JFactory::getUser();

        $result = new JObject;



        $assetName = 'com_receivements';



        $actions = array(

            'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'

        );



        foreach ($actions as $action) {

            $result->set($action, $user->authorise($action, $assetName));

        }



        return $result;

    }

    public static function convertUTF8( $string ) { 

        if ( strlen(utf8_decode($string)) == strlen($string) ) {   

                // $string is not UTF-8

                return iconv("ISO-8859-1", "UTF-8", $string);

        } else {

                // already UTF-8

                return $string;

        }

    }

    public static function insertField($value, $table, $field) {

	$db = JFactory::getDbo();

	$db->setQuery('INSERT INTO '.$db->quoteName($table).' ('.$db->quoteName($field).') VALUES ('.$db->Quote($value).')');

	$db->execute();

	$db->setQuery('SELECT LAST_INSERT_ID()');

	return $db->loadResult();

    }

    public static function idFromName($name, $table, $field) {

	$db = JFactory::getDbo();

	$db->setQuery('SELECT id FROM '.$db->quoteName($table).' WHERE '.$db->quoteName($field).' = '.$db->Quote($name));

	$return = $db->loadResult();



	// Check for a database error.

	if ($db->getErrorNum()) {

		JError::raiseNotice(500, $db->getErrorMsg());

		return null;

	}

	return $return === null ? 0 : $return;

    }

    public static function groupidFromGroupname($groupname) {

        return ReceivementsHelper::idFromName($groupname, '#__usergroups', 'title');

    }

    public static function useridFromUsername($uname) {

        return ReceivementsHelper::idFromName($uname, '#__users', 'username');

    }



    public static function createUser($username, $password, $name, $email, $groupname, $sendmail) {

        // "generate" a new JUser Object

        $user = new JUser; //JFactory::getUser(0); // it's important to set the "0" otherwise your admin user information will be loaded

        jimport('joomla.application.component.helper'); // include libraries/application/component/helper.php

        $usersParams = &JComponentHelper::getParams( 'com_users' ); // load the Params

        $userdata = array(); // place user data in an array for storing.

        //set username

        $userdata['username'] = $username;

        //set email

        $userdata['email'] = $email;

        //set real name

        $userdata['name'] = $name;

        //set password

        $userdata['password'] = $password;

        $userdata['password2'] = $password;//must be set the same as above to confirm password..

        //set default group.

        $defaultUserGroup = $usersParams->get('new_usertype', 2);

        //default to defaultUserGroup i.e.,Registered
        $userdata['groups']=array($defaultUserGroup, ReceivementsHelper::groupidFromGroupname($groupname));

        $userdata['block'] = 0; // set this to 0 so the user will be added immediately.

        //now to add the new user to the dtabase.

        if (!$user->bind($userdata)) { // bind the data and if it fails raise an error

                JError::raiseWarning('', JText::_( $user->getError())); // something went wrong!!  

                return null;  

        }

  

        if (!$user->save()) { // now check if the new user is saved

                JError::raiseWarning('', JText::_( $user->getError())); // something went wrong!!

                return null;

        }

        return $user;

    }

}

