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

jimport('joomla.application.component.controllerform');

/**
 * Sede controller class.
 */
class ReceivementsControllerImportaDocenti extends JControllerForm
{
	public function getModel($name = 'ImportaDocenti', $prefix = 'ReceivementsModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
        public function go () {
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

                $jinput = JFactory::getApplication()->input;
                $jform = $jinput->get('jform', null, null);
                $input = new JInputFiles();
                $files = $input->get('jform');
                $file=$files['upload_teachers'];
                
                //Import filesystem libraries. Perhaps not necessary, but does not hurt
                jimport('joomla.filesystem.file');
 
                //Clean up filename to get rid of strange characters like spaces etc
                $filename = JFile::makeSafe($file['name']);

                //Set up the source and destination of the file
                $src = $file['tmp_name'];
                $dest = JPATH_COMPONENT . DS . "assets" . DS . "csv" . DS . $filename;

                //get model
                $model = $this->getModel();
                
                //First check if the file has the right extension, we need csv only
                if ( strtolower(JFile::getExt($filename)) == 'csv') {
                        if ( JFile::upload($src, $dest) ) {
                                //do import & redirect
                                if (!$model->doImport($jform, $dest)) 
                                        $this->setRedirect('index.php?option=com_receivements&view=importadocenti&layout=error&msg=COM_RECEIVEMENTS_IMPORT_ERROR&tmpl=component');
                                $this->setRedirect('index.php?option=com_receivements&view=importadocenti&layout=response&tmpl=component');
                        } else {
                                //Redirect and throw an error message
                                $this->setRedirect('index.php?option=com_receivements&view=importadocenti&layout=error&msg=COM_RECEIVEMENTS_NO_UPLOAD_FILE&tmpl=component');
                        }
                } else {
                        //Redirect and notify user file is not right extension
                        $this->setRedirect('index.php?option=com_receivements&view=importadocenti&layout=error&msg=COM_RECEIVEMENTS_WRONG_EXTENSION&tmpl=component');
                }
        }
}