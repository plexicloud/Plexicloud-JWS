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