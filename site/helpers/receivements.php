<?php
/**
 * @version     1.0.5
 * @package     com_receivements
 * @copyright   Copyright (C) 2014. Tutti i diritti riservati.
 * @license     GNU General Public License versione 2 o successiva; vedi LICENSE.txt
 * @author      Paolo Bozzo - pagolo.bozzo AT gmail.com - http://dbfweb.com
 */
  
defined('_JEXEC') or die;
 
class ReceivementsRoute extends JRoute
{
	static
	function _($url, $x, $sche) 
	{
		$base = JUri::base();
		$par = parent::_($url, $x , $sche);
		if (strncmp($base, $par, strlen($base)) == 0) {
			$par = substr($par, strlen($base));
		}
		return $par;
	}
}

class ReceivementsFrontendHelper
{
        static
        function buildReceivementCell($text, &$view, $len = 12, $expand = false) {
                if ($expand) $len += 10;
                $display_text = $view->escape(substr($text, 0, $len));
                $text = $view->escape($text);
                $tooltip = $display_text != $text;
                $html = array();
                if ($tooltip) $html[] = JText::sprintf('<span title="%s">', $text);
                $html[] = $display_text . ($tooltip? '...' : '');
                if ($tooltip) $html[] = '</span>';
                return implode($html);
        }
        
	static
	function getSchoolOptions()
	{
		// Build the filter options.
		$parent_group = ReceivementsFrontendHelper::getSchoolsGroup();
		if (empty($parent_group)) return false;
		$db = JFactory::getDbo();
		$parent_group_id = ReceivementsFrontendHelper::getGroupId($parent_group, $db);
		if ($parent_group_id === false) return false;
		
		$db->setQuery('SELECT a.id AS value, a.title AS text' . ' FROM #__usergroups AS a' . ' WHERE parent_id = ' . $parent_group_id  . ' ORDER BY a.title ASC');
		$options = $db->loadObjectList();

		// Check for a database error.

		if ($db->getErrorNum()) {
			JError::raiseNotice(500, $db->getErrorMsg());
			return null;
		}

		return $options;
	}

	static
	function getTeachersOptions()
	{
		// Build the filter options.
		$teachers_group = ReceivementsFrontendHelper::getTeachersGroup();
		if (empty($teachers_group)) return false;
		$db = JFactory::getDbo();
		$teachers_group_id = ReceivementsFrontendHelper::getGroupId($teachers_group, $db);
		if ($teachers_group_id === false) return false;
		
		$db->setQuery('SELECT a.user_id AS value, b.name AS text FROM #__user_usergroup_map AS a LEFT JOIN #__users AS b ON a.user_id = b.id WHERE a.group_id = ' . $teachers_group_id  . ' ORDER BY b.name ASC');
		$options = $db->loadObjectList();

		// Check for a database error.

		if ($db->getErrorNum()) {
			JError::raiseNotice(500, $db->getErrorMsg());
			return null;
		}

		return $options;
	}

        static
	function getTypesOptions()
	{
		$db = JFactory::getDbo();
		
		$db->setQuery('SELECT id AS value, titolo AS text FROM #__receivements_generali WHERE attiva = 1 AND data > CURDATE() + INTERVAL '.ReceivementsFrontendHelper::getPreBooking().' DAY ORDER BY data ASC');
		$options = $db->loadObjectList();

		// Check for a database error.

		if ($db->getErrorNum()) {
			JError::raiseNotice(500, $db->getErrorMsg());
			return null;
		}

		return $options;
	}

        static
	function getBookingDate($search)
	{
		$db = JFactory::getDbo();
		
                $db->setQuery('SELECT data FROM #__receivements_generali WHERE id = '.$db->Quote($search));
                $booking_date = $db->loadResult();

		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseNotice(500, $db->getErrorMsg());
			return null;
		}

		return $booking_date;
	}

	static
	function getWeekDayOptions()
	{

		// Build the filter options.

		$options = array();
		for ($i = 0; $i < 6; $i++) {
			$options[] = JHtml::_('select.option', $i, JText::_('COM_RECEIVEMENTS_ORE_GIORNO_OPTION_' . $i));
		}

		return $options;
	}

	static
	function getClassesOptions()
	{
		$db = JFactory::getDbo();
		$db->setQuery('SELECT a.id AS value, a.classe AS text' . ' FROM #__receivements_classi AS a' . ' ORDER BY a.classe ASC');
		$options = $db->loadObjectList();

		// Check for a database error.

		if ($db->getErrorNum()) {
			JError::raiseNotice(500, $db->getErrorMsg());
			return null;
		}

		return $options;
	}

	static
	function getSitesOptions()
	{
		$db = JFactory::getDbo();
		$db->setQuery('SELECT a.id AS value, a.sede AS text' . ' FROM #__receivements_sedi AS a' . ' ORDER BY a.sede ASC');
		$options = $db->loadObjectList();

		// Check for a database error.

		if ($db->getErrorNum()) {
			JError::raiseNotice(500, $db->getErrorMsg());
			return null;
		}

		return $options;
	}

	static
	function convertDateFrom($date, $fmt_str = 'DATE_FORMAT_LC3')
	{
		$myDate = JFactory::getDate($date);
		$format = JText::_($fmt_str);
		return JHTML::_('date', $myDate, $format, 'UTC');
	}

	static
	function convertDateTo($date)
	{
		$myDate = JFactory::getDate($date);
		$format = JText::_('Y-m-d');
		return JHTML::_('date', $myDate, $format);
	}

        static
        function getSingleDate($id)
        {
                if (empty($id)) return '';
                $db = JFactory::getDbo();
                $query = 'SELECT data FROM #__receivements_generali WHERE id = ' . $id;
                $db->setQuery($query);
                $rawdate = $db->loadResult();
                $translated_date = ReceivementsFrontendHelper::convertDateFrom($rawdate, 'DATE_FORMAT_LC');
                return $translated_date;
        }

	static
	function getGUID()
	{
	       return uniqid('', true);
	}
	
	static
	function getPreBooking()
	{
	        $app = JFactory::getApplication();
                $params = $app->isAdmin() ? JComponentHelper::getParams('com_receivements') : $app->getParams();
                $params_array = $params->toArray();
                return (isset($params_array['pre_booking'])? $params_array['pre_booking'] : 3); 
        }
	
	static
	function getShowTotalDays()
	{
	        $app = JFactory::getApplication();
                $params = $app->isAdmin() ? JComponentHelper::getParams('com_receivements') : $app->getParams();
                $params_array = $params->toArray();
                return (isset($params_array['show_total_days'])? $params_array['show_total_days'] : 7); 
        }
        
	static
	function getCaptcha()
	{
	        $app = JFactory::getApplication();
                $params = $app->isAdmin() ? JComponentHelper::getParams('com_receivements') : $app->getParams();
                $params_array = $params->toArray();
                return (isset($params_array['captcha'])? $params_array['captcha'] : ''); 
        }
        
	function getParentsGroup()
	{
	        $app = JFactory::getApplication();
                $params = $app->isAdmin() ? JComponentHelper::getParams('com_receivements') : $app->getParams();
                $groupname = $params->get('parents_group');
                return (empty($groupname) ? 'Genitori' : $groupname); 
        }
        
	function getTeachersGroup()
	{
	        $app = JFactory::getApplication();
                $params = $app->isAdmin() ? JComponentHelper::getParams('com_receivements') : $app->getParams();
                $groupname = $params->get('teachers_group');
                return (empty($groupname) ? 'Docenti' : $groupname); 
        }
        
	function getStudentsGroup()
	{
	        $app = JFactory::getApplication();
                $params = $app->isAdmin() ? JComponentHelper::getParams('com_receivements') : $app->getParams();
                $groupname = $params->get('students_group');
                return (empty($groupname) ? 'Studenti' : $groupname); 
        }
        
	function getSchoolsGroup()
	{
	        $app = JFactory::getApplication();
                $params = $app->isAdmin() ? JComponentHelper::getParams('com_receivements') : $app->getParams();
                return $params->get('schools_group');
        }

        static
	function getGroupId($groupName, $db) {
		$db->setQuery($db->getQuery(true)
		    ->select('id')
		    ->from("#__usergroups")
		    ->where('title = ' . $db->Quote($groupName))
		);
		$groups = $db->loadObjectList();
		if (count($groups)) return $groups[0]->id;
		return false; // return false if group name not found
	}

	static
	function getForcedLogin()
	{
	        $app = JFactory::getApplication();
                $params = $app->isAdmin() ? JComponentHelper::getParams('com_receivements') : $app->getParams();
                $params_array = $params->toArray();
                return (isset($params_array['forced_login'])? $params_array['forced_login'] : 0); 
        }
        
	static
	function getEmailVerification()
	{
	        $app = JFactory::getApplication();
                $params = $app->isAdmin() ? JComponentHelper::getParams('com_receivements') : $app->getParams();
                $params_array = $params->toArray();
                return (isset($params_array['verify_email'])? $params_array['verify_email'] : 1); 
        }
        
	static
	function canBook()
	{
	        if (!(ReceivementsFrontendHelper::getForcedLogin())) {
	                return true;
                }
                $user = JFactory::getUser();
                if  ($user->authorise('core.admin')) { // administrator
                        return true;
                }
                $group_name = ReceivementsFrontendHelper::getParentsGroup();
		$db = JFactory::getDbo();
		$db->setQuery('SELECT id FROM #__usergroups WHERE title = ' . $db->Quote($group_name));
                $group_id = $db->loadResult();
                if (in_array($group_id, $user->getAuthorisedGroups())) {
                        return true;
                }
                return false;
        }
        
        static
        function handleParentData($data) {
		$db = JFactory::getDbo();
                $user = JFactory::getUser();
                $userid = $user->get('id');
		$db->setQuery('SELECT id FROM #__receivements_parenti WHERE utente = ' . $db->Quote($userid));
                $data_id = $db->loadResult();
                if (empty($data_id)) {  // create record
                        $db->setQuery('INSERT INTO #__receivements_parenti (classe, utente, studente, parentela) VALUES ('.$db->Quote($data['classe']).', '.$db->Quote($userid).', '.$db->Quote($data['nome']).', '.$db->Quote($data['parentela']).')');
                } else {                // update record
                        $db->setQuery('UPDATE #__receivements_parenti SET classe='.$db->Quote($data['classe']).', studente='.$db->Quote($data['nome']).', parentela='.$db->Quote($data['parentela']).' WHERE utente='.$db->Quote($userid));
                }
                return $db->execute();
	}

        static
        function isDateAvailable($date, $utente, $ore) {
		$db = JFactory::getDbo();
		$qdate = $db->Quote($date);
                // controlliamo che non siano le vacanze estive di fine anno, se sì disabilitiamo e restituiamo -1
		$db->setQuery('SELECT COUNT(*) FROM #__receivements_calendario WHERE inizio <= DATE('.$qdate.') AND fine > DATE('.$qdate.') AND finale = TRUE');
                $found = $db->loadResult();
                if ($found > 0) {
                        return -1;
                }
                // controlliamo che non sia un giorno di festa, se sì disabilitiamolo e continuiamo
                $db->setQuery('SELECT COUNT(*) FROM #__receivements_calendario WHERE inizio <= DATE('.$qdate.') AND fine >= DATE('.$qdate.') AND (utente = 0 OR utente = '.$utente.')');
                $found = $db->loadResult();
                if ($found > 0) {
                        return 0;
                }
                // controlliamo che il giorno non abbia raggiunto il max dei ricevimenti
                $db->setQuery('SELECT COUNT(*) FROM #__receivements_agenda AS a LEFT JOIN #__receivements_ore AS o ON (a.id_ore = o.id) WHERE (a.data = '.$qdate.' AND a.id_ore = '.$ore.' AND a.totale_ric >= o.max_app)');
                $found = $db->loadResult();
                if ($found > 0) {
                        return 0;
                }
                return 1;
        }       
        
        static
        function createUndoAddress($guid) {
                return JUri::base() . 'index.php?option=com_receivements&view=disdetta&guid=' . $guid;
        }
}

class ReceivementsAjaxHelper

{
	static
	function changeStudent($user_id, $nome)
	{
	       $db = JFactory::getDBO();
	       $db->setQuery('SELECT c.classe, p.parentela FROM #__receivements_parenti p LEFT JOIN #__receivements_classi c ON (c.id = p.id_classe) WHERE utente = '.$db->Quote($user_id).' AND studente = '.$db->Quote($nome));
	       return $db->loadAssoc();
	}
	static
	function changeReceivement($id)
	{
	       $db = JFactory::getDBO();
	       $db->setQuery('SELECT * FROM #__receivements_generali WHERE id = '.$db->Quote($id));
               $dati = $db->loadAssoc();
               if (isset($dati['data'])) $dati['giorno'] = ReceivementsFrontendHelper::convertDateFrom($dati['data'], 'DATE_FORMAT_LC');
               return $dati;
	}
	static
	function changeTeacher($id)
	{
	       $db = JFactory::getDBO();
	       $db->setQuery('SELECT classi, cattedra FROM #__receivements_ore WHERE id_docente = '.$db->Quote($id));
               $dati = $db->loadAssoc();
               return $dati;
	}        
}

class ReceivementsEmailHelper
{
	static
	function sendEmailToTeacher($dt, $nome, $classe, $email, $parentela)
	{
	        $config	= JFactory::getConfig();
	        $emailSubject	= JText::sprintf(
				'COM_RECEIVEMENTS_EMAIL_BOOKING_SUBJECT',
				$config->get('sitename')
			);

                $param = strtolower(JText::_($parentela));
                if (ReceivementsFrontendHelper::getForcedLogin()) {
                        $parente = JFactory::getUser()->get('name');
                        $param = $parente . ', ' . $param;
                }
                
	        $emailBody = JText::sprintf(
				'COM_RECEIVEMENTS_EMAIL_TO_TEACHER_BODY',
				$dt['teacher_name'],
				$param,
				$nome,
				$classe,
				ReceivementsFrontendHelper::convertDateFrom($dt['datetime'], 'l, d/m/Y H:i'),
				$email
			);
	        $mailer = JFactory::getMailer();
                $mailer->setSender( array( $config->get('mailfrom'), $config->get('fromname') ) );
                $mailer->addRecipient( $dt['teacher_email'] );
                //$mailer->addReplyTo( array( $email ) );
                $mailer->setSubject( $emailSubject );
                $mailer->setBody( $emailBody );

		$return = $mailer->Send();
                return $return;		
        }
        
	static
	function sendDeletionEmailToTeacher($data)
	{
	        $config	= JFactory::getConfig();
	        $emailSubject	= JText::sprintf(
				'COM_RECEIVEMENTS_EMAIL_REMOVAL_SUBJECT',
				$config->get('sitename')
			);

	        $emailBody = JText::sprintf(
				'COM_RECEIVEMENTS_EMAIL_REMOVAL_BODY',
				$data['name'],
				ReceivementsFrontendHelper::convertDateFrom($data['data'], 'l, d/m/Y H:i'),
				$data['student'],
				$data['classe']
			);
	        $mailer = JFactory::getMailer();
                $mailer->setSender( array( $config->get('mailfrom'), $config->get('fromname') ) );
                $mailer->addRecipient( $data['email'] );
                $mailer->setSubject( $emailSubject );
                $mailer->setBody( $emailBody );

		$return = $mailer->Send();
                return $return;		
        }
        
	static
	function sendDeletionEmailToParent($data)
	{
	        $config	= JFactory::getConfig();
	        $emailSubject	= JText::sprintf(
				'COM_RECEIVEMENTS_EMAIL_REMOVAL_SUBJECT',
				$config->get('sitename')
			);

	        $emailBody = JText::sprintf(
				'COM_RECEIVEMENTS_EMAIL_REMOVAL_TO_PARENT_BODY',
				$data['name'],
				ReceivementsFrontendHelper::convertDateFrom($data['data'], 'l, d/m/Y H:i'),
				$data['student']
			);

	        $mailer = JFactory::getMailer();
                $mailer->setSender( array( $config->get('mailfrom'), $config->get('fromname') ) );
                $mailer->addRecipient( $data['email'] );
                $mailer->setSubject( $emailSubject );
                $mailer->setBody( $emailBody );

		$return = $mailer->Send();
                return $return;		
        }
        
	static
	function sendConfirmationEmail($data)
	{
	        $config	= JFactory::getConfig();
	        $emailSubject	= JText::sprintf(
				'COM_RECEIVEMENTS_EMAIL_BOOKING_SUBJECT',
				$config->get('sitename')
			);
                $html = array();
                $html[] = JText::_('COM_RECEIVEMENTS_CONFIRMATION_BODY_1');
                foreach ($data['ricevimenti'] as $datum) {
                        $html[] = JText::sprintf('COM_RECEIVEMENTS_CONFIRMATION_BODY_2',
                                $datum['teacher_name'],
                                ReceivementsFrontendHelper::convertDateFrom($datum['datetime'], 'l, d/m/Y H:i'),
                                $datum['ric_numero'],
                                $datum['ric_totale'],
                                ReceivementsFrontendHelper::createUndoAddress($datum['guid'])
                                );
                }
                $html[] = JText::_('COM_RECEIVEMENTS_CONFIRMATION_BODY_3');
	        $emailBody = implode($html);

	        $mailer = JFactory::getMailer();
	        $mailer->isHTML(true);
                $mailer->setSender( array( $config->get('mailfrom'), $config->get('fromname') ) );
                $mailer->addRecipient( $data['email'] );
                $mailer->setSubject( $emailSubject );
                $mailer->setBody( $emailBody );

		$return = $mailer->Send();
                return $return;		
        }
        /**
         * derived from example by Sam Battat
         * https://github.com/hbattat
         */ 
        function verifyEmail($toemail, $fromemail, &$error) {
	       $email_arr = explode("@", trim($toemail));
	       $domain = array_slice($email_arr, -1);
	       $domain = $domain[0];
	       if( "IPv6:" == substr($domain, 0, strlen("IPv6:")) ) {
	               $domain = substr($domain, strlen("IPv6") + 1);
	       }
	       $mxhosts = array();
	       if( filter_var($domain, FILTER_VALIDATE_IP) )
	               $mx_ip = $domain;
	       else
	               getmxrr($domain, $mxhosts, $mxweight);
	       if(!empty($mxhosts) )
	               $mx_ip = $mxhosts[array_search(min($mxweight), $mxhosts)];
	       else {
	               if( filter_var($domain, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) ) {
		              $record_a = dns_get_record($domain, DNS_A);
		      }
	               elseif( filter_var($domain, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) ) {
		              $record_a = dns_get_record($domain, DNS_AAAA);
		      }
		      if( !empty($record_a) )
		              $mx_ip = $record_a[0]['ip'];
		      else {
		              $error = sprintf(JText::_('COM_RECEIVEMENTS_CANT_FIND_MAIL_SERVER'), $domain);
		              return ( false );
		      }
	       }
	
	       $connect = @fsockopen($mx_ip, 25); 
	       if($connect){ 
		      if(preg_match("/^220/i", $out = fgets($connect, 1024))){
			     fputs ($connect , "HELO $mx_ip\r\n"); 
			     $out = fgets ($connect, 1024);
 
			     fputs ($connect , "MAIL FROM: <$fromemail>\r\n"); 
			     $from = fgets ($connect, 1024); 
			     fputs ($connect , "RCPT TO: <$toemail>\r\n"); 
			     $to = fgets ($connect, 1024);
			     fputs ($connect , "QUIT"); 
			     fclose($connect);
			     if(!preg_match("/^250/i", $from)){
                                     return true;
                                     /*
			             $result = false;
                                     $error = JText::_('COM_RECEIVEMENTS_MAIL_SENDER_NOT_ACCEPTED');
                                     */
			     } elseif(!preg_match("/^250/i", $to)) {
			             $result = false;
                                     $error = sprintf(JText::_('COM_RECEIVEMENTS_MAIL_DOES_NOT_EXIST'), $toemail);
                             }
			     else{
				    $result = true;
			     }
		      } 
	       }
	       else{
		      $result = false;
		      $error = JText::_('COM_RECEIVEMENTS_CANT_CONNECT_TO_MAIL_SERVER');
	       }
	       return $result;
        }
}
