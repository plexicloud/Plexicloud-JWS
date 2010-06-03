<?php
/**
 * @package	JomSocial
 * @subpackage Core 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license http://www.jomsocial.com Copyrighted Commercial Software
 */
defined('_JEXEC') or die();
?>
<div class="ctitle"><h2><?php echo JText::_('CC EDIT PREFERENCES'); ?></h2></div>
<form method="post" action="<?php echo CRoute::getURI();?>" name="saveProfile">

<table class="formtable" cellspacing="1" cellpadding="0">
<tr>
	<td class="key" style="width: 300px;">
		<label for="activityLimit" class="label title">
			<?php echo JText::_('CC PREFERENCES ACTIVITY LIMIT'); ?>
		</label>
	</td>
	<td class="value">
		<input type="text" id="activityLimit" name="activityLimit" value="<?php echo $params->get('activityLimit', 20 );?>" size="5" />
	</td>
</tr>

<?php 
$config = & CFactory::getConfig();

if(empty($user->_apikey) && empty($user->_secretkey)) {
	if($config->get('apisupport') == 1) {
	?>
	<tr>
	<td class="key" style="width: 300px;">
		<label for=requestapikey" class="label title">
			<a href="<?php 
					$content = file_get_contents(JURI::base().'api/index.php?method=requestAPIKey&userid='.$user->id);
				if($content)
			echo CRoute::_('index.php?option=com_community&view=profile&task=preferences');
			?>"><?php echo JText::_('CC REQUEST API KEY'); ?></a>
		</label>
	</td>
	
</tr>
	<?php
	}
}
 else { 
 	if($config->get('apisupport') == 1) {
?>
<tr>
	<td class="key" style="width: 300px;">
		<label for="activityLimit" class="label title">
			<?php echo JText::_('CC APIKEY'); ?>
		</label>
	</td>
	<td class="value">
		<input type="text" id="apikey" name="apikey" readonly="true" value="<?php echo $user->_apikey;?>" size="35" />
	</td>
</tr>

<tr>
	<td class="key" style="width: 300px;">
		<label for="activityLimit" class="label title">
			<?php echo JText::_('CC SECRET KEY'); ?>
		</label>
	</td>
	<td class="value">
		<input type="text" id="secretkey" name="secretkey" readonly="true" value="<?php echo $user->_secretkey;?>" size="35" />
	</td>
</tr>
<?php } 
 }
?>
<tr>
	<td class="key"></td>
	<td class="value">
		<input type="submit" class="button" value="<?php echo JText::_('CC BUTTON SAVE'); ?>" />
	</td>
</tr>
</table>

</form>