<?php
/**
 * @version		$Id: article.php  Sudhi Seshachala $
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

class Article {
	function Article() {}
	
	public function sync($data) {
		$article  =& JTable::getInstance('content');
		$article->title = $data['title'];
		
		
		// Bind the form fields to the web link table
		if (!$article->bind($data, "published")) {
			echo "500 - Error while binding data";
			return false;
		}

		// sanitise id field
		$article->id = (int) $article->id;

		$isNew = ($article->id < 1);
		if ($isNew)
		{
			$article->created 		= gmdate('Y-m-d H:i:s');
			$article->created_by 	= '1';
		}
		else
		{
			$article->modified 		= gmdate('Y-m-d H:i:s');
			$article->modified_by 	= '1;';
		}
		// Search for the {readmore} tag and split the text up accordingly.
		$text = str_replace('<br>', '<br />', $data['text']);

		$pattern = '#<hr\s+id=("|\')system-readmore("|\')\s*\/*>#i';
		$tagPos	= preg_match($pattern, $text);

		if ($tagPos == 0)	{
			$article->introtext	= $text;
		} else 	{
			list($article->introtext, $article->fulltext) = preg_split($pattern, $text, 2);
		}
		
		// Store the article table to the database
		if (!$article->store()) {
			//$this->setError($this->_db->getErrorMsg());
			echo "500 - Error while saving";
			return false;
		}
		
		echo $article->_db->insertId(). ' '.$article->title. ' updated successfully';
		
		
	}
	
	public function getCategories() {
		
		$db = & JFactory::getDBO();
		$sql = "select jos_categories.id as catid, concat_ws('|',jos_sections.title, #__categories.title) as cat from #__categories left join 
				#__sections on #__sections.id=#__categories.section where #__sections.title is not NULL AND #__categories.published=1";
		$db->setQuery($sql);
		$objectList = $db->loadObjectList();
		
		$string = XMLSerializer::generateValidXmlFromArray($objectList, 'cat_data');
				
		$result = WebserviceHelper::constructResultData(200,'Categories Listed', $string, "getCategories");
		return $result->asXML();
		
		//print_r($objectList);
		
		
	}
	
	public function getSections() {
		$db = & JFactory::getDBO();
		$sql = "select * from #__sections where published =1";
		$db->setQuery($sql);
		$objectList = $db->loadObjectList();
		
		$string = XMLSerializer::generateValidXmlFromArray($objectList, 'section_data');
				
		$result = WebserviceHelper::constructResultData(200,'Sections Listed', $string, "getSections");
		return $result->asXML();
	}
	
	public function getCategoriesBySection() {
		if(isset($_GET['sectionid'])){
			$sectionid = $_GET['sectionid'];
		}
		else if(isset($_POST['sectionid'])){
			$sectionid = $_POST['sectionid'];
		}
		if(!isset($sectionid))
		{
			$result = WebserviceHelper::constructResult(500,'One of the required fields is not set', "getBaseUserById");
            return $result->asXML();
		}
		$db = & JFactory::getDBO();
		$sql = "select * from #__categories where published =1 and section=".$sectionid;
		$db->setQuery($sql);
		$objectList = $db->loadObjectList();
		
		$string = XMLSerializer::generateValidXmlFromArray($objectList, 'sectionid');
				
		$result = WebserviceHelper::constructResultData(200,'Categories Listed', $string, "getCategoriesBySection");
		return $result->asXML();
	}
	public function getArticles()
	{
		$db = & Jfactory::getDBO();
		if(isset($_GET['catid'])){
			$catid = $_GET['catid'];
		}
		else if(isset($_POST['catid'])){
			$catid = $_POST['catid'];
		}
		if(isset($catid)){
			$sql = "select c.id, c.title, c.introtext, c.fulltext, c.catid, c.created_by, c.images, c.urls, c.hits,".
						" u.name, u.username, u.email, u.usertype, cc.title as cat_title".
						" from #__content c left join #__categories cc on (c.catid = cc.id) left join #__users u on (c.created_by = u.id)".
						" where catid = ".$catid;
			$db->setQuery($sql);
			$objectList = $db->loadObjectList();
			if(!empty($objectList))
			{
				$string = XMLSerializer::generateValidXmlFromArray($objectList, 'articles_data');
					
				$result = WebserviceHelper::constructResultData(200,'Articles Listed', $string, "getArticles");
				return $result->asXML();
			}else{
				$result = WebserviceHelper::constructResult(200,'No Articles associated to the category id', "getArticles");
				return $result->asXML();
			}
			
		}else{
			$result = WebserviceHelper::constructResult(500,'No category id specified', "getArticles");
			return $result->asXML();
			
		}
		
	}
	
		public function uploadImage($data) {
		
		//print_r($_FILES);
		$target_path1 = "images/uploads/";
		
		/* Add the original filename to our target path.  
		Result is "uploads/filename.extension" */
		//echo JPATH_ROOT.'/'.$_FILES['media']['name'];
		$target_path = JPATH_ROOT.'/'.$target_path1 . basename( $_FILES['media']['name']); 
		if(move_uploaded_file($_FILES['media']['tmp_name'], $target_path)) {
		   // echo "The file ".  basename( $_FILES['media']['name']). 
		  //  " has been uploaded to ".$target_path;
			echo '200:'.$this->get_tiny_url(JURI::root()."../".$target_path1.basename( $_FILES['media']['name']));
		
		} else{
		    echo '500:'."There was an error uploading the file, please try again!";
		}
		
		

		
	}
	
	//gets the data from a URL  
private function get_tiny_url($url)  
{  
	$ch = curl_init();  
	$timeout = 5;  
	curl_setopt($ch,CURLOPT_URL,'http://tinyurl.com/api-create.php?url='.$url);  
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);  
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);  
	$data = curl_exec($ch);  
	curl_close($ch);  
	return $data;  
}


	
	
}
?>