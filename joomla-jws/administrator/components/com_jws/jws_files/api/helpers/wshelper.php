<?php
/**
 * @version		$Id: wshelper.php  Sudhi Seshachala $
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
class WebserviceHelper {
	
	public function constructResult($errorCode,$errorMsg,$method) {
		$xmlStr = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n
		<system>\n
			<method>".$method."</method>\n
				   <error>\n
				   		<error_code>".$errorCode."</error_code>\n
						<error_message>".$errorMsg."</error_message>\n
					</error>\n";
		$xmlStr .= "<data>none</data>\n";
		$xmlStr .= "</system>";

		$response = new SimpleXMLElement($xmlStr);
		return $response;
		
	}
	
	public function constructResultData($errorCode,$errorMsg,$data,$method) {
		
		$xmlStr = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n
		<system>\n
			   <method>".$method."</method>\n
			   <error>\n
			   		<error_code>".$errorCode."</error_code>\n
					<error_message>".$errorMsg."</error_message>\n
				</error>\n";
			$xmlStr .="<data>".htmlspecialchars($data)."</data>";
		$xmlStr .= "</system>";
		
		$response = new SimpleXMLElement($xmlStr);
		return $response;
		
	}
	

}
?>