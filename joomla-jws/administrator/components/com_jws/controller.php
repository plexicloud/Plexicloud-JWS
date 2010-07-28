<?php
/**
 * @version		$Id: controller.php  Sudhi Seshachala $
 * @package		JWS
 * @subpackage	Admin
 * @copyright	Copyright (C) 2005 - 2010 Hooduku/Plexicloud. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.controller' );
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'helper.php' );

/**
 * jws Controller
 *
 * @package Joomla
 * @subpackage jws
 */
class JwsController extends JController {
    /**
     * Constructor
     * @access private
     * @subpackage jws
     */
    function __construct() {
        //Get View
        if(JRequest::getCmd('view') == '') {
            JRequest::setVar('view', 'default');
        }
        $this->item_type = 'Default';
        parent::__construct();
    }
	
	function installWS() {
		//Read the script file and execute
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');	
		$sql = JFile::read(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jws'.DS.'jws_files'.DS.'scripts'.DS.'database'.DS.'ws_create.sql');
		
		$db= &JFactory::getDBO();
		$db->setQuery($sql);
		
		$db->query();
		echo "<br /> DB tables installed..<br />";
		
		//backup files.
		echo "<br /> Backing up files..Renaming...<br />";
		JFile::move(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_users'.DS.'views'.DS.'user'.DS.'view.html.php', 
					JPATH_ADMINISTRATOR.DS.'components'.DS.'com_users'.DS.'views'.DS.'user'.DS.'view.html.php.jws');
					
		JFile::move(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_users'.DS.'views'.DS.'user'.DS.'tmpl'.DS.'form.php',
					JPATH_ADMINISTRATOR.DS.'components'.DS.'com_users'.DS.'views'.DS.'user'.DS.'tmpl'.DS.'form.php.jws');
		
		JFile::move(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_users'.DS.'controller.php',
					JPATH_ADMINISTRATOR.DS.'components'.DS.'com_users'.DS.'controller.php.jws');
					
		echo '<br /> Copying Files...<br/>';
		try {
		JFile::copy(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jws'.DS.'jws_files'.DS.'administrator'.DS.'components'.DS.'com_users'.DS.'views'.DS.'user'.DS.'view.html.php', 
					JPATH_ADMINISTRATOR.DS.'components'.DS.'com_users'.DS.'views'.DS.'user'.DS.'view.html.php');
		
		JFile::copy(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jws'.DS.'jws_files'.DS.'administrator'.DS.'components'.DS.'com_users'.DS.'views'.DS.'tmpl'.DS.'form.php',
					JPATH_ADMINISTRATOR.DS.'components'.DS.'com_users'.DS.'views'.DS.'user'.DS.'tmpl'.DS.'form.php');
		JFile::copy(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jws'.DS.'jws_files'.DS.'administrator'.DS.'components'.DS.'com_users'.DS.'controller.php',
					JPATH_ADMINISTRATOR.DS.'components'.DS.'com_users'.DS.'controller.php');
		} catch (exception $e)
		{
			print_r($e);
		}
		
		//JFile::copy(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jws'.DS.'jws_files'.DS.'administrator'.DS.'components'.DS.'com_users'.DS.'controller.php',
			//		JPATH_ADMINISTRATOR.DS.'components'.DS.'com_users'.DS.'controller.php');
					
		JFile::copy(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jws'.DS.'jws_files'.DS.'standalone.php',JPATH_SITE.DS.'standalone.php');
		
		
		JFolder::copy(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jws'.DS.'jws_files'.DS.'api',JPATH_SITE.DS.'api');
		
		$db = & JFactory::getDBO();


		$sql = "select count(id) from #__components where `option`='com_community'";

		$db->setQuery($sql);

		$result = $db->loadResult();

		If($result > 0) {
			echo '<br/> Jom social Installed <br/>';
			echo "<br /> Backing up files..Renaming...<br />";
			JFile::copy(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_community'.DS.'config.xml',
						JPATH_ADMINISTRATOR.DS.'components'.DS.'com_community'.DS.'config.xml.jws');
			
			JFile::copy(JPATH_SITE.DS.'components'.DS.'com_community'.DS.'templates'.DS.'default'.DS.'profile.preferences.php',
						JPATH_SITE.DS.'components'.DS.'com_community'.DS.'templates'.DS.'default'.DS.'profile.preferences.php.jws');
			
			echo '<br /> Copying Files...<br/>';		
			JFile::copy(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jws'.DS.'jws_files'.DS.'administrator'.DS.'components'.DS.'com_community'.DS.'config.xml',
						JPATH_ADMINISTRATOR.DS.'components'.DS.'com_community'.DS.'config.xml');
			JFile::copy(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jws'.DS.'jws_files'.DS.'components'.DS.'com_community'.DS.'templates'.DS.'default'.DS.'profile.preferences.php',
						JPATH_SITE.DS.'components'.DS.'com_community'.DS.'templates'.DS.'default'.DS.'profile.preferences.php');
			
		}
		
		
	}
	
	function unInstallWS() {
		//Read the script file and execute
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');	
		$sql = JFile::read(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jws'.DS.'jws_files'.DS.'scripts'.DS.'database'.DS.'ws_drop.sql');
		
		$db= &Jfactory::getDBO();
		$db->setQuery($sql);
		
		$db->query();
		echo "<br /> DB tables uninstalled..<br />";
		
		//backup files.
		echo "<br /> Deleting Created files..<br />";
		JFile::delete(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_users'.DS.'views'.DS.'user'.DS.'view.html.php');
		
		JFile::move(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_users'.DS.'views'.DS.'user'.DS.'view.html.php.jws', 
					JPATH_ADMINISTRATOR.DS.'components'.DS.'com_users'.DS.'views'.DS.'user'.DS.'view.html.php');
					
		JFile::delete(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_users'.DS.'views'.DS.'user'.DS.'tmpl'.DS.'form.php');
		
		JFile::move(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_users'.DS.'views'.DS.'user'.DS.'tmpl'.DS.'form.php.jws',
					JPATH_ADMINISTRATOR.DS.'components'.DS.'com_users'.DS.'views'.DS.'user'.DS.'tmpl'.DS.'form.php');
		
		JFile::delete(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_users'.DS.'controller.php');
		
		JFile::move(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_users'.DS.'controller.php.jws',
					JPATH_ADMINISTRATOR.DS.'components'.DS.'com_users'.DS.'controller.php');
					
		
		JFile::delete(JPATH_SITE.DS.'standalone.php');
		
		JFolder::delete(JPATH_SITE.DS.'api');
		
	}
	
	
    /**
     * Copy file or folder from source to destination, it can do
     * recursive copy as well and is very smart
     * It recursively creates the dest file or directory path if there weren't exists
     * Situtaions :
     * - Src:/home/test/file.txt ,Dst:/home/test/b ,Result:/home/test/b -> If source was file copy file.txt name with b as name to destination
     * - Src:/home/test/file.txt ,Dst:/home/test/b/ ,Result:/home/test/b/file.txt -> If source was file Creates b directory if does not exsits and copy file.txt into it
     * - Src:/home/test ,Dst:/home/ ,Result:/home/test/** -> If source was directory copy test directory and all of its content into dest     
     * - Src:/home/test/ ,Dst:/home/ ,Result:/home/**-> if source was direcotry copy its content to dest
     * - Src:/home/test ,Dst:/home/test2 ,Result:/home/test2/** -> if source was directoy copy it and its content to dest with test2 as name
     * - Src:/home/test/ ,Dst:/home/test2 ,Result:->/home/test2/** if source was directoy copy it and its content to dest with test2 as name
     * @todo
     *     - Should have rollback technique so it can undo the copy when it wasn't successful
     *  - Auto destination technique should be possible to turn off
     *  - Supporting callback function
     *  - May prevent some issues on shared enviroments : http://us3.php.net/umask
     * @param $source //file or folder
     * @param $dest ///file or folder
     * @param $options //folderPermission,filePermission
     * @return boolean
     */
    function smartCopy($source, $dest, $options=array('folderPermission'=>0755,'filePermission'=>0755))
    {
        $result=false;
       
        if (is_file($source)) {
            if ($dest[strlen($dest)-1]=='/') {
                if (!file_exists($dest)) {
                    cmfcDirectory::makeAll($dest,$options['folderPermission'],true);
                }
                $__dest=$dest."/".basename($source);
            } else {
                $__dest=$dest;
            }
            $result=copy($source, $__dest);
            chmod($__dest,$options['filePermission']);
           
        } elseif(is_dir($source)) {
            if ($dest[strlen($dest)-1]=='/') {
                if ($source[strlen($source)-1]=='/') {
                    //Copy only contents
                } else {
                    //Change parent itself and its contents
                    $dest=$dest.basename($source);
                    @mkdir($dest);
                    chmod($dest,$options['filePermission']);
                }
            } else {
                if ($source[strlen($source)-1]=='/') {
                    //Copy parent directory with new name and all its content
                    @mkdir($dest,$options['folderPermission']);
                    chmod($dest,$options['filePermission']);
                } else {
                    //Copy parent directory with new name and all its content
                    @mkdir($dest,$options['folderPermission']);
                    chmod($dest,$options['filePermission']);
                }
            }

            $dirHandle=opendir($source);
            while($file=readdir($dirHandle))
            {
                if($file!="." && $file!="..")
                {
                     if(!is_dir($source."/".$file)) {
                        $__dest=$dest."/".$file;
                    } else {
                        $__dest=$dest."/".$file;
                    }
                    //echo "$source/$file ||| $__dest<br />";
                    $result=smartCopy($source."/".$file, $__dest, $options);
                }
            }
            closedir($dirHandle);
           
        } else {
            $result=false;
        }
        return $result;
    } 
}
?>