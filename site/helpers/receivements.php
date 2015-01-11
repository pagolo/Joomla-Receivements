<?php
/**
 * @version     0.0.1
 * @package     com_receivements
 * @copyright   Copyright (C) 2014. Tutti i diritti riservati.
 * @license     GNU General Public License versione 2 o successiva; vedi LICENSE.txt
 * @author      Paolo Bozzo <info@dbfweb.com> - http://dbfweb.com
 */
defined('_JEXEC') or die;
class ReceivementsFrontendHelper

{
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
		return JHTML::_('date', $myDate, $format);
	}

	static
	function convertDateTo($date)
	{
		$myDate = JFactory::getDate($date);
		$format = JText::_('Y-m-d');
		return JHTML::_('date', $myDate, $format);;
	}

	static
	function getGUID()
	{
	       return uniqid('', true);
	}
	
	static
	function getPreBooking()
	{
                $params = JFactory::getApplication()->getParams();
                $params_array = $params->toArray();
                return (isset($params_array['pre_booking'])? $params_array['pre_booking'] : 3); 
        }
	
	static
	function getShowTotalDays()
	{
                $params = JFactory::getApplication()->getParams();
                $params_array = $params->toArray();
                return (isset($params_array['show_total_days'])? $params_array['show_total_days'] : 7); 
        }
        
	static
	function getCaptcha()
	{
                $params = JFactory::getApplication()->getParams();
                $params_array = $params->toArray();
                return (isset($params_array['captcha'])? $params_array['captcha'] : ''); 
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
                return JUri::base() . 'index?option=com_receivements&view=disdetta&guid=' . $guid;
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

	        $emailBody = JText::sprintf(
				'COM_RECEIVEMENTS_EMAIL_TO_TEACHER_BODY',
				$dt['teacher_name'],
				strtolower(JText::_($parentela)),
				$nome,
				$classe,
				ReceivementsFrontendHelper::convertDateFrom($dt['datetime'], 'l, d/m/Y H:i')
			);
	        $mailer = JFactory::getMailer();
                $mailer->setSender( array( $config->get('mailfrom'), $config->get('fromname') ) );
                $mailer->addRecipient( $dt['teacher_email'] );
                $mailer->addReplyTo( array( $email ) );
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
}
/*        
class DateTeacher
{
        private string guid;
        private string teacher;
        private string teachername;
        private string teacher_email;
        private string fulldate;
        private string inizio;
        private string id_ore;
}
*/