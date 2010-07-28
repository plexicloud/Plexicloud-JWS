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

/*
 * Define constants for all pages
 */
define( 'COM_JWS_DIR', 'images'.DS.'jws'.DS );
define( 'COM_JWS_BASE', JPATH_ROOT.DS.COM_JWS_DIR );
define( 'COM_JWS_BASEURL', JURI::root().str_replace( DS, '/', COM_JWS_DIR ));

// Require the base controller
require_once JPATH_COMPONENT.DS.'controller.php';

// Require the base controller
require_once JPATH_COMPONENT.DS.'helpers'.DS.'helper.php';

// Initialize the controller
$controller = new JwsController( );

// Perform the Request task
$controller->execute( JRequest::getCmd('task'));
$controller->redirect();
?>