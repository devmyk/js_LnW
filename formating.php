<?
	require_once(getcwd()."/common.php");
	checkSession("uid");

	if ($_SESSION['uid'] != $conf['admin']) {
		echo "<script>document.location.replace('summary.php');</script>";
		exit();
	}
	$dirs = getDirList();
?>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>FORMATING : <?=$dir?></title>
	<!-- link rel="shortcut icon" href="demos/favicon.ico" -->
	<link rel="stylesheet" href="/css/jquery.mobile-1.4.5.min.css">
	<link rel="stylesheet" href="/css/style.css">
	<style>
form { height: 100%; }
.header .ui-btn { top: .35em; margin-top:0; }
#edit table { width : 100%; border: 0px none; }
#rightpanel table { width : 100%; border: 0px none; }
#rightpanel td { border-bottom: 1px dotted #aaa; padding: .5em 0; }
	</style>
	<script language="javascript" src="/js/jquery.js"></script>
	<script language="javascript" src="/js/jquery.mobile-1.4.5.min.js"></script>
	<script language="javascript" src="/js/common.js"></script>
<script>
function submit() {
	var f = document.backform;
	if (f.dir.value.trim() == "") { return false; }
	f.submit();
}
</script>
</head>
<body>
<div data-role="page" data-theme="b" class="ui-page-theme-b">
	<div data-role="header" class="header" data-position="fixed">
		<h1>FORMATING</h1>
		<a href="#leftpanel" class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-bars ui-nodisc-icon ui-alt-icon ui-btn-left">Menu</a>
		<a href="#rightpanel" class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-grid ui-nodisc-icon ui-alt-icon ui-btn-right">List</a>
	</div><!-- /header -->

	<div role="main" class="ui-content">
		<form name="backform" method="post" action="formating_edit.php">
			<h1 style="text-align:center;">Error</h1>
			<h2 style="text-align:center;">There is no file.</h2>
			<? /* 업로드도 해야하나.. 귀찮은데..
             <label for="file">File:</label>
             <input type="file" name="file" id="file" value="">
			*/ ?>
			<select name="dir" id="dir" data-native-menu="false">
				<option value="" selected="selected" style="background-color:black;">FILE</option>
				<? foreach ($dirs as $d) { echo "<option value=\"{$d}\">{$d}</option>"; } ?>
			</select>
			<input type="button" value="SUBMIT" onclick="submit();" />
		</form>
	</div><!-- /content -->
	<? require_once(getcwd()."/left.php"); ?>
</div><!-- /page -->

</body>
</html>
