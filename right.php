<!-- setting right panel -->
<div data-role="panel" id="rightpanelSetting" data-position="right" data-display="overlay">
<form name="setting" method="post" action="setting.php">
<ul data-role="listview" data-divider-theme="b" data-inset="false">
<li>
	<table><tr>
		<td><label for="maxCount" style="margin:0;">Max Count :</label></td>
		<td class="td40"><input type="text" name="maxCount" id="maxCount" value="<?=$_set['max']?>" /></td>
	</tr></table>
</li>
<li>
	<table><tr>
		<td><label for="playCount" style="margin:0;">Play Count :</label></td>
		<td class="td40"><input type="text" name="playCount" id="playCount" value="<?=$_set['playcount']?>" /></td>
	</tr></table>
</li>
<li>
	<table><tr>
		<td><label for="autoPlay" style="margin:0;">Auto Play :</label></td>
		<td class="td40">
		<input type="checkbox" data-role="flipswitch" name="autoPlay" id="autoPlay" data-on-text="on" data-off-text="off" data-wrapper-class="custom-label-flipswitch" onchange="changeAuto();" <?=(empty($_set['autoplay']) ? "" : "checked=\"true\"");?> />
	</tr></table>
</li>
<li>
	<table><tr>
		<td><label for="autoPass" style="margin:0;">Auto Pass :</label></td>
		<td class="td40">
		<input type="checkbox" data-role="flipswitch" name="autoPass" id="autoPass" data-on-text="on" data-off-text="off" data-wrapper-class="custom-label-flipswitch" onchange="changeAutoPass();" <?=(empty($_set['autopass']) ? "" : "checked=\"true\"");?> />
	</tr></table>
</li>
<li>
	<table><tr>
		<td><label for="defaultMode" style="margin:0;">Default Mode :</label></td>
		<td class="td40">
		<input type="checkbox" data-role="flipswitch" name="defaultMode" id="defaultMode" data-on-text="full" data-off-text="words" data-wrapper-class="custom-label-flipswitch" onchange="changeMode(this);" <?=($_set['defaultmode'] == "words" ? "" : "checked=\"checked\"");?> />
	</tr></table>
</li>

<li>
	<table><tr>
		<td class="td50"><a href="#" class="ui-btn ui-btn-icon-left ui-icon-refresh">Reset</a></td>
		<td class="td50"><a href="#" rel="external" class="ui-btn ui-btn-icon-left ui-icon-check">Set</a></td>
	</tr></table>
</li>
</ul>
</form>
</div><!-- setting right panel -->
