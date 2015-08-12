<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>DICTATION : Change Password</title>
	<link rel="shortcut icon" href="/images/icon.ico">
	<link rel="stylesheet" href="/css/jquery.mobile-1.4.5.min.css">
	<link rel="stylesheet" href="/css/style.css">
<style>
.title { width: 8em; }
</style>
	<script language="javascript" src="/js/jquery.js"></script>
	<script language="javascript" src="/js/jquery.mobile-1.4.5.min.js"></script>
	<script>
function save() {
	var f = document.result;
//	f.submit();
}
	</script>
</head>
<body>
<div id="summary" data-role="page" data-theme="b" class="ui-page-theme-b">
	<div data-role="header" class="header">
		<h1>Change Password</h1>
		<a href="<?=base_url("/c_dictation")?>" class="ui-btn ui-btn-icon-notext ui-corner-all ui-nodisc-icon ui-alt-icon ui-btn-left ui-icon-home">Home</a>
	</div><!-- /header -->

	<div role="main" class="ui-content">
		<form name="result" method="post" action="<?=base_url("/c_dictation/chagne_pwd")?>">
		<ul data-role="listview" data-divider-theme="b" data-inset="false">
		<li>
			<table style="width:100%;"><tr>
			<td class="title">Email :</td>
			<td>
				<?=htmlspecialchars($u['email'])?>
				<!--
				<a class="ui-btn ui-corner-all ui-btn-inline ui-mini">Change Password</a>
				-->
			</td>
			</tr></table>
		</li>
		<li>
			<table><tr>
			<td class="title"><label for="wordMax" style="margin:0;">Word Max :</label></td>
			<td><input type="text" name="wordMax" id="wordMax" value="<?=$u['maxword']?>" /></td>
			</tr></table>
		</li>
		<li>
			<table><tr>
			<td class="title"><label for="fullMax" style="margin:0;">Full Max :</label></td>
			<td><input type="text" name="fullMax" id="fullMax" value="<?=$u['maxfull']?>" /></td>
			</tr></table>
		</li>
		<!--
		<li>
			<table><tr>
			<td class="title"><label for="playCount" style="margin:0;">Play Count :</label></td>
			<td><input type="text" name="playCount" id="playCount" value="<?=$u['playcount']?>" /></td>
			</tr></table>
		</li>
		<li>
			<table><tr>
			<td class="title"><label for="autoPlay" style="margin:0;">Auto Play :</label></td>
			<td><input type="checkbox" data-role="flipswitch" name="autoPlay" id="autoPlay" data-on-text="on" data-off-text="off" data-wrapper-class="custom-label-flipswitch" <?=(empty($u['autoplay']) ? "" : "checked=\"true\"");?> /></td>
			</tr></table>
		</li>
		<li>
			<table><tr>
			<td class="title"><label for="autoPass" style="margin:0;">Auto Pass :</label></td>
			<td><input type="checkbox" data-role="flipswitch" name="autoPass" id="autoPass" data-on-text="on" data-off-text="off" data-wrapper-class="custom-label-flipswitch" <?=(empty($u['autopass']) ? "" : "checked=\"true\"");?> /></td>
			</tr></table>
		</li>
		-->
		<li>
			<table><tr>
			<td class="title"><label for="defaultMode" style="margin:0;">Default Mode :</label></td>
			<td>
				<div width="100%">
				<input type="checkbox" data-role="flipswitch" name="defaultMode" id="defaultMode" data-on-text="full" data-off-text="word" data-wrapper-class="custom-label-flipswitch" <?=($u['defaultmode'] == "word" ? "" : "checked=\"checked\"");?> />
				</div>
			</td>
			</tr></table>
		</li>
		<li>
			<table><tr>
			<td class="title">Latest Login :</td>
			<td><?=htmlspecialchars($u['pre_login_dt'])?></td>
			</tr></table>
		</li>
		<li>
			<table><tr>
				<td style="width:50%;">
					<!--
					<a href="summary.php" rel="external" class="ui-btn ui-btn-icon-left ui-icon-delete">Cancle</a>
					<input type="reset" class="ui-btn ui-btn-icon-left ui-icon-delete" value="Reset" />
					-->
					<button type="reset" class="ui-btn ui-btn-icon-left ui-icon-delete">Reset</button>
				</td>
				<td style="width:50%;"><a href="#" class="ui-btn ui-btn-icon-left ui-icon-check" onclick="save();">Set</a></td>
			</tr></table>
		</li>
		</ul>
		</form>
	</div><!-- content -->

	<? require_once("./app/views/dictation/left.php"); ?>
</div><!-- page -->
</body>
</html>
