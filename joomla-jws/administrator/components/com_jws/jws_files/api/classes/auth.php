<?php
   class Auth {
   	
	function Auth() {
		
	}
	
	function isAuthenticate($apikey, $secret) {
		$db = & JFactory::getDBO();
		$sql = "select userid from #__users_apikey where apikey='".$apikey."' and secretkey='".$secret."'";
		$db->setQuery($sql);
		$result = $db->loadResult();
		if($result) {
			return true;
		} else {
			return false;
		}
	}
	
	function authenticate($apikey, $secret) {
		if($this->isAuthenticate($apikey, $secret)) {
			$result = WebserviceHelper::contructResult(200,'API authentication Successful', "user_authenticate");
		} else {
			$result = WebserviceHelper::contructResult(500,'APIKey and secret key does not match',"user_authenticate");
		}
		return $result->asXML();
	}
	
   }
?>