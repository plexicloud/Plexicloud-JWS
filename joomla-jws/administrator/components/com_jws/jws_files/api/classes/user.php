<?php


    class Api_User {
    	
		function Api_User() {
			
		}
		
		public function requestAPIKey() {
			if(isset($_GET['userid']))
					$userid = $_GET['userid'];
				else if(isset($_POST['userid'])){
					$userid = $_POST['userid'];
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
            $auth = new Auth();
            $db = & Jfactory::getDBO();
            if($auth->isAuthenticate($apikey, $secret)) {
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
            $sql = "select * from #__users where id=".$user_id;
			$db->setQuery($sql);
			$user = $db->loadObject();
			if($user->usertype == 'Super Administrator')
			{
	            $sql = "select * from #__users";
	            $db->setQuery($sql);
	            $all_user = $db->loadObjectList();
	            $users[0] = (object) array( 'editable' => 'true');
	            $x=1;
	            foreach($all_user as $values)
	            {
	            	$users[$x] = $values;
	            	$x++;
	            }
	           
	            $string = XMLSerializer::generateValidXmlFromArray($users, 'users_data');
			}else{
				$string = XMLSerializer::generateValidXmlFromObj($user, 'user_data');
			}
             $result = WebserviceHelper::constructResultData(200,'User Object Loaded', $string, "getBaseUsers");
            return $result->asXML();
        }
        
        
		public function getBaseUserById($apikey, $secret) {
			$auth = new Auth();
			$db = & Jfactory::getDBO();
			if($auth->isAuthenticate($apikey, $secret)) {
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
			$sql = "select * from #__users where id=".$user_id;
			$db->setQuery($sql);
			$user = $db->loadObject();
			$string = XMLSerializer::generateValidXmlFromObj($user, 'user_data');
				
			$result = WebserviceHelper::constructResultData(200,'User Object Loaded', $string, "getUserById");
			return $result->asXML();
		}
		
		public function getBaseUserByUsername($apikey, $secret) {
			$auth = new Auth();
	
			if($auth->isAuthenticate($apikey, $secret)) {
				if(isset($_GET['username']))
					$username = $_GET['username'];
				else if(isset($_POST['username'])){
					$username = $_POST['username'];
				}
			} else {
				$result = WebserviceHelper::constructResult(500,'APIKey and secret key does not match', "getUserById");
				return $result->asXML();
			}
			
			$db = & Jfactory::getDBO();
			//$sql = "select userid from jos_users_apikey where apikey = '$apikey' AND secretkey='$secret'";
			$sql = "SELECT u.username FROM jos_users u LEFT JOIN `jos_users_apikey` uapi ON ( u.id = uapi.userid )".
						" WHERE `apikey` = '$apikey' AND `secretkey` = '$secret'";
	        $db->setQuery($sql);
			$user_name = $db->loadResult();
			
			$sql = "select id from #__users where username='".$user_name."'";
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
			$auth = new Auth();
	
			if($auth->isAuthenticate($apikey, $secret)) {
				if(isset($_GET['userid']))
					$userid = $_GET['userid'];
				else if(isset($_POST['userid'])){
					$userid = $_POST['userid'];
				}
			} else {
				$result = WebserviceHelper::constructResult(500,'APIKey and secret key does not match', "getUserById");
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
			$auth = new Auth();
	
			if($auth->isAuthenticate($apikey, $secret)) {
				if(isset($_GET['username']))
					$username = $_GET['username'];
				else if(isset($_POST['username'])){
					$username = $_POST['username'];
				}
			} else {
				$result = WSHelper::constructResult(500,'APIKey and secret key does not match', "getUserByName");
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