<?
	require_once(getcwd()."/common.php");
	checkSession("uid");
	checkSession("dir", "logout.php");

	$dir = (isset($_REQUEST['dir']) ? $_REQUEST['dir'] : "");
	$file = (isset($_REQUEST['file']) ? $_REQUEST['file'] : "");
	$is_main = ((empty($dir)||empty($file)) ? 1 : 0);
	if (! $is_main) {
		$is_main = ((! is_dir(getcwd()."/data/{$dir}") || ! is_file(getcwd()."/data/{$dir}/{$file}.js")) ? 1 : 0);
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>SUMMARY</title>
	<!-- link rel="shortcut icon" href="demos/favicon.ico" -->
	<link rel="stylesheet" href="/css/jquery.mobile-1.4.5.min.css">
	<link rel="stylesheet" href="/css/style.css">
	<script language="javascript" src="/js/jquery.js"></script>
	<script language="javascript" src="/js/jquery.mobile-1.4.5.min.js"></script>
<!-- 
	<script language="javascript" src="/js/soundmanager2-js.min.js"></script>
	<script language="javascript" src="/js/common.js"></script>
-->
</head>
<body>
<div id="summary" data-role="page" data-theme="b" class="ui-page-theme-b">
	<div data-role="header" class="header">
		<h1>SUMMARY</h1>
		<a href="#leftpanel" class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-bars ui-nodisc-icon ui-alt-icon ui-btn-left">Menu</a>
		<a href="#rightpanelSetting" class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-gear ui-nodisc-icon ui-alt-icon ui-btn-right">Setting</a>
	</div><!-- /header -->

	<div role="main" class="ui-content">
<? if ($is_main) { ?>
	<h1>summary</h1>
<?	} else { ?>
	<h2><?=$dir?> : <?=$file?></h2>
		<a href="dialog.php?dir=<?=$dir?>&file=<?=$file?>" rel="external" class="ui-btn ui-nodisc-icon">dialog</a>
		<a href="dictation.php?dir=<?=$dir?>&file=<?=$file?>" rel="external" class="ui-btn ui-nodisc-icon">dictation</a>
<? } ?>
	</div><!-- content -->

	<? require_once(getcwd()."/right.php"); ?>

	<? require_once(getcwd()."/left.php"); ?>
</div><!-- page -->
</body>
</html>
