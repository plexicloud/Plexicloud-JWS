<?php

include '../standalone.php';

include 'helpers/wshelper.php';
include 'helpers/xmlserializer.php';

include 'classes/user.php';
include 'classes/auth.php';
include 'classes/article.php';

//print_r($mainframe);
$db = & JFactory::getDBO();

//
if(isset($_POST['method']) && $_POST['method'] != "") { 
	$method = $_POST['method'];
} 
elseif(isset($_GET['method']) && $_GET['method'] != "") { 
	$method = $_GET['method'];
}
else{
	$method  = "";
}

if(isset($_POST['apikey']) && $_POST['apikey'] != "") {
	$apikey = $_POST['apikey'];
} 
elseif(isset($_GET['apikey']) && $_GET['apikey'] != "") {
	$apikey = $_GET['apikey'];
}
else{
	$apikey = "";
}

if(isset($_POST['secret']) && $_POST['secret'] != "") {
	$secret = $_POST['secret'];
}
elseif(isset($_GET['secret']) && $_GET['secret'] != "") {
	$secret = $_GET['secret'];
}
else{
	$secret = "";
}


if(empty($method) || $method == "") {
	header ("content-type: text/xml");
	$result = WebserviceHelper::constructResult(500,'No Method defined',  'Method is not defined');
	print $result->asXML(); 
	exit;
}

if($method == 'requestAPIKey' ) {
	header ("content-type: text/xml");
	$apiUser = new Api_User();
	
	print $apiUser->requestAPIKey();
	exit;
}
if(empty($apikey) || $apikey =="") {
	header ("content-type: text/xml");
	$result = WebserviceHelper::constructResult(500,'No api key defined',  $method);
	print $result->asXML(); 
	exit;
}

if(empty($secret) || $secret =="") {
	header ("content-type: text/xml");
	$result = WebserviceHelper::constructResult(500,'No secret defined', $method); 
	print $result->asXML();
	exit;
}

switch($method) {
	
	case 'user_authenticate': 
		header ("content-type: text/xml");
		$auth = new Auth();
 		print $auth->authenticate($apikey,$secret);
		break;
		
	case 'getUserById':
		header ("content-type: text/xml");
		$apiUser = new Api_User();
		print $apiUser->getUserById($apikey, $secret);
		break;
	
	case 'getUserByUsername':
		header ("content-type: text/xml");
		$apiUser = new Api_User();
		print $apiUser->getUserByUsername($apikey, $secret);
		break;
	
	case 'getBaseUserById':
		header ("content-type: text/xml");
		$apiUser = new Api_User();
		print $apiUser->getBaseUserById($apikey, $secret);
		break;
	
	case 'getBaseUsers':
		header ("content-type: text/xml");
		$apiUser = new Api_User();
		print $apiUser->getBaseUsers($apikey, $secret);
		break;
		
	case 'getBaseUserByUsername':
		header ("content-type: text/xml");
		$apiUser = new Api_User();
		print $apiUser->getBaseUserByUsername($apikey, $secret);
		break;
		
	case 'getCategories':
		header ("content-type: text/xml");
		$article = new Article();
		print $article->getCategories();
		break;
		
	case 'getArticles':
		header ("content-type: text/xml");
		$article = new Article();
		print $article->getArticles();
		break;
		
	default: 
		header ("content-type: text/xml");
		$result = WebserviceHelper::constructResult(500,'Not a valid Method','Unknown');
		print $result->asXML(); 
		break;
}



?>