<?php

/**
 * @version		$Id: auth.php  Sudhi Seshachala $
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

   class Auth {
   	const METHOD_AUTH = 1;
    const METHOD_POST = 2;
    const METHOD_DELETE = 3;
    const METHOD_PUT = 4;
	
	private $_apikey;
    private $_secret;
	
	function Auth($apikey, $secret) {
		if (!$apikey || !$secret) {
            throw new Cloud_Exception('Please provide valid API credentials');
        }
		$this->_apikey			= $apikey;
        $this->_secret			= $secret;
	}
	
	function isAuthenticate() {
		$db = & JFactory::getDBO();
		$sql = "select userid from #__users_apikey where apikey='".$this->_apikey."' and secretkey='".$this->_secret."'";
		$db->setQuery($sql);
		$result = $db->loadResult();
		if($result) {
			return true;
		} else {
			return false;
		}
	}
	
	function authenticate() {
		
		if($this->isAuthenticate()) {
			$result = WebserviceHelper::constructResult(200,'API authentication Successful', "user_authenticate");
		} else {
			$result = WebserviceHelper::constructResult(500,'APIKey and secret key does not match',"user_authenticate");
		}
		return $result->asXML();
	}
	
   }
?>