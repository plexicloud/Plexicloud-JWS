<?php
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