<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>DICTATION : <?=substr($ci['code'],0,2)?> <?=$ci['name']?></title>
	<link rel="shortcut icon" href="/images/icon.ico">
	<link rel="stylesheet" href="/css/jquery.mobile-1.4.5.min.css">
	<link rel="stylesheet" href="/css/style.css">
	<script language="javascript" src="/js/common.js"></script>
	<script language="javascript" src="/js/jquery.js"></script>
	<script language="javascript" src="/js/jquery.mobile-1.4.5.min.js"></script>
	<script language="javascript" src="/js/soundmanager2-js.min.js"></script>
	<script language="javascript" src="/js/dictation/common.js"></script>
	<script language="javascript" src="<?=$ci['dir']?><?=$ci['js']?>"></script>
	<script>
var sum = 0;
var curr = 0;
var sm;
function init() {
	attachDialogList();
	sum = data.length;
	sm = soundManager.createSound({id:"sm_" + data[curr].fn , url: path + "mp3/" + data[curr].fn + ".mp3"});
}
function attachDialogList() 
{
	var list = document.getElementById("list");
	if (list) {
		var html = "";
		for(var i=0; i<data.length; i++) {
			var style = "";
			if (i%2==0) style="background-color:#111;";
			html += "<tr><td style=\"width:3em;"+style+"\">"+(startSeq + data[i]['seq'] + 1)+"</td><td style=\""+style+"\">"+data[i]['script']+"</td></tr>";
		}
		list.innerHTML = html;
	}
}
	</script>
<? /*
	<script language="javascript" src="/data/<?=$dir?>/<?=$file?>.js"></script>
	<script language="javascript" src="/js/dictation.js"></script>
	<script>
dir = "<?=$dir?>";
file = "<?=$file?>";
path = "/data/<?=$dir?>/";
max = <?=$_set['max'];?>;
autoplay = <?=$_set['autoplay'];?>;
autopass = <?=$_set['autopass'];?>;
mode = "<?=$_set['defaultmode'];?>";
playCount = <?=$_set['playcount']?>;
maxFull = <?=$_set['maxfull'];?>;
maxWord = <?=$_set['maxword']?>;
	</script>
 */ ?>
</head>
<body onload="init();"<? /*attachRightList();" */ ?>>
<div data-role="page" data-theme="b" class="ui-page-theme-b">
	<div data-role="header" class="header">
		<h1><span><?=substr($ci['code'],0,2)?></span> <?=$ci['name']?></h1>
		<a href="#leftpanel" class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-bars ui-nodisc-icon ui-alt-icon ui-btn-left">Menu</a>
		<a href="#rightpanel" class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-grid ui-nodisc-icon ui-alt-icon ui-btn-right">Search</a>
	</div><!-- /header -->

	<div role="main" class="ui-content">
		<table id="list" style="width:100%;">
		</table>
	</div><!-- /content -->

	<div data-role="footer" data-position="fixed" data-tap-toggle="false" class="footer">
		<div id="bar" style="position:absolute; background-color:#38c; height:100%; width:1px; top:0; left:0; z-index: -1;"></div>
		<p id="progress" style="text-align:center;"></p>
	</div><!-- /footer -->

	<div data-role="panel" id="rightpanel" data-position="right" data-display="overlay" data-position-fixed="true" data-theme="b" data-dismissible="true">
	</div><!-- /rightpanel -->

	<? require_once("./app/views/v_dictation_left.php"); ?>
</div><!-- /page -->

</body>
</html>
