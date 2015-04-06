<?php
/**
 * @package    Receivements
 * @subpackage Models
 * @author     Paolo Bozzo - pagolo.bozzo AT gmail.com  {@link http://www.dbfweb.com}
 * @author     Created on 30-Nov-2014
 * @license    GNU/GPL
 */

//-- No direct access
defined('_JEXEC') || die('=;)');


jimport('joomla.application.component.model');

/**
 * Disdetta Model.
 *
 * @package    Receivements
 * @subpackage Models
 */
class ReceivementsModelDisdetta extends JModelLegacy
{
    /**
     * Gets the Data.
     *
     * @return string The greeting to be displayed to the user
     */
    public function getData()
    {
        $guid = JRequest::getVar('guid', '', 'get', 'string');
        $db = JFactory::getDBO();
        $db->setQuery('SELECT p.id, p.nome AS student, c.classe, o.email AS use_email, u.name, u.email, a.data FROM #__receivements_prenotazioni p LEFT JOIN #__receivements_classi c ON (p.id_classe = c.id) LEFT JOIN #__receivements_agenda a ON (p.id_agenda = a.id) LEFT JOIN #__receivements_ore o ON (a.id_ore = o.id) LEFT JOIN #__users u ON (o.id_docente = u.id) WHERE guid = '.$db->Quote($guid));
	$data = $db->loadAssoc();
        return $data;
    }
}
