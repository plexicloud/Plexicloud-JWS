<?php
defined('_JEXEC') or die('Restricted access');
JToolBarHelper::title(JText::_('jws'), 'generic.png');
JToolBarHelper::preferences('com_jws');

$db = & JFactory::getDBO();


$sql = "select count(id) from #__components where `option`='com_community'";

$db->setQuery($sql);

$result = $db->loadResult();

$str = $result > 0 ? ' Jom Social is Installed' : 'Jom social not installed';

echo $str. ' <br />The following files will be renamed and backed up <br />';
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
$folder = JFolder::folders(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jws'.DS.'jws_files');
print_r($folder);
?>

<script type="text/javascript">
function installWS()
{
xmlhttp=new XMLHttpRequest();
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("jws_status").innerHTML=xmlhttp.responseText;
	"Joomla Webservices Installation Successful";
	//alert(xmlhttp.responseText);
    }
	else {
		
		document.getElementById("jws_status").innerHTML=xmlhttp.status;
		"Error during Installation: Please contact itsupport@plexicloud.com";
	}
  }
xmlhttp.open("GET","index.php?option=com_jws&task=installWS",true);
xmlhttp.send();
}



function uninstallWS()
{
xmlhttp=new XMLHttpRequest();
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("jws_status").innerHTML=xmlhttp.responseText;
	//"Joomla Webservices Installation Successful";
	//alert(xmlhttp.responseText);
    }
	else {
		
		document.getElementById("jws_status").innerHTML=xmlhttp.status;
		//"Error during Installation: Please contact itsupport@plexicloud.com";
	}
  }
xmlhttp.open("GET","index.php?option=com_jws&task=unInstallWS",true);
xmlhttp.send();
}
</script>
<?php if(JFile::exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_users'.DS.'views'.DS.'user'.DS.'view.html.php.jws') && 
						JFile::exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_users'.DS.'views'.DS.'user'.DS.'tmpl'.DS.'form.php.jws') && 
						JFile::exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_users'.DS.'controller.php.jws')){?>
						<button id="iws" name="iws" width="20px" height="30px" onclick="uninstallWS();">Uninstall Web services</button>
						<?php }else{?>
<button id="iws" name="iws" width="20px" height="30px" onclick="installWS();">Install Web services</button>
<?php }?>

<div id="jws_status"></div>
