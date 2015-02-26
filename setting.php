<?
	require_once(getcwd()."/common.php");
	checkSession("uid");
	checkSession("dir", "logout.php");
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>SETTING</title>
	<!-- link rel="shortcut icon" href="demos/favicon.ico" -->
	<link rel="stylesheet" href="/css/jquery.mobile-1.4.5.min.css">
	<link rel="stylesheet" href="/css/style.css">
	<style>
#setting table { width: 100%; }
#setting table .title { width: 8em; }
	</style>
	<script language="javascript" src="/js/jquery.js"></script>
	<script language="javascript" src="/js/jquery.mobile-1.4.5.min.js"></script>
	<script>
function save() {
}
	</script>
</head>
<body>
<div id="summary" data-role="page" data-theme="b" class="ui-page-theme-b">
	<div data-role="header" class="header">
		<h1>SETTING</h1>
		<a href="#leftpanel" class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-bars ui-nodisc-icon ui-alt-icon ui-btn-left">Menu</a>
	</div><!-- /header -->

	<div role="main" class="ui-content">
		<form name="setting" id="setting" method="post" action="setting_save.php">

		<ul data-role="listview" data-divider-theme="b" data-inset="false">
		<li>
			<table><tr>
			<td class="title"><label for="maxCount" style="margin:0;">Max Count :</label></td>
			<td><input type="text" name="maxCount" id="maxCount" value="<?=(empty($_SESSION['set'][0]) ? "3" : $_SESSION['set'][0]);?>" /></td>
			</tr></table>
		</li>
		<li>
			<table><tr>
			<td class="title"><label for="playCount" style="margin:0;">Play Count :</label></td>
			<td><input type="text" name="playCount" id="playCount" value="<?=(empty($_SESSION['set'][4]) ? "1" : $_SESSION['set'][4]);?>" /></td>
			</tr></table>
		</li>
		<li>
			<table><tr>
			<td class="title"><label for="autoPlay" style="margin:0;">Auto Play :</label></td>
			<td><input type="checkbox" data-role="flipswitch" name="autoPlay" id="autoPlay" data-on-text="on" data-off-text="off" data-wrapper-class="custom-label-flipswitch" onchange="changeAuto();" <?=(empty($_SESSION['set'][1]) ? "" : "checked=\"true\"");?> /></td>
			</tr></table>
		</li>
		<li>
			<table><tr>
			<td class="title"><label for="autoPass" style="margin:0;">Auto Pass :</label></td>
			<td><input type="checkbox" data-role="flipswitch" name="autoPass" id="autoPass" data-on-text="on" data-off-text="off" data-wrapper-class="custom-label-flipswitch" onchange="changeAutoPass();" <?=(empty($_SESSION['set'][2]) ? "" : "checked=\"true\"");?> /></td>
			</tr></table>
		</li>
		<li>
			<table><tr>
			<td class="title"><label for="defaultMode" style="margin:0;">Default Mode :</label>
			<td><input type="checkbox" data-role="flipswitch" name="defaultMode" id="defaultMode" data-on-text="full" data-off-text="words" data-wrapper-class="custom-label-flipswitch" onchange="changeMode(this);" <?=($_SESSION['set'][3] == "words" ? "" : "checked=\"checked\"");?> />
			</tr></table>
		</li>

		<li>
			<table><tr>
				<td style="width:50%;"><a href="summary.php" rel="external" class="ui-btn ui-btn-icon-left ui-icon-delete">Cancle</a></td>
				<td style="width:50%;"><a href="#" class="ui-btn ui-btn-icon-left ui-icon-check" onclick="save();">Set</a></td>
			</tr></table>
		</li>
		</ul>
		</form>
	</div><!-- content -->

	<? require_once(getcwd()."/left.php"); ?>
</div><!-- page -->
</body>
</html>
