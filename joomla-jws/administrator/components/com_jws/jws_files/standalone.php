<?php
/**
 * @version		$Id: standalone.php  Sudhi Seshachala $
 * @package		JWS
 * @subpackage	com_jws
 * @copyright	Copyright (C) 2005 - 2010 Hooduku/Plexicloud. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
/* Initialize Joomla framework */
define( '_JEXEC', 1 );
define('JPATH_BASE', dirname(__FILE__) );
define( 'DS', DIRECTORY_SEPARATOR );
/* Required Files */
require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );

/* To use Joomla's Database Class */
require_once ( JPATH_BASE .DS.'libraries'.DS.'joomla'.DS.'factory.php' );
require_once ( JPATH_BASE .DS.'libraries'.DS.'joomla'.DS.'plugin'.DS.'helper.php' );
require_once ( JPATH_BASE .DS.'libraries'.DS.'joomla'.DS.'plugin'.DS.'plugin.php' );
require_once ( JPATH_BASE .DS.'libraries'.DS.'joomla'.DS.'html'.DS.'parameter.php');
require_once(JPATH_BASE .DS.'libraries'.DS.'joomla'.DS.'import.php');
jimport('joomla.filesystem.file');

if(JFile::exists(JPATH_BASE .DS.'components'.DS.'com_community'.DS.'libraries'.DS.'core.php')) { 
	require_once ( JPATH_BASE .DS.'components'.DS.'com_community'.DS.'libraries'.DS.'core.php' );
	define('JOM_SOCIAL',1);
} else {
	define('JOM_SOCIAL',0);
}

/* Create the Application */
$mainframe =& JFactory::getApplication('site');


?>