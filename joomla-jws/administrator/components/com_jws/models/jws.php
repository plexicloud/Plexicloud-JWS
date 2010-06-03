<?php
/**
 * Joomla! 1.5 component jws
 *
 * @version $Id: jws.php 2010-05-27 10:39:17 svn $
 * @author Sudhi
 * @package Joomla
 * @subpackage jws
 * @license GNU/GPL
 *
 * Joomla Webservices
 *
 * This component file was created using the Joomla Component Creator by Not Web Design
 * http://www.notwebdesign.com/joomla_component_creator/
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// Import Joomla! libraries
jimport('joomla.application.component.model');

class JwsModelJws extends JModel {
    function __construct() {
		parent::__construct();
    }
}

function isJSInstalled() {
	$db = & JFactory::getDBO();


	$sql = "select count(id) from #__components where `option`='com_community'";

	$db->setQuery($sql);

	$result = $db->loadResult();
	
	return $result > 0;

}

?>