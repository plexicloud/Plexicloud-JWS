<?php
/**
 * @version		$Id: jws.php  Sudhi Seshachala $
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