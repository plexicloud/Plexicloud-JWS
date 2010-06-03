<?php

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
			$xmlStr .="<data>".$data."</data>";
		$xmlStr .= "</system>";
		
		$response = new SimpleXMLElement($xmlStr);
		return $response;
		
	}
	

}
?>