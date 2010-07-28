<?php
/**
 * @version		$Id: user.php  Sudhi Seshachala $
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

    class Api_User {
    	
		function Api_User() {
			
		}
		
		public function requestAPIKey() {
			if(isset($_GET['userid']))
					$userid = $_GET['userid'];
				else if(isset($_POST['userid'])){
					$userid = $_POST['userid'];
				}
			if(!isset($userid))
			{
				$result = WebserviceHelper::constructResult(500,'One of the required fields is not set', "getBaseUserById");
                return $result->asXML();
			}
				$db = & JFactory::getDBO();
				$sql = "select * from #__users_apikey where userid=".$userid;
				$db->setQuery($sql);
				$my=$db->loadObject();
				
			if(!isset($my)) { 
				$my->apikey = $this->_create_guid();
				$my->secretkey = $this->_create_guid();
				$db = & JFactory::getDBO();
				$query = "insert into #__users_apikey(userid, apikey,secretkey) values(".$userid.",'".$my->apikey."','".$my->secretkey."')";
				$db->setQuery($query);
				$db->query();
				
				$result = WebserviceHelper::constructResult(500,'APIKey and secret key generated',"requestAPIKey");
				return $result->asXML() ;
			} else {
				$result = WebserviceHelper::constructResult(500,'APIKey and secret key already generated',"requestAPIKey");
				return $result->asXML() ;
			}
		
		}
		
		 /**
         * A temporary method of generating GUIDs of the correct format for our DB.
         * @return String contianing a GUID in the format: aaaaaaaa-bbbb-cccc-dddd-eeeeeeeeeeee
         *
         * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
         * All Rights Reserved.
         * Contributor(s): ______________________________________..
         */
        private function _create_guid()
        {
                $microTime = microtime();
                list($a_dec, $a_sec) = explode(" ", $microTime);

                $dec_hex = dechex($a_dec* 1000000);

                $sec_hex = dechex($a_sec);

                $this->ensure_length($dec_hex, 5);
                $this->ensure_length($sec_hex, 6);

                $guid = "";
                $guid .= $dec_hex;
                $guid .= $this->create_guid_section(3);
                $guid .= '-';
                $guid .= $this->create_guid_section(4);
                $guid .= '-';
                $guid .= $this->create_guid_section(4);
                $guid .= '-';
                $guid .= $this->create_guid_section(4);
                $guid .= '-';
                $guid .= $sec_hex;
                $guid .= $this->create_guid_section(6);

                return $guid;

        }

		 private function create_guid_section($characters)
        {
                $return = "";
                for($i=0; $i<$characters; $i++)
                {
                        $return .= dechex(mt_rand(0,15));
                }
                return $return;
        }

        private function ensure_length(&$string, $length)
        {
                $strlen = strlen($string);
                if($strlen < $length)
                {
                        $string = str_pad($string,$length,"0");
                }
                else if($strlen > $length)
                {
                        $string = substr($string, 0, $length);
                }
        }
		
   		 public function getBaseUsers($apikey, $secret) {
            $auth = new Auth($apikey, $secret);
            $db = & Jfactory::getDBO();
            if($auth->isAuthenticate()) {
                if(isset($_GET['userid']))
                    $userid = $_GET['userid'];
                else if(isset($_POST['userid'])){
                    $userid = $_POST['userid'];
                }
            } else {
                $result = WebserviceHelper::constructResult(500,'APIKey and secret key does not match', "getUserById");
                return $result->asXML();
            }
           $sql = "select userid from jos_users_apikey where apikey = '$apikey' AND secretkey='$secret'";
           $db->setQuery($sql);
		   $user_id = $db->loadResult();
			
            //$user = & JFactory::getUser($userid);
            $sql = "select id, name, username, email, usertype, block, registerDate, lastvisitDate from #__users where id=".$user_id;
			$db->setQuery($sql);
			$user = $db->loadObject();
			if($user->usertype == 'Super Administrator')
			{
	            $sql = "select id, name, username, email, usertype, block, registerDate, lastvisitDate from #__users";
	            $db->setQuery($sql);
	            $all_user = $db->loadObjectList();
	            //$users[0] = (object) array( 'editable' => 'true');
	            //$x=1;
	            foreach($all_user as $key => $values)
	            {
	            	$all_user[$key]->editable = 'true';
	            	if($values->id == $user_id)
	            	{
	            		$all_user[$key]->block_val = 'false';
	            	}else{
	            		if($values->block)
	            			$all_user[$key]->unblock = 'true';
	            		else	
	            			$all_user[$key]->block_val = 'true';
	            	}
	            	//$users[$x] = $values;
	            	//$x++;
	            }
	           
	            $string = XMLSerializer::generateValidXmlFromArray($all_user, 'users_data');
			}else{
				$user->editable = 'true';
				$user->block_val = 'false';
				$string = XMLSerializer::generateValidXmlFromObj($user, 'user_data');
			}
             $result = WebserviceHelper::constructResultData(200,'User Object Loaded', $string, "getBaseUsers");
            return $result->asXML();
        }
        
  	  	public function updateUserInfo($apikey, $secret) {
            $auth = new Auth($apikey, $secret);
            $db = & Jfactory::getDBO();
            if($auth->isAuthenticate()) {
                if(isset($_POST['userid'])){
                    $userid = $_POST['userid'];
                }if(isset($_POST['email'])){
                    $email = $_POST['email'];
                }if(isset($_POST['name'])){
                    $name = $_POST['name'];
                }
            } else {
                $result = WebserviceHelper::constructResult(500,'APIKey and secret key does not match', "updateUserInfo");
                return $result->asXML();
            }
            if(!isset($userid) || !isset($email) || !isset($name))
            {
            	$result = WebserviceHelper::constructResult(500,'One of the required fields is not set', "updateUserInfo");
                return $result->asXML();
            }
            	
           $sql = "select userid from jos_users_apikey where apikey = '$apikey' AND secretkey='$secret'";
           $db->setQuery($sql);
		   $user_id = $db->loadResult();
			
            //$user = & JFactory::getUser($userid);
            $sql = "select * from #__users where id=".$user_id;
			$db->setQuery($sql);
			$user = $db->loadObject();
			
			if($user->usertype == 'Super Administrator' || $user_id == $userid)
			{
	            $sql = "update #__users set name = '$name', email = '$email' where id=".$userid;
	            //echo $sql;
	            $db->setQuery($sql);
		   		$db->query();
				if($db->getErrorNum()) {
					$result = WebserviceHelper::constructResult(500,'Update Failed', "updateUserInfo");
             		return $result->asXML();
	 			}else{
	 				$result = WebserviceHelper::constructResult(200,'Update Successfull', "updateUserInfo");
             		return $result->asXML();
	 			}
			}else{
				$result = WebserviceHelper::constructResult(500,'You do not have permissions to update', "updateUserInfo");
             	return $result->asXML();
			}
        }
 	   public function blockUser($apikey, $secret) {
            $auth = new Auth($apikey, $secret);
            $db = & Jfactory::getDBO();
            if($auth->isAuthenticate()) {
               if(isset($_POST['userid'])){
                    $userid = $_POST['userid'];
                }if(isset($_POST['action'])){
                    $action = $_POST['action'];
                }
            } else {
                $result = WebserviceHelper::constructResult(500,'APIKey and secret key does not match', "blockUser");
                return $result->asXML();
            }
 	  		if(!isset($userid) || !isset($action))
            {
            	$result = WebserviceHelper::constructResult(500,'One of the required fields is not set', "blockUser");
                return $result->asXML();
            }
           $sql = "select userid from jos_users_apikey where apikey = '$apikey' AND secretkey='$secret'";
           $db->setQuery($sql);
		   $user_id = $db->loadResult();
		   
		   $sql = "select * from #__users where id=".$user_id;
		   $db->setQuery($sql);
		   $user_logged_in = $db->loadObject();
		   if($user_logged_in->usertype == 'Super Administrator')
		   {
			   if($action == 'block')
			   {
			   		$action_val = 1;
			   }elseif($action == 'unblock')
			   {
			   		$action_val = 0;
			   }else{
			   	$result = WebserviceHelper::constructResult(500,'Invalid field value specified', "blockUser");
             	return $result->asXML();
			   }
			   $sql = "update #__users set block = $action_val where id=".$userid;
	           $db->setQuery($sql);
		   	   $db->query();
			   if($db->getErrorNum()) {
					$result = WebserviceHelper::constructResult(500,'Block/Unblock Failed', "blockUser");
             		return $result->asXML();
	 			}else{
	 				$result = WebserviceHelper::constructResult(200,'Block/Unblock Successfull', "blockUser");
             		return $result->asXML();
	 			}
		   }else{
		   		$result = WebserviceHelper::constructResult(500,'You do not have permissions to block', "blockUser");
             	return $result->asXML();
		   }
        }
		public function getBaseUserById($apikey, $secret) {
			$auth = new Auth($apikey, $secret);
			$db = & Jfactory::getDBO();
			if($auth->isAuthenticate()) {
				if(isset($_GET['userid']))
					$userid = $_GET['userid'];
				else if(isset($_POST['userid'])){
					$userid = $_POST['userid'];
				}
			} else {
				$result = WebserviceHelper::constructResult(500,'APIKey and secret key does not match', "getBaseUserById");
				return $result->asXML();
			}
			if(!isset($userid))
			{
				$result = WebserviceHelper::constructResult(500,'One of the required fields is not set', "getBaseUserById");
                return $result->asXML();
			}
			$sql = "select userid from jos_users_apikey where apikey = '$apikey' AND secretkey='$secret'";
	        $db->setQuery($sql);
			$user_id = $db->loadResult();
			//$user = & JFactory::getUser($userid);
			$sql = "select id, name, username, email, usertype, block, registerDate, lastvisitDate from #__users where id=".$userid;
			$db->setQuery($sql);
			$user = $db->loadObject();
			
			$sql = "select id, name, username, email, usertype, block, registerDate, lastvisitDate from #__users where id=".$user_id;
			$db->setQuery($sql);
			$user_logged_in = $db->loadObject();
			if($user_logged_in->usertype == 'Super Administrator')
			{
				$user->editable = 'true';
				if($user->id == $user_id)
	            	{
	            		$user->block_val = 'false';
	            		$user->unblock = 'false';
	            	}else{
	            		if($user->block){
	            			$user->unblock = 'true';
	            			$user->block_val = 'false';
	            		}
	            		else{	
	            			$user->block_val = 'true';
	            			$user->unblock = 'false';
	            		}
	            	}
			}else{
				$user->editable = 'true';
				$user->block_val = 'false';
				$user->unblock = 'false';
			}
			$string = XMLSerializer::generateValidXmlFromObj($user, 'user_data');
				
			$result = WebserviceHelper::constructResultData(200,'User Object Loaded', $string, "getBaseUserById");
			return $result->asXML();
		}
		
		public function getBaseUserByUsername($apikey, $secret) {
			$auth = new Auth($apikey, $secret);
	
			if($auth->isAuthenticate()) {
				if(isset($_GET['username']))
					$username = $_GET['username'];
				else if(isset($_POST['username'])){
					$username = $_POST['username'];
				}
			} else {
				$result = WebserviceHelper::constructResult(500,'APIKey and secret key does not match', "getUserById");
				return $result->asXML();
			}
			if(!isset($username))
			{
				$result = WebserviceHelper::constructResult(500,'One of the required fields is not set', "getBaseUserById");
                return $result->asXML();
			}
			$db = & Jfactory::getDBO();
			//$sql = "select userid from jos_users_apikey where apikey = '$apikey' AND secretkey='$secret'";
			$sql = "SELECT u.username FROM jos_users u LEFT JOIN `jos_users_apikey` uapi ON ( u.id = uapi.userid )".
						" WHERE `apikey` = '$apikey' AND `secretkey` = '$secret'";
	        $db->setQuery($sql);
			$user_name = $db->loadResult();
			
			$sql = "select id from #__users where username='".$username."'";
			$db->setQuery($sql);
			$userid = $db->loadResult();
			
			//$user = & JFactory::getUser($userid);
			$sql = "select * from #__users where id=".$userid;
			$db->setQuery($sql);
			$user = $db->loadObject();
			$string = XMLSerializer::generateValidXmlFromObj($user, 'user_data');
				
			$result = WebserviceHelper::constructResultData(200,'User Object Loaded', $string, "getUserById");
			return $result->asXML();
		}

		
		public function getUserById($apikey, $secret) {
			
			if(!JOM_SOCIAL) {
				return $this->getBaseUserById($apikey, $secret);	
			}
			$auth = new Auth($apikey, $secret);
	
			if($auth->isAuthenticate()) {
				if(isset($_GET['userid']))
					$userid = $_GET['userid'];
				else if(isset($_POST['userid'])){
					$userid = $_POST['userid'];
				}
			} else {
				$result = WebserviceHelper::constructResult(500,'APIKey and secret key does not match', "getUserById");
				return $result->asXML();
			}
			if(!isset($userid))
			{
				$result = WebserviceHelper::constructResult(500,'One of the required fields is not set', "getBaseUserById");
                return $result->asXML();
			}
			
		
			$db = & JFactory::getDBO();
			$sql =  'SELECT `id` , `name` , `username` , `email` ,  `usertype` ,
					STATUS , points, avatar, thumb, friendcount
					FROM jos_users, jos_community_users
					WHERE jos_users.id = jos_community_users.userid and jos_users.id='.$userid;
					//echo $sql;
			$db->setQuery($sql);
			$user = $db->loadObject();
			
			if($db->getErrorNum())
			{
				//JError::raiseError( 500, $db->stderr());
				$result = WebserviceHelper::constructResult(500,$db->stderr(), "getUserById");
				return $result->asXML();
			} else {
				
				$user->thumb = 'http://'.$_SERVER["SERVER_NAME"].'/'.$user->thumb;
				$user->avatar = 'http://'.$_SERVER["SERVER_NAME"].'/'.$user->avatar;
				$fields = $this->_getUserFields($userid);
			
				$this->_renderFields($fields, $user);
				//print_r($user);
				//die();
				$string = XMLSerializer::generateValidXmlFromObj($user, 'user_data');
				
				$result = WebserviceHelper::constructResultData(200,'User Object Loaded', $string, "getUserById");
				return $result->asXML();
			}
			
			
		
		}
		
		
		private function _renderFields($fields, $user) {
			
			foreach($fields as $fieldObj) {
				$name = str_replace("/", "Or", $fieldObj->name); // for name with "slash"
				$name = str_replace(" ", "_", $name); // for name with "space"
				$user -> {$name} = $fieldObj->field_value;
			}
			
		}
		
		
		private function _getUserFields($user,$type='id') {
			if($type== 'id') { 
				$db = & JFactory::getDBO();
				$sql = 'SELECT  f.name, v.value AS field_value
						 FROM `jos_community_fields` f
						inner join jos_community_fields_values v  on f.id = v.field_id where v.user_id='.$user;
				
			} else if($type == 'username'){
				$db = & JFactory::getDBO();
				$sql = "SELECT  f.name, v.value AS field_value
						 FROM `jos_community_fields` f
						inner join jos_community_fields_values v  on f.id = v.field_id where v.user_id in 
						(select id from jos_users where username ='".$user."')";
			}
			$db->setQuery($sql);
			$objList =  $db->loadObjectList();
			if($db->getErrorNum())
			{
				//JError::raiseError( 500, $db->stderr());
				$result = WebserviceHelper::constructResult(500,$db->stderr(), "_getUserFields");
				return $result->asXML();
			} else {
				return $objList;
			}
					
		}
		
		
		
		public function getUserByUsername($apikey, $secret) {
			
			if(!JOM_SOCIAL) {
				return $this->getBaseUserByUsername($apikey, $secret);	
			}
			$auth = new Auth($apikey, $secret);
	
			if($auth->isAuthenticate()) {
				if(isset($_GET['username']))
					$username = $_GET['username'];
				else if(isset($_POST['username'])){
					$username = $_POST['username'];
				}
			} else {
				$result = WSHelper::constructResult(500,'APIKey and secret key does not match', "getUserByName");
				return $result->asXML();
			}
			if(!isset($username))
			{
				$result = WebserviceHelper::constructResult(500,'One of the required fields is not set', "getBaseUserById");
                return $result->asXML();
			}
			$db = & JFactory::getDBO();
			$sql =  "SELECT `id` , `name` , `username` , `email` , `usertype` ,
					STATUS , points, avatar, thumb, friendcount
					FROM jos_users, jos_community_users
					WHERE jos_users.id = jos_community_users.userid and jos_users.username='".$username."'";
			$db->setQuery($sql);
			$user = $db->loadObject();
			//$user->thumb = 'http://'.$_SERVER["SERVER_NAME"].'/'.$user->thumb;
			//$user->avatar = 'http://'.$_SERVER["SERVER_NAME"].'/'.$user->avatar;
			//$user->fields = $this->_getUserFields($username, 'username') ;
			if($db->getErrorNum())
			{
				$result = WebserviceHelper::constructResult(500,$db->stderr(), "getUserByName");
				return $result->asXML();
			} else {
				
				$user->thumb = 'http://'.$_SERVER["SERVER_NAME"].'/'.$user->thumb;
				$user->avatar = 'http://'.$_SERVER["SERVER_NAME"].'/'.$user->avatar;
				$fields = $this->_getUserFields($username, 'username');
			
				$this->_renderFields($fields, $user);
				//print_r($user);
				//die();
				$string = XMLSerializer::generateValidXmlFromObj($user, 'user_data');
				
				$result = WebserviceHelper::constructResultData(200,'User Object Loaded', $string, "getUserByName");
				return $result->asXML();
			}

			
		}
    }
?>