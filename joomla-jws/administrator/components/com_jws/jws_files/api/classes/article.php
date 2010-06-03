<?php


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
		$sql = "select jos_categories.id as catid, concat_ws('-->',jos_sections.title, jos_categories.title) as cat from jos_categories left join 
				jos_sections on jos_sections.id=jos_categories.section where jos_sections.title is not NULL";
		$db->setQuery($sql);
		$objectList = $db->loadObjectList();
		
		$string = XMLSerializer::generateValidXmlFromArray($objectList, 'cat_data');
				
		$result = WebserviceHelper::constructResultData(200,'Categories Listed', $string, "getCategories");
		return $result->asXML();
		
		//print_r($objectList);
		
		
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
						" from jos_content c left join jos_categories cc on (c.catid = cc.id) left join jos_users u on (c.created_by = u.id)".
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
	
}
?>