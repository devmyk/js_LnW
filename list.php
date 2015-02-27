<?
	require_once(getcwd()."/common.php");
	checkSession("uid");

	$dir = (isset($_REQUEST['dir']) ? $_REQUEST['dir'] : "");
	$file = (isset($_REQUEST['file']) ? $_REQUEST['file'] : "");
	$is_summary = ((empty($dir)||empty($file)) ? 1 : 0);

	if (! $is_summary) {
		$is_summary = (! is_dir(getcwd()."/data/{$dir}") || ! is_file(getcwd()."/data/{$dir}/{$file}.js")) ? 1 : 0;
	}

	if ($is_summary) {
		echo "<script>document.location.replace('summary.php');</script>";
		exit();
	}
?>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>DICTATION : <?="[{$dir}] {$file}"?></title>
	<link rel="stylesheet" href="/css/jquery.mobile-1.4.5.min.css">
	<link rel="stylesheet" href="/css/style.css">
	<style>
.header .ui-btn { top: .35em; margin-top:0; }
#contain td { border-bottom: 1px dotted #aaa; padding: .5em 0; }
#rightpanel li table { width : 100% }
	</style>
	<script language="javascript" src="/js/jquery.js"></script>
	<script language="javascript" src="/js/jquery.mobile-1.4.5.min.js"></script>
	<script language="javascript" src="/js/soundmanager2-js.min.js"></script>
	<script language="javascript" src="/js/common.js"></script>
	<script language="javascript" src="/data/<?=$dir?>/<?=$file?>.js"></script>
	<script language="javascript" src="/js/dialog.js"></script>
	<script>
path = "/data/<?=$dir?>/"
max = <?=$_set['max'];?>;
autoplay = <?=$_set['autoplay'];?>;
autopass = <?=$_set['autopass'];?>;
	</script>
</head>
<body onload="init();">
<div id="dialog" data-role="page" data-theme="b" class="ui-page-theme-b">
	<div data-role="header" class="header" data-position="fixed">
		<h1><span>[<?=$dir?>]</span> <?=$file?></h1>
		<a href="#leftpanel" class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-bars ui-nodisc-icon ui-alt-icon ui-btn-left">Menu</a>
		<a href="#rightpanel" class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-gear ui-nodisc-icon ui-alt-icon ui-btn-right">Option</a>
	</div><!-- /header -->

	<div role="main" class="ui-content">
		<table id="contain" style="text-align:center;width:100%;">
			<tr style="background-color:#1d1d1d;color:#999;">
				<td style="vertical-align:top;width:2.1em;">★</td>
				<td style="vertical-align:top;width:2.5em;">no</td>
				<td>script</td>
			</tr>
		</table>
	</div><!-- /content -->

	<div data-role="footer" data-position="fixed" data-tap-toggle="false" class="footer">
		<table style="width:100%;text-align:center;"><tr>
			<td><span id="currentNo">NO: 1</span></td>
			<td><a href="#" style="padding:0.5em;" class="ui-btn ui-btn-icon-notext ui-nodisc-icon ui-alt-icon ui-icon-carat-l">Pre</a></td>
			<td><a href="#" style="padding:0.5em;" style="padding:0.5em;" class="ui-btn ui-btn-icon-notext ui-nodisc-icon ui-alt-icon ui-icon-play" onclick="playloop('marked', true);">Play</a></td>
			<td><a href="#" style="padding:0.5em;" class="ui-btn ui-btn-icon-notext ui-nodisc-icon ui-alt-icon ui-icon-carat-r">Next</a></td>
			<td><a href="#" class="ui-btn ui-btn-icon-notext ui-nodisc-icon ui-alt-icon ui-icon-carat-u ui-corner-all">Up</a></td>
		</tr></table>
	</div><!-- /footer -->

	<div data-role="panel" id="rightpanel" data-position="right" data-display="overlay" data-position-fixed="true" data-theme="b">
		<ul data-role="listview" data-divider-theme="b" data-inset="false">
			<li>
				<fieldset data-role="controlgroup" data-type="horizontal" data-mini="true" style="text-align:center;margin-bottom:0;">
					<input type="radio" name="sort" id="sort1" value="asc" checked="checked" onclick="changeSort('asc');"/>
					<label for="sort1">asc</label>
					<input type="radio" name="sort" id="sort2" value="desc" checked="checked" onclick="changeSort('desc');"/>
					<label for="sort2">desc</label>
					<input type="radio" name="sort" id="sort3" value="marked" onclick="changeSort('marked');"/>
					<label for="sort3">marked</label>
					<input type="radio" name="sort" id="sort4" value="shuffle" onclick="changeSort('shuffle');"/>
					<label for="sort4">shuffle</label>
				</fieldset>
			</li>
			<li>
				<table><tr>
					<td><label for="playCount" style="margin:0;">play Count :</label></td>
					<td width="40%;"><input type="text" name="playCount" id="playCount" value="<?=$_set['max']?>" /></td>
				</tr></table>
			</li>
			<li>
				<table><tr> <!-- 어차피 둘 중 하나만 실행하니까 radio 로 바꿔야겠다 -->
					<td width="33%"><a href="#" class="ui-btn ui-btn-icon-left ui-mini ui-icon-audio" onclick="playloop('all',true);">All</a></td>
					<td width="33%"><a href="#" class="ui-btn ui-btn-icon-left ui-mini ui-icon-star" onclick="playloop('marked',true);">Marked</a></td>
					<td width="33%"><a href="#" class="ui-btn ui-btn-icon-left ui-mini ui-icon-recycle" onclick="playloop('shuffle',true);">shuffle</a></td>
				</tr></table>
			</li>
		</ul>
	</div><!-- /rightpanel -->

	<? require_once(getcwd()."/left.php"); ?>
</div><!-- /page -->

</body>
</html>
