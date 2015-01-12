<?
	$dir = $_REQUEST['dir'];
	$file = $_REQUEST['file'];

	if (empty($dir) || empty($file)) {
		header("Location: /index.html");
		exit();
	}

	if (! is_dir(getcwd()."/data/{$dir}") || ! is_file(getcwd()."/data/{$dir}/{$file}.js")) {
		header("Location: /index.html");
		exit();
	}
?>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>dictation : <?="{$dir} {$file}"?></title>
	<!-- link rel="shortcut icon" href="demos/favicon.ico" -->
	<link rel="stylesheet" href="/css/jquery.mobile-1.4.5.min.css">
	<link rel="stylesheet" href="/css/style.css">
	<script language="javascript" src="/js/jquery.js"></script>
	<script language="javascript" src="/js/jquery.mobile-1.4.5.min.js"></script>
	<script language="javascript" src="/js/soundmanager2-js.min.js"></script>
	<script language="javascript" src="/js/common.js"></script>
	<script language="javascript" src="/data/<?=$dir?>/<?=$file?>.js"></script>
	<script language="javascript" src="/js/dictation.js"></script>
	<script>
		path = "/data/<?=$dir?>/";
	</script>
</head>
<body onload="init();attachRightList();">
<div data-role="page" class="ui-page-theme-b">

	<div data-role="header" class="header">
		<h1><span>[<?=$dir?>]</span> <?=$file?></h1>
		<a href="/index.php" rel="external" class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-back ui-nodisc-icon ui-alt-icon ui-btn-left">Back</a>
		<a href="#rightpanel" class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-bullets ui-nodisc-icon ui-alt-icon ui-btn-right">Search</a>
	</div><!-- /header -->

	<div role="main" id="main" class="ui-content">
		<table id="container" style="text-align:center;width:100%;min-height:50%;">
			<tr>
				<td style="vertical-align:top;text-align:left;">
					<input type="checkbox" data-role="flipswitch" name="mode" id="mode" data-on-text="full" data-off-text="words" data-wrapper-class="custom-label-flipswitch" data-mini="true" onchange="changeMode(this);"/>
				</td>
				<td style="vertical-align:top;text-align:right;" class="ui-nodisc-icon">
					<a id="btnAuto" class="ui-btn ui-icon-audio ui-corner-all ui-btn-b ui-mini ui-btn-icon-notext ui-btn-inline ui-btn-nomargin" onclick="changeAuto();">autoplay</a>
					<a id="btnMark" class="ui-btn ui-icon-star ui-corner-all ui-btn-b ui-mini ui-btn-icon-notext ui-btn-inline ui-btn-nomargin" onclick="changeMark();">mark</a>
					<a class="ui-btn ui-icon-refresh ui-corner-all ui-btn-b ui-mini ui-btn-icon-notext ui-btn-inline ui-btn-nomargin" onclick="init();">refresh</a>
					<a id="btnRecycle" class="ui-btn ui-icon-recycle ui-corner-all ui-btn-b ui-mini ui-btn-icon-notext ui-btn-inline ui-btn-nomargin" onclick="changeRecycle();">recycle</a>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<table width="100%"><tr>
						<td style="width:30px;">
							<a href="#" id="btnPre" class="ui-btn ui-btn-icon-notext ui-icon-carat-l ui-nodisc-icon ui-btn-inline ui-btn-nomargin" style="height:90%;min-height:5em;" onclick="changeCurr(curr-1);">pre</a>
						</td>
						<td width="*">
							<div id="result" style="text-align:center;color:white;padding:1em 0.5em;" onclick="play();"></div>
						</td>
						<td style="width:30px;">
							<a href="#" id="btnNext" class="ui-btn ui-btn-icon-notext ui-icon-carat-r ui-nodisc-icon ui-btn-inline ui-btn-nomargin" style="height:90%;min-height:5em;" onclick="changeCurr(curr+1);">next</a>
						</td>
					</tr></table>
				</td>
			</tr>
			<tr>
				<td style="vertical-align:bottom;" colspan="2"><div id="count" style="text-align:left;color:#999;"></div></td>
			</tr>
			<tr><td colspan="2">
				<div id="full">
					<a href="#" id="btnSubmit" class="ui-btn ui-btn-icon-notext ui-icon-action ui-nodisc-icon ui-btn-inline" style="width:2.5em;height:2em;float:right;margin:.55em 0;" onclick="check_full();">enter</a>
					<div style="overflow:hidden; padding-right:.1em;">
					<input id="put" name="put" type="text" data-clear-btn="false" placeholder="" style="font-size:11pt;" onkeydown="check(event, this);" value="" />
					</div>
				</div>
				<div id="fld"></div>
			</td></tr>
		</table>
	</div><!-- /content -->

	<div data-role="footer" data-position="fixed" data-tap-toggle="false" class="footer">
		<div id="bar" style="position:absolute; background-color:#38c; height:100%; width:1px; top:0; left:0; z-index: -1;"></div>
		<p id="progress" style="text-align:center;"></p>
	</div><!-- /footer -->

	<div data-role="panel" id="rightpanel" data-position="right" data-display="overlay" data-position-fixed="true" data-theme="b">
		<fieldset data-role="controlgroup" data-type="horizontal" data-mini="true" style="text-align:center;">
			<input type="radio" name="sort" id="sort1" value="asc" checked="checked" onclick="changeSort('asc');"/>
			<label for="sort1">asc</label>
			<input type="radio" name="sort" id="sort2" value="marked" onclick="changeSort('marked');"/>
			<label for="sort2">marked</label>
			<input type="radio" name="sort" id="sort3" value="shuffle" onclick="changeSort('shuffle');"/>
			<label for="sort3">shuffle</label>
		</fieldset>
		<div id="list"  style="text-align:left;"></div>
	</div><!-- /rightpanel -->

</div><!-- /page -->

</body>
</html>
